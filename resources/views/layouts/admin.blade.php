<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin Portal</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body class="font-sans antialiased">
    <nav class="fixed top-0 z-50 w-full border-b border-gray-200" style="background-color: #d7dfe5;">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button id="sidebar-toggle" type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-400 rounded-lg sm:hidden hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600">
                        <span class="sr-only">Toggle sidebar</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <span
                        class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white cursor-pointer select-none ml-2 hover:text-gray-300">
                        <img src="{{ asset('assets\images\home\logo3.png') }}" alt="Company Logo"
                            class="w-[150px] h-[45px] object-contain hover:opacity-80 transition-opacity">
                    </span>
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
                                <x-dropdown-link :href="route('admin.profile.edit')">Profile</x-dropdown-link>
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
        </div>
    </nav>


    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-[#100d35] border-r border-gray-700 sm:translate-x-0">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-[#100d35] scrollbar-hide">
            <ul class="space-y-2 font-medium">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>

                <!-- Users -->
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span class="ms-3">Users</span>
                    </a>
                </li>

                <!-- Medicines -->
                <li>
                    <a href="{{ route('admin.medicines.index') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                        <span class="ms-3">Medicines</span>
                    </a>
                </li>

                <!-- Reports -->
                {{-- <li>
                    <button type="button" data-collapse-toggle="dropdown-reports"
                        class="flex items-center w-full p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Reports</span>
                        <svg class="w-3 h-3 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <ul id="dropdown-reports" class="hidden py-2 space-y-2">
                        <li>
                            <a href="{{ route('admin.reports.users') }}"
                                class="flex items-center w-full p-2 text-gray-200 rounded-lg pl-11 hover:bg-gray-700 group">
                                Users Report
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reports.medicines') }}"
                                class="flex items-center w-full p-2 text-gray-200 rounded-lg pl-11 hover:bg-gray-700 group">
                                Medicines Report
                            </a>
                        </li>
                    </ul>
                </li> --}}

                <!-- Appointments -->
                <li>
                    <a href="{{ route('admin.appointments.index') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="ms-3">Appointments</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>


    <!-- Main content wrapper remains the same -->
    <div class="p-4 sm:ml-64" id="main-content">
        <div class="p-4 mt-14">
            @if (isset($header))
                <header class="mb-4">
                    {{ $header }}
                </header>
            @endif

            <main>
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get DOM elements
            const sidebarToggle = document.querySelector('.whitespace-nowrap'); // "Doctor Portal" text
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
                sidebar.style.transform = 'translateX(-100%)';
                mainContent.style.marginLeft = '0';
                isSidebarOpen = false;
            }

            // Handle hover events for both the sidebar toggle and the sidebar itself
            sidebarToggle.addEventListener('mouseenter', function() {
                openSidebar();
            });

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

            // Add smooth transitions
            sidebar.classList.add('transition-transform', 'duration-300', 'ease-in-out');
            mainContent.classList.add('transition-[margin]', 'duration-300', 'ease-in-out');

            // Initialize sidebar state - closed by default
            closeSidebar();
        });
    </script>
</body>

</html>
