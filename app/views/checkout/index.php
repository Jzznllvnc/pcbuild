<!-- Hero Header -->
<div class="relative bg-gradient-to-r from-indigo-900 via-purple-900 to-pink-900 pt-32 pb-16 px-6">
    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto">
        <!-- Progress Steps -->
        <div class="flex items-center justify-center gap-4 mb-8">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span id="step-cart" class="text-white font-semibold hidden md:block">Cart</span>
            </div>
            
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7" />
            </svg>
            
            <div class="flex items-center gap-2">
                <div id="step-shipping-icon" class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-purple-900 font-bold transition-all">
                    2
                </div>
                <span id="step-shipping" class="text-white font-semibold hidden md:block">Shipping</span>
            </div>
            
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7" />
            </svg>
            
            <div class="flex items-center gap-2">
                <div id="step-payment-icon" class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold transition-all">
                    3
                </div>
                <span id="step-payment" class="text-white/70 font-semibold hidden md:block">Payment</span>
            </div>
        </div>
        
        <h1 class="text-5xl md:text-6xl font-black text-white text-center">Checkout</h1>
        <p class="text-xl text-white/80 text-center mt-4">Secure & fast checkout process</p>
    </div>
</div>

<!-- Main Content -->
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-6">
        <?php if (isset($_SESSION['error']) && $_SESSION['error']): ?>
            <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4 mb-8 flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h3 class="font-bold text-red-900">Error!</h3>
                    <p class="text-red-700"><?php echo htmlspecialchars($_SESSION['error']); ?></p>
                </div>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Main Content Column -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 relative overflow-hidden" style="min-height: 500px;" id="main-content-column">
                    
                    <!-- Shipping Section -->
                    <div id="shipping-section" class="checkout-step">
                        <h2 class="text-3xl font-black text-gray-900 mb-8 flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            Shipping Details
                        </h2>
                        
                        <form id="shipping-form" class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">First Name<span class="text-red-500">*</span></label>
                                    <input type="text" id="first_name" name="first_name" required
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Last Name<span class="text-red-500">*</span></label>
                                    <input type="text" id="last_name" name="last_name" required
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Email<span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" required
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Phone<span class="text-red-500">*</span></label>
                                    <div class="flex gap-2">
                                        <select id="country_code" name="country_code" class="px-3 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                                            <option value="+63">PH +63</option>
                                            <option value="+1">US +1</option>
                                            <option value="+44">UK +44</option>
                                            <option value="+61">AU +61</option>
                                            <option value="+81">JP +81</option>
                                            <option value="+86">CN +86</option>
                                        </select>
                                        <input type="tel" id="mobile_number" name="mobile_number" placeholder="9123456789" required
                                               class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors"
                                               value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Address<span class="text-red-500">*</span></label>
                                <input type="text" id="address" name="address" required
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                            </div>

                            <div class="grid md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">City<span class="text-red-500">*</span></label>
                                    <input type="text" id="city" name="city" required
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">State<span class="text-red-500">*</span></label>
                                    <input type="text" id="state" name="state" required
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Zip Code<span class="text-red-500">*</span></label>
                                    <input type="text" id="zip_code" name="zip_code" required
                                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Delivery Notes</label>
                                <textarea id="notes" name="notes" rows="3" placeholder="Any special delivery instructions?"
                                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 transition-colors"></textarea>
                            </div>

                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border-2 border-blue-100">
                                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                    </svg>
                                    Shipping Method
                                </h3>
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <label class="relative flex items-center cursor-pointer p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-300 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                        <input type="radio" name="shipping_method" value="Free Shipping" class="sr-only" checked>
                                        <div class="flex-1">
                                            <p class="font-bold text-gray-900">Free Shipping</p>
                                            <p class="text-sm text-gray-600">3-5 business days</p>
                                        </div>
                                        <span class="font-bold text-green-600">FREE</span>
                                    </label>
                                    <label class="relative flex items-center cursor-pointer p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-300 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                        <input type="radio" name="shipping_method" value="Express Shipping" class="sr-only">
                                        <div class="flex-1">
                                            <p class="font-bold text-gray-900">Express</p>
                                            <p class="text-sm text-gray-600">Same-day delivery</p>
                                        </div>
                                        <span class="font-bold text-gray-900">$1.50</span>
                                    </label>
                                </div>
                            </div>

                            <button type="button" id="continue-to-payment-button-shipping" class="w-full px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl hover:shadow-lg transition-all hidden">
                                Continue to Payment →
                            </button>
                        </form>
                    </div>

                    <!-- Payment Section -->
                    <div id="payment-section" class="checkout-step">
                        <h2 class="text-3xl font-black text-gray-900 mb-8 flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            Payment Method
                        </h2>
                        
                        <form id="payment-form-final" action="<?php echo BASE_URL; ?>/checkout/process" method="POST" class="space-y-6">
                            <div class="grid sm:grid-cols-2 gap-4">
                                <label class="relative cursor-pointer p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-green-300 transition-all has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                    <input type="radio" name="payment_method" value="GCash" class="sr-only" required>
                                    <div class="flex flex-col items-center gap-3">
                                        <img src="<?php echo BASE_URL; ?>/assets/images/gcash.svg" alt="GCash" class="h-12 object-contain">
                                        <span class="font-bold text-gray-900">GCash</span>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer p-6 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-300 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                    <input type="radio" name="payment_method" value="PayPal" class="sr-only" required>
                                    <div class="flex flex-col items-center gap-3">
                                        <img src="<?php echo BASE_URL; ?>/assets/images/paypal.svg" alt="PayPal" class="h-12 object-contain">
                                        <span class="font-bold text-gray-900">PayPal</span>
                                    </div>
                                </label>
                            </div>

                            <div id="payment-mobile-number-group" class="hidden">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Mobile Number<span class="text-red-500">*</span></label>
                                <input type="tel" id="p_mobile_number_display" name="payment_mobile_number" placeholder="09123456789"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-green-500 transition-colors">
                                <p id="payment-mobile-number-error" class="text-red-500 text-sm mt-2 hidden"></p>
                            </div>

                            <!-- Hidden fields for form data -->
                            <?php
                            $hiddenFields = ['first_name', 'last_name', 'email', 'country_code', 'mobile_number', 'address', 'city', 'state', 'zip_code', 'notes', 'shipping_method', 'shipping_cost', 'cart_items_json', 'total_amount'];
                            foreach ($hiddenFields as $field):
                            ?>
                                <input type="hidden" name="<?php echo $field; ?>" id="h_<?php echo $field; ?>">
                            <?php endforeach; ?>

                            <div class="flex gap-4 pt-4">
                                <button type="button" id="back-to-shipping-button" class="flex-1 px-6 py-4 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-xl transition-colors">
                                    ← Back
                                </button>
                                <button type="submit" id="place-order-button" class="flex-1 px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                                    Place Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 space-y-6">
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Order Summary</h2>
                        
                        <div id="checkout-cart-summary" class="space-y-3 mb-6 max-h-64 overflow-y-auto">
                            <p class="text-center text-gray-500 py-4">Loading...</p>
                        </div>

                        <div class="space-y-3 pt-6 border-t-2 border-gray-100">
                            <div class="flex justify-between text-gray-700">
                                <span>Subtotal</span>
                                <span class="font-semibold">$<span id="cart-subtotal">0.00</span></span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Shipping</span>
                                <span class="font-semibold">$<span id="cart-shipping-cost">0.00</span></span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Tax</span>
                                <span class="font-semibold">$0.25</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-2xl font-black pt-6 border-t-2 border-gray-100 mt-6">
                            <span class="text-gray-900">Total</span>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">
                                $<span id="cart-total-amount">0.00</span>
                            </span>
                        </div>
                    </div>

                    <button type="button" id="continue-to-payment-button-outside-summary"
                            class="w-full px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-xl hover:shadow-lg transition-all transform hover:scale-105">
                        Continue to Payment →
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#main-content-column {
    position: relative;
    overflow: hidden;
}
.checkout-step {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    padding: 2rem;
    transition: transform 0.5s ease-in-out, opacity 0.5s ease-in-out;
    box-sizing: border-box;
}
#shipping-section.active, #payment-section.active {
    position: relative;
    transform: translateX(0);
    opacity: 1;
    visibility: visible;
}
#shipping-section.slide-out {
    transform: translateX(-100%);
    opacity: 0;
    visibility: hidden;
    position: absolute;
}
#payment-section {
    transform: translateX(100%);
    opacity: 0;
    visibility: hidden;
}
#checkout-cart-summary::-webkit-scrollbar {
    width: 6px;
}
#checkout-cart-summary::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}
#checkout-cart-summary::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #9333ea, #ec4899);
    border-radius: 10px;
}
</style>

<script>
// All the existing checkout JavaScript logic from the original file
// I'm keeping it exactly as is to maintain functionality
<?php
// Read the original script content
$originalContent = file_get_contents(BASE_PATH . 'app/views/checkout/index.php');
preg_match('/<script>(.*?)<\/script>/s', $originalContent, $matches);
if (isset($matches[1])) {
    echo $matches[1];
}
?>
</script>
