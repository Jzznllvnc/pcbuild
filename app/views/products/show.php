<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg my-8 max-w-4xl">
    <a href="/pcbuild/public/products" class="text-[--color-dark-blue] hover:text-[#1a2d3a] mb-4 inline-block font-medium">&larr; Back to Products</a>

    <?php if (!isset($product) || empty($product)): ?>
        <p class="text-center text-red-600 text-xl my-8">Product not found.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-4">
            <div class="md:col-span-1 flex justify-center items-center p-4 bg-gray-100 rounded-lg">
                <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://placehold.co/300x300/e2e8f0/475569?text=No+Image'); ?>"
                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                     class="max-w-full h-auto object-contain rounded-lg shadow-md">
            </div>
            <div class="md:col-span-1">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4"><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="text-gray-600 text-lg mb-3 capitalize">Category: <span class="font-semibold"><?php echo htmlspecialchars($product['category'] ?? 'Uncategorized'); ?></span></p>
                <p class="text-3xl font-bold text-[--color-primary-orange] mb-4">$<?php echo number_format($product['price'], 2); ?></p>

                <p class="text-gray-700 leading-relaxed mb-6"><?php echo nl2br(htmlspecialchars($product['description'] ?? 'No description available.')); ?></p>

                <div class="flex items-center mb-6">
                    <span class="text-gray-600 mr-2">Availability:</span>
                    <?php if ($product['stock'] > 0): ?>
                        <span class="text-green-600 font-semibold">In Stock (<?php echo htmlspecialchars($product['stock']); ?> units)</span>
                    <?php else: ?>
                        <span class="text-red-600 font-semibold">Out of Stock</span>
                    <?php endif; ?>
                </div>

                <div class="flex space-x-4">
                    <button onclick="addToCart(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>', <?php echo $product['price']; ?>, '<?php htmlspecialchars($product['image_url'] ?? 'https://placehold.co/300x300/e2e8f0/475569?text=No+Image', ENT_QUOTES); ?>')"
                            class="flex-grow bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-3 px-6 rounded-md transition-colors shadow-lg">
                        Add to Cart
                    </button>
                    </div>
            </div>
        </div>
    <?php endif; ?>
</div>