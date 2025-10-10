<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg mt-28 mb-16">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 mt-8 text-center"><?php echo htmlspecialchars($title); ?></h1>

    <?php if (isset($error) && $error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 flex-wrap gap-4">
        <a href="<?php echo BASE_URL; ?>/admin/products/create" class="bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-2 px-4 rounded-md shadow-lg transition-colors w-fit sm:w-auto text-center">
            Add New Product
        </a>
        <a href="<?php echo BASE_URL; ?>/admin" class="text-[--color-primary-orange] hover:text-[#e76c3e] font-medium w-fit sm:w-auto text-center">
            &larr; Back to Admin Dashboard
        </a>
    </div>

    <form action="<?php echo BASE_URL; ?>/admin/products" method="GET" class="mb-8 flex flex-col sm:flex-row gap-4 items-center">
        <input type="text" name="search" placeholder="Search by name or description..."
               value="<?php echo htmlspecialchars($currentSearch ?? ''); ?>"
               class="flex-grow px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[--color-primary-orange]">
        <button type="submit"
                class="bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-2 px-6 rounded-md transition-colors shadow-md">
            Search
        </button>
        <?php if (!empty($currentSearch) || !empty($currentCategory)): ?>
            <a href="<?php echo BASE_URL; ?>/admin/products" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded-md transition-colors shadow-md">
                Clear Filters
            </a>
        <?php endif; ?>
    </form>

    <div class="mb-8 overflow-x-auto pb-4 scrollbar-hide">
        <div class="flex flex-nowrap space-x-3">
            <?php
            $categories = ['CPU', 'GPU', 'Motherboard', 'RAM', 'Storage', 'PSU', 'Case', 'Cooler', 'Keyboard', 'Monitor', 'Mouse'];
            ?>
            <a href="<?php echo BASE_URL; ?>/admin/products?search=<?php echo htmlspecialchars($currentSearch ?? ''); ?>"
               class="flex-shrink-0 px-5 py-2 rounded-full text-sm font-medium
               <?php echo empty($currentCategory) ? 'bg-[--color-dark-blue] text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>
               transition-colors duration-200 whitespace-nowrap">
                All Products
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="<?php echo BASE_URL; ?>/admin/products?category=<?php echo urlencode($cat); ?>&search=<?php echo htmlspecialchars($currentSearch ?? ''); ?>"
                   class="flex-shrink-0 px-5 py-2 rounded-full text-sm font-medium
                   <?php echo ($currentCategory === $cat) ? 'bg-[--color-dark-blue] text-white shadow-md' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>
                   transition-colors duration-200 whitespace-nowrap">
                    <?php echo htmlspecialchars($cat); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (empty($products)): ?>
        <p class="text-center text-gray-600 text-lg py-8">No products found matching your criteria.</p>
    <?php else: ?>
        <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Image
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">Category</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Price
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Stock
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($product['id']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <img src="<?php echo htmlspecialchars($product['image_url'] ?? 'https://placehold.co/40x40/cbd5e1/475569?text=Img'); ?>"
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-10 h-10 object-contain rounded-md">
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($product['name']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm hidden md:table-cell"><p class="text-gray-900 whitespace-no-wrap capitalize"><?php echo htmlspecialchars($product['category'] ?? 'N/A'); ?></p></td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">$<?php echo number_format($product['price'], 2); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($product['stock']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex space-x-2">
                                    <a href="<?php echo BASE_URL; ?>/admin/products/edit/<?php echo htmlspecialchars($product['id']); ?>"
                                       class="text-[--color-primary-orange] hover:text-[#e76c3e]" title="Edit">
                                       <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                    </a>
                                    <button type="button"
                                            class="js-delete-product-btn text-red-600 hover:text-red-900"
                                            title="Delete"
                                            data-product-id="<?php echo htmlspecialchars($product['id']); ?>"
                                            data-product-name="<?php echo htmlspecialchars($product['name']); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const successMsg = urlParams.get('success_msg');

        if (successMsg) {
            alertMessage('success', decodeURIComponent(successMsg));
            urlParams.delete('success_msg');
            const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
            window.history.replaceState({}, document.title, newUrl);
        }
    });
</script>