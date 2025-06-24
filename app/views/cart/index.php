<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg mt-40 mb-8 max-w-4xl">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center"><?php echo htmlspecialchars($title); ?></h1>

    <div id="cart-items-container" class="border border-gray-200 rounded-lg p-4 mb-6">
        <p class="text-center text-gray-500 py-8">Loading cart...</p>
    </div>

    <div id="checkout-section" class="border-t border-gray-200 pt-6 mt-6">
        <div class="flex justify-between items-center text-xl font-bold text-gray-800 mb-4">
            <span>Subtotal:</span>
            <span>$<span id="cart-subtotal">0.00</span></span>
        </div>
        <div class="flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-4">
            <button onclick="clearCart()"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-md transition-colors shadow-lg">
                Clear Cart
            </button>
            <?php if ($isLoggedIn): /* Check if user is logged in */ ?>
                <a href="/pcbuild/public/checkout"
                   id="proceed-to-checkout-button"
                   class="inline-flex justify-center items-center bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-3 px-6 rounded-md transition-colors shadow-lg">
                    Proceed to Checkout
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            <?php else: /* If not logged in */ ?>
                <button type="button" disabled
                        id="proceed-to-checkout-button"
                        class="inline-flex justify-center items-center bg-gray-400 text-white font-bold py-3 px-6 rounded-md shadow-lg cursor-not-allowed">
                    You must be logged in to checkout
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Ensure renderCartItems is called when the DOM is fully loaded for this page
    document.addEventListener('DOMContentLoaded', () => {
        // Only call renderCartItems if the container exists, ensuring it's the cart page
        if (document.getElementById('cart-items-container')) {
            renderCartItems();
        }

        // Adjust the "Proceed to Checkout" button's state based on cart emptiness and login status
        const checkoutButton = document.getElementById('proceed-to-checkout-button');
        const cart = getCart(); // Get current cart items

        if (cart.length === 0) {
            // If cart is empty, always disable the button and show appropriate message
            checkoutButton.disabled = true;
            checkoutButton.classList.add('opacity-50', 'cursor-not-allowed');
            checkoutButton.textContent = 'Your cart is empty'; // Override text if cart is empty
        } else {
            // If cart has items, and user is not logged in, PHP has already handled the text and disabled state
            // If cart has items and user is logged in, PHP has rendered the active link
        }
    });
</script>