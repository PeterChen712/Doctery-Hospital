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
                    <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white cursor-pointer select-none" id="sidebar-toggle">Patient Portal</span>
                </div>
                
                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile_image ? Storage::url(Auth::user()->profile_image) : 'https://ui-avatars.com/api/?name='.Auth::user()->username }}" alt="user photo">
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-3">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ Auth::user()->username }}</p>
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300">{{ Auth::user()->email }}</p>
                                </div>
                                
                                <ul class="py-1">
                                    <li>
                                        <a href="{{ route('patient.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Dashboard</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Profile</a>
                                    </li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600">Sign out</a>
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
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('patient.dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.appointments.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="ms-3">Appointments</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.medical-records') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="ms-3">Medical Records</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.prescriptions') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span class="ms-3">Prescriptions</span>
                    </a>
                </li>
            </ul>
            
            <!-- Notifications -->
            <div class="mt-6 px-3">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Notifications</h3>
                <div class="space-y-3">
                    @forelse(Auth::user()->customNotifications()->latest()->take(5)->get() as $notification)
                        <div class="p-3 bg-gray-50 rounded-lg {{ $notification->is_read ? 'opacity-50' : '' }}">
                            <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No notifications</p>
                    @endforelse
                </div>
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
                    mainContent.style.marginLeft = '16rem'; // 64 in rem
                }
                isSidebarOpen = !isSidebarOpen;
            }
            
            sidebarToggle.addEventListener('click', toggleSidebar);
        });
    </script>
</body>
</html>