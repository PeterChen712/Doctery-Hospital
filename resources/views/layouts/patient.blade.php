<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    </style>
</head>

<body class="font-sans antialiased">

    <!-- Top Navigation -->
    <nav class="fixed top-0 z-50 w-full border-b border-gray-200" style="background-color: #d7dfe5;">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <a href="/" class="ml-2" id="sidebar-toggle">
                        <img src="{{ asset('assets\images\home\logo3.png') }}" alt="Company Logo"
                            class="w-[150px] h-[45px] object-contain hover:opacity-80 transition-opacity">
                    </a>
                </div>




                <div class="flex items-center">
                    <div class="flex items-center ms-3">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button type="button"
                                    class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-600">
                                    <span class="sr-only">Open user menu</span>
                                    @if (Auth::user()->profile_image)
                                        <img class="w-8 h-8 rounded-full"
                                            src="{{ route('avatar.show', Auth::user()->user_id) }}"
                                            alt="{{ Auth::user()->username }}">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-gray-600 font-medium text-sm">
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
                                        {{ Auth::user()->email }}</p>
                                </div>

                                <ul class="py-1">

                                    <li>
                                        <a href="{{ route('patient.profile.show') }}"
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
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform border-r border-gray-700 sm:translate-x-0"
        style="background-color: #100d35;">
        <div class="h-full px-3 pb-4 overflow-y-auto scrollbar-hide" style="background-color: #100d35;">

            <!-- User Profile Section -->
            <div class="flex flex-col items-center pb-6 border-b border-gray-700 pt-10">
                @if (Auth::user()->profile_image)
                    <img class="w-20 h-20 mb-3 rounded-full shadow-lg"
                        src="{{ route('avatar.show', Auth::user()->user_id) }}" alt="{{ Auth::user()->username }}">
                @else
                    <div class="w-20 h-20 mb-3 rounded-full shadow-lg bg-gray-600 flex items-center justify-center">
                        <span class="text-white font-medium text-xl">
                            {{ substr(Auth::user()->username, 0, 1) }}
                        </span>
                    </div>
                @endif
                <h5 class="mb-1 text-xl font-medium text-white">{{ Auth::user()->username }}</h5>
                <span class="text-sm text-gray-300">{{ Auth::user()->email }}</span>
            </div>

            <!-- Navigation Menu -->
            <ul class="space-y-2 font-medium mt-4">
                <li>
                    <a href="{{ route('patient.dashboard') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-300 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="ms-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.appointments.index') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-300 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="ms-3">Appointments</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.medical-records') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-300 transition duration-75 group-hover:text-white" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="ms-3">Medical Records</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.prescriptions') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group">
                        <svg class="w-5 h-5 text-gray-300 transition duration-75 group-hover:text-white"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="ms-3">Prescriptions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('patient.notifications') }}"
                        class="flex items-center p-2 text-gray-200 rounded-lg hover:bg-gray-700 group relative">
                        <div class="relative">
                            <svg class="w-5 h-5 transition duration-75 
                            {{ Auth::user()->unreadNotifications()->count() > 0 ? 'text-red-400' : 'text-gray-300' }} 
                            group-hover:text-white"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="{{ Auth::user()->unreadNotifications()->count() > 0 ? '2.5' : '2' }}"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>

                            @if (Auth::user()->unreadNotifications()->count() > 0)
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                    <span
                                        class="absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75 animate-ping"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </span>
                            @endif
                        </div>

                        <span class="ms-3">Notifications</span>

                        @if (Auth::user()->unreadNotifications()->count() > 0)
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 ms-3 text-xs font-medium text-white bg-red-500 rounded-full">
                                {{ Auth::user()->unreadNotifications()->count() }}
                            </span>
                        @endif
                    </a>
                </li>
            </ul>

            <!-- Profile Completion CTA -->
            @if (!Auth::user()->isProfileComplete())
                <div class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 rounded-lg mx-2">
                    <div class="mb-2">
                        <span class="bg-yellow-200 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Action Required
                        </span>
                    </div>
                    <p class="mb-2 text-sm text-yellow-700">
                        Your profile is incomplete. Complete your profile to get personalized medical care and
                        appointment scheduling.
                    </p>
                    <a href="{{ route('patient.profile.edit') }}"
                        class="text-sm bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-1 px-3 rounded inline-flex items-center">
                        Complete Profile
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </aside>

    <!-- Main Content -->
    <div class="p-4 sm:ml-64" id="main-content">
        <div class="p-4 mt-14">
            @yield('content')
        </div>
    </div>

  
    <script>

        // Sidebar Elements
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('logo-sidebar');
    const mainContent = document.getElementById('main-content');
    const betaNotification = document.querySelector('.bg-blue-900');
    const closeButton = betaNotification?.querySelector('button');
    let isSidebarOpen = true;

    // Sidebar Functions
    function openSidebar() {
        sidebar.style.transform = 'translateX(0)';
        mainContent.style.marginLeft = '16rem';
        isSidebarOpen = true;
    }

    function closeSidebar() {
        sidebar.style.transform = 'translateX(-100%)';
        mainContent.style.marginLeft = '0';
        isSidebarOpen = false;
    }

    // Sidebar hover events
    const sidebarElements = [sidebar, sidebarToggle];
    sidebarElements.forEach(element => {
        element.addEventListener('mouseenter', openSidebar);
        element.addEventListener('mouseleave', function(e) {
            const isEnteringOtherElement = sidebarElements.some(el =>
                el !== element && el.contains(e.relatedTarget)
            );
            if (!isEnteringOtherElement) {
                closeSidebar();
            }
        });
    });

    // Mobile menu toggle
    const sidebarToggles = document.querySelectorAll('[data-drawer-toggle]');
    sidebarToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
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
        // Initialize Select2 for doctor selection
$(document).ready(function() {
    $('.doctor-select').select2({
        placeholder: 'Cari dokter...',
        allowClear: true,
        width: '100%'
    });

    // Add change event listener to doctor select
    $('#doctor_id').on('change', function() {
        loadDoctorSchedules();
    });
});

// Toggle appointment type sections
function toggleAppointmentType() {
    const appointmentType = document.querySelector('input[name="appointment_type"]:checked').value;
    const scheduledSection = document.getElementById('scheduled-section');
    const preferredSection = document.getElementById('preferred-section');
    const scheduleSelect = document.getElementById('schedule_id');
    const preferredDate = document.getElementById('preferred_date');
    const preferredTime = document.getElementById('preferred_time');

    if (appointmentType === 'scheduled') {
        scheduledSection.classList.remove('hidden');
        preferredSection.classList.add('hidden');
        scheduleSelect.required = true;
        preferredDate.required = false;
        preferredTime.required = false;
    } else {
        scheduledSection.classList.add('hidden');
        preferredSection.classList.remove('hidden');
        scheduleSelect.required = false;
        preferredDate.required = true;
        preferredTime.required = true;
    }
}

// Load doctor schedules
function loadDoctorSchedules() {
    const doctorId = document.getElementById('doctor_id').value;
    const scheduleSelect = document.getElementById('schedule_id');
    const token = document.querySelector('meta[name="csrf-token"]').content;

    console.log('Loading schedules for doctor:', doctorId); // Debug log

    if (!doctorId) {
        scheduleSelect.innerHTML = '<option value="">Pilih Dokter Terlebih Dahulu</option>';
        scheduleSelect.disabled = true;
        return;
    }

    scheduleSelect.disabled = true;
    scheduleSelect.innerHTML = '<option value="">Memuat jadwal...</option>';

    fetch(`/patient/doctors/${doctorId}/schedules?_=${new Date().getTime()}`, {
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
            'Cache-Control': 'no-cache'
        }
    })
    .then(response => {
        console.log('Response status:', response.status); // Debug log
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data); // Debug log
        scheduleSelect.innerHTML = '<option value="">Pilih Jadwal</option>';

        if (!data.schedules || data.schedules.length === 0) {
            scheduleSelect.innerHTML = '<option value="">Tidak ada jadwal tersedia</option>';
            scheduleSelect.disabled = true;
            return;
        }

        data.schedules.forEach(schedule => {
            const option = document.createElement('option');
            option.value = schedule.schedule_id;

            try {
                const scheduleDate = new Date(schedule.schedule_date + 'T00:00:00');
                if (isNaN(scheduleDate)) throw new Error('Invalid date');

                const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const formattedDate = scheduleDate.toLocaleDateString('id-ID');
                const dayName = dayNames[scheduleDate.getDay()];

                const formatTime = (timeStr) => {
                    if (!timeStr) return '';
                    const [hours, minutes] = timeStr.split(':');
                    return new Date(2000, 0, 1, hours, minutes)
                        .toLocaleTimeString('id-ID', {
                            hour: 'numeric',
                            minute: '2-digit'
                        });
                };

                const availableSlots = schedule.max_patients - schedule.booked_patients;
                const slotText = availableSlots > 0 ? 
                    `(${availableSlots} slot tersedia)` : 
                    '(Penuh)';

                option.textContent = `${dayName}, ${formattedDate} ${formatTime(schedule.start_time)} - ${formatTime(schedule.end_time)} ${slotText}`;
                option.disabled = availableSlots <= 0;

            } catch (error) {
                console.error('Date parsing error:', error, schedule);
                option.textContent = `Jadwal tidak valid`;
                option.disabled = true;
            }

            scheduleSelect.appendChild(option);
        });

        scheduleSelect.disabled = false;
    })
    .catch(error => {
        console.error('Error loading schedules:', error);
        scheduleSelect.innerHTML = '<option value="">Gagal memuat jadwal</option>';
        scheduleSelect.disabled = true;
    });
}

// Set minimum date for preferred date
function setMinimumDate() {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('preferred_date').min = tomorrow.toISOString().split('T')[0];
}

// Form validation
function validateForm(event) {
    const appointmentType = document.querySelector('input[name="appointment_type"]:checked').value;
    
    if (appointmentType === 'scheduled' && !document.getElementById('schedule_id').value) {
        event.preventDefault();
        alert('Silakan pilih jadwal yang tersedia');
        return false;
    }
    
    if (appointmentType === 'preferred' && 
        (!document.getElementById('preferred_date').value || 
         !document.getElementById('preferred_time').value)) {
        event.preventDefault();
        alert('Silakan isi tanggal dan waktu yang diinginkan');
        return false;
    }
    
    return true;
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initialize appointment type
    toggleAppointmentType();
    
    // Set minimum date for preferred date
    setMinimumDate();
    
    // Add event listeners
    document.getElementById('doctor_id').addEventListener('change', loadDoctorSchedules);
    document.querySelector('form').addEventListener('submit', validateForm);
    
    // Load schedules if doctor is preselected
    if (document.getElementById('doctor_id').value) {
        loadDoctorSchedules();
    }
});
    </script>
</body>


</html>
