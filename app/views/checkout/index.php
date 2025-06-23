<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg my-8 max-w-3xl">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center"><?php echo htmlspecialchars($title); ?></h1>

    <?php if (isset($_SESSION['error']) && $_SESSION['error']): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($_SESSION['error']); ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your Order Summary</h2>
    <div id="checkout-cart-summary" class="border border-gray-200 rounded-lg p-4 mb-6">
        <p class="text-center text-gray-500 py-4">Your cart is empty. Please add items to proceed.</p>
    </div>

    <div class="flex justify-between items-center text-xl font-bold text-gray-800 mb-6 border-t border-gray-200 pt-4">
        <span>Total Amount:</span>
        <span>$<span id="checkout-total-amount">0.00</span></span>
    </div>

    <form id="checkout-form" action="/pcbuild/public/checkout/process" method="POST" class="space-y-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Payment Method</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <label class="flex items-center cursor-pointer p-4 border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 has-[:checked]:border-[--color-primary-orange] has-[:checked]:ring-2 has-[:checked]:ring-[--color-primary-orange]">
                <input type="radio" name="payment_method" value="GCash" class="form-radio text-[--color-primary-orange] h-5 w-5 flex-shrink-0" required>
                <span class="ml-4 text-lg font-medium text-gray-700 flex items-center">
                    <img src="/pcbuild/assets/images/gcash.svg" alt="GCash Logo" class="h-8 w-auto mr-3 object-contain">
                    GCash
                </span>
            </label>
            <label class="flex items-center cursor-pointer p-4 border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 has-[:checked]:border-[--color-primary-orange] has-[:checked]:ring-2 has-[:checked]:ring-[--color-primary-orange]">
                <input type="radio" name="payment_method" value="PayPal" class="form-radio text-[--color-primary-orange] h-5 w-5 flex-shrink-0" required>
                <span class="ml-4 text-lg font-medium text-gray-700 flex items-center">
                    <img src="/pcbuild/assets/images/paypal.svg" alt="PayPal Logo" class="h-8 w-auto mr-3 object-contain">
                    PayPal
                </span>
            </label>
        </div>

        <input type="hidden" name="cart_items_json" id="cart-items-json">
        <input type="hidden" name="total_amount" id="total-amount-hidden">

        <button type="submit"
                id="place-order-button"
                class="w-full flex justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-[--color-dark-blue] hover:bg-[#1a2d3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[--color-primary-orange] transition-colors">
            Place Order
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cart = getCart(); // From main.js
        const checkoutCartSummary = document.getElementById('checkout-cart-summary');
        const checkoutTotalAmountSpan = document.getElementById('checkout-total-amount');
        const cartItemsJsonInput = document.getElementById('cart-items-json');
        const totalAmountHiddenInput = document.getElementById('total-amount-hidden');
        const placeOrderButton = document.getElementById('place-order-button');

        let total = 0;
        let summaryHtml = '';

        if (cart.length === 0) {
            summaryHtml = '<p class="text-center text-red-600 font-semibold py-4">Your cart is empty. Please add items to proceed to checkout.</p>';
            placeOrderButton.disabled = true; // Disable button if cart is empty
            placeOrderButton.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                summaryHtml += `
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center">
                            <img src="${item.image || 'https://placehold.co/40x40/e2e8f0/475569?text=Img'}" alt="${item.name}" class="w-10 h-10 object-contain rounded-md mr-3">
                            <span class="text-gray-800">${item.name}</span>
                        </div>
                        <span class="text-gray-700">${item.quantity} x $${item.price.toFixed(2)} = <strong>$${itemTotal.toFixed(2)}</strong></span>
                    </div>
                `;
            });
            placeOrderButton.disabled = false; // Enable button
            placeOrderButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }

        checkoutCartSummary.innerHTML = summaryHtml;
        checkoutTotalAmountSpan.textContent = total.toFixed(2);

        // Set hidden input values for form submission
        cartItemsJsonInput.value = JSON.stringify(cart);
        totalAmountHiddenInput.value = total.toFixed(2);

        // Client-side validation for payment method selection
        document.getElementById('checkout-form').addEventListener('submit', (e) => {
            const paymentMethodSelected = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethodSelected) {
                e.preventDefault();
                alertMessage('error', 'Please select a payment method.');
            }
        });
    });
</script>