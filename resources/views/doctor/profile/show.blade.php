<!-- resources/views/doctor/profile/show.blade.php -->
@extends('layouts.doctor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Profile Header -->
            <div class="relative h-48 bg-gradient-to-r from-blue-500 to-blue-600">
                <div class="absolute bottom-0 left-0 right-0 px-6 py-4 transform translate-y-1/2">
                    <div class="flex items-center">
                        @if ($user->profile_image)
                            <img class="w-24 h-24 rounded-full border-4 border-white shadow-lg"
                                src="{{ route('avatar.show', $user->user_id) }}" 
                                alt="{{ $user->username }}">
                        @else
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-600 font-medium text-2xl">
                                    {{ substr($user->username, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div class="ml-6">
                            <h1 class="text-2xl font-bold text-white">Dr. {{ $user->username }}</h1>
                            <p class="text-blue-100">{{ $doctor->specialization }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="px-6 py-12 mt-12">
                <!-- Basic Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informasi Dasar</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Telepon</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $user->phone_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Lisensi</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $doctor->license_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Spesialisasi</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $doctor->specialization }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informasi Kontak</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</label>
                            <p class="mt-1 text-gray-900 dark:text-white">{{ $user->address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informasi Jadwal</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jam Kerja</label>
                            <p class="mt-1 text-gray-900 dark:text-white">
                                Lihat <a href="{{ route('doctor.schedules.index') }}" class="text-blue-600 hover:text-blue-800">halaman jadwal</a> untuk informasi lengkap
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex justify-end">
                    <a href="{{ route('doctor.profile.edit') }}"
                        class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection