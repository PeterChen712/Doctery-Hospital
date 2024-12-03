@extends('layouts.doctor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex items-center">
        <div class="flex items-center gap-2 mb-6 bg-gradient-to-r from-blue-400 to-blue-600 p-4 rounded-lg shadow-lg w-full">
            <h1 class="text-3xl font-bold text-white">Rekam Medis</h1>
        </div>
    </div>

    <!-- Blue Divider -->
    <div class="h-1 bg-blue-500 my-4 rounded-full"></div>

    <!-- Search Form -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <form action="{{ route('doctor.medical-records.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="patient" value="{{ request('patient') }}"
                        class="pl-10 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        placeholder="Cari berdasarkan nama pasien...">
                </div>
            </div>
            <div class="flex-1">
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>
            <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-md transition-all">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Cari
                </div>
            </button>
        </form>
    </div>

    <!-- Records Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Pasien
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Tanggal Pemeriksaan
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Status
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Aksi
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($records as $record)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $record->patient->user->username }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $record->treatment_date->format('d M Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $record->treatment_date->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs rounded-full font-medium
                                @if($record->status === 'COMPLETED') bg-green-100 text-green-800
                                @elseif($record->status === 'IN_PROGRESS') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $record->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="{{ route('doctor.medical-records.show', $record) }}"
                                class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded-md text-sm transition-colors">
                                Lihat
                            </a>
                            @if(Auth::user()->doctor->doctor_id === $record->creator_doctor_id)
                                <a href="{{ route('doctor.medical-records.edit', $record) }}"
                                    class="bg-yellow-100 text-yellow-700 hover:bg-yellow-200 px-3 py-1 rounded-md text-sm transition-colors">
                                    Ubah
                                </a>
                                <form action="{{ route('doctor.medical-records.destroy', $record) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1 rounded-md text-sm transition-colors"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?')">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada rekam medis ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $records->links() }}
    </div>
</div>
@endsection