<div class="container mx-auto p-8 pt-20 bg-white shadow-lg rounded-lg mt-40 mb-16">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center">Manage Users</h1>

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

    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <form action="/pcbuild/public/admin/users" method="GET" class="flex-grow flex gap-2 max-w-lg">
            <input type="text" name="search" placeholder="Search by username or email..."
                   value="<?php echo htmlspecialchars($searchTerm ?? ''); ?>"
                   class="flex-grow px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[--color-primary-orange]">
            <button type="submit" class="bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-2 px-4 rounded-md shadow-lg transition-colors">
                Search
            </button>
            <?php if (!empty($searchTerm)): ?>
                <a href="/pcbuild/public/admin/users" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md shadow-lg transition-colors">
                    Clear
                </a>
            <?php endif; ?>
        </form>
        <a href="/pcbuild/public/admin" class="text-[--color-primary-orange] hover:text-[#e76c3e] font-medium flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Admin Dashboard
        </a>
    </div>

    <?php if (empty($users)): ?>
        <p class="text-center text-gray-600 text-lg py-10">No users found.</p>
    <?php else: ?>
        <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Username
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Signup Date
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Last Login
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Admin Status
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Account Status
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($user['id']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($user['username']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($user['email']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars(date('Y-m-d H:i:s', strtotime($user['created_at']))); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo $user['last_login'] ? htmlspecialchars(date('Y-m-d H:i:s', strtotime($user['last_login']))) : 'N/A'; ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <?php if ($user['is_admin']): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Yes
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            No
                                        </span>
                                    <?php endif; ?>
                                </p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    <?php if ($user['is_banned']): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Banned
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    <?php endif; ?>
                                </p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <div class="flex space-x-2">
                                    <button type="button"
                                            class="js-toggle-ban-btn text-sm font-medium py-1 px-3 rounded-md transition-colors
                                            <?php echo $user['is_banned'] ? 'bg-yellow-500 hover:bg-yellow-600 text-white' : 'bg-red-500 hover:bg-red-600 text-white'; ?>"
                                            data-user-id="<?php echo htmlspecialchars($user['id']); ?>"
                                            data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                            data-is-banned="<?php echo htmlspecialchars($user['is_banned']); ?>">
                                        <?php echo $user['is_banned'] ? 'Unban' : 'Ban'; ?>
                                    </button>
                                    
                                    <button type="button"
                                            class="js-delete-user-btn text-sm font-medium bg-gray-700 hover:bg-gray-800 text-white py-1 px-3 rounded-md transition-colors"
                                            data-user-id="<?php echo htmlspecialchars($user['id']); ?>"
                                            data-username="<?php echo htmlspecialchars($user['username']); ?>">
                                        Delete
                                    </button>
                                    
                                    <a href="/pcbuild/public/admin/users/orders/<?php echo htmlspecialchars($user['id']); ?>"
                                       class="text-sm font-medium bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded-md transition-colors">
                                        Orders
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>