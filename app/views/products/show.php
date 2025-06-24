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
                Stock: <span class="font-bold <?php echo ($product['stock'] > 0) ? 'text-green-600' : 'text-red-600'; ?>">
                    <?php echo htmlspecialchars($product['stock']); ?>
                </span>
            </p>

            <p class="text-gray-800 leading-relaxed mb-6">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>

            <div class="flex space-x-4">
                <button onclick="openQuantityModal(<?php echo htmlspecialchars(json_encode($product)); ?>)"
                        class="flex-1 bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-3 px-6 rounded-md transition-colors shadow-md
                        <?php echo ($product['stock'] <= 0) ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                        <?php echo ($product['stock'] <= 0) ? 'disabled' : ''; ?>>
                    Add to Cart
                </button>
                <a href="/pcbuild/public/products"
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

<div id="quantity-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 hidden z-50 transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm transform scale-95 opacity-0 transition-all duration-300">
        <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Add to Cart</h3>
        <div class="flex flex-col items-center mb-6">
            <img id="modal-product-image" src="" alt="Product Image" class="w-32 h-32 object-contain mb-3 rounded-md">
            <p id="modal-product-name" class="text-xl font-semibold text-gray-800 text-center"></p>
            <p id="modal-product-price" class="text-lg text-[--color-primary-orange] mb-2"></p>
            <p id="modal-product-stock" class="text-sm text-gray-600"></p>
        </div>
        <div class="mb-6">
            <label for="quantity-input" class="block text-gray-700 text-sm font-bold mb-2">Quantity:</label>
            <input type="number" id="quantity-input" min="1" value="1"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-2 focus:ring-[--color-primary-orange]">
            <p id="quantity-error" class="text-red-500 text-xs italic mt-2 hidden">Please enter a valid quantity.</p>
        </div>
        <div class="flex justify-end space-x-3">
            <button id="cancel-quantity" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md transition-colors">
                Cancel
            </button>
            <button id="add-to-cart-modal-btn" class="bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-2 px-4 rounded-md transition-colors">
                Add to Cart
            </button>
        </div>
    </div>
</div>