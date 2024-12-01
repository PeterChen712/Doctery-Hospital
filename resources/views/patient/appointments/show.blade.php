@extends('layouts.patient')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                <!-- Header Section -->
                <div class="border-b border-gray-200 p-6">
                    <div class="flex justify-between items-start">
                        <h1 class="text-2xl font-bold text-gray-800">Detail Janji Temu</h1>
                        <span class="px-3 py-1 rounded-full text-sm
                            @if ($appointment->status === 'CONFIRMED') bg-green-100 text-green-800
                            @elseif($appointment->status === 'PENDING_CONFIRMATION') bg-blue-100 text-blue-800
                            @elseif($appointment->status === 'PENDING') bg-yellow-100 text-yellow-800
                            @elseif($appointment->status === 'CANCELLED') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ str_replace('_', ' ', $appointment->status) }}
                        </span>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <h3 class="text-gray-600 font-medium text-sm uppercase tracking-wide">Dokter</h3>
                            <p class="text-gray-900 font-medium">
                                @if($appointment->doctor)
                                    Dr. {{ $appointment->doctor->user->username }}
                                @else
                                    Belum Ditugaskan
                                @endif
                            </p>
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-gray-600 font-medium text-sm uppercase tracking-wide">Jadwal</h3>
                            <p class="text-gray-900 font-medium">
                                @if($appointment->schedule)
                                    {{ \Carbon\Carbon::parse($appointment->schedule->schedule_date)->translatedFormat('l, j F Y') }}
                                    pukul {{ \Carbon\Carbon::parse($appointment->schedule->start_time)->format('H:i') }}
                                @else
                                    {{ $appointment->appointment_date->translatedFormat('l, j F Y \p\u\k\u\l H:i') }}
                                @endif
                            </p>
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-gray-600 font-medium text-sm uppercase tracking-wide">Alasan Kunjungan</h3>
                            <p class="text-gray-900">{{ $appointment->reason }}</p>
                        </div>

                        @if ($appointment->symptoms)
                            <div class="space-y-2">
                                <h3 class="text-gray-600 font-medium text-sm uppercase tracking-wide">Gejala</h3>
                                <p class="text-gray-900">{{ $appointment->symptoms }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end gap-4">
                        <a href="{{ route('patient.appointments.index') }}"
                            class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded hover:bg-gray-200 transition-colors duration-200">
                            Kembali
                        </a>

                        @if ($appointment->status === 'PENDING_CONFIRMATION')
                            <form action="{{ route('patient.appointments.confirm', $appointment) }}" method="POST" class="flex gap-2">
                                @csrf
                                <button name="confirm" value="1" class="px-6 py-2.5 bg-green-600 text-white font-medium rounded hover:bg-green-700 transition-colors duration-200">
                                    Konfirmasi
                                </button>
                                <button name="confirm" value="0" class="px-6 py-2.5 bg-red-600 text-white font-medium rounded hover:bg-red-700 transition-colors duration-200">
                                    Tolak
                                </button>
                            </form>
                        @endif

                        @if ($appointment->status === 'PENDING')
                            <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="px-6 py-2.5 bg-red-600 text-white font-medium rounded hover:bg-red-700 transition-colors duration-200">
                                    Batalkan Janji Temu
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection