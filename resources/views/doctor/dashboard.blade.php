@extends('layouts.doctor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex items-center">
        <div class="flex items-center gap-2 mb-6 bg-gradient-to-r from-blue-400 to-blue-600 p-4 rounded-lg shadow-lg w-full">
            <h1 class="text-3xl font-bold text-white">Dashboard Dokter</h1>
        </div>
    </div>

    <!-- Blue Divider -->
    <div class="h-1 bg-blue-500 my-4 rounded-full"></div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Patients Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
            <div class="bg-gradient-to-r from-purple-400 to-purple-600 p-4">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    5 Pasien Terbaru
                </h3>
            </div>
            <div class="divide-y dark:divide-gray-700">
                @forelse($recentPatients->take(5) as $record)
                    <div class="p-4 hover:bg-purple-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $record->patient->user->username }}
                                </span>
                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $record->treatment_date->format('d M Y') }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    Diagnosis: {{ Str::limit($record->diagnosis, 50) }}
                                </p>
                            </div>
                            <a href="{{ route('doctor.medical-records.show', $record) }}" 
                               class="px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition-all">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Belum ada pasien yang diperiksa</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Ongoing Records Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 p-4">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Rekam Medis dalam Proses
                </h3>
            </div>
            <div class="divide-y dark:divide-gray-700">
                @forelse($ongoingTreatments as $record)
                    <div class="p-4 hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ $record->patient->user->username }}
                                </span>
                                <p class="text-sm text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $record->treatment_date->format('d M Y') }}
                                </p>
                                <p class="text-sm text-gray-500">Status: {{ $record->status }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('doctor.medical-records.show', $record) }}" 
                                   class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                                    Lihat
                                </a>
                                <a href="{{ route('doctor.medical-records.edit', $record) }}" 
                                   class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all">
                                    Update
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada rekam medis yang sedang diproses</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-gradient-to-r from-green-400 to-green-600 p-6 rounded-xl text-white shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex items-center gap-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <div>
                    <h4 class="text-lg font-semibold">Total Pasien Hari Ini</h4>
                    <p class="text-3xl font-bold mt-1">{{ $todayPatients }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-red-400 to-red-600 p-6 rounded-xl text-white shadow-lg hover:shadow-xl transition-shadow">
            <div class="flex items-center gap-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h4 class="text-lg font-semibold">Menunggu Konfirmasi</h4>
                    <p class="text-3xl font-bold mt-1">{{ $pendingAppointments }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection