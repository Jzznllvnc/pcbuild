// Function to initialize or retrieve the cart from localStorage
function getCart() {
    const cart = localStorage.getItem('pcbuild_cart');
    return cart ? JSON.parse(cart) : [];
}

// Function to save the cart to localStorage
function saveCart(cart) {
    localStorage.setItem('pcbuild_cart', JSON.stringify(cart));
    updateCartCount(); // Update the cart count in the header whenever cart changes
}

// Function to add a product to the cart
function addToCart(productId, productName, productPrice, productImage, quantity = 1) {
    let cart = getCart();
    // Ensure productId is treated as a string for consistent comparison,
    // as IDs from HTML data attributes might be strings.
    const existingItemIndex = cart.findIndex(item => String(item.id) === String(productId));

    if (existingItemIndex > -1) {
        // Item already in cart, update quantity
        cart[existingItemIndex].quantity += quantity;
    } else {
        // Item not in cart, add new
        cart.push({
            id: productId,
            name: productName,
            price: parseFloat(productPrice), // Ensure price is a number
            image: productImage,
            quantity: quantity
        });
    }

    saveCart(cart);
    alertMessage('success', `${productName} added to cart!`); // Custom alert
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
        console.error("Missing quantity control elements for:", container.id);
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


// Function to update item quantity in cart
function updateCartItemQuantity(productId, newQuantity) {
    let cart = getCart();
    const itemIndex = cart.findIndex(item => String(item.id) === String(productId));

    if (itemIndex > -1) {
        // Ensure newQuantity is at least 1 before updating, unless it's 0 (to remove item)
        // This prevents the quantity from going below 1 via the minus button.
        const quantityToSet = Math.max(0, newQuantity); // Allow 0 to enable removal logic below

        if (quantityToSet > 0) {
            cart[itemIndex].quantity = quantityToSet;
        } else {
            // If newQuantity is 0 or less, remove the item
            cart.splice(itemIndex, 1);
        }
        saveCart(cart);
        // Re-render cart if on cart page (logic for this will be in cart/index.php)
        if (window.location.pathname.includes('/cart')) {
            renderCartItems();
        }
    }
}

// Function to remove a product from the cart
function removeFromCart(productId) {
    let cart = getCart();
    // Ensure productId is treated as a string for consistent comparison
    cart = cart.filter(item => String(item.id) !== String(productId));
    saveCart(cart);
    alertMessage('success', 'Item removed from cart.'); // Custom alert
    // Re-render cart if on cart page
    if (window.location.pathname.includes('/cart')) {
        renderCartItems();
    }
}


// Function to clear the entire cart
function clearCart() {
    localStorage.setItem('pcbuild_cart', JSON.stringify([])); // Explicitly set empty array
    updateCartCount(); // Ensure the header count also updates to 0
    if (window.location.pathname.includes('/cart')) {
        renderCartItems(); // Re-render cart on the cart page after clearing
    }
    alertMessage('success', 'Cart cleared!'); // Provide feedback to the user
}


// Function to update the cart count displayed in the header
function updateCartCount() {
    const cart = getCart();
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.getElementById('cart-item-count');
    if (cartCountElement) {
        cartCountElement.textContent = totalItems > 0 ? totalItems : ''; // Show count only if > 0
        cartCountElement.classList.toggle('hidden', totalItems === 0);
    }
}

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
    initializeConfirmationModals(); // Call new initializer
    // renderCartItems will be called by cart/index.php if on that page.
});


// The following functions are for the cart page specifically
// They will only run if the element #cart-items-container exists

function renderCartItems() {
    const cartItemsContainer = document.getElementById('cart-items-container');
    const cart = getCart();
    let cartHtml = '';
    let subtotal = 0;

    if (cart.length === 0) {
        cartHtml = '<p class="text-center text-gray-600 text-lg py-8">Your cart is empty.</p>';
        document.getElementById('checkout-section').classList.add('hidden'); // Hide checkout if cart is empty
    } else {
        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;

            // Determine if the minus button should be disabled
            const isMinusDisabled = item.quantity <= 1 ? 'disabled' : '';
            const minusButtonClasses = item.quantity <= 1 ? 'opacity-50 cursor-not-allowed' : '';

            cartHtml += `
                <div class="flex items-center border-b border-gray-200 py-4">
                    <img src="${item.image || 'https://placehold.co/80x80/e2e8f0/475569?text=No+Image'}" alt="${item.name}" class="w-20 h-20 object-contain rounded-md mr-4">
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
                        <button class="quantity-btn p-1 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors" onclick="updateCartItemQuantity(${item.id}, ${item.quantity + 1})">
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
        document.getElementById('checkout-section').classList.remove('hidden'); // Hide checkout if cart is empty
    }

    cartItemsContainer.innerHTML = cartHtml;
    document.getElementById('cart-subtotal').textContent = subtotal.toFixed(2);

    // No need to attach change listeners to quantity inputs as buttons handle updates directly.
}


// --- AI Chat Pop-up Logic (Centralized and Initialized) ---
let chatHistory = []; // Moved global here
// Declared globally, initialized inside initializeAiChat
let chatMessages, userInput, sendButton, chatInputForm;

function initializeAiChat() {
    const aiChatFab = document.getElementById('ai-chat-fab');
    const aiChatSidebar = document.getElementById('ai-chat-sidebar');
    const closeChatSidebarButton = document.getElementById('close-chat-sidebar');
    const chatContentPlaceholder = document.getElementById('ai-chat-content-placeholder');
    const body = document.body; // Reference to the body element

    // Function to load initial chat content (now only HTML)
    async function loadInitialChatContent() {
        try {
            const response = await fetch('/pcbuild/public/ai-chat-content'); // This fetches the pure HTML
            const htmlContent = await response.text();

            if (response.ok) {
                chatContentPlaceholder.innerHTML = htmlContent;

                // Now that content is loaded, get references to its elements
                chatMessages = document.getElementById('chat-messages');
                userInput = document.getElementById('user-input');
                sendButton = document.getElementById('send-button');
                chatInputForm = document.getElementById('chat-input-form'); // Get form reference

                // Initialize chat history and display initial message ONLY IF it's empty
                // This prevents duplicate initial messages if sidebar is opened/closed
                if (chatHistory.length === 0) {
                    const initialMessage = "Hello! I'm Kraft-E, your PC Build Assistant. Ask me anything about PC components, compatibility, or general build advice!";
                    chatHistory.push({ role: "model", text: initialMessage });
                    // Append this initial message to the displayed chat
                    appendMessage('model', initialMessage);
                }

                // Attach event listeners for chat functionality
                if (chatInputForm) { // Attach listener to the form to handle submission
                    chatInputForm.addEventListener('submit', (e) => {
                        e.preventDefault(); // Prevent default form submission (page reload)
                        sendMessage();
                    });
                } else if (sendButton && userInput) { // Fallback for direct button click if form not found
                    sendButton.addEventListener('click', sendMessage);
                    userInput.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault(); // Also prevent default for keypress if not handled by form submit
                            sendMessage();
                        }
                    });
                }

                // Ensure chat messages scroll to bottom after content load
                if (chatMessages) {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }

            } else {
                chatContentPlaceholder.innerHTML = `<p class="text-center text-red-600 py-8">Failed to load AI Chat content.</p>`;
                console.error('Failed to fetch AI chat content:', htmlContent);
            }
        } catch (error) {
            alertMessage('error', 'Could not connect to the AI assistant.');
            console.error('Fetch error:', error);
        }
    }

    // Load chat content immediately when the page's DOM is ready
    loadInitialChatContent();

    // Toggle sidebar visibility
    if (aiChatFab) {
        aiChatFab.addEventListener('click', () => {
            aiChatSidebar.classList.add('open'); // Show sidebar
            body.classList.add('ai-chat-open'); // Add class to body to prevent scrolling
            if (chatMessages) { // Ensure scroll after opening
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            if (userInput) { // Focus on input field when chat opens
                userInput.focus();
            }
        });
    }

    if (closeChatSidebarButton) {
        closeChatSidebarButton.addEventListener('click', () => {
            aiChatSidebar.classList.remove('open'); // Hide sidebar
            body.classList.remove('ai-chat-open'); // Remove class from body
        });
    }

    // Close sidebar if clicked outside
    document.addEventListener('click', (event) => {
        // Check if the click is outside the sidebar AND outside the FAB, and if the sidebar is open
        if (aiChatSidebar && aiChatFab && aiChatSidebar.classList.contains('open') &&
            !aiChatSidebar.contains(event.target) &&
            !aiChatFab.contains(event.target)) {
            aiChatSidebar.classList.remove('open');
            body.classList.remove('ai-chat-open');
        }
    });
}

// Function to append a message to the chat display (made globally accessible)
function appendMessage(role, text) {
    if (!chatMessages) { // Ensure chatMessages element exists before appending
        console.error("Chat messages container not found!");
        return;
    }

    const messageDiv = document.createElement('div');
    // Ensure padding for message bubbles directly, not just their container
    messageDiv.className = `flex items-start ${role === 'user' ? 'justify-end' : ''} p-2`;

    const avatar = document.createElement('div');
    // Updated avatar colors and added margin for spacing
    avatar.className = `flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm ${role === 'user' ? 'bg-[--color-primary-orange] ml-3' : 'bg-[--color-dark-blue] mr-3'}`;
    avatar.textContent = role === 'user' ? 'You' : 'AI';

    const textBubble = document.createElement('div');
    // Updated text bubble colors for user message
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
    chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom
}

// Function to send message to AI API (made globally accessible)
async function sendMessage() {
    const prompt = userInput.value.trim();
    if (prompt === '') return;

    appendMessage('user', prompt);
    chatHistory.push({ role: "model", text: prompt });
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
            console.error('AI API Error:', data.error);
        }
    } catch (error) {
        alertMessage('error', 'Could not connect to the AI assistant.');
        console.error('Fetch error:', error);
    } finally {
        sendButton.disabled = false;
        sendButton.textContent = 'Send';
        sendButton.classList.remove('opacity-50', 'cursor-not-allowed');
        chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom
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
        console.error('Logout modal element not found!');
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
    console.log("Logout button element found:", logoutButton); // Debugging line

    if (logoutButton) {
        logoutButton.addEventListener('click', (e) => {
            e.preventDefault(); // Prevent default link behavior
            console.log("Logout button clicked, showing confirmation modal."); // Debugging line
            showLogoutConfirmation();
        });
    } else {
        console.error("Logout button with ID 'logout-button' not found on DOMContentLoaded."); // Debugging line
    }

    const confirmLogoutBtn = document.getElementById('confirm-logout-btn');
    const cancelLogoutBtn = document.getElementById('cancel-logout-btn');

    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener('click', () => {
            console.log("Logout confirmed, submitting logout form."); // Debugging line
            const logoutForm = document.getElementById('logout-form'); // Get the hidden logout form
            if (logoutForm) {
                // Use requestSubmit() for more robust programmatic submission
                if (typeof logoutForm.requestSubmit === 'function') {
                    logoutForm.requestSubmit();
                    console.log("Logout form submitted with requestSubmit().");
                } else {
                    logoutForm.submit();
                    console.log("Logout form submitted with .submit().");
                }
            } else {
                console.error("Logout form not found with ID 'logout-form'.");
                // Fallback to direct redirect if form is not found (less reliable for POST)
                window.location.href = '/pcbuild/public/logout';
            }
        });
    }

    if (cancelLogoutBtn) {
        cancelLogoutBtn.addEventListener('click', () => {
            console.log("Logout cancelled."); // Debugging line
            hideLogoutConfirmation();
        });
    }

    const logoutModal = document.getElementById('logout-confirmation-modal');
    if (logoutModal) {
        logoutModal.addEventListener('click', (e) => {
            if (e.target === logoutModal) {
                console.log("Clicked outside logout modal, hiding."); // Debugging line
                hideLogoutConfirmation();
            }
        });
    }
}

// --- Custom Confirmation Modal Logic ---
let currentConfirmationCallback = null; // Stores the function to call on confirmation

// These functions are defined here so they are hoisted/available before initializeConfirmationModals might reference them.
/**
 * Shows a custom confirmation modal.
 * @param {string} title The title for the modal.
 * @param {string} message The message to display in the modal.
 * @param {function(boolean): void} callback Function to call when user confirms/cancels.
 * Receives `true` for confirm, `false` for cancel.
 */
function showConfirmationModal(title, message, callback) {
    const confirmationModal = document.getElementById('confirmation-modal');
    const modalTitle = document.getElementById('confirmation-modal-title');
    const modalMessage = document.getElementById('confirmation-modal-message');

    if (!confirmationModal || !modalTitle || !modalMessage) {
        console.error("Custom confirmation modal elements not found!");
        return; // Prevent errors if elements are missing
    }

    modalTitle.textContent = title;
    modalMessage.textContent = message;
    currentConfirmationCallback = callback; // Store the callback

    // Show modal with animation and make it interactive
    confirmationModal.classList.remove('hidden', 'pointer-events-none');
    setTimeout(() => {
        confirmationModal.classList.add('opacity-100');
        confirmationModal.querySelector('div').classList.remove('scale-95', 'opacity-0');
        confirmationModal.querySelector('div').classList.add('scale-100', 'opacity-100');
    }, 10);
    console.log("Custom confirmation modal shown."); // Debugging line
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
    console.log("Custom confirmation modal hidden."); // Debugging line
}

// Helper function to perform fetch requests for actions.
async function performActionViaFetch(url, method, body = {}) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded', // Standard form submission type
        },
    };

    // For POST/PUT, convert body object to URL-encoded string
    if (method === 'POST' || method === 'PUT') {
        const formData = new URLSearchParams();
        for (const key in body) {
            formData.append(key, body[key]);
        }
        options.body = formData.toString();
    }

    console.log("Initiating fetch request:", url, options); // Debugging: Fetch request details

    try {
        const response = await fetch(url, options);
        console.log("Fetch request completed. Status:", response.status); // Debugging: Fetch response status

        if (!response.ok) {
            const errorText = await response.text();
            console.error(`Fetch request failed with status ${response.status}:`, errorText);
            throw new Error(`HTTP error! status: ${response.status}, response: ${errorText}`);
        }

        // Try to parse JSON, but handle cases where response might be empty or not JSON (e.g., redirect)
        try {
            const jsonResponse = await response.json();
            return jsonResponse;
        } catch (e) {
            console.warn("Fetch response was not JSON or empty. Likely a redirect or simple success.", e);
            return {}; // Return empty object if not JSON
        }
    } catch (error) {
        console.error('Caught error during fetch operation:', error); // More generic fetch error handling
        alertMessage('error', 'An error occurred during the action. Please check console for details.');
        throw error; // Re-throw to propagate for further handling if needed
    }
}


function initializeConfirmationModals() {
    const confirmationModal = document.getElementById('confirmation-modal');
    const confirmActionBtn = document.getElementById('confirm-action-btn');
    const cancelActionBtn = document.getElementById('cancel-action-btn');

    if (confirmActionBtn) {
        confirmActionBtn.addEventListener('click', () => {
            console.log("Custom modal confirmed button clicked."); // Debugging line
            hideConfirmationModal(); // Hides modal
            // IMPORTANT: Get the callback before potentially clearing it in hideConfirmationModal if it was there
            const callbackToExecute = currentConfirmationCallback;
            currentConfirmationCallback = null; // Clear it immediately before executing, or after. Safer here.

            if (callbackToExecute) {
                callbackToExecute(true); // Execute callback with true for confirm
            }
        });
    }

    if (cancelActionBtn) {
        cancelActionBtn.addEventListener('click', () => {
            console.log("Custom modal cancelled button clicked."); // Debugging line
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
                console.log("Clicked outside custom modal, treating as cancel."); // Debugging line
                hideConfirmationModal();
                const callbackToExecute = currentConfirmationCallback;
                currentConfirmationCallback = null;

                if (callbackToExecute) {
                    callbackToExecute(false);
                }
            }
        });
    }

    // --- Event Delegation for Ban/Delete Buttons ---
    document.addEventListener('click', (e) => {
        const target = e.target;

        // Check if the clicked element (or its parent) is a Ban/Unban button
        if (target.matches('.js-toggle-ban-btn')) {
            e.preventDefault(); // Prevent default button behavior (if any)
            const userId = target.dataset.userId;
            const username = target.dataset.username;
            const isBanned = target.dataset.isBanned === '1'; // Convert to boolean
            const actionUrl = `/pcbuild/public/admin/users/toggle-ban/${userId}`;

            console.log("Ban/Unban button clicked. User ID:", userId, "Username:", username, "Is Banned:", isBanned); // Debugging: Button click detected

            showConfirmationModal(
                isBanned ? 'Unban User' : 'Ban User',
                `Are you sure you want to ${isBanned ? 'unban' : 'ban'} user ${username}?`,
                (confirmed) => {
                    if (confirmed) {
                        console.log('Callback: Confirmed BAN/UNBAN for user ID:', userId); // Debugging: Callback received true
                        performActionViaFetch(actionUrl, 'POST', {}) // Perform action via fetch
                            .then(response => {
                                console.log("Fetch response for Ban/Unban:", response); // Debugging: Check fetch response
                                window.location.reload(); // Reload page to reflect changes
                            })
                            .catch(error => {
                                console.error('Fetch error for Ban/Unban:', error); // Debugging: Log fetch error
                                alertMessage('error', 'An error occurred during the ban/unban action.');
                            });
                    } else {
                        console.log('Callback: BAN/UNBAN cancelled for user ID:', userId); // Debugging: Callback received false
                    }
                }
            );
        }

        // Check if the clicked element (or its parent) is a Delete button
        if (target.matches('.js-delete-user-btn')) {
            e.preventDefault(); // Prevent default button behavior (if any)
            const userId = target.dataset.userId;
            const username = target.dataset.username;
            const actionUrl = `/pcbuild/public/admin/users/delete/${userId}`;

            console.log("Delete button clicked. User ID:", userId, "Username:", username); // Debugging: Button click detected

            showConfirmationModal(
                'Delete User',
                `Are you sure you want to delete user ${username}? This action cannot be undone.`,
                (confirmed) => {
                    if (confirmed) {
                        console.log('Callback: Confirmed DELETE for user ID:', userId); // Debugging: Callback received true
                        performActionViaFetch(actionUrl, 'POST', {}) // Perform action via fetch
                            .then(response => {
                                console.log("Fetch response for Delete:", response); // Debugging: Check fetch response
                                window.location.reload(); // Reload page to reflect changes
                            })
                            .catch(error => {
                                console.error('Fetch error for Delete:', error); // Debugging: Log fetch error
                                alertMessage('error', 'An error occurred during the delete action.');
                            });
                    } else {
                        console.log('Callback: DELETE cancelled for user ID:', userId); // Debugging: Callback received false
                    }
                }
            );
        }
    });
}