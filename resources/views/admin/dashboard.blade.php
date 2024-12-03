@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-semibold mb-4">Dashboard</h2>

    <div class="h-1 bg-red-500 my-4 rounded-full"></div>

    <!-- Statistik Pengguna -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Statistik Dokter -->
        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-indigo-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-indigo-700">Total Dokter</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $userStats['total_doctors'] }}</p>
                </div>
                <div class="p-3 bg-indigo-200 rounded-full">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.users.index', ['role' => 'doctor']) }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat semua dokter →</a>
        </div>

        <!-- Statistik Pasien -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-green-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-green-700">Total Pasien</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $userStats['total_patients'] }}</p>
                </div>
                <div class="p-3 bg-green-200 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.users.index', ['role' => 'patient']) }}" class="text-sm text-green-600 hover:text-green-800">Lihat semua pasien →</a>
        </div>

        <!-- Statistik Admin -->
        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 border border-purple-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-purple-700">Total Admin</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $userStats['total_admins'] }}</p>
                </div>
                <div class="p-3 bg-purple-200 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="text-sm text-purple-600 hover:text-purple-800">Kelola admin →</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Dokter Aktif -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg shadow-md border border-blue-200">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-blue-800">Dokter Aktif Hari Ini</h3>
                <a href="{{ route('admin.users.index', ['role' => 'doctor', 'status' => 'active']) }}" 
                   class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
            </div>
            <div class="space-y-4">
                @forelse ($activeDoctors as $doctor)
                    <div class="flex items-center justify-between p-4 bg-white hover:bg-blue-50 rounded-lg transition-colors duration-150 border border-blue-200">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="font-medium text-blue-900">{{ $doctor->user->username }}</p>
                                <p class="text-sm text-blue-600">{{ $doctor->specialization }}</p>
                            </div>
                        </div>
                        
                    </div>
                @empty
                    <div class="text-center py-4 text-blue-600 bg-white border border-blue-200 rounded-lg">
                        Tidak ada dokter aktif hari ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-lg shadow-md border border-gray-200">
            <h3 class="text-xl font-semibold mb-6 text-gray-800">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('admin.users.create') }}" 
                   class="bg-white p-4 border border-indigo-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-500 hover:shadow-md transition-all duration-300">
                    <svg class="w-8 h-8 text-indigo-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    <h4 class="font-medium text-indigo-700">Tambah Pengguna Baru</h4>
                </a>
                
                <a href="{{ route('admin.medicines.create') }}" 
                   class="bg-white p-4 border border-green-200 rounded-lg hover:bg-green-50 hover:border-green-500 hover:shadow-md transition-all duration-300">
                    <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    <h4 class="font-medium text-green-700">Tambah Obat</h4>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection