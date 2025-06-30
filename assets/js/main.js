// Function to initialize or retrieve the cart from localStorage (for guests)
function getCart() {
    const cart = localStorage.getItem('pcbuild_cart');
    return cart ? JSON.parse(cart) : [];
}

// Function to save the cart to localStorage
function saveCart(cart) {
    localStorage.setItem('pcbuild_cart', JSON.stringify(cart));
    updateCartCount(); // Update the cart count in the header whenever cart changes
}

// Function to manage new order notification display and clearing
function initializeNewOrderNotification() {
    const orderHistoryLink = document.getElementById('order-history-link');
    const newOrderNotificationSpan = document.getElementById('new-order-notification');

    if (!orderHistoryLink || !newOrderNotificationSpan) {
        // Elements not found (e.g., user not logged in, or on a page without the header)
        return;
    }

    // Check the global constant from header.php
    if (typeof hasNewOrderNotification !== 'undefined' && hasNewOrderNotification) {
        newOrderNotificationSpan.classList.remove('hidden'); // Show the notification
    } else {
        newOrderNotificationSpan.classList.add('hidden'); // Ensure it's hidden if no new notification
    }

    // Add event listener to clear notification on click
    orderHistoryLink.addEventListener('click', (e) => {
        // Hide the notification immediately on click
        newOrderNotificationSpan.classList.add('hidden');

        // Send AJAX request to clear the session flag on the server
        // Use performActionViaFetch which is already available in main.js
        performActionViaFetch('/pcbuild/public/user/clear-order-notification', 'POST', {})
            .then(response => {
                if (response.success) {
                    // console.log('New order notification cleared on server.');
                } else {
                    // console.error('Failed to clear new order notification on server:', response.error);
                }
            })
            .catch(error => {
                // console.error('Fetch error clearing new order notification:', error);
            });
        // Note: The default link navigation will still occur, taking the user to the dashboard.
    });
}

/**
 * Helper function to perform fetch requests for cart actions.
 * @param {string} url The URL to send the request to.
 * @param {string} method The HTTP method (e.g., 'POST', 'GET').
 * @param {object} body An object containing the data to send.
 * @returns {Promise<object>} A promise that resolves with the JSON response.
 */
async function performActionViaFetch(url, method, body = {}) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // Standard form submission type
        },
    };

    if (method === 'POST') {
        const formData = new URLSearchParams();
        for (const key in body) {
            formData.append(key, body[key]);
        }
        options.body = formData.toString();
    }

    try {
        const response = await fetch(url, options);
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({ error: 'Unknown error', message: response.statusText }));
            throw new Error(errorData.error || errorData.message || `HTTP error! status: ${response.status}`);
        }
        return await response.json();
    } catch (error) {
        // console.error('Fetch operation failed:', error);
        throw error;
    }
}

// Function to add a product to the cart (handles both local and server-side)
function addToCart(productId, productName, productPrice, productImage, quantity = 1) {
    if (isLoggedIn) { // isLoggedIn is defined in header.php
        // Server-side cart logic
        performActionViaFetch('/pcbuild/public/cart/add', 'POST', {
            product_id: productId,
            quantity: quantity
        }).then(response => {
            if (response.success) {
                alertMessage('success', `${productName} added to cart!`);
                updateCartCount(); // Update count from server
            } else {
                alertMessage('error', response.error || 'Failed to add to cart.');
            }
        }).catch(error => {
            // console.error('Add to cart fetch error:', error);
            alertMessage('error', 'An error occurred while adding to cart.');
        });
    } else {
        // LocalStorage cart logic
        let cart = getCart();
        const existingItemIndex = cart.findIndex(item => String(item.id) === String(productId));

        if (existingItemIndex > -1) {
            cart[existingItemIndex].quantity += quantity;
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: parseFloat(productPrice), // Ensure price is a number
                image: productImage,
                quantity: quantity
            });
        }
        saveCart(cart);
        alertMessage('success', `${productName} added to cart!`);
    }
}

// Function to update item quantity in cart (handles both local and server-side)
function updateCartItemQuantity(productId, newQuantity) {
    if (isLoggedIn) {
        // Server-side cart logic
        performActionViaFetch('/pcbuild/public/cart/update-quantity', 'POST', {
            product_id: productId,
            quantity: newQuantity
        }).then(response => {
            if (response.success) {
                alertMessage('success', response.message);
                // Re-render cart on cart page or just update count
                if (window.location.pathname.includes('/cart')) {
                    renderCartItems();
                }
                updateCartCount();
            } else {
                alertMessage('error', response.error || 'Failed to update quantity.');
            }
        }).catch(error => {
            // console.error('Update quantity fetch error:', error);
            alertMessage('error', 'An error occurred while updating quantity.');
        });
    } else {
        // LocalStorage cart logic
        let cart = getCart();
        const itemIndex = cart.findIndex(item => String(item.id) === String(productId));

        if (itemIndex > -1) {
            const quantityToSet = Math.max(0, newQuantity); // Allow 0 to enable removal logic below

            if (quantityToSet > 0) {
                cart[itemIndex].quantity = quantityToSet;
            } else {
                cart.splice(itemIndex, 1);
            }
            saveCart(cart);
            if (window.location.pathname.includes('/cart')) {
                renderCartItems();
            }
        }
    }
}

// Function to remove a product from the cart (handles both local and server-side)
function removeFromCart(productId) {
    if (isLoggedIn) {
        // Server-side cart logic
        performActionViaFetch('/pcbuild/public/cart/remove', 'POST', {
            product_id: productId
        }).then(response => {
            if (response.success) {
                alertMessage('success', response.message || 'Item removed from cart.');
                if (window.location.pathname.includes('/cart')) {
                    renderCartItems();
                }
                updateCartCount();
            } else {
                alertMessage('error', response.error || 'Failed to remove item.');
            }
        }).catch(error => {
            // console.error('Remove from cart fetch error:', error);
            alertMessage('error', 'An error occurred while removing item.');
        });
    } else {
        // LocalStorage cart logic
        let cart = getCart();
        cart = cart.filter(item => String(item.id) !== String(productId));
        saveCart(cart);
        alertMessage('success', 'Item removed from cart.');
        if (window.location.pathname.includes('/cart')) {
            renderCartItems();
        }
    }
}

// Function to clear the entire cart (handles both local and server-side)
function clearCart() {
    if (isLoggedIn) {
        // Server-side cart logic
        performActionViaFetch('/pcbuild/public/cart/clear', 'POST', {})
            .then(response => {
                if (response.success) {
                    alertMessage('success', response.message || 'Cart cleared!');
                    if (window.location.pathname.includes('/cart')) {
                        renderCartItems();
                    }
                    updateCartCount();
                } else {
                    alertMessage('error', response.error || 'Failed to clear cart.');
                }
            })
            .catch(error => {
                // console.error('Clear cart fetch error:', error);
                alertMessage('error', 'An error occurred while clearing cart.');
            });
    } else {
        // LocalStorage cart logic
        localStorage.setItem('pcbuild_cart', JSON.stringify([]));
        updateCartCount();
        if (window.location.pathname.includes('/cart')) {
            renderCartItems();
        }
        alertMessage('success', 'Cart cleared!');
    }
}

// Function to update the cart count displayed in the header
function updateCartCount() {
    const cartCountElement = document.getElementById('cart-item-count');
    if (!cartCountElement) return;

    if (isLoggedIn) {
        performActionViaFetch('/pcbuild/public/cart/get', 'GET')
            .then(response => {
                if (response.success && response.cart) {
                    const totalItems = response.cart.reduce((sum, item) => sum + item.quantity, 0);
                    cartCountElement.textContent = totalItems > 0 ? totalItems : '';
                    cartCountElement.classList.toggle('hidden', totalItems === 0);
                } else {
                    cartCountElement.textContent = '';
                    cartCountElement.classList.add('hidden');
                }
            })
            .catch(error => {
                // console.error('Failed to fetch server cart count:', error);
                cartCountElement.textContent = ''; // Fallback to empty if API fails
                cartCountElement.classList.add('hidden');
            });
    } else {
        const cart = getCart();
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCountElement.textContent = totalItems > 0 ? totalItems : '';
        cartCountElement.classList.toggle('hidden', totalItems === 0);
    }
}

/**
 * Synchronizes items from localStorage cart to the server-side database cart.
 * This function should be called after a successful login or registration.
 */
async function syncLocalCartToServer() {
    const localCart = getCart();
    if (localCart.length > 0) {
        try {
            const response = await performActionViaFetch('/pcbuild/public/cart/sync', 'POST', {
                cart: JSON.stringify(localCart)
            });
            if (response.success) {
                // alertMessage('success', response.message);
                localStorage.removeItem('pcbuild_cart'); // Clear local cart after successful sync
                updateCartCount(); // Refresh cart count from server
                // If there were warnings, you might want to log them or display them more prominently
                if (response.warnings && response.warnings.length > 0) {
                    // alertMessage('warning', 'Some items could not be synced due to stock issues.');
                }
            } else {
                // alertMessage('error', response.error || 'Cart sync failed.');
            }
        } catch (error) {
            // console.error('Cart sync error:', error);
            alertMessage('error', 'An error occurred during cart synchronization.');
        }
    }
}


// Global variables for the modal and current product
let currentProduct = null;
const quantityModal = document.getElementById('quantity-modal');
const modalProductName = document.getElementById('modal-product-name');
const modalProductPrice = document.getElementById('modal-product-price');
const modalProductImage = document.getElementById('modal-product-image');
const modalProductStock = document.getElementById('modal-product-stock');

// New: Quantity display element for modal
const modalQuantityDisplay = document.getElementById('modal-quantity-display');
// New: Hidden input to store actual quantity value for the modal
const modalQuantityInputHidden = document.getElementById('quantity-input');
const modalQuantityError = document.getElementById('quantity-error');

const addToCartModalBtn = document.getElementById('add-to-cart-modal-btn');
const cancelQuantityBtn = document.getElementById('cancel-quantity');

/**
 * Sets up quantity increment/decrement controls for a given quantity display and hidden input.
 * @param {HTMLElement} container The parent element containing the buttons and display.
 * @param {string} displayId The ID of the span/div displaying the quantity.
 * @param {string} hiddenInputId The ID of the hidden input storing the quantity value.
 * @param {string} minusBtnId The ID of the minus button.
 * @param {string} plusBtnId The ID of the plus button.
 * @param {string} errorId The ID of the error message element.
 * @param {number} maxStock The maximum allowed quantity (product stock).
 */
function setupQuantityControls(container, displayId, hiddenInputId, minusBtnId, plusBtnId, errorId, maxStock) {
    const quantityDisplay = container.querySelector(`#${displayId}`);
    const quantityHiddenInput = container.querySelector(`#${hiddenInputId}`);
    const minusBtn = container.querySelector(`#${minusBtnId}`);
    const plusBtn = container.querySelector(`#${plusBtnId}`);
    const errorElement = container.querySelector(`#${errorId}`);

    if (!quantityDisplay || !quantityHiddenInput || !minusBtn || !plusBtn || !errorElement) {
        // console.error("Missing quantity control elements for:", container.id);
        return;
    }

    let currentQuantity = parseInt(quantityHiddenInput.value) || 1;
    quantityDisplay.textContent = currentQuantity;
    errorElement.classList.add('hidden'); // Hide errors initially

    const updateQuantity = (newQuantity) => {
        // Ensure quantity is at least 1
        newQuantity = Math.max(1, newQuantity);
        // Ensure quantity does not exceed stock
        newQuantity = Math.min(newQuantity, maxStock);

        currentQuantity = newQuantity;
        quantityDisplay.textContent = currentQuantity;
        quantityHiddenInput.value = currentQuantity; // Update hidden input

        // Hide error message if quantity is valid
        if (errorElement) {
             errorElement.classList.add('hidden');
        }

        // Disable buttons if limits are reached
        minusBtn.disabled = currentQuantity <= 1;
        plusBtn.disabled = currentQuantity >= maxStock;

        minusBtn.classList.toggle('opacity-50', currentQuantity <= 1);
        minusBtn.classList.toggle('cursor-not-allowed', currentQuantity <= 1);
        plusBtn.classList.toggle('opacity-50', currentQuantity >= maxStock);
        plusBtn.classList.toggle('cursor-not-allowed', currentQuantity >= maxStock);
    };

    // Initial state based on maxStock
    updateQuantity(currentQuantity); // Call once to set initial state and button disable status

    minusBtn.onclick = () => updateQuantity(currentQuantity - 1);
    plusBtn.onclick = () => updateQuantity(currentQuantity + 1);

    // If stock is 0, disable buttons
    if (maxStock <= 0) {
        minusBtn.disabled = true;
        plusBtn.disabled = true;
        minusBtn.classList.add('opacity-50', 'cursor-not-allowed');
        plusBtn.classList.add('opacity-50', 'cursor-not-allowed');
        quantityDisplay.textContent = 0; // Display 0 if out of stock
        quantityHiddenInput.value = 0; // Store 0 in hidden input
    }
}


// Function to open the quantity selection modal
function openQuantityModal(product) {
    currentProduct = product; // Store the product globally for access when adding to cart

    modalProductImage.src = product.image_url || 'https://placehold.co/128x128/e2e8f0/475569?text=No+Image';
    modalProductName.textContent = product.name;
    modalProductPrice.textContent = `$${parseFloat(product.price).toFixed(2)}`;
    modalProductStock.textContent = `Available Stock: ${product.stock}`;

    // Initialize quantity to 1 for the modal, then set up controls
    modalQuantityInputHidden.value = 1;
    setupQuantityControls(quantityModal.querySelector('.bg-white'), 'modal-quantity-display', 'quantity-input', 'modal-quantity-minus', 'modal-quantity-plus', 'quantity-error', product.stock);

    // Update stock text color
    if (product.stock <= 0) {
        modalProductStock.classList.remove('text-green-600');
        modalProductStock.classList.add('text-red-600');
        addToCartModalBtn.disabled = true;
        addToCartModalBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        modalProductStock.classList.remove('text-red-600');
        modalProductStock.classList.add('text-green-600');
        addToCartModalBtn.disabled = false;
        addToCartModalBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }

    // Show modal with animation
    quantityModal.classList.remove('hidden', 'pointer-events-none');
    setTimeout(() => {
        quantityModal.classList.add('opacity-100');
        quantityModal.querySelector('div').classList.remove('scale-95', 'opacity-0');
        quantityModal.querySelector('div').classList.add('scale-100', 'opacity-100');
    }, 10);
}

// Function to close the quantity selection modal
function closeQuantityModal() {
    quantityModal.classList.remove('opacity-100');
    quantityModal.querySelector('div').classList.remove('scale-100', 'opacity-100');
    quantityModal.querySelector('div').classList.add('scale-95', 'opacity-0');
    quantityModal.addEventListener('transitionend', () => {
        quantityModal.classList.add('hidden', 'pointer-events-none');
    }, { once: true });
    currentProduct = null; // Clear current product data
    modalQuantityError.classList.add('hidden'); // Hide any errors on close
}

// Event listener for adding to cart from the modal (products/index.php)
if (addToCartModalBtn) {
    addToCartModalBtn.addEventListener('click', () => {
        const quantity = parseInt(modalQuantityInputHidden.value);

        if (isNaN(quantity) || quantity <= 0) {
            modalQuantityError.textContent = 'Please enter a valid quantity greater than 0.';
            modalQuantityError.classList.remove('hidden');
            return;
        }

        if (quantity > currentProduct.stock) {
            modalQuantityError.textContent = `You can only add up to ${currentProduct.stock} units.`;
            modalQuantityError.classList.remove('hidden');
            return;
        }

        // If validation passes, add to cart and close modal
        addToCart(currentProduct.id, currentProduct.name, currentProduct.price, currentProduct.image_url, quantity);
        closeQuantityModal();
    });
}

// Event listener for canceling quantity selection
if (cancelQuantityBtn) {
    cancelQuantityBtn.addEventListener('click', () => {
        closeQuantityModal();
    });
}

// Close modal if clicked outside of the content box
if (quantityModal) {
    quantityModal.addEventListener('click', (e) => {
        if (e.target === quantityModal) {
            closeQuantityModal();
        }
    });
}

// New: Event listener for adding to cart on the product show page (products/show.php)
document.addEventListener('DOMContentLoaded', () => {
    const addToCartPageBtn = document.getElementById('add-to-cart-page-btn');
    if (addToCartPageBtn) {
        const productId = addToCartPageBtn.dataset.productId;
        const productName = addToCartPageBtn.dataset.productName;
        const productPrice = addToCartPageBtn.dataset.productPrice;
        const productImage = addToCartPageBtn.dataset.productImage;
        const productStock = parseInt(addToCartPageBtn.dataset.productStock);

        // Setup quantity controls for the show page
        const showPageQuantityContainer = document.querySelector('.flex.items-center.justify-start.space-x-3.mb-6');
        if (showPageQuantityContainer) {
            setupQuantityControls(showPageQuantityContainer, 'page-quantity-display', 'page-quantity-value', 'page-quantity-minus', 'page-quantity-plus', 'page-quantity-error', productStock);
        }

        // Handle disabled state based on stock
        const stockDisplay = document.getElementById('product-stock-display');
        if (productStock <= 0) {
            addToCartPageBtn.disabled = true;
            addToCartPageBtn.classList.add('opacity-50', 'cursor-not-allowed');
            if (stockDisplay) {
                stockDisplay.classList.remove('text-green-600');
                stockDisplay.classList.add('text-red-600');
            }
        } else {
             addToCartPageBtn.disabled = false;
             addToCartPageBtn.classList.remove('opacity-50', 'cursor-not-allowed');
             if (stockDisplay) {
                 stockDisplay.classList.remove('text-red-600');
                 stockDisplay.classList.add('text-green-600');
             }
        }

        addToCartPageBtn.addEventListener('click', () => {
            const quantity = parseInt(document.getElementById('page-quantity-value').value);
            const errorElement = document.getElementById('page-quantity-error');

            if (isNaN(quantity) || quantity <= 0) {
                errorElement.textContent = 'Please enter a valid quantity greater than 0.';
                errorElement.classList.remove('hidden');
                return;
            }

            if (quantity > productStock) {
                errorElement.textContent = `You can only add up to ${productStock} units.`;
                errorElement.classList.remove('hidden');
                return;
            }

            addToCart(productId, productName, productPrice, productImage, quantity);
        });
    }
});


// Custom alert message function (replaces alert())
function alertMessage(type, message) {
    const alertBox = document.createElement('div');
    // Changed positioning to top-center, using flexbox for centering
    alertBox.className = `fixed top-4 left-1/2 -translate-x-1/2 p-4 rounded-md shadow-lg text-white z-50 transition-all duration-300 transform -translate-y-full opacity-0`;

    if (type === 'success') {
        alertBox.classList.add('bg-green-500'); // Keep green for success alerts
    } else if (type === 'error') {
        alertBox.classList.add('bg-red-500'); // Keep red for error alerts
    } else {
        alertBox.classList.add('bg-gray-700');
    }

    alertBox.textContent = message;
    document.body.appendChild(alertBox);

    // Animate in (slide down from top)
    setTimeout(() => {
        alertBox.classList.remove('-translate-y-full', 'opacity-0');
        alertBox.classList.add('translate-y-0', 'opacity-100');
    }, 100);

    // Animate out and remove (slide up to top)
    setTimeout(() => {
        alertBox.classList.remove('translate-y-0', 'opacity-100');
        alertBox.classList.add('-translate-y-full', 'opacity-0');
        alertBox.addEventListener('transitionend', () => alertBox.remove());
    }, 3000); // Message disappears after 3 seconds
}


// Function to initialize password visibility toggles
function initializePasswordToggle() {
    document.querySelectorAll('.toggle-password-visibility').forEach(toggleBtn => {
        const targetId = toggleBtn.dataset.target;
        const passwordInput = document.getElementById(targetId);
        const eyeShow = toggleBtn.querySelector('.eye-show-password'); // Open eye icon
        const eyeHide = toggleBtn.querySelector('.eye-hide-password'); // Crossed-out eye icon

        // Function to update icon visibility based on input type
        const updateIconVisibility = () => {
            if (passwordInput.type === 'password') {
                // Password is hidden (asterisks) -> show open eye
                eyeShow.classList.remove('hidden');
                eyeHide.classList.add('hidden');
            } else {
                // Password is shown (plain text) -> show crossed-out eye
                eyeShow.classList.add('hidden');
                eyeHide.classList.remove('hidden');
            }
        };

        // Initial state: hide the toggle button if input is empty
        if (passwordInput.value === '') {
            toggleBtn.classList.add('hidden');
        } else {
            toggleBtn.classList.remove('hidden');
            // If there's content, ensure correct icon based on current password type
            updateIconVisibility();
        }

        // Add event listener for toggling visibility on click
        toggleBtn.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
            updateIconVisibility(); // Update icons after type change
            passwordInput.focus(); // Keep focus on the input field
        });

        // Add event listener to show/hide toggle based on input content AND update icon
        passwordInput.addEventListener('input', () => {
            if (passwordInput.value === '') {
                toggleBtn.classList.add('hidden');
            } else {
                toggleBtn.classList.remove('hidden');
                // When user types, the password is initially hidden, so show the open eye icon
                if (passwordInput.type === 'password') {
                    eyeShow.classList.remove('hidden');
                    eyeHide.classList.add('hidden');
                }
            }
        });

        // Also handle focus/blur if needed, though 'input' covers most cases
        passwordInput.addEventListener('focus', () => {
            if (passwordInput.value !== '') {
                toggleBtn.classList.remove('hidden');
                updateIconVisibility(); // Ensure correct icon on focus if already typed
            }
        });
    });
}


// Initialize cart count on page load
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    // Initialize AI chat components on DOMContentLoaded
    initializeAiChat();
    // Initialize password toggle functionality
    initializePasswordToggle();
    // Initialize logout confirmation modal
    initializeLogoutConfirmation();
    // NEW: Initialize custom confirmation modals
    initializeConfirmationModals();

    // NEW: Perform cart sync if flag is set (from AuthController after login/register)
    if (typeof performCartSync !== 'undefined' && performCartSync) {
        syncLocalCartToServer();
    }

    // NEW: Initialize new order notification
    initializeNewOrderNotification();

    initializeDismissibleAlerts();

    // Initialize newsletter subscription form
    initializeNewsletterSubscription(); // [NEW] Call the new function
});


// The following functions are for the cart page specifically
// They will only run if the element #cart-items-container exists

function renderCartItems() {
    const cartItemsContainer = document.getElementById('cart-items-container');
    const emptyCartState = document.getElementById('empty-cart-state');
    if (!cartItemsContainer || !emptyCartState) return; // Exit if not on cart page

    const checkoutSection = document.getElementById('checkout-section');
    let subtotal = 0;

    // Determine cart source based on login status
    if (isLoggedIn) {
        // Fetch cart from server
        performActionViaFetch('/pcbuild/public/cart/get', 'GET')
            .then(response => {
                if (response.success && response.cart) {
                    displayCartContent(response.cart);
                } else {
                    cartItemsContainer.innerHTML = ''; // Clear loading text
                    cartItemsContainer.classList.add('hidden'); // Hide the container itself
                    emptyCartState.classList.remove('hidden'); // Show empty cart message
                    checkoutSection.classList.add('hidden');
                    updateCheckoutButtonState(0, false); // Disable checkout button
                }
            })
            .catch(error => {
                cartItemsContainer.innerHTML = ''; // Clear loading text
                cartItemsContainer.classList.add('hidden'); // Hide the container itself
                emptyCartState.classList.remove('hidden'); // Show empty cart message
                checkoutSection.classList.add('hidden');
                updateCheckoutButtonState(0, false); // Disable checkout button
            });
    } else {
        // Use local storage cart
        const cart = getCart();
        displayCartContent(cart);
    }

    // Helper function to display cart content in the DOM
    function displayCartContent(cart) {
        let cartHtml = '';
        subtotal = 0;

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = ''; // Clear any existing content
            cartItemsContainer.classList.add('hidden'); // Hide the container itself
            emptyCartState.classList.remove('hidden'); // Show empty cart message
            checkoutSection.classList.add('hidden');
            updateCheckoutButtonState(0, false); // Disable checkout button
        } else {
            emptyCartState.classList.add('hidden'); // Hide empty cart message
            cartItemsContainer.classList.remove('hidden'); // Ensure container is visible
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                subtotal += itemTotal;

                // Determine if the minus button should be disabled (item.stock property is passed from CartItem model)
                const isMinusDisabled = item.quantity <= 1 ? 'disabled' : '';
                const minusButtonClasses = item.quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '';

                // You might need to adjust quantity increment based on stock here too
                // For cart page, typically you can update to max available stock
                const isPlusDisabled = item.quantity >= item.stock ? 'disabled' : '';
                const plusButtonClasses = item.quantity >= item.stock ? 'opacity-50 cursor-not-allowed' : '';

                cartHtml += `
                    <div class="flex items-center border-b border-gray-200 py-4">
                        <img src="${item.image || '/pcbuild/assets/images/placeholder.png'}" alt="${item.name}" class="w-20 h-20 object-contain rounded-md mr-4">
                        <div class="flex-grow">
                            <h3 class="text-lg font-semibold text-gray-800">${item.name}</h3>
                            <p class="text-gray-600 text-sm">$${item.price.toFixed(2)}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button class="quantity-btn p-1 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors ${minusButtonClasses}"
                                    onclick="updateCartItemQuantity(${item.id}, ${item.quantity - 1})"
                                    ${isMinusDisabled}>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                                </svg>
                            </button>
                            <span class="text-lg font-semibold text-gray-900 w-12 text-center">${item.quantity}</span>
                            <button class="quantity-btn p-1 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors ${plusButtonClasses}"
                                    onclick="updateCartItemQuantity(${item.id}, ${item.quantity + 1})"
                                    ${isPlusDisabled}>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m0 0H6" />
                                </svg>
                            </button>
                            <span class="text-lg font-semibold text-gray-800 ml-2">$${itemTotal.toFixed(2)}</span>
                            <button onclick="removeFromCart(${item.id})" class="ml-4 text-red-600 hover:text-red-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                `;
            });
            checkoutSection.classList.remove('hidden');
            updateCheckoutButtonState(cart.length, isLoggedIn); // Update checkout button state
        }

        cartItemsContainer.innerHTML = cartHtml;
        document.getElementById('cart-subtotal').textContent = subtotal.toFixed(2);
    }
}

// Helper function to update the state of the "Proceed to Checkout" button
function updateCheckoutButtonState(cartItemCount, isUserLoggedIn) {
    const checkoutButton = document.getElementById('proceed-to-checkout-button');
    if (!checkoutButton) return;

    if (cartItemCount === 0) {
        checkoutButton.disabled = true;
        checkoutButton.classList.add('opacity-50', 'cursor-not-allowed');
        checkoutButton.textContent = 'Your cart is empty';
    } else if (!isUserLoggedIn) {
        checkoutButton.disabled = true;
        checkoutButton.classList.add('opacity-50', 'cursor-not-allowed');
        checkoutButton.textContent = 'You must be logged in to checkout';
    } else {
        checkoutButton.disabled = false;
        checkoutButton.classList.remove('opacity-50', 'cursor-not-allowed');
        // Restore original HTML if it was changed
        checkoutButton.innerHTML = `
            Proceed to Checkout
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
        `;
    }
}


// --- AI Chat Pop-up Logic (Centralized and Initialized) ---
let chatHistory = [];
let chatMessages, userInput, sendButton, chatInputForm, initialAiGreeting;
let initialChatHtmlContent = ''; // [NEW] Global variable to store the original HTML of the initial chat view

function initializeAiChat() {
    const aiChatFab = document.getElementById('ai-chat-fab');
    const aiChatSidebar = document.getElementById('ai-chat-sidebar');
    const closeChatSidebarButton = document.getElementById('close-chat-sidebar');
    const newChatButton = document.getElementById('new-chat-button');
    const chatContentPlaceholder = document.getElementById('ai-chat-content-placeholder');
    const body = document.body;

    async function loadInitialChatContent() {
        try {
            const response = await fetch('/pcbuild/public/ai-chat-content');
            const htmlContent = await response.text();

            if (response.ok) {
                chatContentPlaceholder.innerHTML = htmlContent;

                // Now that content is loaded, get references to its elements
                chatMessages = document.getElementById('chat-messages');
                userInput = document.getElementById('user-input');
                sendButton = document.getElementById('send-button');
                chatInputForm = document.getElementById('chat-input-form');
                initialAiGreeting = document.getElementById('initial-ai-greeting'); // Get reference to the greeting div

                // [NEW] Store the initial HTML content of chatMessages
                // This includes the initialAiGreeting div itself.
                initialChatHtmlContent = chatMessages.innerHTML;

                // If history exists, hide the initial greeting and re-render messages from history.
                if (chatHistory.length > 0) {
                    if (initialAiGreeting) {
                        initialAiGreeting.classList.add('hidden'); // Hide the greeting
                    }
                    chatMessages.innerHTML = ''; // Clear initial content to append historical messages
                    chatHistory.forEach(msg => appendMessage(msg.role, msg.text));
                } else {
                    // If chat history is empty, ensure the initial greeting is visible initially
                    if (initialAiGreeting) {
                        initialAiGreeting.classList.remove('hidden');
                    }
                    // Add the initial message to history for AI context for the *first* conversation if not already there
                    const initialMessageTextForHistory = "Hello! I'm Kraft-E, your PC Build Assistant.\n\nAsk me anything about PC components, compatibility,\nor general build advice!"; //
                    if (chatHistory.length === 0) { // Only push if history is genuinely empty
                        chatHistory.push({ role: "model", text: initialMessageTextForHistory });
                    }
                }

                // Attach event listeners for chat functionality
                if (chatInputForm) {
                    chatInputForm.addEventListener('submit', (e) => {
                        e.preventDefault();
                        sendMessage();
                    });
                } else if (sendButton && userInput) {
                    sendButton.addEventListener('click', sendMessage);
                    userInput.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            sendMessage();
                        }
                    });
                }

                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }

            } else {
                chatContentPlaceholder.innerHTML = `<p class="text-center text-red-600 py-8">Failed to load AI Chat content.</p>`;
            }
        } catch (error) {
            alertMessage('error', 'Could not connect to the AI assistant.');
        }
    }

    // [NEW] Function to reset the chat to its initial state
    function resetChat() {
        chatHistory = []; // Clear chat history

        // Restore the initial HTML content, which includes the greeting
        if (chatMessages && initialChatHtmlContent) {
            chatMessages.innerHTML = initialChatHtmlContent;
        }

        // Re-get reference to initialAiGreeting as it might have been re-created by innerHTML
        initialAiGreeting = document.getElementById('initial-ai-greeting');
        if (initialAiGreeting) {
            initialAiGreeting.classList.remove('hidden'); // Ensure it's visible
        }

        // Re-add the initial message to history for AI context for the *next* conversation
        const initialMessageTextForHistory = "Hello! I'm Kraft-E, your PC Build Assistant.\n\nAsk me anything about PC components, compatibility,\nor general build advice!"; //
        chatHistory.push({ role: "model", text: initialMessageTextForHistory }); // Push the clean text version

        if (userInput) {
            userInput.value = ''; // Clear input field
            userInput.focus();
        }

        if (chatMessages) {
            chatMessages.scrollTop = 0; // Scroll to top
        }
    }

    loadInitialChatContent();

    if (aiChatFab) {
        aiChatFab.addEventListener('click', () => {
            aiChatSidebar.classList.add('open');
            body.classList.add('ai-chat-open');
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            if (userInput) {
                userInput.focus();
            }
        });
    }

    if (closeChatSidebarButton) {
        closeChatSidebarButton.addEventListener('click', () => {
            aiChatSidebar.classList.remove('open');
            body.classList.remove('ai-chat-open');
        });
    }

    if (newChatButton) {
        newChatButton.addEventListener('click', resetChat); // Attach reset function to the button
    }

    document.addEventListener('click', (event) => {
        if (aiChatSidebar && aiChatFab && aiChatSidebar.classList.contains('open') &&
            !aiChatSidebar.contains(event.target) &&
            !aiChatFab.contains(event.target)) {
            aiChatSidebar.classList.remove('open');
            body.classList.remove('ai-chat-open');
        }
    });
}

function appendMessage(role, text) {
    if (!chatMessages) {
        return;
    }

    const messageDiv = document.createElement('div');
    messageDiv.className = `flex items-start ${role === 'user' ? 'justify-end' : ''} p-2`;

    const avatar = document.createElement('div');
    avatar.className = `flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm ${role === 'user' ? 'bg-[--color-primary-orange] ml-3' : 'bg-[--color-dark-blue] mr-3'}`;
    avatar.textContent = role === 'user' ? 'You' : 'AI';

    const textBubble = document.createElement('div');
    textBubble.className = `p-3 rounded-lg max-w-[80%] shadow-sm ${role === 'user' ? 'bg-[--color-primary-orange] text-white ml-auto' : 'bg-gray-200 text-gray-800 mr-auto'} markdown-content`;

    if (role === 'model') {
        textBubble.innerHTML = marked.parse(text);
    } else {
        textBubble.textContent = text;
    }

    if (role === 'user') {
        messageDiv.appendChild(textBubble);
        messageDiv.appendChild(avatar);
    } else {
        messageDiv.appendChild(avatar);
        messageDiv.appendChild(textBubble);
    }

    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

async function sendMessage() {
    const prompt = userInput.value.trim();
    if (prompt === '') return;

    // Hide the initial AI greeting when the user sends their first message
    if (initialAiGreeting && !initialAiGreeting.classList.contains('hidden')) {
        initialAiGreeting.classList.add('hidden'); // Hide the greeting
        chatMessages.innerHTML = ''; // Clear ALL messages (including the now-hidden greeting)
    }

    appendMessage('user', prompt);
    chatHistory.push({ role: "user", text: prompt });

    userInput.value = '';

    sendButton.disabled = true;
    sendButton.textContent = 'Thinking...';
    sendButton.classList.add('opacity-50', 'cursor-not-allowed');

    try {
        const response = await fetch('/pcbuild/public/ai-chat/api', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ prompt: prompt, history: chatHistory }),
        });

        const data = await response.json();

        if (response.ok) {
            const aiResponse = data.response;
            appendMessage('model', aiResponse);
            chatHistory.push({ role: "model", text: aiResponse });
        } else {
            alertMessage('error', `AI Service error: ${data.error || 'Something went wrong.'}`);
        }
    } catch (error) {
        alertMessage('error', 'Could not connect to the AI assistant.');
    } finally {
        sendButton.disabled = false;
        sendButton.textContent = 'Send';
        sendButton.classList.remove('opacity-50', 'cursor-not-allowed');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

// --- Logout Confirmation Modal Logic ---
function showLogoutConfirmation() {
    const logoutModal = document.getElementById('logout-confirmation-modal');
    if (logoutModal) {
        logoutModal.classList.remove('hidden', 'pointer-events-none');
        setTimeout(() => {
            logoutModal.classList.add('opacity-100');
            logoutModal.querySelector('div').classList.remove('scale-95', 'opacity-0');
            logoutModal.querySelector('div').classList.add('scale-100', 'opacity-100');
        }, 10);
    } else {
        // console.error('Logout modal element not found!');
    }
}

function hideLogoutConfirmation() {
    const logoutModal = document.getElementById('logout-confirmation-modal');
    if (logoutModal) {
        logoutModal.classList.remove('opacity-100');
        logoutModal.querySelector('div').classList.remove('scale-100', 'opacity-100');
        logoutModal.querySelector('div').classList.add('scale-95', 'opacity-0');
        logoutModal.addEventListener('transitionend', () => {
            logoutModal.classList.add('hidden', 'pointer-events-none');
        }, { once: true });
    }
}

function initializeLogoutConfirmation() {
    const logoutButton = document.getElementById('logout-button');
    if (logoutButton) {
        logoutButton.addEventListener('click', (e) => {
            e.preventDefault();
            showLogoutConfirmation();
        });
    }

    const confirmLogoutBtn = document.getElementById('confirm-logout-btn');
    const cancelLogoutBtn = document.getElementById('cancel-logout-btn');

    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener('click', () => {
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                if (typeof logoutForm.requestSubmit === 'function') {
                    logoutForm.requestSubmit();
                } else {
                    logoutForm.submit();
                }
            } else {
                window.location.href = '/pcbuild/public/logout';
            }
        });
    }

    if (cancelLogoutBtn) {
        cancelLogoutBtn.addEventListener('click', () => {
            hideLogoutConfirmation();
        });
    }

    const logoutModal = document.getElementById('logout-confirmation-modal');
    if (logoutModal) {
        logoutModal.addEventListener('click', (e) => {
            if (e.target === logoutModal) {
                hideLogoutConfirmation();
            }
        });
    }
}

// Function to initialize auto-hide and dismiss functionality for static PHP-rendered alerts
function initializeDismissibleAlerts() {
    const dismissibleAlerts = document.querySelectorAll('.js-dismissible-alert');

    dismissibleAlerts.forEach(alertDiv => {
        const dismissBtn = alertDiv.querySelector('.js-dismiss-btn');

        alertDiv.classList.add('transition-opacity', 'duration-300', 'ease-in-out');
        
        alertDiv.classList.remove('opacity-0');
        alertDiv.style.opacity = '1';

        const hideAndRemoveAlert = () => {
            alertDiv.style.opacity = '0';
            alertDiv.style.pointerEvents = 'none';

            setTimeout(() => {
                alertDiv.remove();
            }, 300);
        };

        setTimeout(hideAndRemoveAlert, 5000);

        if (dismissBtn) {
            dismissBtn.addEventListener('click', hideAndRemoveAlert);
        }
    });
}

// --- Custom Confirmation Modal Logic ---
let currentConfirmationCallback = null;

function showConfirmationModal(title, message, callback) {
    const confirmationModal = document.getElementById('confirmation-modal');
    const modalTitle = document.getElementById('confirmation-modal-title');
    const modalMessage = document.getElementById('confirmation-modal-message');

    if (!confirmationModal || !modalTitle || !modalMessage) {
        return;
    }

    modalTitle.textContent = title;
    modalMessage.textContent = message;
    currentConfirmationCallback = callback;

    confirmationModal.classList.remove('hidden', 'pointer-events-none');
    setTimeout(() => {
        confirmationModal.classList.add('opacity-100');
        confirmationModal.querySelector('div').classList.remove('scale-95', 'opacity-0');
        confirmationModal.querySelector('div').classList.add('scale-100', 'opacity-100');
    }, 10);
}

function hideConfirmationModal() {
    const confirmationModal = document.getElementById('confirmation-modal');
    if (confirmationModal) {
        confirmationModal.classList.remove('opacity-100');
        confirmationModal.querySelector('div').classList.remove('scale-100', 'opacity-100');
        confirmationModal.querySelector('div').classList.add('scale-95', 'opacity-0');
        confirmationModal.addEventListener('transitionend', () => {
            confirmationModal.classList.add('hidden', 'pointer-events-none');
        }, { once: true });
    }
}

function initializeConfirmationModals() {
    const confirmationModal = document.getElementById('confirmation-modal');
    const confirmActionBtn = document.getElementById('confirm-action-btn');
    const cancelActionBtn = document.getElementById('cancel-action-btn');

    if (confirmActionBtn) {
        confirmActionBtn.addEventListener('click', () => {
            hideConfirmationModal();
            const callbackToExecute = currentConfirmationCallback;
            currentConfirmationCallback = null;

            if (callbackToExecute) {
                callbackToExecute(true);
            }
        });
    }

    if (cancelActionBtn) {
        cancelActionBtn.addEventListener('click', () => {
            hideConfirmationModal();
            const callbackToExecute = currentConfirmationCallback;
            currentConfirmationCallback = null;

            if (callbackToExecute) {
                callbackToExecute(false);
            }
        });
    }

    if (confirmationModal) {
        confirmationModal.addEventListener('click', (e) => {
            if (e.target === confirmationModal) {
                hideConfirmationModal();
                const callbackToExecute = currentConfirmationCallback;
                currentConfirmationCallback = null;

                if (callbackToExecute) {
                    callbackToExecute(false);
                }
            }
        });
    }

    document.addEventListener('click', (e) => {
        const target = e.target;

        if (target.matches('.js-toggle-ban-btn')) {
            e.preventDefault();
            const userId = target.dataset.userId;
            const username = target.dataset.username;
            const isBanned = target.dataset.isBanned === '1';
            const actionUrl = `/pcbuild/public/admin/users/toggle-ban/${userId}`;

            showConfirmationModal(
                isBanned ? 'Unban User' : 'Ban User',
                `Are you sure you want to ${isBanned ? 'unban' : 'ban'} user ${username}?`,
                (confirmed) => {
                    if (confirmed) {
                        performActionViaFetch(actionUrl, 'POST', {})
                            .then(response => {
                                window.location.reload();
                            })
                            .catch(error => {
                                alertMessage('error', 'An error occurred during the ban/unban action.');
                            });
                    }
                }
            );
        }

        if (target.matches('.js-delete-user-btn')) {
            e.preventDefault();
            const userId = target.dataset.userId;
            const username = target.dataset.username;
            const actionUrl = `/pcbuild/public/admin/users/delete/${userId}`;

            showConfirmationModal(
                'Delete User',
                `Are you sure you want to delete user ${username}? This action cannot be undone.`,
                (confirmed) => {
                    if (confirmed) {
                        performActionViaFetch(actionUrl, 'POST', {})
                            .then(response => {
                                window.location.reload();
                            })
                            .catch(error => {
                                alertMessage('error', 'An error occurred during the delete action.');
                            });
                    }
                }
            );
        }

        const deleteProductButton = target.closest('.js-delete-product-btn');
        if (deleteProductButton) {
            e.preventDefault();
            const productId = deleteProductButton.dataset.productId;
            const productName = deleteProductButton.dataset.productName;
            const actionUrl = `/pcbuild/public/admin/products/delete/${productId}`;

            showConfirmationModal(
                'Delete Product',
                `Are you sure you want to delete product "${productName}"? This action cannot be undone.`,
                (confirmed) => {
                    if (confirmed) {
                        fetch(actionUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: new URLSearchParams({ product_id: productId }).toString(),
                        })
                        .then(response => {
                            if (response.redirected) {
                                window.location.href = response.url;
                            } else {
                                return response.json()
                                    .then(errorData => {
                                        alertMessage('error', errorData.error || 'Deletion failed with unexpected response.');
                                    })
                                    .catch(() => {
                                        alertMessage('error', 'Deletion failed: Unexpected non-redirecting response from server.');
                                    });
                            }
                        })
                        .catch(error => {
                            alertMessage('error', 'A network error occurred during deletion. Please try again.');
                        });
                    }
                }
            );
        }
    });
}

// [NEW FUNCTION] Handle newsletter subscription form submission (dummy modal)
function initializeNewsletterSubscription() {
    const newsletterForm = document.getElementById('newsletter-form');
    const newsletterEmailInput = document.getElementById('newsletter-email');
    const newsletterSuccessModal = document.getElementById('newsletter-success-modal');
    const newsletterModalCloseBtn = document.getElementById('newsletter-modal-close-btn');

    if (newsletterForm && newsletterEmailInput && newsletterSuccessModal && newsletterModalCloseBtn) {
        newsletterForm.addEventListener('submit', (e) => {
            e.preventDefault(); // Prevent default form submission

            const userEmail = newsletterEmailInput.value.trim();

            if (!userEmail) {
                alertMessage('error', 'Please enter your email address.');
                return;
            }

            // Basic email validation regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(userEmail)) {
                alertMessage('error', 'Please enter a valid email format.');
                return;
            }

            // --- Dummy Submission Logic ---
            // Disable form elements temporarily
            newsletterEmailInput.disabled = true;
            newsletterForm.querySelector('button[type="submit"]').disabled = true;
            newsletterForm.querySelector('button[type="submit"]').textContent = 'Subscribing...';
            newsletterForm.querySelector('button[type="submit"]').classList.add('opacity-50', 'cursor-not-allowed');

            // Simulate an async operation (e.g., sending data to a server)
            setTimeout(() => {
                // Show the success modal with animation
                newsletterSuccessModal.classList.remove('hidden', 'pointer-events-none');
                setTimeout(() => {
                    newsletterSuccessModal.classList.add('opacity-100');
                    newsletterSuccessModal.querySelector('div').classList.remove('scale-95', 'opacity-0');
                    newsletterSuccessModal.querySelector('div').classList.add('scale-100', 'opacity-100');
                }, 10);

                // Clear the input field
                newsletterEmailInput.value = '';

                // Re-enable form elements
                newsletterEmailInput.disabled = false;
                newsletterForm.querySelector('button[type="submit"]').disabled = false;
                newsletterForm.querySelector('button[type="submit"]').textContent = 'Subscribe';
                newsletterForm.querySelector('button[type="submit"]').classList.remove('opacity-50', 'cursor-not-allowed');
            }, 1000); // Simulate 1 second delay

        });

        // Event listener to close the modal
        newsletterModalCloseBtn.addEventListener('click', () => {
            newsletterSuccessModal.classList.remove('opacity-100');
            newsletterSuccessModal.querySelector('div').classList.remove('scale-100', 'opacity-100');
            newsletterSuccessModal.querySelector('div').classList.add('scale-95', 'opacity-0');
            newsletterSuccessModal.addEventListener('transitionend', () => {
                newsletterSuccessModal.classList.add('hidden', 'pointer-events-none');
            }, { once: true });
        });

        // Close modal if clicked outside of the content box
        newsletterSuccessModal.addEventListener('click', (e) => {
            if (e.target === newsletterSuccessModal) {
                newsletterSuccessModal.classList.remove('opacity-100');
                newsletterSuccessModal.querySelector('div').classList.remove('scale-100', 'opacity-100');
                newsletterSuccessModal.querySelector('div').classList.add('scale-95', 'opacity-0');
                newsletterSuccessModal.addEventListener('transitionend', () => {
                    newsletterSuccessModal.classList.add('hidden', 'pointer-events-none');
                }, { once: true });
            }
        });
    }
}