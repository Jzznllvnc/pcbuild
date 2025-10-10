<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg mt-40 mb-16 max-w-6xl">
    <?php if ($product): ?>
    <div class="flex flex-col md:flex-row gap-8">
        <div class="md:w-1/2 flex items-center justify-center bg-gray-50 rounded-lg p-6 shadow-sm">
            <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://placehold.co/400x400/e2e8f0/475569?text=No+Image'); ?>"
                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                 class="max-w-full max-h-96 object-contain rounded-md">
        </div>
        <div class="md:w-1/2 p-4">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="text-lg text-gray-600 mb-3 capitalize">Category: <?php echo htmlspecialchars($product['category']); ?></p>
            <p class="text-3xl font-bold text-[--color-primary-orange] mb-4">$<?php echo number_format($product['price'], 2); ?></p>

            <p class="text-gray-700 text-lg mb-6">
                Stock: <span id="product-stock-display" class="font-bold <?php echo ($product['stock'] > 0) ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo htmlspecialchars($product['stock']); ?>
                </span>
            </p>

            <p class="text-gray-800 leading-relaxed mb-6">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>

            <?php if (!empty($product['additional_details'])): ?>
                <div class="bg-gray-100 p-4 rounded-md mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Specifications:</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-1">
                        <?php
                        $details = explode("\n", $product['additional_details']);
                        foreach ($details as $detail) {
                            $trimmedDetail = trim($detail);
                            if (!empty($trimmedDetail)) {
                                // REMOVED \d from the regex to prevent stripping leading numbers
                                $cleanedDetail = preg_replace('/^[\s\*\-\•]+\s*/', '', $trimmedDetail);
                                echo '<li>' . htmlspecialchars($cleanedDetail) . '</li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="flex items-center justify-start space-x-3 mb-6">
                <button id="page-quantity-minus" class="quantity-btn p-2 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                    </svg>
                </button>
                <span id="page-quantity-display" class="text-2xl font-bold text-gray-900 w-16 text-center">1</span>
                <button id="page-quantity-plus" class="quantity-btn p-2 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0H6" />
                    </svg>
                </button>
                <input type="hidden" id="page-quantity-value" value="1"> <p id="page-quantity-error" class="text-red-500 text-xs italic ml-4 hidden"></p>
            </div>

            <div class="flex space-x-4">
                <button id="add-to-cart-page-btn"
                        data-product-id="<?php echo htmlspecialchars($product['id']); ?>"
                        data-product-name="<?php echo htmlspecialchars($product['name']); ?>"
                        data-product-price="<?php echo htmlspecialchars($product['price']); ?>"
                        data-product-image="<?php echo htmlspecialchars($product['image_url'] ?? ''); ?>"
                        data-product-stock="<?php echo htmlspecialchars($product['stock']); ?>"
                        class="flex-1 bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-3 px-6 rounded-md transition-colors shadow-md
                        <?php echo ($product['stock'] <= 0) ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                        <?php echo ($product['stock'] <= 0) ? 'disabled' : ''; ?>>
                    Add to Cart
                </button>
                <a href="<?php echo BASE_URL; ?>/products"
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-6 rounded-md transition-colors shadow-md text-center">
                    Back to Products
                </a>
            </div>
        </div>
    </div>
    <?php else: ?>
        <p class="text-center text-gray-600 text-lg py-10">Product not found.</p>
    <?php endif; ?>
</div>