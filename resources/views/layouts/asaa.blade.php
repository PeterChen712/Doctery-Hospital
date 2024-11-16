<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!-- ... (head section remains the same) ... -->

<body class="font-sans antialiased">
    <!-- Top Navigation -->
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <a href="/"
                        class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white cursor-pointer select-none ml-2 hover:text-gray-600 dark:hover:text-gray-300"
                        id="sidebar-toggle">Patient Portal</a>
                </div>
                <!-- ... (rest of the nav content remains the same) ... -->
            </div>
        </div>
    </nav>

    <!-- ... (rest of the HTML remains the same until the script section) ... -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('logo-sidebar');
            const mainContent = document.getElementById('main-content');
            let isSidebarOpen = true;

            // Function to open sidebar
            function openSidebar() {
                sidebar.style.transform = 'translateX(0)';
                mainContent.style.marginLeft = '16rem';
                isSidebarOpen = true;
            }

            // Function to close sidebar
            function closeSidebar() {
                if (window.innerWidth < 640) {  // Only close on mobile
                    sidebar.style.transform = 'translateX(-100%)';
                    mainContent.style.marginLeft = '0';
                    isSidebarOpen = false;
                }
            }

            // Handle hover events for the "Patient Portal" text
            sidebarToggle.addEventListener('mouseenter', function() {
                openSidebar();
            });

            // Close sidebar when clicking the toggle (for mobile)
            sidebarToggle.addEventListener('click', function(e) {
                if (window.innerWidth < 640) {
                    if (isSidebarOpen) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                }
                // If it's not mobile, clicking will navigate to '/' due to the anchor tag
            });

            // Handle both sidebar toggles for mobile menu button
            const sidebarToggles = document.querySelectorAll('[data-drawer-toggle]');
            sidebarToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-drawer-target');
                    const targetElement = document.getElementById(targetId);

                    if (targetElement && targetId === 'logo-sidebar') {
                        if (isSidebarOpen) {
                            closeSidebar();
                        } else {
                            openSidebar();
                        }
                    }
                });
            });

            // Close sidebars when clicking outside
            document.addEventListener('click', function(event) {
                const sidebar = document.getElementById('logo-sidebar');
                const sidebarButton = document.querySelector('[data-drawer-target="logo-sidebar"]');
                const sidebarToggleElement = document.getElementById('sidebar-toggle');

                if (!sidebar?.contains(event.target) &&
                    !sidebarButton?.contains(event.target) &&
                    !sidebarToggleElement?.contains(event.target)) {
                    closeSidebar();
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 640) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });

            // ... (rest of the script remains the same) ...
        });
    </script>
</body>
</html>