<!-- resources/views/layouts/doctor.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Doctor Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .scrollbar-hide {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        body {
            background-image: url('/assets/images/home/blurbg3.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Top Navigation -->
    <!-- Update the top navigation section -->
    <nav class="fixed top-0 z-50 w-full border-b border-gray-200" style="background-color: #d7dfe5;">
        <!-- Add gradient background -->
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-300 rounded-lg sm:hidden 
                                   hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600">
                        <span class="sr-only">Open sidebar</span>
                    </button>
                    <a href="/" class="ml-2" id="sidebar-toggle">
                        <img src="{{ asset('assets\images\home\logo3.png') }}" alt="Company Logo"
                            class="w-[150px] h-[45px] object-contain hover:opacity-80 transition-opacity">
                    </a>
                </div>

                <div class="flex items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button type="button"
                                class="flex text-sm rounded-full focus:ring-4 focus:ring-gray-600 border-2 border-gray-600">
                                <span class="sr-only">Open user menu</span>
                                @if (Auth::user()->profile_image)
                                    <img class="w-8 h-8 rounded-full"
                                        src="{{ route('avatar.show', Auth::user()->user_id) }}"
                                        alt="{{ Auth::user()->username }}">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-200 font-medium text-sm">
                                            {{ substr(Auth::user()->username, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3">
                                <p class="text-sm text-gray-900 dark:text-white">{{ Auth::user()->username }}</p>
                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300">
                                    {{ Auth::user()->email }}
                                </p>
                            </div>
                            <x-dropdown-link :href="route('doctor.profile.show')">Profile</x-dropdown-link>
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
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-[#100d35] border-r border-gray-700 sm:translate-x-0">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-[#100d35] scrollbar-hide">
            <!-- User Profile Section -->
            <div class="flex flex-col items-center pb-6 border-b border-gray-700 pt-10">
                @if (Auth::user()->profile_image)
                    <img class="w-20 h-20 mb-3 rounded-full shadow-lg object-cover"
                        src="{{ route('avatar.show', Auth::user()->user_id) }}" alt="{{ Auth::user()->username }}">
                @else
                    <div class="w-20 h-20 mb-3 rounded-full shadow-lg bg-gray-700 flex items-center justify-center">
                        <span class="text-white font-medium text-xl">
                            {{ substr(Auth::user()->username, 0, 1) }}
                        </span>
                    </div>
                @endif
                <h5 class="mb-1 text-xl font-medium text-white">{{ Auth::user()->username }}</h5>
                <span class="text-sm text-gray-300">{{ Auth::user()->email }}</span>
            </div>

            <!-- Navigation Menu -->
            <ul class="space-y-2 font-medium mt-6">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('doctor.dashboard') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <!-- Patients -->
                <li>
                    <a href="{{ route('doctor.patients.index') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="ms-3">Pasien</span>
                    </a>
                </li>

                <!-- Appointments -->
                <li>
                    <a href="{{ route('doctor.appointments.index') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="ms-3">Janji Temu</span>
                    </a>
                </li>

                <!-- Schedule -->
                <li>
                    <a href="{{ route('doctor.schedules.index') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ms-3">Jadwal</span>
                    </a>
                </li>

                <!-- Medical Records -->
                <li>
                    <a href="{{ route('doctor.medical-records.index') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ms-3">Rekam Medis</span>
                    </a>
                </li>
            </ul>
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
