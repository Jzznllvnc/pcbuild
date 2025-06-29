<?php
// Ensure session is started for auth status check (though it's also in index.php)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC Build e-commerce</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <style>
        /* Define custom colors as CSS variables for easier use with arbitrary values or direct CSS */
        :root {
            --color-primary-orange: #FE7743;
            --color-light-bg: #EFEEEE;
            --color-dark-blue: #273F4F;
            --color-black: #000000;

            /* NEW: Colors for the new footer design */
            --footer-bg-dark: #1A1E26; /* A very dark grey, similar to the screenshot */
            --footer-text-light: #E0E6EB; /* Light grey for general text */
            --footer-link-hover: #A0A9B0; /* Slightly lighter on hover */
        }

        /* General body styling and font */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-light-bg); /* Use the new light background color */
            color: #334155; /* Darker text */
            min-height: 100vh; /* Ensure body takes full viewport height */
            display: flex; /* Make body a flex container */
            flex-direction: column; /* Stack children (header, main, footer) vertically */
        }

        /* Markdown content styling */
        .markdown-content p { margin-bottom: 0.5rem; }
        .markdown-content strong { font-weight: bold; }
        .markdown-content em { font-style: italic; }
        .markdown-content ul, .markdown-content ol { list-style-position: inside; margin-left: 1rem; }
        .markdown-content ul { list-style-type: disc; }
        .markdown-content ol { list-style-type: decimal; }
        .markdown-content li { margin-bottom: 0.25rem; }
        .markdown-content h1, .markdown-content h2, .markdown-content h3 { font-weight: bold; margin-top: 1rem; margin-bottom: 0.5rem; }
        .markdown-content h1 { font-size: 1.5rem; }
        .markdown-content h2 { font-size: 1.25rem; }
        .markdown-content h3 { font-size: 1.125rem; }
        .markdown-content pre { background-color: #e2e8f0; padding: 0.75rem; border-radius: 0.375rem; overflow-x: auto; margin-top: 0.5rem; margin-bottom: 0.5rem; font-size: 0.875rem; }
        .markdown-content code { background-color: #e2e8f0; padding: 0.125rem 0.25rem; border-radius: 0.25rem; color: #b91c1c; }
        .markdown-content pre code { background-color: transparent; padding: 0; border-radius: 0; color: inherit; }


        /* Floating Action Button for AI Chat */
        #ai-chat-fab {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            background-color: var(--color-primary-orange);
            color: white;
            border-radius: 9999px;
            padding: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: background-color 0.3s ease;
            cursor: pointer;
            z-index: 1000;
        }
        #ai-chat-fab:hover {
            background-color: #e76c3e;
        }

        /* AI Chat Sidebar */
        #ai-chat-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 100vw;
            height: 100vh;
            background-color: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            z-index: 1010;
            display: flex;
            flex-direction: column;
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
            overflow: hidden;

            @media (min-width: 768px) {
                width: 448px;
                max-width: 448px;
                height: calc(100vh - 64px);
                top: 64px;
                border-top-left-radius: 0.5rem;
                border-bottom-left-radius: 0.5rem;
            }
        }
        #ai-chat-sidebar.open {
            transform: translateX(0);
        }

        /* Prevent body scrolling when sidebar is open */
        body.ai-chat-open {
             overflow: hidden !important;
        }

        /* Styling for the sidebar's header */
        .sidebar-header {
            flex-shrink: 0;
            padding: 1rem;
            background-color: var(--color-dark-blue);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Styling for the content placeholder (where ai_chat/index.php content is loaded) */
        #ai-chat-content-placeholder {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Styling for the chat messages area within the loaded content */
        #chat-messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1rem;
            background-color: var(--color-light-bg);
        }

        /* Styling for the chat input area within the loaded content */
        .chat-input-area {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            background-color: white;
        }

        /* Updated Header Style for Blur Effect */
        .main-header {
            background-color: rgba(16, 19, 24, 0.69); /* Explicit RGBA for semi-transparency */
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px); /* For Safari */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* Existing shadow-md equivalent */
        }
        /* New styles for logout confirmation modal */
        #logout-confirmation-modal {
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black overlay */
            transition: opacity 0.3s ease-in-out;
        }

        #logout-confirmation-modal > div {
            transition: all 0.3s ease-in-out;
        }
        /* Hide scrollbar for category tabs */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        /* Define custom breakpoint */
        @media (min-width: 525px) {
            .sm-custom-flex { display: flex; }
            .sm-custom-hidden { display: none; }
        }

        /* Mobile Menu Specific Styles (applies from 0px to 767px) */
        @media (max-width: 767px) {
            .mobile-menu {
                display: none; /* Hidden by default */
                flex-direction: column;
                position: absolute;
                top: 100%; /* Position below the header */
                left: 0;
                width: 100%;
                background-color: var(--color-dark-blue); /* Same as header */
                padding: 1rem 0;
                box-shadow: 0 8px 10px -5px rgba(0, 0, 0, 0.2);
                z-index: 40; /* Below header, above content */
            }

            .mobile-menu.active {
                display: flex; /* Show when active */
            }

            .mobile-menu a {
                color: white;
                padding: 0.75rem 1rem;
                text-align: center;
                width: 100%;
            }

            .mobile-menu a:hover {
                background-color: rgba(255, 255, 255, 0.1);
            }

            /* Ensure desktop links are hidden on mobile */
            .main-header .desktop-nav-links {
                display: none;
            }

            /* Show mobile specific user actions and hamburger by default on small screens */
            .main-header .mobile-nav-actions {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            /* Adjust spacing for mobile buttons/links to avoid wrapping */
            .mobile-nav-actions a {
                padding: 0.5rem 0.75rem; /* Smaller padding */
                font-size: 0.875rem; /* Smaller font */
                white-space: nowrap; /* Prevent text wrapping */
            }

            /* Adjust logout button specifically for mobile */
            .mobile-nav-actions #logout-button-mobile {
                padding: 0.5rem 0.75rem; /* Smaller padding */
            }

            .mobile-nav-actions #logout-button-mobile img {
                margin-right: 0.25rem; /* Reduce margin on icon */
            }
        }

        /* Responsive behavior for elements within mobile-nav-actions for the custom breakpoint */
        @media (max-width: 524px) { /* This will apply when width is 524px or less */
            .main-header .mobile-nav-actions .sm-custom-hidden-below {
                display: none; /* Hide profile/logout links outside hamburger */
            }
            .mobile-menu a#mobile-profile-link-in-menu,
            .mobile-menu a#logout-button-mobile-menu {
                display: block; /* Show them inside the hamburger menu */
            }
        }

        /* Default desktop styles (applies from 768px and up) */
        @media (min-width: 768px) {
            .main-header .desktop-nav-links {
                display: flex;
            }
            .main-header .mobile-nav-actions {
                display: none;
            }
            .mobile-menu {
                display: none !important;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <header class="main-header text-white p-4 fixed top-0 w-full z-50">
        <nav class="container mx-auto flex justify-between items-center relative">
            <a href="/pcbuild/public/home" class="text-2xl font-bold rounded-md px-3 py-1 hover:bg-gray-700 transition-colors flex items-center">
                <img src="/pcbuild/assets/images/CraftWise.png" alt="CraftWise Logo" class="h-12 w-12 mr-2 object-contain">
                CraftWise
            </a>

            <div class="desktop-nav-links items-center space-x-4">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/pcbuild/public/products" class="rounded-md px-3 py-2 text-sm font-medium hover:bg-gray-700 transition-colors">All Products</a>
                    <a href="/pcbuild/public/orderhistory" id="order-history-link" class="relative rounded-md px-3 py-2 text-sm font-medium hover:bg-gray-700 transition-colors flex items-center">
                        Order History
                        <span id="new-order-notification" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center hidden">
                            1
                        </span>
                    </a>
                    <a href="/pcbuild/public/build-rate" class="rounded-md px-3 py-2 text-sm font-medium hover:bg-gray-700 transition-colors">Rate Your Build</a>

                    <a href="/pcbuild/public/cart" class="relative rounded-md px-3 py-2 text-sm font-medium hover:bg-gray-700 transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Cart
                        <span id="cart-item-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center hidden">
                            </span>
                    </a>

                    <div class="w-4"></div> <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <a href="/pcbuild/public/admin/dashboard" class="rounded-md px-3 py-2 text-sm font-medium hover:bg-gray-700 transition-colors">
                            Administration
                        </a>
                    <?php else: ?>
                        <a href="/pcbuild/public/profile" class="rounded-md px-3 py-2 text-sm font-medium hover:bg-gray-700 transition-colors">
                            Manage Profile
                        </a>
                    <?php endif; ?>

                    <a href="javascript:void(0);" id="logout-button" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md shadow-lg transition-colors flex items-center">
                        <img src="/pcbuild/assets/images/logout.svg" alt="Logout Icon" class="h-5 w-5 mr-2 inline" />
                        Logout
                    </a>
                <?php else: ?>
                    <a href="/pcbuild/public/products" class="rounded-md px-3 py-2 text-sm font-medium hover:bg-gray-700 transition-colors">All Products</a>
                    <a href="/pcbuild/public/cart" class="relative rounded-md px-3 py-2 text-sm font-medium hover:bg-gray-700 transition-colors flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 0a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Cart
                        <span id="cart-item-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center hidden">
                            </span>
                    </a>
                    <div class="w-4"></div> <a href="/pcbuild/public/login" class="bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-2 px-4 rounded-md shadow-lg transition-colors">Login / Register</a>
                <?php endif; ?>
            </div>

            <div class="mobile-nav-actions md:hidden">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                        <a href="/pcbuild/public/admin/dashboard" class="rounded-md px-2 py-1 text-sm font-medium hover:bg-gray-700 transition-colors sm-custom-hidden-below">
                            Administration
                        </a>
                    <?php else: ?>
                        <a href="/pcbuild/public/profile" class="rounded-md px-2 py-1 text-sm font-medium hover:bg-gray-700 transition-colors sm-custom-hidden-below">
                            Manage Profile
                        </a>
                    <?php endif; ?>
                    <a href="javascript:void(0);" id="logout-button-mobile" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-md shadow-lg transition-colors flex items-center text-sm sm-custom-hidden-below">
                        <img src="/pcbuild/assets/images/logout.svg" alt="Logout Icon" class="h-4 w-4 mr-1 inline" />
                        Logout
                    </a>
                <?php else: ?>
                    <a href="/pcbuild/public/login" class="bg-[--color-primary-orange] hover:bg-[#e76c3e] text-white font-bold py-1 px-2 rounded-md shadow-lg transition-colors text-sm sm-custom-hidden-below">Login / Register</a>
                <?php endif; ?>

                <button id="hamburger-button" class="text-white focus:outline-none p-1">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </nav>

        <div id="mobile-menu" class="mobile-menu md:hidden">
            <a href="/pcbuild/public/products" class="block">All Products</a>
            <a href="/pcbuild/public/orderhistory" class="block relative">
                Order History
                <span id="new-order-notification-mobile" class="absolute top-1/2 -translate-y-1/2 right-4 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center hidden">1</span>
            </a>
            <a href="/pcbuild/public/build-rate" class="block">Rate Your Build</a>
            <a href="/pcbuild/public/cart" class="block relative">
                Cart
                <span id="cart-item-count-mobile" class="absolute top-1/2 -translate-y-1/2 right-4 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center hidden"></span>
            </a>

            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?php echo (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) ? '/pcbuild/public/admin/dashboard' : '/pcbuild/public/profile'; ?>" class="block sm-custom-hidden-up">
                    <?php echo (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) ? 'Administration' : 'Manage Profile'; ?>
                </a>
                <a href="javascript:void(0);" id="logout-button-mobile-menu" class="block sm-custom-hidden-up">
                    <img src="/pcbuild/assets/images/logout.svg" alt="Logout Icon" class="h-4 w-4 mr-2 inline" />
                    Logout
                </a>
            <?php else: ?>
                <a href="/pcbuild/public/login" class="block sm-custom-hidden-up">Login / Register</a>
            <?php endif; ?>
        </div>

    <script>
        const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
        const performCartSync = <?php echo isset($_SESSION['sync_cart_on_load']) && $_SESSION['sync_cart_on_load'] === true ? 'true' : 'false'; ?>;
        <?php unset($_SESSION['sync_cart_on_load']); // Clear the flag immediately after setting JS variable ?>

        // Add this line for new order notification
        const hasNewOrderNotification = <?php echo isset($_SESSION['new_order_notification']) && $_SESSION['new_order_notification'] === true ? 'true' : 'false'; ?>;
        <?php unset($_SESSION['new_order_notification']); // Clear the flag immediately after reading ?>

        document.addEventListener('DOMContentLoaded', () => {
            const hamburgerButton = document.getElementById('hamburger-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const logoutButtonMobile = document.getElementById('logout-button-mobile');
            const logoutButtonMobileMenu = document.getElementById('logout-button-mobile-menu'); // New logout button inside menu
            const logoutButtonDesktop = document.getElementById('logout-button');

            // Toggle mobile menu visibility
            if (hamburgerButton) {
                hamburgerButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('active');
                    document.body.classList.toggle('overflow-hidden'); // Prevent scrolling body
                });
            }

            // Centralized logout logic for all buttons
            const handleLogoutClick = (e) => {
                e.preventDefault();
                if (typeof showLogoutConfirmation === 'function') {
                    showLogoutConfirmation(); // Function from main.js
                } else {
                    console.error('showLogoutConfirmation function not found. Ensure main.js is loaded.');
                    window.location.href = '/pcbuild/public/logout'; // Fallback
                }
            };

            if (logoutButtonDesktop) {
                logoutButtonDesktop.addEventListener('click', handleLogoutClick);
            }

            if (logoutButtonMobile) {
                logoutButtonMobile.addEventListener('click', handleLogoutClick);
            }
            if (logoutButtonMobileMenu) { // Attach listener to the new button inside the menu
                logoutButtonMobileMenu.addEventListener('click', handleLogoutClick);
            }


            // Adjust notification for mobile menu if present
            const newOrderNotificationDesktop = document.getElementById('new-order-notification');
            const newOrderNotificationMobile = document.getElementById('new-order-notification-mobile');

            if (hasNewOrderNotification) {
                if (newOrderNotificationDesktop) newOrderNotificationDesktop.classList.remove('hidden');
                if (newOrderNotificationMobile) newOrderNotificationMobile.classList.remove('hidden');
            } else {
                if (newOrderNotificationDesktop) newOrderNotificationDesktop.classList.add('hidden');
                if (newOrderNotificationMobile) newOrderNotificationMobile.classList.add('hidden');
            }

            // Adjust cart count for mobile menu if present
            const cartItemCountDesktop = document.getElementById('cart-item-count');
            const cartItemCountMobile = document.getElementById('cart-item-count-mobile');

            function updateMobileCartCountDisplay() {
                if (!cartItemCountDesktop || !cartItemCountMobile) return;

                if (isLoggedIn) {
                    // Assuming performActionViaFetch is available globally from main.js
                    if (typeof performActionViaFetch === 'function') {
                        performActionViaFetch('/pcbuild/public/cart/get', 'GET')
                            .then(response => {
                                if (response.success && response.cart) {
                                    const totalItems = response.cart.reduce((sum, item) => sum + item.quantity, 0);
                                    cartItemCountDesktop.textContent = totalItems > 0 ? totalItems : '';
                                    cartItemCountDesktop.classList.toggle('hidden', totalItems === 0);
                                    cartItemCountMobile.textContent = totalItems > 0 ? totalItems : '';
                                    cartItemCountMobile.classList.toggle('hidden', totalItems === 0);
                                } else {
                                    cartItemCountDesktop.textContent = '';
                                    cartItemCountDesktop.classList.add('hidden');
                                    cartItemCountMobile.textContent = '';
                                    cartItemCountMobile.classList.add('hidden');
                                }
                            })
                            .catch(error => {
                                console.error('Failed to fetch server cart count for mobile:', error);
                                cartItemCountDesktop.textContent = '';
                                cartItemCountDesktop.classList.add('hidden');
                                cartItemCountMobile.textContent = '';
                                cartItemCountMobile.classList.add('hidden');
                            });
                    } else {
                        console.error('performActionViaFetch function not found. Ensure main.js is loaded.');
                        // Fallback to local cart if the fetch function isn't ready
                        const cart = getCart(); // From main.js
                        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                        cartItemCountDesktop.textContent = totalItems > 0 ? totalItems : '';
                        cartItemCountDesktop.classList.toggle('hidden', totalItems === 0);
                        cartItemCountMobile.textContent = totalItems > 0 ? totalItems : '';
                        cartItemCountMobile.classList.toggle('hidden', totalItems === 0);
                    }
                } else {
                    const cart = getCart(); // From main.js
                    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                    cartItemCountDesktop.textContent = totalItems > 0 ? totalItems : '';
                    cartItemCountDesktop.classList.toggle('hidden', totalItems === 0);
                    cartItemCountMobile.textContent = totalItems > 0 ? totalItems : '';
                    cartItemCountMobile.classList.toggle('hidden', totalItems === 0);
                }
            }

            // Initial call to update mobile cart count
            updateMobileCartCountDisplay();
        });
    </script>
</head>
    </header>
    <main class="flex-grow">