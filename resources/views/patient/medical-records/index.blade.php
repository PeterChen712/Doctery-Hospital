<!-- resources/views/patient/medical-records/index.blade.php -->
@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">My Medical Records</h1>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6">
        @forelse($records as $record)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <!-- Header Section -->
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900">Dr. {{ $record->doctor->user->username }}</h3>
                                <p class="text-sm text-gray-500">{{ $record->treatment_date->format('F d, Y - h:i A') }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            {{ $record->status === 'COMPLETED' ? 'bg-green-100 text-green-800' : 
                               ($record->status === 'IN_PROGRESS' ? 'bg-yellow-100 text-yellow-800' : 
                                'bg-gray-100 text-gray-800') }}">
                            {{ $record->status }}
                        </span>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-6 space-y-6">
                    <!-- Symptoms Section -->
                    @if($record->symptoms)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Symptoms
                            </h4>
                            <p class="text-gray-600">{{ $record->symptoms }}</p>
                        </div>
                    @endif

                    <!-- Diagnosis Section -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Diagnosis
                        </h4>
                        <p class="text-gray-600">{{ $record->diagnosis }}</p>
                    </div>

                    <!-- Medical Action Section -->
                    @if($record->medical_action)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Medical Action
                            </h4>
                            <p class="text-gray-600">{{ $record->medical_action }}</p>
                        </div>
                    @endif

                    <!-- Prescriptions Section -->
                    @if($record->medicines->count() > 0)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                                Prescribed Medicines
                            </h4>
                            <div class="space-y-3">
                                @foreach($record->medicines as $medicine)
                                    <div class="flex justify-between items-start p-3 bg-white rounded shadow-sm">
                                        <div>
                                            <h5 class="font-medium text-gray-900">{{ $medicine->name }}</h5>
                                            <p class="text-sm text-gray-500">{{ $medicine->pivot->dosage }}</p>
                                            @if($medicine->pivot->instructions)
                                                <p class="text-sm text-gray-500 mt-1">{{ $medicine->pivot->instructions }}</p>
                                            @endif
                                        </div>
                                        <span class="text-sm font-medium text-gray-600">Qty: {{ $medicine->pivot->quantity }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Notes Section -->
                    @if($record->notes)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-700 mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Additional Notes
                            </h4>
                            <p class="text-gray-600">{{ $record->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Footer Section -->
                @if($record->follow_up_date)
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-lg">
                        <p class="text-sm text-gray-600 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Follow-up Date: <span class="font-medium ml-1">{{ $record->follow_up_date->format('F d, Y') }}</span>
                        </p>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No medical records</h3>
                <p class="mt-1 text-sm text-gray-500">You don't have any medical records yet.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $records->links() }}
    </div>
</div>
@endsection