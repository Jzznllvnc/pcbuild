<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg my-8 max-w-4xl">
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
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M12 20.354a4 4 0 110-5.292M12 13.535V13a4 4 0 014 4v2M12 13.535V13a4 4 0 00-4 4v2m-4 0v2H20v-2A4 4 0 0016 9.354a4.999 4.999 0 00-2-2.828V5a4 4 0 00-4-4H8a4 4 0 00-4 4v2.535A4.999 4.999 0 002 9.354C.674 10.742 0 11.528 0 12v1C0 14.595.674 15.38 2 16.768A4.999 4.999 0 004 19.354a4 4 0 004 4h4a4 4 0 004-4v-2a4 4 0 004-4v-1C24 11.528 23.326 10.742 22 9.354z" />
            </svg>
            <h2 class="text-2xl font-bold text-gray-800">Manage Users (Coming Soon)</h2>
            <p class="text-gray-600 mt-2">View and manage user accounts.</p>
        </div>
    </div>
</div>