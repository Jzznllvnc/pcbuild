<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg my-8">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center"><?php echo htmlspecialchars($title); ?></h1>

    <?php if (isset($success) && $success): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($success); ?></span>
        </div>
    <?php endif; ?>

    <?php if (isset($error) && $error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <div class="flex justify-between items-center mb-6">
        <a href="/pcbuild/public/admin/products/create" class="bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-2 px-4 rounded-md shadow-lg transition-colors">
            Add New Product
        </a>
        <a href="/pcbuild/public/admin" class="text-[--color-primary-orange] hover:text-[#e76c3e] font-medium">
            &larr; Back to Admin Dashboard
        </a>
    </div>

    <?php if (empty($products)): ?>
        <p class="text-center text-gray-600 text-lg py-8">No products found. Add your first product!</p>
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
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Category
                        </th>
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
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap capitalize"><?php echo htmlspecialchars($product['category'] ?? 'N/A'); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">$<?php echo number_format($product['price'], 2); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php htmlspecialchars($product['stock']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex space-x-2">
                                    <a href="/pcbuild/public/admin/products/edit/<?php echo htmlspecialchars($product['id']); ?>"
                                       class="text-[--color-primary-orange] hover:text-[#e76c3e]" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-7.793 7.793a.5.5 0 01-.128.128H4.5a.5.5 0 01-.5-.5V10.5a.5.5 0 01.128-.128l7.793-7.793zM10.121 5.929L14.071 9.879l-7.293 7.293a1 1 0 01-.707.293H4a1 1 0 01-1-1v-4a1 1 0 01.293-.707l7.293-7.293z" />
                                        </svg>
                                    </a>
                                    <form action="/pcbuild/public/admin/products/delete/<?php echo htmlspecialchars($product['id']); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>