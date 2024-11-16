<!-- resources/views/layouts/doctor.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Doctor Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

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
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <a href="/"
                        class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white cursor-pointer select-none ml-2 hover:text-gray-600 dark:hover:text-gray-300"
                        id="sidebar-toggle">Doctor Portal</a>
                </div>

                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                                <img class="w-8 h-8 rounded-full"
                                    src="{{ Auth::user()->profile_image ? Storage::url(Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name=' . Auth::user()->username }}"
                                    alt="user photo">
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3">
                                <p class="text-sm text-gray-900 dark:text-white">{{ Auth::user()->username }}</p>
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300">
                                    {{ Auth::user()->email }}</p>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Logout
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">

            <!-- User Profile Section -->
            <div class="flex flex-col items-center pb-6 border-b border-gray-200 dark:border-gray-700">
                <img class="w-20 h-20 mb-3 rounded-full shadow-lg"
                    src="{{ Auth::user()->profile_image ? Storage::url(Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name=' . Auth::user()->username }}"
                    alt="{{ Auth::user()->username }} profile image">
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ Auth::user()->username }}</h5>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</span>
            </div>

            <ul class="space-y-2 font-medium">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('doctor.dashboard') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <!-- Patients -->
                <li>
                    <a href="{{ route('doctor.patients.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="ms-3">Patients</span>
                    </a>
                </li>

                <!-- Appointments -->
                <li>
                    <a href="{{ route('doctor.appointments.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="ms-3">Appointments</span>
                    </a>
                </li>

                <!-- Schedule -->
                <li>
                    <a href="{{ route('doctor.schedules.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ms-3">My Schedule</span>
                    </a>
                </li>

                <!-- Prescriptions -->
                <li>
                    <a href="{{ route('doctor.prescriptions.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ms-3">Prescriptions</span>
                    </a>
                </li>
            </ul>

            <!-- Beta CTA -->
            <div class="mt-4 p-4 bg-blue-900 dark:bg-blue-800 rounded-lg mx-2">
                <div class="flex justify-between items-start mb-2">
                    <span class="bg-pink-100 text-pink-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Beta</span>
                    <button type="button" class="text-blue-400 hover:text-blue-300"
                        aria-label="Close beta notification">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <p class="mb-3 text-sm text-blue-800 dark:text-blue-400">
                    Preview the new Doctor portal navigation! You can turn the new navigation off for a limited time in
                    your profile.
                </p>
                <a class="text-sm text-blue-800 underline font-medium hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                    href="#">Turn new navigation off</a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-4 sm:ml-64" id="main-content">
        <div class="p-4 mt-14">
            @yield('content')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get DOM elements
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('logo-sidebar');
            const mainContent = document.getElementById('main-content');
            const betaNotification = document.querySelector('.bg-blue-900');
            const closeButton = betaNotification?.querySelector('button');
            let isSidebarOpen = true;

            // Function to open sidebar
            function openSidebar() {
                sidebar.style.transform = 'translateX(0)';
                mainContent.style.marginLeft = '16rem';
                isSidebarOpen = true;
            }

            // Function to close sidebar
            function closeSidebar() {
                sidebar.style.transform = 'translateX(-100%)';
                mainContent.style.marginLeft = '0';
                isSidebarOpen = false;
            }

            // Combine sidebar and toggle for hover detection
            const sidebarElements = [sidebar, sidebarToggle];

            sidebarElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    openSidebar();
                });

                element.addEventListener('mouseleave', function(e) {
                    // Check if the mouse isn't entering another monitored element
                    const isEnteringOtherElement = sidebarElements.some(el =>
                        el !== element && el.contains(e.relatedTarget)
                    );

                    if (!isEnteringOtherElement) {
                        closeSidebar();
                    }
                });
            });

            // Handle click for navigation
            sidebarToggle.addEventListener('click', function(e) {
                // Handle navigation to home
                window.location.href = '/';
            });

            // Beta notification handling
            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    betaNotification.style.display = 'none';
                    localStorage.setItem('betaNotificationClosed', 'true');
                });

                if (localStorage.getItem('betaNotificationClosed') === 'true') {
                    betaNotification.style.display = 'none';
                }
            }

            // Initialize sidebar state
            if (window.innerWidth < 640) {
                closeSidebar();
            }

            // Add smooth transitions matching patient layout
            sidebar.classList.add('transition-transform', 'duration-300', 'ease-in-out');
            mainContent.classList.add('transition-[margin]', 'duration-300', 'ease-in-out');
        });
    </script>
</body>

</html>
