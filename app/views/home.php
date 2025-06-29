<div class="relative">
    <div class="absolute inset-0 overflow-hidden" style="z-index: 0;">
        <div class="w-full h-full bg-cover bg-center animate-bg-zoom"
             style="background-image: url('/pcbuild/public/images/landing.jpg');">
        </div>
        <div class="absolute inset-0 bg-black opacity-40"></div>
    </div>

    <div class="relative min-h-screen flex items-center justify-center text-white pt-20 pb-8 z-10">
        <div class="container mx-auto px-4 text-center">
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
</div>
<div class="bg-white py-12 brand-carousel-section">
    <div class="flex items-center justify-center mb-8 px-4">
        <div class="flex-grow border-t-2 border-[--color-primary-orange] mx-4"></div>
        <h2 class="text-3xl font-extrabold text-gray-900 whitespace-nowrap">Our Special Partners</h2>
        <div class="flex-grow border-t-2 border-[--color-primary-orange] mx-4"></div>
    </div>
    <div class="overflow-hidden relative py-4">
        <div class="flex whitespace-nowrap animate-slide-brands">
            <?php
            $brands_with_images = [
                ['name' => 'AMD', 'logo_url' => '/pcbuild/assets/brands/amd.png'],
                ['name' => 'NVIDIA', 'logo_url' => '/pcbuild/assets/brands/nvidia.png'],
                ['name' => 'Corsair', 'logo_url' => '/pcbuild/assets/brands/corsair.png'],
                ['name' => 'ASUS', 'logo_url' => '/pcbuild/assets/brands/asus.png'],
                ['name' => 'Samsung', 'logo_url' => '/pcbuild/assets/brands/samsung.png'],
                ['name' => 'NZXT', 'logo_url' => '/pcbuild/assets/brands/nzxt.png'],
                ['name' => 'Asrock', 'logo_url' => '/pcbuild/assets/brands/asrock.png'],
                ['name' => 'Intel', 'logo_url' => '/pcbuild/assets/brands/intel.png'],
                ['name' => 'G.Skill', 'logo_url' => '/pcbuild/assets/brands/gskill.png'],
                ['name' => 'MSI', 'logo_url' => '/pcbuild/assets/brands/msi.png'],
                ['name' => 'Seasonic', 'logo_url' => '/pcbuild/assets/brands/seasonic.png'],
                ['name' => 'Western Digital', 'logo_url' => '/pcbuild/assets/brands/westernd.png'],
                ['name' => 'Lian Li', 'logo_url' => '/pcbuild/assets/brands/lianli.png'],
                ['name' => 'Noctua', 'logo_url' => '/pcbuild/assets/brands/noctua.png'],
                ['name' => 'DeepCool', 'logo_url' => '/pcbuild/assets/brands/deepcool.png'],
                ['name' => 'LG', 'logo_url' => '/pcbuild/assets/brands/lg.png'],
                ['name' => 'Razer', 'logo_url' => '/pcbuild/assets/brands/razer.png'],
                ['name' => 'Logitech', 'logo_url' => '/pcbuild/assets/brands/logitech.png'],
                ['name' => 'EVGA', 'logo_url' => '/pcbuild/assets/brands/evga.png'],
                ['name' => 'Crucial', 'logo_url' => '/pcbuild/assets/brands/crucial.png'],
                ['name' => 'Fractal Design', 'logo_url' => '/pcbuild/assets/brands/fractal.png'],
                ['name' => 'GIGABYTE', 'logo_url' => '/pcbuild/assets/brands/gigabyte.png'],
                ['name' => 'Kingston', 'logo_url' => '/pcbuild/assets/brands/kingston.png'],
            ];

            for ($i = 0; $i < 3; $i++) {
                foreach ($brands_with_images as $brand) {
                    echo '<div class="inline-flex items-center justify-center w-48 h-20 mx-4 bg-gray-100 rounded-lg shadow-sm flex-shrink-0 transition-shadow hover:shadow-md">';
                    echo '<img src="' . htmlspecialchars($brand['logo_url']) . '" alt="' . htmlspecialchars($brand['name']) . ' Logo" class="h-full w-auto object-contain">';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</div>

<div class="bg-gradient-to-r from-[#0d1012] via-[#1f2223] to-[#505354] py-12">
    <div class="container mx-auto p-8 my-12 text-center">
        <h2 class="text-4xl font-extrabold text-white mb-10">Why <span class="custom-underline">Choose</span> CraftWise?</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center feature-card-hover text-white">
                <div class="text-5xl mb-4 text-[--color-primary-orange]">🛠️</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Build Your Dream PC</h3>
                <p class="text-gray-700 mb-6 flex-grow">Select components, check compatibility, and get a build rating tailored to your needs. Create the ultimate machine with ease!</p>
            </div>

            <div class="p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center feature-card-hover text-white">
                <div class="text-5xl mb-4 text-green-600">🛒</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Vast Product Selection</h3>
                <p class="text-gray-700 mb-6 flex-grow">Browse thousands of GPUs, CPUs, Motherboards, RAM, Storage, and more from top brands. Find exactly what you need!</p>
            </div>

            <div class="p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center feature-card-hover text-white">
                <div class="text-5xl mb-4 text-purple-600">🤖</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">AI-Powered Assistance</h3>
                <p class="text-gray-700 mb-6 flex-grow">Get instant answers and expert advice on components, compatibility, or general build advice from our Gemini AI assistant.</p>
            </div>

            <div class="p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center feature-card-hover text-white">
                <div class="text-5xl mb-4 text-[--color-dark-blue]">📞</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Dedicated Customer Support</h3>
                <p class="text-gray-700 mb-6 flex-grow">Our expert team is ready to assist you with any questions or issues, ensuring a smooth and satisfactory experience.</p>
            </div>

            <div class="p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center feature-card-hover text-white">
                <div class="text-5xl mb-4 text-[--color-primary-orange]">🔒</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Secure & Seamless Checkout</h3>
                <p class="text-gray-700 mb-6 flex-grow">Shop with confidence using our secure payment gateways and enjoy a hassle-free checkout process.</p>
            </div>

            <div class="p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center feature-card-hover text-white">
                <div class="text-5xl mb-4 text-gray-700">📦</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Real-time Stock Updates</h3>
                <p class="text-gray-700 mb-6 flex-grow">Always know what's available with up-to-the-minute stock information on all our products.</p>
            </div>
            </div>
    </div>
</div>

<div class="bg-white py-16" id="crafting-process-section">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-sm font-bold uppercase tracking-widest text-gray-500 mb-16">THE CRAFTING PROCESS</h2>
        <h2 class="text-5xl sm:text-6xl font-extrabold text-gray-900 mb-12 leading-tight flex flex-col items-center justify-center overflow-hidden">
            <span class="inline-block translate-x-[-100%] opacity-0 transition-all duration-1000 ease-out animate-on-scroll-left-in" id="build-your-dream-pc">
                BUILD YOUR <span class="text-[--color-primary-orange]">DREAM PC</span>
            </span>
            <span class="inline-block translate-x-[100%] opacity-0 transition-all duration-1000 ease-out" id="in-3-simple-steps">
                IN 3 SIMPLE STEPS
            </span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 border border-gray-200 rounded-lg overflow-hidden max-w-5xl mx-auto" id="crafting-steps-container">
            <div class="px-8 py-16 border-b md:border-b-0 md:border-r border-gray-200 flex flex-col items-center text-center card-animate-on-scroll card-blur-initial">
                <div class="mb-8 flex justify-center">
                    <div class="w-28 h-28 rounded-full flex items-center justify-center border-4 border-[--color-primary-orange] bg-white">
                        <div class="text-4xl font-extrabold text-[--color-primary-orange]">1</div>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 uppercase tracking-wider">Select Components</h3>
                <p class="text-gray-700 leading-relaxed text-base">Handpick your CPU, GPU, Motherboard, and RAM from our extensive catalog to form the foundation of your build.</p>
            </div>

            <div class="px-8 py-16 border-b md:border-b-0 md:border-r border-gray-200 flex flex-col items-center text-center card-animate-on-scroll card-blur-initial">
                <div class="mb-8 flex justify-center">
                    <div class="w-28 h-28 rounded-full flex items-center justify-center border-4 border-[--color-dark-blue] bg-white">
                        <div class="text-4xl font-extrabold text-[--color-dark-blue]">2</div>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 uppercase tracking-wider">Check Compatibility</h3>
                <p class="text-gray-700 leading-relaxed text-base">Our intelligent builder instantly verifies component compatibility, flagging any potential issues and guaranteeing a smooth assembly.</p>
            </div>

            <div class="px-8 py-16 flex flex-col items-center text-center card-animate-on-scroll card-blur-initial">
                <div class="mb-8 flex justify-center">
                    <div class="w-28 h-28 rounded-full flex items-center justify-center border-4 border-gray-400 bg-white">
                        <div class="text-4xl font-extrabold text-gray-700">3</div>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3 uppercase tracking-wider">Finalize & Order</h3>
                <p class="text-gray-700 leading-relaxed text-base">Once you're satisfied with your custom PC build, confidently place your order and prepare for a superior computing experience.</p>
            </div>
        </div>

        <div class="mt-16">
            <a href="/pcbuild/public/build-rate" class="inline-block bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-4 px-10 rounded-full text-xl transition-all duration-300 transform hover:scale-105 shadow-2xl">
                Start Your PC Build Journey
            </a>
        </div>
    </div>
</div>

<div class="bg-gray-100 py-20"> <div class="container mx-auto px-4 text-center">
        <h2 class="text-sm font-bold uppercase tracking-widest text-gray-500 mb-10">WHAT OUR CUSTOMERS SAY</h2>
        <h2 class="text-5xl sm:text-6xl font-extrabold text-gray-900 mb-24 leading-tight">
            HEAR FROM OUR <span class="text-[--color-primary-orange]">SATISFIED BUILDERS</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto mb-16">
            <div class="bg-white p-8 rounded-lg shadow-md flex flex-col items-center text-center">
                <div class="flex justify-center mb-4">
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                </div>
                <div class="flex-grow flex flex-col justify-between w-full">
                    <p class="text-gray-700 italic mb-6">"CraftWise made building my first PC incredibly easy and fun! The compatibility checker saved me so much hassle. Highly recommend!"</p>
                    <div>
                        <p class="font-bold text-gray-900">- Alex R.</p>
                        <p class="text-gray-600 text-sm">Gaming Enthusiast</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-md flex flex-col items-center text-center">
                <div class="flex justify-center mb-4">
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                </div>
                <div class="flex-grow flex flex-col justify-between w-full">
                    <p class="text-gray-700 italic mb-6">"The product selection is massive, and their AI assistant is a game-changer. I got exactly the advice I needed for my professional workstation."</p>
                    <div>
                        <p class="font-bold text-gray-900">- Sarah L.</p>
                        <p class="text-gray-600 text-sm">Graphic Designer</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-md flex flex-col items-center text-center">
                <div class="flex justify-center mb-4">
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                    <svg class="h-5 w-5 text-yellow-400 fill-current" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.922 1.48 8.283L12 18.896l-7.416 3.815 1.48-8.283-6.064-5.922 8.332-1.151z"/></svg>
                </div>
                <div class="flex-grow flex flex-col justify-between w-full">
                    <p class="text-gray-700 italic mb-6">"Unmatched customer service and quality components. My new PC is a beast, all thanks to CraftWise!"</p>
                    <div>
                        <p class="font-bold text-gray-900">- Mike D.</p>
                        <p class="text-gray-600 text-sm">Streaming Professional</p>
                    </div>
                </div>
            </div>
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

    /* Styles for background image zoom */
    @keyframes bg-zoom {
        0% { transform: scale(1); }
        100% { transform: scale(1.1); } /* Zooms in to 110% of original size */
    }
    .animate-bg-zoom {
        animation: bg-zoom 20s ease-in-out infinite alternate; /* Slower, smoother, alternates direction */
    }

    /* Styles for sliding brands carousel */
    @keyframes slide-brands {
        0% {
            transform: translateX(0%);
        }
        100% {
            transform: translateX(-50%); /* This will move it by exactly half its content, creating a perfect loop */
        }
    }

    .animate-slide-brands {
        animation: slide-brands 90s linear infinite; /* Increased time for smoother, slower loop */
        /* Ensure the container is wide enough to hold all duplicated content side-by-side */
        width: max-content; /* This allows the flex container to be as wide as its content */
    }

    /* Pause animation on hover */
    .brand-carousel-section:hover .animate-slide-brands {
        animation-play-state: paused;
    }

    .custom-underline {
        position: relative;
        display: inline-block;
        line-height: 1.2;
    }
    .custom-underline::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 4px;
        background: var(--color-primary-orange);
        z-index: 0;
    }

    /* NEW ANIMATIONS FOR CRAFTING PROCESS SECTION */

    /* Initial states for text animation */
    .animate-on-scroll-left-in {
        transform: translateX(-100%);
        opacity: 0;
        transition: transform 1s ease-out, opacity 1s ease-out;
    }
    .animate-on-scroll-right-in {
        transform: translateX(100%);
        opacity: 0;
        transition: transform 1s ease-out, opacity 1s ease-out;
    }

    /* Active states for text animation (when 'in-view' class is added) */
    #build-your-dream-pc.in-view {
        transform: translateX(0);
        opacity: 1;
    }
    #in-3-simple-steps.in-view {
        transform: translateX(0);
        opacity: 1;
        transition-delay: 0.5s; /* Delay for the right element to merge */
    }

    /* Initial states for card animation */
    .card-blur-initial {
        filter: blur(8px); /* Initial blur effect */
        opacity: 0;
        transition: filter 0.8s ease-out, opacity 0.8s ease-out; /* Transition for blur and opacity */
    }
    .card-animate-on-scroll.in-view {
        filter: blur(0); /* Remove blur when in view */
        opacity: 1;
    }

    /* Styles for feature cards (base look) */
    .feature-card-hover {
        /* Glassy dark background color */
        background-color:rgba(55, 57, 58, 0.07); /* A dark desaturated green/teal */
        backdrop-filter: none; /* Ensure no actual blur on the card itself */
        -webkit-backdrop-filter: none;

        /* Initial border and shadow */
        border: 2px solid rgba(255, 255, 255, 0.19); /* Very subtle light border for depth */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* Default subtle shadow */

        /* Ensure smooth transitions for transform and box-shadow */
        transition: transform 0.3s ease-out, box-shadow 0.3s ease-out, border-color 0.3s ease-out;

        /* Override default text colors for content within the card */
        color: #ffffff !important; /* Force all text inside to white */

        /* Spotlight effect properties */
        position: relative; /* Needed for pseudo-element positioning */
        overflow: hidden; /* Crucial to contain the spotlight within the card */
        --x: 50%; /* Default x position for spotlight */
        --y: 50%; /* Default y position for spotlight */
    }

    /* Target h3, p, and icon div specifically within feature cards to ensure white color */
    .feature-card-hover h3 {
        color: #ffffff !important;
    }
    .feature-card-hover p {
        color: #ffffff !important;
    }
    .feature-card-hover .text-5xl { /* This targets the emoji/icon container */
        color: #ffffff !important; /* Forces the icon color to white */
    }

    /* Pseudo-element for the spotlight glow */
    .feature-card-hover::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(
            circle at var(--x) var(--y),
            rgba(255, 126, 62, 0.4) 0%, /* Brighter center of spotlight */
            transparent 70% /* Fades out to transparent */
        );
        opacity: 0; /* Hidden by default */
        transition: opacity 0.3s ease-out; /* Smooth fade in/out */
        pointer-events: none; /* Allows clicks to pass through to the card content */
    }

    /* Hover effect for feature cards */
    .feature-card-hover:hover {
        transform: translateY(-5px); /* Slight lift */
        box-shadow: 0 0 25px rgba(255, 126, 62, 0.8), /* Stronger orange glow */
                    0 0 40px rgba(255, 126, 62, 0.4), /* Wider, softer secondary glow */
                    0 5px 15px rgba(0, 0, 0, 0.1); /* Subtle darker shadow for depth */
        border-color: var(--color-primary-orange); /* Orange border on hover for accent */
    }

    /* Show spotlight on hover */
    .feature-card-hover:hover::before {
        opacity: 1; /* Make spotlight visible on hover */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // NEW: Intersection Observer for "BUILD YOUR DREAM PC IN 3 SIMPLE STEPS" text animation
        const textAnimationContainer = document.getElementById('crafting-process-section');
        const buildYourDreamPc = document.getElementById('build-your-dream-pc');
        const in3SimpleSteps = document.getElementById('in-3-simple-steps');

        if (textAnimationContainer && buildYourDreamPc && in3SimpleSteps) {
            const textObserverOptions = {
                root: null, // viewport as the root
                rootMargin: '0px',
                threshold: 0.2 // Trigger when 20% of the section is visible for text
            };

            const textObserverCallback = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        buildYourDreamPc.classList.add('in-view');
                        in3SimpleSteps.classList.add('in-view');
                        observer.unobserve(entry.target); // Stop observing once animated
                    }
                });
            };

            const textObserver = new IntersectionObserver(textObserverCallback, textObserverOptions);
            textObserver.observe(textAnimationContainer);
        }

        // NEW: Separate Intersection Observer for the 3 boxes animation
        const craftingStepsContainer = document.getElementById('crafting-steps-container');
        const animatedCards = document.querySelectorAll('#crafting-steps-container .card-animate-on-scroll');

        if (craftingStepsContainer && animatedCards.length > 0) {
            const cardsObserverOptions = {
                root: null, // viewport as the root
                rootMargin: '0px',
                threshold: 0.7 // Trigger when 70% of the steps container is visible
            };

            const cardsObserverCallback = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const initialDelay = 700; // Overall delay for cards to start after text
                        animatedCards.forEach((card, index) => {
                            const staggerDelay = index * 200; // 200ms delay between each card
                            setTimeout(() => {
                                card.classList.add('in-view');
                            }, initialDelay + staggerDelay);
                        });
                        observer.unobserve(entry.target); // Stop observing once animated
                    }
                });
            };

            const cardsObserver = new IntersectionObserver(cardsObserverCallback, cardsObserverOptions);
            cardsObserver.observe(craftingStepsContainer);
        }

        // NEW: Spotlight Effect JavaScript for feature cards
        const featureCards = document.querySelectorAll('.feature-card-hover');

        featureCards.forEach(card => {
            card.addEventListener('mousemove', e => {
                // Get the bounding box of the card
                const rect = card.getBoundingClientRect();

                // Calculate mouse position relative to the card
                // e.clientX/Y is mouse position relative to viewport
                // rect.left/top is card position relative to viewport
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                // Update CSS custom properties on the specific card
                card.style.setProperty('--x', `${x}px`);
                card.style.setProperty('--y', `${y}px`);
            });
        });
    });
</script>