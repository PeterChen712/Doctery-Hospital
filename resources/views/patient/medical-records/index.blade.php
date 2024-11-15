<!-- resources/views/patient/medical-records/index.blade.php -->
@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">My Medical Records</h1>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6">
        @forelse($records as $record)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold text-lg">Dr. {{ $record->doctor->user->username }}</p>
                        <p class="text-gray-600">{{ $record->treatment_date->format('M d, Y') }}</p>
                        
                        @if($record->symptoms)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">Symptoms:</p>
                                <p>{{ $record->symptoms }}</p>
                            </div>
                        @endif

                        <div class="mt-4">
                            <p class="text-sm text-gray-600">Diagnosis:</p>
                            <p>{{ $record->diagnosis }}</p>
                        </div>

                        @if($record->medical_action)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">Medical Action:</p>
                                <p>{{ $record->medical_action }}</p>
                            </div>
                        @endif

                        @if($record->notes)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">Notes:</p>
                                <p>{{ $record->notes }}</p>
                            </div>
                        @endif

                        @if($record->prescriptions->count() > 0)
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">Prescriptions:</p>
                                <ul class="list-disc list-inside">
                                    @foreach($record->prescriptions as $prescription)
                                        <li>{{ $prescription->medicine_name }} - {{ $prescription->dosage }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">No medical records found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $records->links() }}
    </div>
</div>
@endsection