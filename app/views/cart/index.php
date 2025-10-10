<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg mt-28 mb-16 max-w-5xl">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-12 mt-6 text-center"><?php echo htmlspecialchars($title); ?></h1>

    <div id="cart-items-container" class="border border-gray-200 rounded-lg p-4 mb-6">
        <p class="text-center text-gray-500 py-8">Loading cart...</p>
    </div>

    <div id="empty-cart-state" class="text-center py-10 hidden">
        <img src="<?php echo BASE_URL; ?>/assets/images/emptycart.png" alt="Empty Cart" class="mx-auto w-48 h-48 object-contain mb-6">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Your Cart is <span class="text-[--color-primary-orange]">Empty!</span></h2>
        <p class="text-gray-600 mb-8">Must add items on the cart before you proceed to check out.</p>
        <a href="<?php echo BASE_URL; ?>/products" class="inline-block bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-3 px-8 rounded-full
         shadow-lg transition-all duration-300 transform hover:scale-105">
            Browse Products
        </a>
    </div>

    <div id="checkout-section" class="border-t border-gray-200 pt-6 mt-6">
        <div class="flex justify-between items-center text-xl font-bold text-gray-800 mb-4">
            <span>Subtotal:</span>
            <span>$<span id="cart-subtotal">0.00</span></span>
        </div>
        <div class="flex flex-col items-center gap-4 sm:flex-row sm:justify-end sm:items-stretch sm:gap-4">
            <button onclick="clearCart()"
                class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-md transition-colors shadow-lg w-full max-w-xs sm:w-auto sm:max-w-none">
                Clear Cart
            </button>
            <?php if ($isLoggedIn): ?>
                <a href="<?php echo BASE_URL; ?>/checkout"
                id="proceed-to-checkout-button"
                class="inline-flex justify-center items-center bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-3 px-6 rounded-md transition-colors shadow-lg w-full max-w-xs sm:w-auto sm:max-w-none">
                    Proceed to Checkout
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            <?php else: ?>
                <button type="button" disabled
                    id="proceed-to-checkout-button"
                    class="inline-flex justify-center items-center bg-gray-400 text-white font-bold py-3 px-6 rounded-md shadow-lg cursor-not-allowed w-full max-w-xs sm:w-auto sm:max-w-none">
                    You must be logged in to checkout
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('cart-items-container')) {
            renderCartItems();
        }
    });
</script>