@extends('layouts.doctor')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex items-center">
        <div class="flex items-center gap-2 mb-6 bg-gradient-to-r from-blue-400 to-blue-600 p-4 rounded-lg shadow-lg w-full">
            <h1 class="text-3xl font-bold text-white">Janji Temu</h1>
        </div>
    </div>

    <!-- Blue Divider -->
    <div class="h-1 bg-blue-500 my-4 rounded-full"></div>

    <!-- Filter Section -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form action="{{ route('doctor.appointments.index') }}" method="GET" class="flex gap-4 flex-wrap">
            <div class="flex-1 min-w-[200px]">
                <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="CONFIRMED" {{ request('status') === 'CONFIRMED' ? 'selected' : '' }}>Confirmed</option>
                    <option value="COMPLETED" {{ request('status') === 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                    <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <input type="date" name="date" value="{{ request('date') }}"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                Filter
            </button>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tanggal & Waktu
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Pasien
                        </div>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($appointments as $appointment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $appointment->patient->user->username }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $appointment->patient->user->phone_number }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs rounded-full font-medium
                                @if ($appointment->status === 'CONFIRMED') bg-green-100 text-green-800
                                @elseif($appointment->status === 'PENDING') bg-yellow-100 text-yellow-800
                                @elseif($appointment->status === 'COMPLETED') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $appointment->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada janji temu yang ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
@endsection