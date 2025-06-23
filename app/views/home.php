<div class="relative min-h-[500px] flex items-center justify-center bg-gradient-to-r from-[--color-dark-blue] to-[#1a2d3a] text-white overflow-hidden py-16">
    <div class="absolute inset-0 z-0">
        </div>
    <div class="relative z-10 container mx-auto px-4 text-center">
        <h1 class="text-5xl sm:text-6xl font-extrabold leading-tight mb-4 drop-shadow-lg animate-fade-in-down">
            <?php echo htmlspecialchars($title ?? 'Welcome to CraftWise!'); ?>
        </h1>
        <p class="text-xl sm:text-2xl text-gray-200 mb-8 animate-fade-in-up">
            <?php echo htmlspecialchars($message ?? 'Your ultimate destination for building custom PCs.'); ?>
        </p>
        <div class="flex justify-center space-x-4 animate-scale-in">
            <a href="/pcbuild/public/products" class="bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-3 px-8 rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl">
                Shop Now
            </a>
            <a href="/pcbuild/public/build-rate" class="bg-white hover:bg-gray-200 text-[--color-dark-blue] font-bold py-3 px-8 rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl">
                Build Your PC
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto p-8 my-12">
    <h2 class="text-4xl font-extrabold text-gray-900 mb-10 text-center">Why Choose CraftWise?</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="bg-white p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center">
            <div class="text-5xl mb-4 text-[--color-primary-orange]">🛠️</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Build Your Dream PC</h3>
            <p class="text-gray-700 mb-6 flex-grow">Select components, check compatibility, and get a build rating tailored to your needs. Create the ultimate machine with ease!</p>
            <a href="/pcbuild/public/products" class="inline-block bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-3 px-6 rounded-lg transition-colors shadow-lg">Start Building</a>
        </div>

        <div class="bg-white p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center">
            <div class="text-5xl mb-4 text-green-600">🛒</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Vast Product Selection</h3>
            <p class="text-gray-700 mb-6 flex-grow">Browse thousands of GPUs, CPUs, Motherboards, RAM, Storage, and more from top brands. Find exactly what you need!</p>
            <a href="/pcbuild/public/products" class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors shadow-lg">View Products</a>
        </div>

        <div class="bg-white p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center">
            <div class="text-5xl mb-4 text-purple-600">🤖</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">AI-Powered Assistance</h3>
            <p class="text-gray-700 mb-6 flex-grow">Get instant answers and expert advice on components, compatibility, or general build advice from our Gemini AI assistant.</p>
            <p class="text-purple-700 font-bold text-lg">Try the Chat Icon below</p>
        </div>
    </div>
</div>

<style>
    /* Keyframe animations for hero section */
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in-down {
        animation: fadeInDown 1s ease-out forwards;
    }
    .animate-fade-in-up {
        animation: fadeInUp 1s ease-out forwards 0.3s; /* Delayed start */
        opacity: 0; /* Hide until animation starts */
    }
    .animate-scale-in {
        animation: scaleIn 1s ease-out forwards 0.6s; /* Delayed start */
        opacity: 0; /* Hide until animation starts */
    }
</style>