<div class="container mx-auto p-10 bg-white shadow-lg rounded-lg mt-28 mb-24">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 mt-8 text-center"><?php echo htmlspecialchars($title); ?></h1>
    <p class="text-center text-gray-700 mb-12 text-lg">Your <span class="text-[--color-primary-orange]">perfect build</span> starts here. Shop the latest CPUs, GPUs, and more!</p>

    <!-- Search and Filter Form -->
    <form action="<?php echo BASE_URL; ?>/products" method="GET" class="mb-8 flex flex-col sm:flex-row gap-4 items-center">
        <input type="text" name="search" placeholder="Search by name or description..."
               value="<?php echo htmlspecialchars($currentSearch ?? ''); ?>"
               class="flex-grow px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--color-primary-orange]">
        <select name="category"
                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--color-primary-orange]">
            <option value="">All Categories</option>
            <?php
            $categories = ['CPU', 'GPU', 'Motherboard', 'RAM', 'Storage', 'PSU', 'Case', 'Cooler', 'Keyboard', 'Monitor', 'Mouse'];
            foreach ($categories as $cat) {
                $selected = ($currentCategory === $cat) ? 'selected' : '';
                echo "<option value=\"{$cat}\" {$selected}>{$cat}</option>";
            }
            ?>
        </select>
        <button type="submit"
                class="bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-2 px-6 rounded-md transition-colors shadow-md">
            Apply Filters
        </button>
    </form>
    <div class="mb-8 overflow-x-auto pb-4 scrollbar-hide">
        <div class="flex flex-nowrap space-x-3">
            <a href="<?php echo BASE_URL; ?>/products?search=<?php echo htmlspecialchars($currentSearch ?? ''); ?>"
               class="flex-shrink-0 px-5 py-2 rounded-full text-sm font-medium
               <?php echo empty($currentCategory) ? 'bg-[--color-dark-blue] text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>
               transition-colors duration-200 whitespace-nowrap">
                All Products
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?php echo BASE_URL; ?>/products?category=<?php echo htmlspecialchars($cat); ?>&search=<?php echo htmlspecialchars($currentSearch ?? ''); ?>"
                   class="flex-shrink-0 px-5 py-2 rounded-full text-sm font-medium
                   <?php echo ($currentCategory === $cat) ? 'bg-[--color-dark-blue] text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>
                   transition-colors duration-200 whitespace-nowrap">
                    <?php echo htmlspecialchars($cat); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <p class="text-center text-gray-600 text-lg py-10">No products found matching your criteria.</p>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-8">
            <?php foreach ($products as $product): ?>
                <div class="bg-gray-50 p-6 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 flex flex-col items-center text-center">
                    <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://placehold.co/300x300/e2e8f0/475569?text=No+Image'); ?>"
                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                         class="w-48 h-48 object-contain mb-4 rounded-md">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="text-gray-600 text-sm mb-3"><?php echo htmlspecialchars($product['category']); ?></p>
                    <p class="text-2xl font-bold text-[--color-primary-orange] mb-2">$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
                    <p class="text-gray-700 text-sm mb-4">
                        Stock: <span class="font-bold <?php echo ($product['stock'] > 0) ? 'text-green-600' : 'text-red-600'; ?>">
                            <?php echo htmlspecialchars($product['stock']); ?>
                        </span>
                    </p>
                    <div class="flex space-x-2 mt-auto w-full justify-center">
                        <a href="<?php echo BASE_URL; ?>/products/<?php echo htmlspecialchars($product['id']); ?>"
                           class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md transition-colors">
                            View Details
                        </a>
                        <button onclick="openQuantityModal(<?php echo htmlspecialchars(json_encode($product)); ?>)"
                                class="flex-1 bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-2 px-4 rounded-md transition-colors
                                <?php echo ($product['stock'] <= 0) ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                                <?php echo ($product['stock'] <= 0) ? 'disabled' : ''; ?>>
                            Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination Controls -->
        <div class="flex justify-center items-center space-x-2 mt-12">
            <?php if ($currentPage > 1): ?>
                <a href="<?php echo BASE_URL; ?>/products?page=<?php echo $currentPage - 1; ?>
                    <?php echo $currentCategory ? '&category=' . htmlspecialchars($currentCategory) : ''; ?>
                    <?php echo $currentSearch ? '&search=' . htmlspecialchars($currentSearch) : ''; ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?php echo BASE_URL; ?>/products?page=<?php echo $i; ?>
                    <?php echo $currentCategory ? '&category=' . htmlspecialchars($currentCategory) : ''; ?>
                    <?php echo $currentSearch ? '&search=' . htmlspecialchars($currentSearch) : ''; ?>"
                   class="px-4 py-2 rounded-md
                   <?php echo ($i === $currentPage) ? 'bg-[--color-primary-orange] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>
                   transition-colors">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="<?php echo BASE_URL; ?>/products?page=<?php echo $currentPage + 1; ?>
                    <?php echo $currentCategory ? '&category=' . htmlspecialchars($currentCategory) : ''; ?>
                    <?php echo $currentSearch ? '&search=' . htmlspecialchars($currentSearch) : ''; ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Quantity Selection Modal for products/index.php -->
<div id="quantity-modal" class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 hidden z-50 transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm transform scale-95 opacity-0 transition-all duration-300">
        <h3 class="text-2xl font-bold text-gray-900 mb-4 text-center">Add to Cart</h3>
        <div class="flex flex-col items-center mb-6">
            <img id="modal-product-image" src="" alt="Product Image" class="w-32 h-32 object-contain mb-3 rounded-md">
            <p id="modal-product-name" class="text-xl font-semibold text-gray-800 text-center"></p>
            <p id="modal-product-price" class="text-lg text-[--color-primary-orange] mb-2"></p>
            <p id="modal-product-stock" class="text-sm text-gray-600"></p>
        </div>
        <div class="mb-6 flex items-center justify-center space-x-3">
            <button id="modal-quantity-minus" class="quantity-btn p-2 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                </svg>
            </button>
            <span id="modal-quantity-display" class="text-2xl font-bold text-gray-900 w-16 text-center">1</span>
            <button id="modal-quantity-plus" class="quantity-btn p-2 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0H6" />
                </svg>
            </button>
            <input type="hidden" id="quantity-input" value="1">
            <p id="quantity-error" class="text-red-500 text-xs italic mt-2 hidden absolute bottom-12"></p>
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
