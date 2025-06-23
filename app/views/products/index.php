<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg my-8">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center"><?php echo htmlspecialchars($title); ?></h1>

    <!-- Search and Filter Form -->
    <form action="/pcbuild/public/products" method="GET" class="mb-8 flex flex-col sm:flex-row gap-4 items-center">
        <input type="text" name="search" placeholder="Search by name or description..."
               value="<?php echo htmlspecialchars($currentSearch ?? ''); ?>"
               class="flex-grow px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--color-primary-orange]">
        <select name="category"
                class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--color-primary-orange]">
            <option value="">All Categories</option>
            <?php
            $categories = ['CPU', 'GPU', 'Motherboard', 'RAM', 'Storage', 'PSU', 'Case', 'CPU Cooler'];
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
                    <p class="text-2xl font-bold text-[--color-primary-orange] mb-4">$<?php echo htmlspecialchars(number_format($product['price'], 2)); ?></p>
                    <div class="flex space-x-2 mt-auto w-full justify-center">
                        <a href="/pcbuild/public/products/<?php echo htmlspecialchars($product['id']); ?>"
                           class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md transition-colors">
                            View Details
                        </a>
                        <button onclick="addToCart(<?php echo htmlspecialchars(json_encode($product['id'])); ?>, <?php echo htmlspecialchars(json_encode($product['name'])); ?>, <?php echo htmlspecialchars(json_encode($product['price'])); ?>, <?php echo htmlspecialchars(json_encode($product['image_url'])); ?>)"
                                class="flex-1 bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-2 px-4 rounded-md transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination Controls -->
        <div class="flex justify-center items-center space-x-2 mt-12">
            <?php if ($currentPage > 1): ?>
                <a href="/pcbuild/public/products?page=<?php echo $currentPage - 1; ?>
                    <?php echo $currentCategory ? '&category=' . htmlspecialchars($currentCategory) : ''; ?>
                    <?php echo $currentSearch ? '&search=' . htmlspecialchars($currentSearch) : ''; ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="/pcbuild/public/products?page=<?php echo $i; ?>
                    <?php echo $currentCategory ? '&category=' . htmlspecialchars($currentCategory) : ''; ?>
                    <?php echo $currentSearch ? '&search=' . htmlspecialchars($currentSearch) : ''; ?>"
                   class="px-4 py-2 rounded-md
                   <?php echo ($i === $currentPage) ? 'bg-[--color-primary-orange] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>
                   transition-colors">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="/pcbuild/public/products?page=<?php echo $currentPage + 1; ?>
                    <?php echo $currentCategory ? '&category=' . htmlspecialchars($currentCategory) : ''; ?>
                    <?php echo $currentSearch ? '&search=' . htmlspecialchars($currentSearch) : ''; ?>"
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

