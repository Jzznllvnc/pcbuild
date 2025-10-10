<div class="relative">
    <div class="absolute inset-0 overflow-hidden" style="z-index: 0;">
        <div class="w-full h-full bg-cover bg-center animate-bg-zoom"
             style="background-image: url('<?php echo BASE_URL; ?>/app/views/products/images/landing.jpg');">
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
                <a href="<?php echo BASE_URL; ?>/products" class="bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-3 px-8 rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl">
                    Shop Now
                </a>
                <a href="<?php echo BASE_URL; ?>/build-rate" class="bg-white hover:bg-gray-200 text-[--color-dark-blue] font-bold py-3 px-8 rounded-full transition-all duration-300 transform hover:scale-105 shadow-xl">
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
            $base = BASE_URL;
            $brands_with_images = [
                ['name' => 'AMD', 'logo_url' => $base . '/assets/brands/amd.png'],
                ['name' => 'NVIDIA', 'logo_url' => $base . '/assets/brands/nvidia.png'],
                ['name' => 'Corsair', 'logo_url' => $base . '/assets/brands/corsair.png'],
                ['name' => 'ASUS', 'logo_url' => $base . '/assets/brands/asus.png'],
                ['name' => 'Samsung', 'logo_url' => $base . '/assets/brands/samsung.png'],
                ['name' => 'NZXT', 'logo_url' => $base . '/assets/brands/nzxt.png'],
                ['name' => 'Asrock', 'logo_url' => $base . '/assets/brands/asrock.png'],
                ['name' => 'Intel', 'logo_url' => $base . '/assets/brands/intel.png'],
                ['name' => 'G.Skill', 'logo_url' => $base . '/assets/brands/gskill.png'],
                ['name' => 'MSI', 'logo_url' => $base . '/assets/brands/msi.png'],
                ['name' => 'Seasonic', 'logo_url' => $base . '/assets/brands/seasonic.png'],
                ['name' => 'Western Digital', 'logo_url' => $base . '/assets/brands/westernd.png'],
                ['name' => 'Lian Li', 'logo_url' => $base . '/assets/brands/lianli.png'],
                ['name' => 'Noctua', 'logo_url' => $base . '/assets/brands/noctua.png'],
                ['name' => 'DeepCool', 'logo_url' => $base . '/assets/brands/deepcool.png'],
                ['name' => 'LG', 'logo_url' => $base . '/assets/brands/lg.png'],
                ['name' => 'Razer', 'logo_url' => $base . '/assets/brands/razer.png'],
                ['name' => 'Logitech', 'logo_url' => $base . '/assets/brands/logitech.png'],
                ['name' => 'EVGA', 'logo_url' => $base . '/assets/brands/evga.png'],
                ['name' => 'Crucial', 'logo_url' => $base . '/assets/brands/crucial.png'],
                ['name' => 'Fractal Design', 'logo_url' => $base . '/assets/brands/fractal.png'],
                ['name' => 'GIGABYTE', 'logo_url' => $base . '/assets/brands/gigabyte.png'],
                ['name' => 'Kingston', 'logo_url' => $base . '/assets/brands/kingston.png'],
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
                <h3 class="text-2xl font-bold text-white mb-3">Build Your Dream PC</h3>
                <p class="text-white mb-6 flex-grow">Select components, check compatibility, and get a build rating tailored to your needs. Create the ultimate machine with ease!</p>
            </div>

            <div class="p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center feature-card-hover text-white">
                <div class="text-5xl mb-4 text-green-600">🛒</div>
                <h3 class="text-2xl font-bold text-white mb-3">Vast Product Selection</h3>
                <p class="text-white mb-6 flex-grow">Browse thousands of GPUs, CPUs, Motherboards, RAM, Storage, and more from top brands. Find exactly what you need!</p>
            </div>

            <div class="p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center feature-card-hover text-white">
                <div class="text-5xl mb-4 text-purple-600">🤖</div>
                <h3 class="text-2xl font-bold text-white mb-3">AI-Powered Assistance</h3>
                <p class="text-white mb-6 flex-grow">Get instant answers and expert advice on components, compatibility, or general build advice from our very own Kraft-E AI chat assistant.</p>
            </div>

            <div class="p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center feature-card-hover text-white">
                <div class="text-5xl mb-4 text-[--color-dark-blue]">😎</div>
                <h3 class="text-2xl font-bold text-white mb-3">Ease of Use</h3>
                <p class="text-white mb-6 flex-grow">Our system is made to be a universal experience, you will not get lost navigating your needs.</p>
            </div>

            <div class="p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center feature-card-hover text-white">
                <div class="text-5xl mb-4 text-[--color-primary-orange]">🔒</div>
                <h3 class="text-2xl font-bold text-white mb-3">Secure & Seamless Checkout</h3>
                <p class="text-white mb-6 flex-grow">Shop with confidence using our secure payment gateways and enjoy a hassle-free checkout process.</p>
            </div>

            <div class="p-8 border border-gray-200 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 flex flex-col items-center text-center feature-card-hover text-white">
                <div class="text-5xl mb-4 text-gray-700">📦</div>
                <h3 class="text-2xl font-bold text-white mb-3">Real-time Stock Updates</h3>
                <p class="text-white mb-6 flex-grow">Always know what's available with up-to-the-minute stock information on all our products.</p>
            </div>
        </div>
    </div>
</div>

<div class="relative py-16 overflow-hidden" id="crafting-process-section">
    <video class="absolute inset-0 w-full h-full object-cover z-0" autoplay loop muted playsinline poster="<?php echo BASE_URL; ?>/assets/videos/homevid.png">
        <source src="<?php echo BASE_URL; ?>/assets/videos/homevid.webm" type="video/webm">
        <source src="<?php echo BASE_URL; ?>/assets/videos/homevid.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="absolute inset-0 bg-black opacity-40 z-0"></div>
    <div class="relative z-10 container mx-auto px-4 text-center">
        <h2 class="text-sm font-bold uppercase tracking-widest text-white mb-16">THE CRAFTING PROCESS</h2>
        <h2 class="text-5xl sm:text-6xl font-extrabold text-white mb-12 leading-tight flex flex-col items-center justify-center overflow-hidden">
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
                <h3 class="text-xl font-bold text-white mb-3 uppercase tracking-wider">Select Components</h3>
                <p class="text-white leading-relaxed text-base">Handpick your CPU, GPU, Motherboard, and RAM from our extensive catalog to form the foundation of your build.</p>
            </div>

            <div class="px-8 py-16 border-b md:border-b-0 md:border-r border-gray-200 flex flex-col items-center text-center card-animate-on-scroll card-blur-initial">
                <div class="mb-8 flex justify-center">
                    <div class="w-28 h-28 rounded-full flex items-center justify-center border-4 border-[--color-dark-blue] bg-white">
                        <div class="text-4xl font-extrabold text-[--color-dark-blue]">2</div>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 uppercase tracking-wider">Check Compatibility</h3>
                <p class="text-white leading-relaxed text-base">Our intelligent builder instantly verifies component compatibility, flagging any potential issues and guaranteeing a smooth assembly.</p>
            </div>

            <div class="px-8 py-16 flex flex-col items-center text-center card-animate-on-scroll card-blur-initial">
                <div class="mb-8 flex justify-center">
                    <div class="w-28 h-28 rounded-full flex items-center justify-center border-4 border-gray-400 bg-white">
                        <div class="text-4xl font-extrabold text-gray-700">3</div>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-white mb-3 uppercase tracking-wider">Finalize & Order</h3>
                <p class="text-white leading-relaxed text-base">Once you're satisfied with your custom PC build, confidently place your order and prepare for a superior computing experience.</p>
            </div>
        </div>

        <div class="mt-16">
            <a href="<?php echo BASE_URL; ?>/build-rate" class="inline-block bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-4 px-10 rounded-full text-xl transition-all duration-300 transform hover:scale-105 shadow-2xl">
                Start Your PC Build Journey
            </a>
        </div>
    </div>
</div>

<!-- FAQ Section: PC Build Related -->
<div class="bg-white py-20">
  <div class="container mx-auto px-4 max-w-4xl">
    <h2 class="text-5xl font-extrabold mb-10 text-gray-900"><span class="text-[--color-primary-orange]">F</span>requently <span class="text-[--color-primary-orange]">A</span>sked <span class="text-[--color-primary-orange]">Q</span>uestions</h2>
    <div id="faq-list" class="divide-y divide-gray-200">
      <div class="faq-item py-8">
        <button type="button" class="faq-question flex justify-between items-center w-full text-left text-2xl font-medium text-gray-900 focus:outline-none">
          <span>How do I start building a PC on CraftWise?</span>
          <span class="faq-toggle-icon transition-transform duration-300 text-3xl">+</span>
        </button>
        <div class="faq-answer mt-6 text-gray-700 text-base max-h-0 overflow-hidden transition-all duration-500">
          Click "Build Your PC" on the homepage or navigation. You'll be guided step-by-step to select compatible components and see your build rating in real time.
        </div>
      </div>
      <div class="faq-item py-8">
        <button type="button" class="faq-question flex justify-between items-center w-full text-left text-2xl font-medium text-gray-900 focus:outline-none">
          <span>Does CraftWise check component compatibility?</span>
          <span class="faq-toggle-icon transition-transform duration-300 text-3xl">+</span>
        </button>
        <div class="faq-answer mt-6 text-gray-700 text-base max-h-0 overflow-hidden transition-all duration-500">
          Yes! Our system automatically checks compatibility between your selected CPU, motherboard, RAM, GPU, and more, warning you of any issues before you order.
        </div>
      </div>
      <div class="faq-item py-8">
        <button type="button" class="faq-question flex justify-between items-center w-full text-left text-2xl font-medium text-gray-900 focus:outline-none">
          <span>Can I get help choosing parts?</span>
          <span class="faq-toggle-icon transition-transform duration-300 text-3xl">+</span>
        </button>
        <div class="faq-answer mt-6 text-gray-700 text-base max-h-0 overflow-hidden transition-all duration-500">
          Absolutely! Use our Kraft-E AI chat assistant for instant advice, or browse our guides for tips on picking the best components for your needs.
        </div>
      </div>
      <div class="faq-item py-8">
        <button type="button" class="faq-question flex justify-between items-center w-full text-left text-2xl font-medium text-gray-900 focus:outline-none">
          <span>Do you show real-time stock and prices?</span>
          <span class="faq-toggle-icon transition-transform duration-300 text-3xl">+</span>
        </button>
        <div class="faq-answer mt-6 text-gray-700 text-base max-h-0 overflow-hidden transition-all duration-500">
          Yes, all product listings display up-to-the-minute stock status and current prices, so you always know what's available.
        </div>
      </div>
      <div class="faq-item py-8">
        <button type="button" class="faq-question flex justify-between items-center w-full text-left text-2xl font-medium text-gray-900 focus:outline-none">
          <span>Is my payment and data secure?</span>
          <span class="faq-toggle-icon transition-transform duration-300 text-3xl">+</span>
        </button>
        <div class="faq-answer mt-6 text-gray-700 text-base max-h-0 overflow-hidden transition-all duration-500">
          Definitely. We use secure payment gateways and industry-standard encryption to keep your information safe.
        </div>
      </div>
      <div class="faq-item py-8">
        <button type="button" class="faq-question flex justify-between items-center w-full text-left text-2xl font-medium text-gray-900 focus:outline-none">
          <span>Can I save or share my PC build?</span>
          <span class="faq-toggle-icon transition-transform duration-300 text-3xl">+</span>
        </button>
        <div class="faq-answer mt-6 text-gray-700 text-base max-h-0 overflow-hidden transition-all duration-500">
          Yes! You can save your build to your account or share a link with friends or our support team for feedback.
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
        animation: fadeInUp 1s ease-out forwards 0.3s;
        opacity: 0;
    }
    .animate-scale-in {
        animation: scaleIn 1s ease-out forwards 0.6s;
        opacity: 0;
    }
    @keyframes bg-zoom {
        0% { transform: scale(1); }
        100% { transform: scale(1.1); }
    }
    .animate-bg-zoom {
        animation: bg-zoom 20s ease-in-out infinite alternate;
    }
    @keyframes slide-brands {
        0% {
            transform: translateX(0%);
        }
        100% {
            transform: translateX(-50%);
        }
    }

    .animate-slide-brands {
        animation: slide-brands 90s linear infinite;
        width: max-content;
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

    /* ANIMATIONS FOR CRAFTING PROCESS SECTION */
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
    #build-your-dream-pc.in-view {
        transform: translateX(0);
        opacity: 1;
    }
    #in-3-simple-steps.in-view {
        transform: translateX(0);
        opacity: 1;
        transition-delay: 0.5s;
    }
    .card-blur-initial {
        filter: blur(8px);
        opacity: 0;
        transition: filter 0.8s ease-out, opacity 0.8s ease-out;
    }
    .card-animate-on-scroll.in-view {
        filter: blur(0);
        opacity: 1;
    }
    .feature-card-hover {
        background-color:rgba(55, 57, 58, 0.07);
        backdrop-filter: none;
        -webkit-backdrop-filter: none;
        border: 2px solid rgba(255, 255, 255, 0.19);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease-out, box-shadow 0.3s ease-out, border-color 0.3s ease-out;
        color: #ffffff !important;
        position: relative;
        overflow: hidden;
        --x: 50%;
        --y: 50%;
    }
    .feature-card-hover h3,
    .feature-card-hover p,
    .feature-card-hover .text-5xl {
        color: #ffffff !important;
    }
    .feature-card-hover::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(
            circle at var(--x) var(--y),
            rgba(255, 126, 62, 0.4) 0%,
            transparent 70%
        );
        opacity: 0;
        transition: opacity 0.3s ease-out;
        pointer-events: none;
    }
    .feature-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 25px rgba(255, 126, 62, 0.8),
                    0 0 40px rgba(255, 126, 62, 0.4),
                    0 5px 15px rgba(0, 0, 0, 0.1);
        border-color: var(--color-primary-orange);
    }
    .feature-card-hover:hover::before {
        opacity: 1;
    }
    /* FAQ Accordion */
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s cubic-bezier(0.4,0,0.2,1), padding 0.5s;
        padding-top: 0;
        padding-bottom: 0;
    }
    .faq-answer.faq-open {
        padding-top: 1.5rem;  /* mt-6 */
        padding-bottom: 1.5rem;
    }
    .faq-toggle-icon.rotate-45 {
        transform: rotate(45deg);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        // "BUILD YOUR DREAM PC IN 3 SIMPLE STEPS" text animation
        const textAnimationContainer = document.getElementById('crafting-process-section');
        const buildYourDreamPc = document.getElementById('build-your-dream-pc');
        const in3SimpleSteps = document.getElementById('in-3-simple-steps');

        if (textAnimationContainer && buildYourDreamPc && in3SimpleSteps) {
            const textObserverOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.2
            };

            const textObserverCallback = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        buildYourDreamPc.classList.add('in-view');
                        in3SimpleSteps.classList.add('in-view');
                        observer.unobserve(entry.target);
                    }
                });
            };

            const textObserver = new IntersectionObserver(textObserverCallback, textObserverOptions);
            textObserver.observe(textAnimationContainer);
        }

        // Separate Intersection Observer for the 3 boxes animation
        const craftingStepsContainer = document.getElementById('crafting-steps-container');
        const animatedCards = document.querySelectorAll('#crafting-steps-container .card-animate-on-scroll');

        if (craftingStepsContainer && animatedCards.length > 0) {
            const cardsObserverOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.25
            };

            const cardsObserverCallback = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const initialDelay = 700;
                        animatedCards.forEach((card, index) => {
                            const staggerDelay = index * 200;
                            setTimeout(() => {
                                card.classList.add('in-view');
                            }, initialDelay + staggerDelay);
                        });
                        observer.unobserve(entry.target);
                    }
                });
            };

            const cardsObserver = new IntersectionObserver(cardsObserverCallback, cardsObserverOptions);
            cardsObserver.observe(craftingStepsContainer);
        }

        // Spotlight Effect JavaScript for feature cards
        const featureCards = document.querySelectorAll('.feature-card-hover');

        featureCards.forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--x', `${x}px`);
                card.style.setProperty('--y', `${y}px`);
            });
        });
    });

    document.querySelectorAll('.faq-question').forEach(btn => {
        btn.addEventListener('click', function() {
            const answer = this.parentElement.querySelector('.faq-answer');
            const icon = this.querySelector('.faq-toggle-icon');
            const isOpen = answer.classList.contains('faq-open');

            // Close all
            document.querySelectorAll('.faq-answer').forEach(a => {
                a.classList.remove('faq-open');
                a.style.maxHeight = null;
            });
            document.querySelectorAll('.faq-toggle-icon').forEach(i => {
                i.textContent = '+';
                i.classList.remove('rotate-45');
            });

            // Open if not already open
            if (!isOpen) {
                answer.classList.add('faq-open');
                answer.style.maxHeight = answer.scrollHeight + "px";
                icon.textContent = '×';
                icon.classList.add('rotate-45');
            }
        });
    });
</script>