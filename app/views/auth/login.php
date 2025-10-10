<div class="relative w-full h-full flex items-center min-h-screen justify-center px-4 sm:px-6 lg:px-8 pt-60 pb-72" style="background-image: url('<?php echo BASE_URL; ?>/assets/images/CraftWisebg.png'); background-size: cover; background-position: center;">
    <div class="relative z-10 p-8 sm:p-10 bg-white shadow-2xl rounded-xl w-full max-w-md mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center leading-tight">
            <?php echo htmlspecialchars($title); ?>
        </h1>

        <?php if (isset($error) && $error): ?>
            <div class="js-dismissible-alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4 transition-all duration-300 ease-in-out" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($error); ?></span>
                <button type="button" class="js-dismiss-btn absolute top-2 right-2 text-red-700 hover:text-red-900 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        <?php endif; ?>

        <?php if (isset($success) && $success): ?>
            <div class="js-dismissible-alert bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4 transition-all duration-300 ease-in-out" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline"><?php echo htmlspecialchars($success); ?></span>
                <button type="button" class="js-dismiss-btn absolute top-2 right-2 text-green-700 hover:text-green-900 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>/login" method="POST" class="space-y-6">
            <div class="relative border border-gray-300 rounded-md shadow-sm focus-within:ring-2 focus-within:ring-[--color-primary-orange] focus-within:border-[--color-primary-orange]">
                <input type="text" name="identifier" id="identifier" required
                       class="peer w-full px-4 py-3 pt-6 text-lg bg-transparent outline-none focus:outline-none transition-all duration-200"
                       placeholder=" ">
                <label for="identifier" class="absolute left-4 top-1 text-sm text-gray-500 transition-all duration-200 pointer-events-none
                                          peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-lg
                                          peer-focus:top-1 peer-focus:text-sm peer-focus:text-[--color-primary-orange]">
                    Username or Email
                </label>
            </div>
            <div class="relative border border-gray-300 rounded-md shadow-sm focus-within:ring-2 focus-within:ring-[--color-primary-orange] focus-within:border-[--color-primary-orange]">
                <input type="password" name="password" id="password" required autocomplete="off"
                    class="peer w-full px-4 py-3 pt-6 text-lg bg-transparent outline-none focus:outline-none transition-all duration-200 pr-10"
                    placeholder=" ">
                <label for="password" class="absolute left-4 top-1 text-sm text-gray-500 transition-all duration-200 pointer-events-none
                                         peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-lg
                                         peer-focus:top-1 peer-focus:text-sm peer-focus:text-[--color-primary-orange]">
                    Password
                </label>
                <span class="absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer toggle-password-visibility" data-target="password">
                    <svg class="h-6 w-6 text-gray-500 eye-show-password" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg class="h-6 w-6 text-gray-500 hidden eye-hide-password" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <circle cx="12" cy="12" r="7" stroke-width="2"></circle>
                      <line x1="7" y1="17" x2="17" y2="7" stroke-width="2"></line>
                    </svg>
                </span>
            </div>
            <div class="text-sm text-right">
                <a href="<?php echo BASE_URL; ?>/forgot-password" class="font-medium text-[--color-primary-orange] hover:text-[#e76c3e] hover:underline">Forgot password?</a>
            </div>
            <div>
                <button type="submit"
                        class="w-full flex justify-center py-3 px-6 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white
                               bg-[--color-primary-orange] hover:bg-[#e76c3e] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[--color-primary-orange]
                               transition-colors">
                    Login
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-md text-gray-700">Don't have an account?
                <a href="<?php echo BASE_URL; ?>/register" class="font-bold text-[--color-dark-blue] hover:text-[#1a2d3a] hover:underline">Register here</a>
            </p>
        </div>
    </div>
</div>