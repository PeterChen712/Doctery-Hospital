@extends('layouts.doctor')

@section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Patient Info Header -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-2xl font-bold mb-4">Patient Medical History</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <label class="font-semibold block">Patient Name:</label>
                        <p>{{ $patient->user->username }}</p>
                    </div>
                    <div>
                        <label class="font-semibold block">Patient ID:</label>
                        <p>{{ $patient->patient_id }}</p>
                    </div>
                    <div>
                        <label class="font-semibold block">Total Records:</label>
                        <p>{{ $records->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Records Timeline -->
            <div class="space-y-4">
                @forelse($records as $record)
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold">
                                    Treatment Date: {{ $record->treatment_date->format('Y-m-d H:i') }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    Record ID: #{{ $record->record_id }}
                                </p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm
                                {{ $record->status === 'COMPLETED' ? 'bg-green-100 text-green-800' : 
                                   ($record->status === 'IN_PROGRESS' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-gray-100 text-gray-800') }}">
                                {{ $record->status }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="font-semibold block">Symptoms:</label>
                                <p>{{ $record->symptoms }}</p>
                            </div>
                            <div>
                                <label class="font-semibold block">Diagnosis:</label>
                                <p>{{ $record->diagnosis }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="font-semibold block">Medical Action:</label>
                                <p>{{ $record->medical_action }}</p>
                            </div>

                            @if($record->lab_results)
                                <div class="md:col-span-2">
                                    <label class="font-semibold block">Lab Results:</label>
                                    <p>{{ $record->lab_results }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="font-semibold block">Prescribed Medicines:</label>
                                <ul class="list-disc ml-5">
                                    @foreach($record->medicines as $medicine)
                                        <li>{{ $medicine->name }}</li>
                                    @endforeach
                                </ul>
                            </div>

                            @if($record->follow_up_date)
                                <div>
                                    <label class="font-semibold block">Follow-up Date:</label>
                                    <p>{{ $record->follow_up_date->format('Y-m-d') }}</p>
                                </div>
                            @endif

                            @if($record->notes)
                                <div class="md:col-span-2">
                                    <label class="font-semibold block">Notes:</label>
                                    <p>{{ $record->notes }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 flex gap-2">
                            <a href="{{ route('doctor.medical-records.show', $record) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                View Full Details
                            </a>
                            @if(Auth::user()->doctor->doctor_id === $record->creator_doctor_id)
                                <a href="{{ route('doctor.medical-records.edit', $record) }}" 
                                   class="text-yellow-600 hover:text-yellow-800">
                                    Edit
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow p-6 text-center">
                        <p class="text-gray-500">No medical records found for this patient.</p>
                    </div>
                @endforelse
            </div>

            <!-- Navigation -->
            <div class="mt-6">
                <a href="{{ route('doctor.patients.index') }}" 
                   class="text-gray-600 hover:text-gray-800">
                    &larr; Back to Patients List
                </a>
            </div>
        </div>
    </div>
@endsection