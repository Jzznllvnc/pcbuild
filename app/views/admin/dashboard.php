<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg mt-60 mb-40 max-w-4xl">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center"><?php echo htmlspecialchars($title); ?></h1>

    <p class="text-center text-xl text-gray-700 mb-8">Welcome to the Admin Dashboard, <span class="font-semibold text-[--color-primary-orange]"><?php echo htmlspecialchars($username); ?></span>!</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="/pcbuild/public/admin/products" class="p-6 bg-[--color-light-bg] border border-gray-200 rounded-lg shadow-md hover:bg-gray-100 hover:shadow-lg transition-all duration-200 flex flex-col items-center justify-center text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[--color-dark-blue] mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <h2 class="text-2xl font-bold text-gray-800">Manage Products</h2>
            <p class="text-gray-600 mt-2">Add, edit, or delete products in your catalog.</p>
        </a>
        
        <div class="p-6 bg-gray-50 border border-gray-200 rounded-lg shadow-md flex flex-col items-center justify-center text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <h2 class="text-2xl font-bold text-gray-800">Manage Users (Coming Soon)</h2>
            <p class="text-gray-600 mt-2">View and manage user accounts.</p>
        </div>
    </div>
</div>