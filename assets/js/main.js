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

// Function to update item quantity in cart
function updateCartItemQuantity(productId, newQuantity) {
    let cart = getCart();
    const itemIndex = cart.findIndex(item => String(item.id) === String(productId));

    if (itemIndex > -1) {
        if (newQuantity > 0) {
            cart[itemIndex].quantity = newQuantity;
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
    // Changed 'top-4' to 'top-16' to move it further down from the top
    alertBox.className = `fixed top-16 right-4 p-4 rounded-md shadow-lg text-white z-50 transition-all duration-300 transform -translate-y-full opacity-0`;

    if (type === 'success') {
        alertBox.classList.add('bg-green-500'); // Keep green for success alerts
    } else if (type === 'error') {
        alertBox.classList.add('bg-red-500'); // Keep red for error alerts
    } else {
        alertBox.classList.add('bg-gray-700');
    }

    alertBox.textContent = message;
    document.body.appendChild(alertBox);

    // Animate in (changed translate direction)
    setTimeout(() => {
        alertBox.classList.remove('-translate-y-full', 'opacity-0');
        alertBox.classList.add('translate-y-0', 'opacity-100');
    }, 100);

    // Animate out and remove (changed translate direction)
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
            cartHtml += `
                <div class="flex items-center border-b border-gray-200 py-4">
                    <img src="${item.image || 'https://placehold.co/80x80/e2e8f0/475569?text=No+Image'}" alt="${item.name}" class="w-20 h-20 object-contain rounded-md mr-4">
                    <div class="flex-grow">
                        <h3 class="text-lg font-semibold text-gray-800">${item.name}</h3>
                        <p class="text-gray-600 text-sm">$${item.price.toFixed(2)}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="number"
                               min="1"
                               value="${item.quantity}"
                               data-product-id="${item.id}"
                               class="w-16 text-center border border-gray-300 rounded-md py-1 quantity-input">
                        <span class="text-lg font-semibold text-gray-800">$${itemTotal.toFixed(2)}</span>
                        <button onclick="removeFromCart(${item.id})" class="ml-4 text-red-600 hover:text-red-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        });
        document.getElementById('checkout-section').classList.remove('hidden'); // Show checkout if items exist
    }

    cartItemsContainer.innerHTML = cartHtml;
    document.getElementById('cart-subtotal').textContent = subtotal.toFixed(2);

    // Attach event listeners to quantity inputs
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', (event) => {
            const productId = parseInt(event.target.dataset.productId);
            const newQuantity = parseInt(event.target.value);
            updateCartItemQuantity(productId, newQuantity);
        });
    });
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
            chatContentPlaceholder.innerHTML = `<p class="text-center text-red-600 py-8">Error loading AI Chat content.`;
            console.error('Error loading AI chat content:', error);
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
    // Updated avatar colors
    avatar.className = `flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-sm ${role === 'user' ? 'bg-[--color-primary-orange]' : 'bg-[--color-dark-blue]'}`;
    avatar.textContent = role === 'user' ? 'You' : 'AI';

    const textBubble = document.createElement('div');
    // Updated text bubble colors
    textBubble.className = `p-3 rounded-lg max-w-[80%] shadow-sm ${role === 'user' ? 'bg-[--color-light-bg] text-gray-800 ml-auto' : 'bg-gray-200 text-gray-800 mr-auto'} markdown-content`;
    
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
            appendMessage('model', `Error: ${data.error || 'Something went wrong.'}`);
            console.error('AI API Error:', data.error);
        }
    } catch (error) {
        appendMessage('model', 'Error: Could not connect to the AI assistant.');
        console.error('Fetch error:', error);
    } finally {
        sendButton.disabled = false;
        sendButton.textContent = 'Send';
        sendButton.classList.remove('opacity-50', 'cursor-not-allowed');
        chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to bottom
    }
}
