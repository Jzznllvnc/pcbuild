<div class="container mx-auto p-4 md:p-8 pt-20 mt-20 mb-8 max-w-7xl">
    <div class="flex justify-center items-center space-x-2 text-gray-500 mb-10">
        <span id="step-cart" class="text-lg font-semibold text-[--color-dark-blue]">Cart</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
        <span id="step-shipping" class="text-lg font-semibold text-[--color-primary-orange]">Shipping</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
        <span id="step-payment" class="text-lg font-semibold">Payment</span>
    </div>

    <?php if (isset($_SESSION['error']) && $_SESSION['error']): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($_SESSION['error']); ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="flex flex-col lg:flex-row gap-8">
        <div class="lg:w-2/3 bg-white shadow-lg rounded-lg p-6 relative min-h-[475px]" id="main-content-column">
            <div id="shipping-section" class="checkout-step transition-transform duration-500 ease-in-out transform translate-x-0">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Shipping Address</h2>
                <form id="shipping-form" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name<span class="text-red-500">*</span></label>
                            <input type="text" id="first_name" name="first_name" required
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name<span class="text-red-500">*</span></label>
                            <input type="text" id="last_name" name="last_name" required
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email<span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" required
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
                        </div>
                        <div>
                            <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-2">Phone number<span class="text-red-500">*</span></label>
                            <div class="flex flex-col sm:flex-row mt-1">
                                <select id="country_code" name="country_code" class="w-fit px-3 py-2 border border-gray-300 rounded-md sm:rounded-l-md sm:rounded-r-none shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm mb-2 sm:mb-0">
                                    <option value="+63" data-placeholder="e.g., 9123456789">PH +63</option>
                                    <option value="+1" data-placeholder="e.g., 201-555-0123">US +1</option>
                                    <option value="+44" data-placeholder="e.g., 7911 123456">UK +44</option>
                                    <option value="+61" data-placeholder="e.g., 412 345 678">AU +61</option>
                                    <option value="+81" data-placeholder="e.g., 90-1234-5678">JP +81</option>
                                    <option value="+86" data-placeholder="e.g., 138-0013-8000">CN +86</option>
                                    </select>
                                <input type="tel" id="mobile_number" name="mobile_number" placeholder="e.g., 9123456789" required
                                       class="flex-grow px-4 py-2 border border-gray-300 rounded-md sm:rounded-r-md sm:rounded-l-none shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm"
                                       value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>"> </div>
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address<span class="text-red-500">*</span></label>
                        <input type="text" id="address" name="address" required
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City<span class="text-red-500">*</span></label>
                            <input type="text" id="city" name="city" required
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
                        </div>
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State<span class="text-red-500">*</span></label>
                            <input type="text" id="state" name="state" required
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
                        </div>
                        <div>
                            <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">Zip Code<span class="text-red-500">*</span></label>
                            <input type="text" id="zip_code" name="zip_code" required
                                   class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea id="notes" name="notes" rows="4" placeholder="Enter any special notes or delivery instructions..."
                                  class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm"></textarea>
                    </div>

                    <h2 class="text-2xl font-extrabold text-gray-900 mb-4 pt-4">Shipping Method</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-8"> <label class="flex items-center cursor-pointer p-4 border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 has-[:checked]:border-[--color-primary-orange] has-[:checked]:ring-2 has-[:checked]:ring-[--color-primary-orange]">
                            <input type="radio" name="shipping_method" value="Free Shipping" class="form-radio text-[--color-primary-orange] h-5 w-5 flex-shrink-0" checked>
                            <span class="ml-4 text-lg font-medium text-gray-700">Free Shipping (3-5 days)</span>
                            <span class="ml-auto text-gray-500">$0</span>
                        </label>
                        <label class="flex items-center cursor-pointer p-4 border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 has-[:checked]:border-[--color-primary-orange] has-[:checked]:ring-2 has-[:checked]:ring-[--color-primary-orange]">
                            <input type="radio" name="shipping_method" value="Express Shipping" class="form-radio text-[--color-primary-orange] h-5 w-5 flex-shrink-0">
                            <span class="ml-4 text-lg font-medium text-gray-700">Express Shipping<br>(Same-day delivery)</span>
                            <span class="ml-auto text-gray-500">$1.50</span>
                        </label>
                    </div>

                    <button type="button"
                            id="continue-to-payment-button-shipping"
                            class="w-full flex justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-[--color-dark-blue] hover:bg-[#1a2d3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[--color-primary-orange] transition-colors mt-6 hidden">
                        Continue to Payment
                    </button>
                </form>
            </div>

            <div id="payment-section" class="checkout-step absolute top-0 left-0 w-full h-full p-6 bg-white transition-transform duration-500 ease-in-out transform translate-x-full opacity-0">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Payment Method</h2>
                <form id="payment-form-final" action="<?php echo BASE_URL; ?>/checkout/process" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="flex items-center cursor-pointer p-4 border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 has-[:checked]:border-[--color-primary-orange] has-[:checked]:ring-2 has-[:checked]:ring-[--color-primary-orange]">
                            <input type="radio" name="payment_method" value="GCash" class="form-radio text-[--color-primary-orange] h-5 w-5 flex-shrink-0" required>
                            <span class="ml-4 text-lg font-medium text-gray-700 flex items-center">
                                <img src="<?php echo BASE_URL; ?>/assets/images/gcash.svg" alt="GCash Logo" class="h-8 w-auto mr-3 object-contain">
                                GCash
                            </span>
                        </label>
                        <label class="flex items-center cursor-pointer p-4 border border-gray-300 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 has-[:checked]:border-[--color-primary-orange] has-[:checked]:ring-2 has-[:checked]:ring-[--color-primary-orange]">
                            <input type="radio" name="payment_method" value="PayPal" class="form-radio text-[--color-primary-orange] h-5 w-5 flex-shrink-0" required>
                            <span class="ml-4 text-lg font-medium text-gray-700 flex items-center">
                                <img src="<?php echo BASE_URL; ?>/assets/images/paypal.svg" alt="PayPal Logo" class="h-8 w-auto mr-3 object-contain">
                                PayPal
                            </span>
                        </label>
                    </div>

                    <div id="payment-mobile-number-group" class="hidden">
                        <label for="p_mobile_number_display" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number<span class="text-red-500">*</span></label>
                        <input type="tel" id="p_mobile_number_display" name="payment_mobile_number" placeholder="e.g., 09123456789"
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
                        <p id="payment-mobile-number-error" class="text-red-500 text-xs italic mt-2 hidden"></p>
                    </div>

                    <input type="hidden" name="first_name" id="h_first_name">
                    <input type="hidden" name="last_name" id="h_last_name">
                    <input type="hidden" name="email" id="h_email">
                    <input type="hidden" name="country_code" id="h_country_code">
                    <input type="hidden" name="mobile_number" id="h_mobile_number">
                    <input type="hidden" name="address" id="h_address">
                    <input type="hidden" name="city" id="h_city">
                    <input type="hidden" name="state" id="h_state">
                    <input type="hidden" name="zip_code" id="h_zip_code">
                    <input type="hidden" name="notes" id="h_notes">
                    <input type="hidden" name="shipping_method" id="h_shipping_method">
                    <input type="hidden" name="shipping_cost" id="h_shipping_cost">
                    <input type="hidden" name="cart_items_json" id="h_cart_items_json">
                    <input type="hidden" name="total_amount" id="h_total_amount">

                    <div class="flex flex-col sm:flex-row-reverse justify-between gap-4 pt-4">
                        <button type="submit"
                                id="place-order-button"
                                class="w-full sm:w-auto flex-1 justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-[--color-dark-blue] hover:bg-[#1a2d3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[--color-primary-orange] transition-colors">
                            Place Order
                        </button>
                        <button type="button"
                                id="back-to-shipping-button"
                                class="w-full sm:w-auto flex-1 justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-lg font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-colors">
                            Back to Shipping
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:w-1/3 flex flex-col">
            <div class="bg-white shadow-lg rounded-lg p-6 h-fit mb-4">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-6">Your Cart</h2>
                <div id="checkout-cart-summary" class="border-b border-gray-200 pb-4 mb-4">
                    <p class="text-center text-gray-500 py-4">Your cart is empty.</p>
                </div>

                <div class="space-y-2 mb-4 text-gray-700">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>$<span id="cart-subtotal">0.00</span></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span>$<span id="cart-shipping-cost">0.00</span></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Estimated taxes</span>
                        <span>$0.25</span>
                    </div>
                </div>

                <div class="flex justify-between items-center text-xl font-bold text-gray-900 pt-4 border-t border-gray-200">
                    <span>Total</span>
                    <span>$<span id="cart-total-amount">0.00</span></span>
                </div>
            </div>
            <button type="button"
                    id="continue-to-payment-button-outside-summary"
                    class="w-full flex justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-[--color-dark-blue] hover:bg-[#1a2d3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[--color-primary-orange] transition-colors mt-4">
                Continue to Payment
            </button>
        </div>
    </div>
</div>

<style>
    #main-content-column {
        position: relative;
        overflow: hidden;
        min-height: 475px;
    }
    .checkout-step {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 1.5rem;
        transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
        box-sizing: border-box;
        height: auto;
        min-height: fit-content;
    }
    #shipping-section.active, #payment-section.active {
        position: relative;
        transform: translateX(0);
        opacity: 1;
        visibility: visible;
    }
    #shipping-section.slide-out,
    #payment-section.translate-x-full {
        position: absolute;
        visibility: hidden;
        opacity: 0;
        height: 1px;
        overflow: hidden;
    }
    #payment-section {
        transform: translateX(100%);
        opacity: 0;
        visibility: hidden;
    }
    #shipping-section.slide-out {
        transform: translateX(-100%);
        opacity: 0;
        visibility: hidden;
    }

    /* Specific styling for the Your Cart summary content */

    /* Custom Scrollbar Styles for WebKit browsers (Chrome, Safari, Edge) */
    #checkout-cart-summary::-webkit-scrollbar {
        width: 8px;
    }
    #checkout-cart-summary::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    #checkout-cart-summary::-webkit-scrollbar-thumb {
        background: var(--color-primary-orange);
        border-radius: 10px;
    }
    #checkout-cart-summary::-webkit-scrollbar-thumb:hover {
        background: #e76c3e;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkoutCartSummary = document.getElementById('checkout-cart-summary');
        const cartSubtotalSpan = document.getElementById('cart-subtotal');
        const cartShippingCostSpan = document.getElementById('cart-shipping-cost');
        const cartTotalAmountSpan = document.getElementById('cart-total-amount');

        const mainContentColumn = document.getElementById('main-content-column');
        const shippingSection = document.getElementById('shipping-section');
        const paymentSection = document.getElementById('payment-section');
        const shippingForm = document.getElementById('shipping-form');
        const paymentFormFinal = document.getElementById('payment-form-final');
        const continueToPaymentButtonShipping = document.getElementById('continue-to-payment-button-shipping');
        const continueToPaymentButtonOutsideSummary = document.getElementById('continue-to-payment-button-outside-summary');
        const placeOrderButton = document.getElementById('place-order-button');
        const backToShippingButton = document.getElementById('back-to-shipping-button');
        const shippingMethodRadios = document.querySelectorAll('input[name="shipping_method"]');
        
        if (shippingMethodRadios.length > 0) {
            shippingMethodRadios.forEach(radio => {
                radio.addEventListener('change', () => {
                    if (typeof updateOrderSummary === 'function' && currentCartData) {
                        updateOrderSummary(currentCartData);
                    }
                });
            });
        }

        const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
        const paymentMobileNumberGroup = document.getElementById('payment-mobile-number-group');
        const paymentMobileNumberDisplayInput = document.getElementById('p_mobile_number_display');
        const paymentMobileNumberError = document.getElementById('payment-mobile-number-error');
        const stepShipping = document.getElementById('step-shipping');
        const stepPayment = document.getElementById('step-payment');
        const taxRate = 0.00;

        let subtotal = 0;
        let shippingCost = 0;
        let total = 0;
        let currentCartData = [];

        const countryPhoneFormats = {
            "+63": {
                placeholder: "e.g., 9123456789",
                regex: /^9\d{9}$/,
                minLength: 10,
                maxLength: 10,
                error: "Please enter a valid 10-digit Philippine mobile number for shipping (e.g., 9123456789)."
            },
            "+1": {
                placeholder: "e.g., 201-555-0123",
                regex: /^\d{10}$/,
                minLength: 10,
                maxLength: 10,
                error: "Please enter a valid 10-digit US mobile number."
            },
            "+44": {
                placeholder: "e.g., 7911 123456",
                regex: /^(?:7\d{7,9}|0\d{9,10})$/,
                minLength: 9,
                maxLength: 10,
                error: "Please enter a valid UK mobile number."
            },
            "+61": {
                placeholder: "e.g., 412 345 678",
                regex: /^4\d{8}$/,
                minLength: 9,
                maxLength: 9,
                error: "Please enter a valid 9-digit Australian mobile number starting with 4."
            },
            "+81": {
                placeholder: "e.g., 90-1234-5678",
                regex: /^(70|80|90)\d{8}$/,
                minLength: 10,
                maxLength: 10,
                error: "Please enter a valid 10-digit Japanese mobile number."
            },
            "+86": {
                placeholder: "e.g., 138-0013-8000",
                regex: /^1\d{10}$/,
                minLength: 11,
                maxLength: 11,
                error: "Please enter a valid 11-digit Chinese mobile number."
            }
        };

        function setMainContentColumnHeight() {
            requestAnimationFrame(() => {
                const activeSection = shippingSection.classList.contains('active') ? shippingSection : paymentSection;
                const originalVisibility = activeSection.style.visibility;
                const originalPosition = activeSection.style.position;
                const originalTransform = activeSection.style.transform;
                const originalOpacity = activeSection.style.opacity;

                activeSection.style.visibility = 'visible';
                activeSection.style.position = 'relative';
                activeSection.style.transform = 'translateX(0)';
                activeSection.style.opacity = '1';

                const targetHeight = activeSection.scrollHeight;
                mainContentColumn.style.minHeight = `${targetHeight + 20}px`;

                activeSection.style.visibility = originalVisibility;
                activeSection.style.position = originalPosition;
                activeSection.style.transform = originalTransform;
                activeSection.style.opacity = originalOpacity;
            });
        }

        function updateActionButtonsVisibility() {
            if (shippingSection.classList.contains('active')) {
                continueToPaymentButtonOutsideSummary.style.display = 'block';
                placeOrderButton.style.display = 'none';
                backToShippingButton.style.display = 'none';
            } else if (paymentSection.classList.contains('active')) {
                continueToPaymentButtonOutsideSummary.style.display = 'none';
                placeOrderButton.style.display = 'block';
                backToShippingButton.style.display = 'block';
            }
        }

        function updateOrderSummary(cartItems) {
            currentCartData = cartItems;
            subtotal = 0;
            let summaryHtml = '';

            if (currentCartData.length === 0) {
                summaryHtml = '<p class="text-center text-red-600 font-semibold py-4">Your cart is empty. Please add items to proceed to checkout.</p>';
                continueToPaymentButtonShipping.disabled = true;
                continueToPaymentButtonShipping.classList.add('opacity-50', 'cursor-not-allowed');
                continueToPaymentButtonOutsideSummary.disabled = true;
                continueToPaymentButtonOutsideSummary.classList.add('opacity-50', 'cursor-not-allowed');
                placeOrderButton.disabled = true;
                placeOrderButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                currentCartData.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    subtotal += itemTotal;
                    summaryHtml += `
                        <div class="flex items-center justify-between py-2 product-summary-item">
                            <div class="flex items-center">
                                <img src="${item.image || 'https://placehold.co/40x40/e2e8f0/475569?text=Img'}" alt="${item.name}" class="w-10 h-10 object-contain rounded-md mr-3">
                                <div class="flex flex-col">
                                    <span class="text-gray-800 font-medium">${item.name}</span>
                                    <span class="text-gray-500 text-sm">Qty: ${item.quantity}</span>
                                </div>
                            </div>
                            <span class="text-gray-700 font-semibold">$${itemTotal.toFixed(2)}</span>
                        </div>
                    `;
                });
                continueToPaymentButtonShipping.disabled = false;
                continueToPaymentButtonShipping.classList.remove('opacity-50', 'cursor-not-allowed');
                continueToPaymentButtonOutsideSummary.disabled = false;
                continueToPaymentButtonOutsideSummary.classList.remove('opacity-50', 'cursor-not-allowed');
                placeOrderButton.disabled = false;
                placeOrderButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            const selectedShippingMethod = document.querySelector('input[name="shipping_method"]:checked');
            if (selectedShippingMethod && selectedShippingMethod.value === 'Express Shipping') {
                shippingCost = 1.50;
            } else {
                shippingCost = 0.00;
            }

            const estimatedTaxes = 0.25;
            total = subtotal + shippingCost + estimatedTaxes;

            checkoutCartSummary.innerHTML = summaryHtml;
            cartSubtotalSpan.textContent = subtotal.toFixed(2);
            cartShippingCostSpan.textContent = shippingCost.toFixed(2);
            cartTotalAmountSpan.textContent = total.toFixed(2);

            const maxVisibleItems = 3;
            const actualProductRows = checkoutCartSummary.querySelectorAll('.product-summary-item').length;

            if (actualProductRows > maxVisibleItems) {
                requestAnimationFrame(() => {
                    const firstItem = checkoutCartSummary.querySelector('.product-summary-item');
                    if (firstItem) {
                        const itemHeight = firstItem.getBoundingClientRect().height;
                        const targetMaxHeight = (itemHeight * maxVisibleItems) + 25;
                        checkoutCartSummary.style.maxHeight = `${targetMaxHeight}px`;
                        checkoutCartSummary.style.overflowY = 'auto';
                        checkoutCartSummary.style.paddingRight = '1rem';
                        checkoutCartSummary.style.paddingBottom = '0.5rem';
                    }
                });
            } else {
                checkoutCartSummary.style.maxHeight = 'none';
                checkoutCartSummary.style.overflowY = 'visible';
                checkoutCartSummary.style.paddingRight = '0';
                checkoutCartSummary.style.paddingBottom = '0.25rem';
            }
        }

        if (isLoggedIn) {
            performActionViaFetch('/cart/get', 'GET')
                .then(response => {
                    if (response.success && response.cart) {
                        updateOrderSummary(response.cart);
                    } else {
                        updateOrderSummary([]);
                        alertMessage('error', response.error || 'Failed to load cart for checkout.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching cart for checkout:', error);
                    updateOrderSummary([]);
                    alertMessage('error', 'An error occurred while loading your cart.');
                });
        } else {
            updateOrderSummary(getCart()); // getCart is from main.js
        }


        function updatePhoneNumberField(countryCodeElement, phoneNumberElement) {
            const selectedOption = countryCodeElement.options[countryCodeElement.selectedIndex];
            const countryCode = selectedOption.value;
            const format = countryPhoneFormats[countryCode];

            if (format) {
                phoneNumberElement.placeholder = format.placeholder;
                phoneNumberElement.pattern = format.regex ? format.regex.source : '';
                phoneNumberElement.minLength = format.minLength;
                phoneNumberElement.maxLength = format.maxLength;
            } else {
                phoneNumberElement.placeholder = "Enter phone number";
                phoneNumberElement.removeAttribute('pattern');
                phoneNumberElement.removeAttribute('minLength');
                phoneNumberElement.removeAttribute('maxLength');
            }
        }

        const shippingCountryCode = document.getElementById('country_code');
        const shippingMobileNumber = document.getElementById('mobile_number');
        updatePhoneNumberField(shippingCountryCode, shippingMobileNumber);

        shippingCountryCode.addEventListener('change', () => {
            updatePhoneNumberField(shippingCountryCode, shippingMobileNumber);
        });

        function goToPayment() {
            const requiredInputs = shippingForm.querySelectorAll('[required]');
            let formIsValid = true;

            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    formIsValid = false;
                    input.classList.add('border-red-500', 'focus:border-red-500');
                } else {
                    input.classList.remove('border-red-500', 'focus:border-500');
                }
            });

            if (!formIsValid) {
                alertMessage('error', 'Please fill in all required fields for shipping.');
                return;
            }

            const currentCountryCode = shippingCountryCode.value;
            const currentMobileNumber = shippingMobileNumber.value.trim();
            const format = countryPhoneFormats[currentCountryCode];

            if (format && !format.regex.test(currentMobileNumber)) {
                alertMessage('error', format.error);
                shippingMobileNumber.classList.add('border-red-500', 'focus:border-red-500');
                return;
            } else {
                shippingMobileNumber.classList.remove('border-red-500', 'focus:border-red-500');
            }

            document.getElementById('h_first_name').value = document.getElementById('first_name').value;
            document.getElementById('h_last_name').value = document.getElementById('last_name').value;
            document.getElementById('h_email').value = document.getElementById('email').value;
            document.getElementById('h_country_code').value = document.getElementById('country_code').value;
            document.getElementById('h_mobile_number').value = document.getElementById('mobile_number').value;
            document.getElementById('h_address').value = document.getElementById('address').value;
            document.getElementById('h_city').value = document.getElementById('city').value;
            document.getElementById('h_state').value = document.getElementById('state').value;
            document.getElementById('h_zip_code').value = document.getElementById('zip_code').value;
            document.getElementById('h_notes').value = document.getElementById('notes').value;
            document.getElementById('h_shipping_method').value = document.querySelector('input[name="shipping_method"]:checked').value;
            document.getElementById('h_shipping_cost').value = shippingCost.toFixed(2);
            document.getElementById('h_cart_items_json').value = JSON.stringify(currentCartData);
            document.getElementById('h_total_amount').value = total.toFixed(2);

            shippingSection.classList.remove('active');
            shippingSection.classList.add('slide-out');

            paymentSection.style.position = 'absolute';
            paymentSection.classList.add('active');
            paymentSection.classList.remove('translate-x-full', 'opacity-0');

            setTimeout(() => {
                paymentSection.style.visibility = 'visible';
                setMainContentColumnHeight();
                updateOrderSummary(currentCartData);

                backToShippingButton.style.display = 'block';
                placeOrderButton.style.display = 'block';
                continueToPaymentButtonOutsideSummary.style.display = 'none';
            }, 10);


            stepShipping.classList.remove('text-[--color-primary-orange]');
            stepShipping.classList.add('text-[--color-dark-blue]');
            stepPayment.classList.add('text-[--color-primary-orange]');
            stepPayment.classList.remove('text-gray-500');

            togglePaymentMobileNumberInput();
        }

        function goToShipping() {
            // Animate transition
            paymentSection.classList.remove('active');
            paymentSection.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                paymentSection.style.visibility = 'hidden';
            }, 500);

            shippingSection.classList.add('active');
            shippingSection.classList.remove('slide-out');
            shippingSection.style.visibility = 'visible';
            shippingSection.offsetWidth;

            setTimeout(() => {
                setMainContentColumnHeight();
                updateOrderSummary(currentCartData);

                backToShippingButton.style.display = 'none';
                placeOrderButton.style.display = 'none';
                continueToPaymentButtonOutsideSummary.style.display = 'block';
            }, 10);


            stepPayment.classList.remove('text-[--color-primary-orange]');
            stepPayment.classList.add('text-gray-500');
            stepShipping.classList.add('text-[--color-primary-orange]');
            stepShipping.classList.remove('text-[--color-dark-blue]');
        }

        function togglePaymentMobileNumberInput() {
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');

            if (selectedPaymentMethod && (selectedPaymentMethod.value === 'GCash' || selectedPaymentMethod.value === 'PayPal')) {
                paymentMobileNumberGroup.classList.remove('hidden');
                paymentMobileNumberDisplayInput.setAttribute('required', 'required');

                paymentMobileNumberDisplayInput.placeholder = "e.g., 09123456789";
                paymentMobileNumberDisplayInput.minLength = 11;
                paymentMobileNumberDisplayInput.maxLength = 11;

                let prefillMobileNumber = document.getElementById('mobile_number').value;
                const shippingCountryCodeValue = document.getElementById('country_code').value;

                if (shippingCountryCodeValue === '+63' && prefillMobileNumber.length === 10 && prefillMobileNumber.startsWith('9')) {
                    prefillMobileNumber = '0' + prefillMobileNumber;
                }
                paymentMobileNumberDisplayInput.value = prefillMobileNumber;

                paymentMobileNumberError.classList.add('hidden');
                paymentMobileNumberError.textContent = '';

                paymentMobileNumberDisplayInput.focus();
            } else {
                paymentMobileNumberGroup.classList.add('hidden');
                paymentMobileNumberDisplayInput.removeAttribute('required');
                paymentMobileNumberDisplayInput.value = '';
                paymentMobileNumberError.classList.add('hidden');
            }
        }

        paymentMethodRadios.forEach(radio => {
            radio.addEventListener('change', togglePaymentMobileNumberInput);
        });

        // Event listeners for action buttons
        continueToPaymentButtonShipping.addEventListener('click', goToPayment);
        continueToPaymentButtonOutsideSummary.addEventListener('click', goToPayment);

        backToShippingButton.addEventListener('click', () => {
            goToShipping();
        });

        paymentFormFinal.addEventListener('submit', (e) => {
            const paymentMethodSelected = document.querySelector('input[name="payment_method"]:checked');
            if (!paymentMethodSelected) {
                e.preventDefault();
                alertMessage('error', 'Please select a payment method.');
                return;
            }

            if (!paymentMobileNumberGroup.classList.contains('hidden') &&
                (paymentMethodSelected.value === 'GCash' || paymentMethodSelected.value === 'PayPal')) {

                const mobileNumber = paymentMobileNumberDisplayInput.value.trim();
                const phElevenDigitRegex = /^09\d{9}$/;

                if (!phElevenDigitRegex.test(mobileNumber)) {
                    e.preventDefault();
                    paymentMobileNumberError.textContent = "Please enter a valid 11-digit mobile number for GCash/PayPal (e.g., 09123456789).";
                    paymentMobileNumberError.classList.remove('hidden');
                    paymentMobileNumberDisplayInput.focus();
                    return;
                } else {
                    paymentMobileNumberError.classList.add('hidden');
                }
            }
        });

        // Initial setup on page load
        shippingSection.classList.add('active');
        paymentSection.classList.add('translate-x-full', 'opacity-0');
        paymentSection.style.visibility = 'hidden';

        updateActionButtonsVisibility();
        togglePaymentMobileNumberInput();
    });
</script>