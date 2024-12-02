@extends('layouts.doctor')

@section('content')
<h2 class="text-xl font-semibold">Dashboard Dokter</h2>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Pasien Terbaru -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="bg-purple-500 p-4">
            <h3 class="text-lg font-semibold text-white">5 Pasien Terbaru</h3>
        </div>
        <div class="divide-y dark:divide-gray-700">
            @forelse($recentPatients->take(5) as $record)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $record->patient->user->username }}
                            </span>
                            <p class="text-sm text-gray-500">
                                Terakhir diperiksa: {{ $record->treatment_date->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Diagnosis: {{ Str::limit($record->diagnosis, 50) }}
                            </p>
                        </div>
                        <a href="{{ route('doctor.medical-records.show', $record) }}" 
                           class="px-3 py-1 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition-colors">
                            Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-4">
                    <p class="text-gray-500 dark:text-gray-400">Belum ada pasien yang diperiksa</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Rekam Medis dalam Proses -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="bg-blue-500 p-4">
            <h3 class="text-lg font-semibold text-white">Rekam Medis dalam Proses</h3>
        </div>
        <div class="divide-y dark:divide-gray-700">
            @forelse($ongoingTreatments as $record)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $record->patient->user->username }}
                            </span>
                            <p class="text-sm text-gray-500">
                                Mulai treatment: {{ $record->treatment_date->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-500">
                                Status: {{ $record->status }}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('doctor.medical-records.show', $record) }}" 
                               class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                Lihat
                            </a>
                            <a href="{{ route('doctor.medical-records.edit', $record) }}" 
                               class="px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                Update
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4">
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada rekam medis yang sedang diproses</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <div class="bg-green-500 p-6 rounded-xl text-white shadow-lg">
        <h4 class="font-semibold">Total Pasien Hari Ini</h4>
        <p class="text-2xl font-bold mt-2">{{ $todayPatients }}</p>
    </div>
    
    <div class="bg-red-500 p-6 rounded-xl text-white shadow-lg">
        <h4 class="font-semibold">Menunggu Konfirmasi</h4>
        <p class="text-2xl font-bold mt-2">{{ $pendingAppointments }}</p>
    </div>
</div>
@endsection