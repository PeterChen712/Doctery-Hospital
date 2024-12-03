{{-- resources/views/patient/medical-records/prescriptions.blade.php --}}
@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header Section -->
    <div class="flex items-center">
        <div class="flex items-center gap-2 mb-6 bg-gradient-to-r from-green-400 to-green-600 p-4 rounded-lg shadow-lg w-full">
            <h1 class="text-3xl font-bold text-white">Resep Obat</h1>
        </div>
    </div>

    <!-- Green Divider -->
    <div class="h-1 bg-green-500 my-4 rounded-full"></div>

    <div class="grid gap-6">
        @forelse($prescriptions as $record)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition p-6 border-l-4 border-green-500">
                <div class="mb-4 border-b pb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="font-semibold text-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $record->treatment_date->format('d M, Y') }}
                            </h2>
                            <p class="text-gray-600 flex items-center gap-2 mt-1">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Dr. {{ $record->doctor->user->username }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($record->medicalRecordMedicines as $prescription)
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-green-700 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                {{ $prescription->medicine->name }}
                            </h3>
                            <p class="text-green-600 text-sm mt-2 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Dosis: {{ $prescription->dosage }}
                            </p>
                            @if($prescription->instructions)
                                <p class="text-gray-600 text-sm mt-2 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Instruksi: {{ $prescription->instructions }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-green-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-gray-500">Tidak ada resep yang ditemukan.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $prescriptions->links() }}
    </div>
</div>
@endsection