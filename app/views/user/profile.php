<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg mt-40 mb-16 max-w-5xl">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-10 text-center">User Profile</h1>
    <p class="text-center text-gray-600 mb-10">Manage your details, update username and phone number, or change your password.</p>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 bg-gray-50 p-6 rounded-lg shadow-md flex flex-col items-center justify-center text-center">
            <img src="<?php echo BASE_URL; ?>/assets/images/user.png"
                 alt="User Avatar"
                 class="w-32 h-32 rounded-full object-cover border-4 border-[--color-primary-orange] mb-4 shadow-lg">
            <h2 class="text-2xl font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($user['username']); ?></h2>
            <p class="text-gray-600 text-sm flex items-center">
                <?php echo htmlspecialchars($user['email']); ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </p>
        </div>

        <div class="lg:col-span-2 bg-gray-50 p-6 rounded-lg shadow-md">
            <h3 class="2xl font-bold text-gray-900 mb-6">General Information</h3>
            <form action="<?php echo BASE_URL; ?>/profile/update-general" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm">
                </div>
                <div class="flex justify-start">
                    <button type="submit"
                            class="profile-button-fixed-width py-2 px-6 rounded-md shadow-lg transition-colors bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold">
                        Update Username
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-3 bg-gray-50 p-6 rounded-lg shadow-md">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Security</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <p class="mt-1 block w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm text-gray-900 sm:text-sm">
                        <?php echo htmlspecialchars($user['email']); ?>
                    </p>
                </div>

                <div class="flex flex-col items-start">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <p class="mt-1 block w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm text-gray-900 sm:text-sm">
                        ••••••••
                    </p>
                    <a href="<?php echo BASE_URL; ?>/forgot-password"
                       class="mt-4 profile-button-fixed-width bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-2 px-6 rounded-md shadow-lg transition-colors text-sm text-center">
                        Change Password
                    </a>
                </div>

                <div class="flex flex-col items-start">
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <form action="<?php echo BASE_URL; ?>/profile/update-phone" method="POST" class="space-y-4 w-full">
                        <input type="text" id="phone_number" name="phone_number"
                               value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>"
                               class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-sm"
                               placeholder="e.g., 9123456789"
                               maxlength="10" pattern="[0-9]{10}" title="Please enter exactly 10 digits">
                        <button type="submit"
                                class="profile-button-fixed-width bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-2 px-6 rounded-md shadow-lg transition-colors text-sm">
                            <?php echo empty($user['phone_number']) ? 'Add Number' : 'Update Number'; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const successMsg = urlParams.get('success_msg');
        const errorMsg = urlParams.get('error_msg');

        if (successMsg) {
            alertMessage('success', decodeURIComponent(successMsg));
        } else if (errorMsg) {
            alertMessage('error', decodeURIComponent(errorMsg));
        }
        if (successMsg || errorMsg) {
            urlParams.delete('success_msg');
            urlParams.delete('error_msg');
            const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
            window.history.replaceState({}, document.title, newUrl);
        }
    });
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.5s forwards;
    }
    .profile-button-fixed-width {
        max-width: 200px;
        width: fit-content;
        min-width: 150px;
    }
    .flex.justify-start > .profile-button-fixed-width,
    .flex-col.items-start > .profile-button-fixed-width,
    .flex-col.items-start form > .profile-button-fixed-width
    {
        margin-left: 0;
        margin-right: auto;
    }
    .profile-button-fixed-width.text-center {
        text-align: center;
    }
</style>