@extends('layouts.patient')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex items-center">
            <div class="flex items-center gap-2 mb-6 bg-gradient-to-r from-blue-400 to-blue-500 p-4 rounded-lg shadow-lg w-full">
                <h1 class="text-3xl font-bold text-white">Janji Temu</h1>
            </div>
        </div>

        <!-- Blue Divider -->
        <div class="h-1 bg-blue-500 my-4 rounded-full"></div>

        <!-- Filter Section -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <form action="{{ route('patient.appointments.index') }}" method="GET" class="flex gap-4 flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                        <option value="PENDING_CONFIRMATION"
                            {{ request('status') == 'PENDING_CONFIRMATION' ? 'selected' : '' }}>Pending Confirmation
                        </option>
                        <option value="CONFIRMED" {{ request('status') == 'CONFIRMED' ? 'selected' : '' }}>Confirmed
                        </option>
                        <option value="CANCELLED" {{ request('status') == 'CANCELLED' ? 'selected' : '' }}>Cancelled
                        </option>
                        <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : '' }}>Completed
                        </option>
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Filter
                </button>
                <a href="{{ route('patient.appointments.create') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Tambah
                </a>
            </form>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @php
            $statusColors = [
                'PENDING' => [
                    'border' => 'border-yellow-500',
                    'bg' => 'bg-yellow-100',
                    'text' => 'text-yellow-800',
                ],
                'PENDING_CONFIRMATION' => [
                    'border' => 'border-orange-500',
                    'bg' => 'bg-orange-100',
                    'text' => 'text-orange-800',
                ],
                'CONFIRMED' => [
                    'border' => 'border-green-500',
                    'bg' => 'bg-green-100',
                    'text' => 'text-green-800',
                ],
                'CANCELLED' => [
                    'border' => 'border-red-500',
                    'bg' => 'bg-red-100',
                    'text' => 'text-red-800',
                ],
                'COMPLETED' => [
                    'border' => 'border-blue-500',
                    'bg' => 'bg-blue-100',
                    'text' => 'text-blue-800',
                ],
            ];
        @endphp

        <!-- Appointments Grid -->
        <div class="grid gap-4">
            @forelse($appointments as $appointment)
                <div
                    class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 
                    {{ $statusColors[$appointment->status]['border'] }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-lg">
                                @if ($appointment->doctor)
                                    {{ $appointment->doctor->user->username }}
                                @else
                                    Tidak Ada Dokter yang Ditugaskan
                                @endif
                            </h3>
                            <p class="text-gray-600">
                                @if ($appointment->schedule)
                                    @php
                                        $dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                        $dayOfWeek = $appointment->schedule->day_of_week;
                                        $scheduleDate = $appointment->schedule->schedule_date;
                                    @endphp
                                    {{ $dayNames[$dayOfWeek] }},
                                    {{ $scheduleDate->format('d M, Y') }} pukul
                                    {{ \Carbon\Carbon::parse($appointment->schedule->start_time)->format('H:i') }}
                                @else
                                    Jadwal sedang diproses
                                @endif
                            </p>
                            <p class="mt-2 text-gray-700">{{ $appointment->reason }}</p>
                        </div>
                        <span
                            class="px-3 py-1 rounded-full text-sm 
                            {{ $statusColors[$appointment->status]['bg'] }} 
                            {{ $statusColors[$appointment->status]['text'] }}">
                            {{ str_replace('_', ' ', $appointment->status) }}
                        </span>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('patient.appointments.show', $appointment) }}"
                            class="text-blue-500 hover:text-blue-700 font-medium">
                            Lihat Detail
                        </a>
                        @if (in_array($appointment->status, ['PENDING', 'PENDING_CONFIRMATION']))
                            <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="text-red-500 hover:text-red-700 font-medium ml-4">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-gray-500 text-center py-8 bg-white rounded-lg shadow">
                    Tidak ada janji temu yang ditemukan.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $appointments->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
