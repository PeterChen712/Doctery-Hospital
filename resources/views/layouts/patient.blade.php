<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
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
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <span
                        class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white cursor-pointer select-none ml-2"
                        id="sidebar-toggle">Patient Portal</span>
                </div>

                <div class="flex items-center">

                    <div class="flex items-center ms-3">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button type="button"
                                    class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                                    <span class="sr-only">Open user menu</span>
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

                                <ul class="py-1">
                                    <li>
                                        <a href="{{ route('patient.dashboard') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Dashboard</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('profile.edit') }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Profile</a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); this.closest('form').submit();"
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Sign
                                                out</a>
                                        </form>
                                    </li>
                                </ul>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">

            <!-- User Profile Section -->
            <div class="flex flex-col items-center pb-6 border-b border-gray-200 dark:border-gray-700">
                <img class="w-20 h-20 mb-3 rounded-full shadow-lg"
                    src="{{ Auth::user()->profile_image ? Storage::url(Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name=' . Auth::user()->username }}"
                    alt="{{ Auth::user()->username }} profile image">
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ Auth::user()->username }}</h5>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</span>
            </div>

            <!-- Navigation Menu -->
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('patient.dashboard') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.appointments.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="ms-3">Appointments</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.medical-records') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ms-3">Medical Records</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.prescriptions') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="ms-3">Prescriptions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.notifications') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="ms-3">Notifications</span>
                        @if (Auth::user()->unreadNotifications()->count() > 0)
                            <span
                                class="inline-flex items-center justify-center w-6 h-6 ms-3 text-sm font-medium text-white bg-red-500 rounded-full">
                                {{ Auth::user()->unreadNotifications()->count() }}
                            </span>
                        @endif
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
                <p class="mb-2 text-sm text-blue-400">Preview the new Patient Portal navigation! You can turn the new
                    navigation off for a limited time in your profile.</p>
                <a class="text-sm text-blue-500 hover:text-blue-400 hover:underline" href="#">Turn new
                    navigation off</a>
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
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('logo-sidebar');
            const mainContent = document.getElementById('main-content');
            let isSidebarOpen = true;

            function toggleSidebar() {
                if (isSidebarOpen) {
                    sidebar.style.transform = 'translateX(-100%)';
                    mainContent.style.marginLeft = '0';
                } else {
                    sidebar.style.transform = 'translateX(0)';
                    mainContent.style.marginLeft = '16rem';
                }
                isSidebarOpen = !isSidebarOpen;
            }

            sidebarToggle.addEventListener('click', toggleSidebar);

            // Handle both sidebar toggles
            const sidebarToggles = document.querySelectorAll('[data-drawer-toggle]');
            sidebarToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-drawer-target');
                    const targetElement = document.getElementById(targetId);

                    if (targetElement) {
                        if (targetId === 'logo-sidebar') {
                            targetElement.classList.toggle('-translate-x-full');
                        } else if (targetId === 'cta-button-sidebar') {
                            // Handle CTA button sidebar
                            // You can add specific behavior for CTA sidebar here
                            targetElement.classList.toggle('translate-x-0');
                            targetElement.classList.toggle('translate-x-full');
                        }
                    }
                });
            });

            // Close sidebars when clicking outside
            document.addEventListener('click', function(event) {
                const sidebar = document.getElementById('logo-sidebar');
                const ctaSidebar = document.getElementById('cta-button-sidebar');
                const sidebarButton = document.querySelector('[data-drawer-target="logo-sidebar"]');
                const ctaButton = document.querySelector('[data-drawer-target="cta-button-sidebar"]');

                // Check if click is outside both sidebars and their toggle buttons
                if (!sidebar?.contains(event.target) &&
                    !ctaSidebar?.contains(event.target) &&
                    !sidebarButton?.contains(event.target) &&
                    !ctaButton?.contains(event.target)) {

                    // Close sidebars on mobile
                    if (window.innerWidth < 640) {
                        sidebar?.classList.add('-translate-x-full');
                        ctaSidebar?.classList.remove('translate-x-0');
                        ctaSidebar?.classList.add('translate-x-full');
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                const sidebar = document.getElementById('logo-sidebar');
                const ctaSidebar = document.getElementById('cta-button-sidebar');

                if (window.innerWidth >= 640) {
                    sidebar?.classList.remove('-translate-x-full');
                    mainContent.style.marginLeft = '16rem';
                    isSidebarOpen = true;
                } else {
                    sidebar?.classList.add('-translate-x-full');
                    ctaSidebar?.classList.remove('translate-x-0');
                    ctaSidebar?.classList.add('translate-x-full');
                    mainContent.style.marginLeft = '0';
                    isSidebarOpen = false;
                }
            });

            const betaNotification = document.querySelector('.bg-blue-900');
            const closeButton = betaNotification?.querySelector('button');

            closeButton?.addEventListener('click', function() {
                betaNotification.style.display = 'none';
                // Optional: Save state to localStorage or make API call to remember user's preference
                localStorage.setItem('betaNotificationClosed', 'true');
            });

            // Check if notification was previously closed
            if (localStorage.getItem('betaNotificationClosed') === 'true') {
                betaNotification.style.display = 'none';
            }
        });
    </script>
</body>

</html>
