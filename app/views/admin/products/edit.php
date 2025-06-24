<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg mt-40 mb-24 max-w-3xl">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center"><?php echo htmlspecialchars($title); ?></h1>

    <?php if (isset($error) && $error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
        </div>
    <?php endif; ?>

    <form action="/pcbuild/public/admin/products/edit/<?php echo htmlspecialchars($product['id']); ?>" method="POST" class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Product Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" required
                   value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
        </div>
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">Price ($) <span class="text-red-500">*</span></label>
            <input type="number" name="price" id="price" step="0.01" min="0.01" required
                   value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
        </div>
        <div>
            <label for="image_url" class="block text-sm font-medium text-gray-700">Image URL</label>
            <input type="url" name="image_url" id="image_url"
                   value="<?php echo htmlspecialchars($product['image_url'] ?? ''); ?>"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
            <?php if (!empty($product['image_url'])): ?>
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Current Product Image" class="mt-2 w-24 h-24 object-contain rounded-md border border-gray-200">
            <?php endif; ?>
        </div>
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
            <select name="category" id="category" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm bg-white">
                <option value="">-- Select Category --</option>
                <?php
                $categories = ['CPU', 'GPU', 'Motherboard', 'RAM', 'Storage', 'PSU', 'Case', 'Cooler', 'Monitor', 'Keyboard', 'Mouse', 'Webcam'];
                foreach ($categories as $cat) {
                    $selected = (isset($product['category']) && $product['category'] === $cat) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($cat) . '" ' . $selected . '>' . htmlspecialchars($cat) . '</option>';
                }
                ?>
            </select>
        </div>
        <div>
            <label for="stock" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
            <input type="number" name="stock" id="stock" min="0"
                   value="<?php echo htmlspecialchars($product['stock'] ?? '0'); ?>"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
        </div>

        <div class="flex justify-end space-x-4">
            <a href="/pcbuild/public/admin/products" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md shadow-lg transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-2 px-4 rounded-md shadow-lg transition-colors">
                Update Product
            </button>
        </div>
    </form>
</div>