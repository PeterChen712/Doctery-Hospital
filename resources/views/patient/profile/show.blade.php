@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <!-- Header Profile -->
            <div class="relative">
                <div class="h-40 bg-gradient-to-r from-blue-600 via-blue-500 to-blue-700 rounded-t-lg"></div>
                <div class="absolute bottom-0 left-0 w-full transform translate-y-1/2 flex justify-center">
                    @if(Auth::user()->profile_image)
                        <img src="{{ route('avatar.show', Auth::user()->user_id) }}" 
                             alt="Foto Profil"
                             class="w-36 h-36 rounded-full border-6 border-white dark:border-gray-800 shadow-2xl object-cover">
                    @else
                        <div class="w-36 h-36 rounded-full border-6 border-white dark:border-gray-800 shadow-2xl 
                                  bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center">
                            <span class="text-5xl font-bold text-blue-600">
                                {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Konten Profil -->
            <div class="p-8 pt-24">
                <!-- Informasi Dasar -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">{{ Auth::user()->username }}</h2>
                    <p class="text-gray-500 dark:text-gray-400 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                        {{ Auth::user()->email }}
                    </p>
                </div>

                <!-- Grid Informasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Informasi Kontak -->
                    <div class="space-y-4 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-600 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Informasi Kontak
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Telepon</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ Auth::user()->phone_number ?? 'Belum diatur' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</label>
                                <p class="text-gray-900 dark:text-white font-medium">{{ Auth::user()->address ?? 'Belum diatur' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Medis -->
                    <div class="space-y-4 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-600 pb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Informasi Medis
                        </h3>
                        @if(Auth::user()->patient)
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Lahir</label>
                                    <p class="text-gray-900 dark:text-white font-medium">
                                        {{ Auth::user()->patient->date_of_birth ? Auth::user()->patient->date_of_birth->translatedFormat('d F Y') : 'Belum diatur' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Golongan Darah</label>
                                    <p class="text-gray-900 dark:text-white font-medium">
                                        {{ Auth::user()->patient->blood_type ?? 'Belum diatur' }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500">Informasi medis belum tersedia</p>
                        @endif
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end">
                    <a href="{{ route('patient.profile.edit') }}" 
                       class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 
                              text-white px-6 py-2.5 rounded-lg text-sm font-medium shadow-lg 
                              hover:shadow-xl transition duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Ubah Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection