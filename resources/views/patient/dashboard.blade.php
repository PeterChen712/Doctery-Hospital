@extends('layouts.patient')

@section('content')
    <!-- Carousel Section -->


    <div id="default-carousel" class="relative w-full" data-carousel="slide">
        <!-- Carousel wrapper -->
        <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
            <!-- Item 1 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ asset('assets\images\patient-dashboard\1.png') }}"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
            <!-- Item 2 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ asset('assets\images\patient-dashboard\2.png') }}"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
            <!-- Item 3 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ asset('assets\images\patient-dashboard\3.png') }}"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
            <!-- Item 4 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ asset('assets\images\patient-dashboard\4.png') }}"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
            <!-- Item 5 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="{{ asset('assets\images\patient-dashboard\5.png') }}"
                    class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
            </div>
        </div>
        <!-- Slider indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
            <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1"
                data-carousel-slide-to="0"></button>
            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2"
                data-carousel-slide-to="1"></button>
            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3"
                data-carousel-slide-to="2"></button>
            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 4"
                data-carousel-slide-to="3"></button>
            <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 5"
                data-carousel-slide-to="4"></button>
        </div>
        <!-- Slider controls -->
        <button type="button"
            class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-prev>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 1 1 5l4 4" />
                </svg>
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
            class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
            data-carousel-next>
            <span
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 9 4-4-4-4" />
                </svg>
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>


    <!-- Gradient Header -->
    <div class="bg-gradient-to-r from-green-400 to-blue-500 text-white py-12 mb-6">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Selamat Datang di Portal Kesehatan Anda</h1>
            <p class="text-xl mb-4">Kesehatan Anda adalah Prioritas Kami</p>
            <button class="bg-white text-green-600 px-6 py-2 rounded-lg text-sm font-semibold hover:bg-green-50">Lihat Fitur
                Baru</button>
        </div>
    </div>


    <div class="container mx-auto px-4">
        <!-- Action Buttons -->
        <div class="container mx-auto px-4">
            <div class="flex justify-center gap-8 mb-8 mx-auto max-w-4xl">
                <!-- Medical Records Button -->
                <a href="{{ route('patient.medical-records') }}"
                    class="flex flex-col items-center group hover:scale-110 transition-all duration-300">
                    <div
                        class="w-16 h-16 rounded-full bg-purple-500 flex items-center justify-center mb-2 group-hover:bg-purple-600 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-purple-600">Catatan Medis</span>
                </a>

                <!-- Appointments Button -->
                <a href="{{ route('patient.appointments.index') }}"
                    class="flex flex-col items-center group hover:scale-110 transition-all duration-300">
                    <div
                        class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center mb-2 group-hover:bg-blue-600 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-600">Janji Temu</span>
                </a>

                <!-- Prescriptions Button -->
                <a href="{{ route('patient.prescriptions') }}"
                    class="flex flex-col items-center group hover:scale-110 transition-all duration-300">
                    <div
                        class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-2 group-hover:bg-green-600 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-600">Resep</span>
                </a>

                <!-- Notifications Button -->
                <a href="{{ route('patient.notifications') }}"
                    class="flex flex-col items-center group hover:scale-110 transition-all duration-300">
                    <div
                        class="w-16 h-16 rounded-full bg-red-500 flex items-center justify-center mb-2 group-hover:bg-red-600 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-red-600">Notifikasi</span>
                </a>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Medical Records Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Catatan Medis
                </h2>
                <div class="space-y-4">
                    @forelse($medicalRecords as $record)
                        <div class="border-b pb-4 hover:bg-gray-50 p-2 rounded">
                            @if ($record->treatment_date)
                                <p class="font-medium text-purple-600">{{ $record->treatment_date->format('M d, Y') }}
                                </p>
                            @endif
                            @if ($record->doctor && $record->doctor->user)
                                <p class="text-gray-600">Dokter: {{ $record->doctor->user->username }}</p>
                            @endif
                            <p class="text-gray-600">Diagnosis: {{ $record->diagnosis ?? 'Tidak ditentukan' }}</p>
                            <p class="text-gray-600">Tindakan: {{ $record->medical_action ?? 'Tidak ditentukan' }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">Tidak ada catatan medis.</p>
                    @endforelse
                </div>
            </div>

            <!-- Appointments Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Janji Temu Berikutnya
                </h2>
                <div class="space-y-4">
                    @forelse($appointments as $appointment)
                        <div class="border-b pb-4 hover:bg-gray-50 p-2 rounded">
                            <p class="font-medium text-blue-600">{{ $appointment->appointment_date->format('M d, Y') }}
                            </p>
                            <p class="text-gray-600">Jam: {{ $appointment->appointment_time }}</p>
                            <p class="text-gray-600">Dokter:
                                {{ $appointment->doctor->user->username ?? 'Tidak ditentukan' }}</p>
                            <p class="text-gray-600">Status: {{ $appointment->status }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">Tidak ada janji temu yang akan datang.</p>
                    @endforelse
                </div>
            </div>

            <!-- Prescriptions Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Resep Terbaru
                </h2>
                <div class="space-y-4">
                    @forelse($prescriptions as $prescription)
                        <div class="border-b pb-4 hover:bg-gray-50 p-2 rounded">
                            <p class="font-medium text-green-600">{{ $prescription->created_at->format('M d, Y') }}
                            </p>
                            <p class="text-gray-600">Dokter:
                                {{ $prescription->doctor->user->username ?? 'Tidak ditentukan' }}</p>
                            <p class="text-gray-600">Obat: {{ $prescription->medicine_name }}</p>
                            <p class="text-gray-600">Dosis: {{ $prescription->dosage }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">Tidak ada resep terbaru.</p>
                    @endforelse
                </div>
            </div>

            <!-- Notifications Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Notifikasi Terbaru
                </h2>
                <div class="space-y-4">
                    @forelse($notifications->sortByDesc('created_at')->take(5) as $notification)
                        <div
                            class="border-b pb-4 hover:bg-gray-50 p-2 rounded transition-colors duration-200 
                {{ $loop->first ? 'bg-red-50' : '' }}">
                            <p class="font-medium {{ $loop->first ? 'text-red-600' : 'text-gray-600' }}">
                                {{ $notification->created_at->format('d M Y') }}
                            </p>
                            <p class="text-gray-600 mt-1">{{ $notification->message }}</p>
                            <p class="text-gray-500 text-sm mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500">Tidak ada notifikasi baru.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
