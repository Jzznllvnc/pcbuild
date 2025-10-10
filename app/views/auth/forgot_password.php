<div class="relative w-full h-full flex items-center justify-center px-4 pt-20 sm:px-6 lg:px-8 bg-cover bg-center min-h-screen" style="background-image: url('<?php echo BASE_URL; ?>/assets/images/CraftWisebg.png'); background-size: cover; background-position: center;">
    <div class="relative z-10 p-8 sm:p-10 bg-white shadow-2xl rounded-xl w-full max-w-md mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center leading-tight">
            Reset Your Password
        </h1>
        <p class="text-center text-gray-600 mb-8">
            To reset your password, please enter your username or email and the code shown below.
        </p>

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

        <form action="<?php echo BASE_URL; ?>/forgot-password" method="POST" class="space-y-6">
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

            <div class="text-center bg-gray-100 p-4 rounded-md border border-gray-200">
                <p class="text-gray-800 text-xl font-bold tracking-widest mb-2">Type this code:</p>
                <p class="text-[--color-dark-blue] text-3xl font-extrabold tracking-widest select-none cursor-pointer" id="captcha-code-display" onclick="location.reload();">
                    <?php echo htmlspecialchars($captcha_code ?? '-----'); ?>
                </p>
                <p class="text-sm text-gray-500 mt-2">Click the code to refresh</p>
            </div>

            <div class="relative border border-gray-300 rounded-md shadow-sm focus-within:ring-2 focus-within:ring-[--color-primary-orange] focus-within:border-[--color-primary-orange]">
                <input type="text" name="captcha_input" id="captcha_input" required autocomplete="off"
                       class="peer w-full px-4 py-3 pt-6 text-lg bg-transparent outline-none focus:outline-none transition-all duration-200"
                       placeholder=" ">
                <label for="captcha_input" class="absolute left-4 top-1 text-sm text-gray-500 transition-all duration-200 pointer-events-none
                                         peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-lg
                                         peer-focus:top-1 peer-focus:text-sm peer-focus:text-[--color-primary-orange]">
                    Enter Code
                </label>
            </div>

            <div>
                <button type="submit"
                        class="w-full flex justify-center py-3 px-6 border border-transparent rounded-lg shadow-lg text-lg font-bold text-white
                               bg-[--color-primary-orange] hover:bg-[#e76c3e] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[--color-primary-orange]
                               transition-colors">
                    Continue
                </button>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-md text-gray-700">Remembered your password?
                <a href="<?php echo BASE_URL; ?>/login" class="font-bold text-[--color-dark-blue] hover:text-[#1a2d3a] hover:underline">Login here</a>
            </p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const captchaDisplay = document.getElementById('captcha-code-display');
    const captchaText = captchaDisplay.textContent.trim();
    captchaDisplay.innerHTML = '';
    const colors = ['#FE7743', '#273F4F', '#475569'];
    
    for (let i = 0; i < captchaText.length; i++) {
        const charSpan = document.createElement('span');
        charSpan.textContent = captchaText[i];
        charSpan.style.display = 'inline-block';
        
        // Apply random rotation (-5deg to +5deg)
        charSpan.style.transform = `rotate(${Math.random() * 10 - 5}deg)`;
        charSpan.style.marginRight = `${Math.random() * 3}px`;
        charSpan.style.color = colors[Math.floor(Math.random() * colors.length)];
        charSpan.style.fontSize = `${30 + Math.random() * 10}px`; 
        
        captchaDisplay.appendChild(charSpan);
    }
    captchaDisplay.onclick = function() {
        location.reload();
    };
});
</script>