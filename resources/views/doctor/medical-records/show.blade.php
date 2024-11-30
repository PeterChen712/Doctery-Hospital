@extends('layouts.doctor')

@section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Medical Record Details</h2>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="font-semibold">Patient:</label>
                        <p>{{ $medicalRecord->patient->user->username }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold">Treatment Date:</label>
                        <p>{{ $medicalRecord->treatment_date->format('Y-m-d H:i') }}</p>
                    </div>

                    <div class="mb-4 col-span-2">
                        <label class="font-semibold">Symptoms:</label>
                        <p>{{ $medicalRecord->symptoms }}</p>
                    </div>

                    <div class="mb-4 col-span-2">
                        <label class="font-semibold">Diagnosis:</label>
                        <p>{{ $medicalRecord->diagnosis }}</p>
                    </div>

                    <div class="mb-4 col-span-2">
                        <label class="font-semibold">Medical Action:</label>
                        <p>{{ $medicalRecord->medical_action }}</p>
                    </div>

                    @if($medicalRecord->lab_results)
                    <div class="mb-4 col-span-2">
                        <label class="font-semibold">Lab Results:</label>
                        <p>{{ $medicalRecord->lab_results }}</p>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label class="font-semibold">Status:</label>
                        <p class="inline-flex px-2 py-1 rounded text-sm
                            {{ $medicalRecord->status === 'COMPLETED' ? 'bg-green-100 text-green-800' : 
                               ($medicalRecord->status === 'IN_PROGRESS' ? 'bg-yellow-100 text-yellow-800' : 
                                'bg-gray-100 text-gray-800') }}">
                            {{ $medicalRecord->status }}
                        </p>
                    </div>

                    @if($medicalRecord->follow_up_date)
                    <div class="mb-4">
                        <label class="font-semibold">Follow-up Date:</label>
                        <p>{{ $medicalRecord->follow_up_date->format('Y-m-d') }}</p>
                    </div>
                    @endif

                    <div class="col-span-2">
                        <label class="font-semibold">Prescribed Medicines:</label>
                        <ul class="list-disc ml-5 mt-2">
                            @foreach($medicalRecord->medicines as $medicine)
                                <li>{{ $medicine->name }}</li>
                            @endforeach
                        </ul>
                    </div>

                    @if($medicalRecord->notes)
                    <div class="col-span-2">
                        <label class="font-semibold">Notes:</label>
                        <p>{{ $medicalRecord->notes }}</p>
                    </div>
                    @endif
                </div>

                @if(Auth::user()->doctor->doctor_id === $medicalRecord->creator_doctor_id)
                    <div class="mt-6 flex gap-2">
                        <a href="{{ route('doctor.medical-records.edit', $medicalRecord) }}" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                            Edit Record
                        </a>
                        <form method="POST" action="{{ route('doctor.medical-records.destroy', $medicalRecord) }}" 
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                                onclick="return confirm('Are you sure you want to delete this record?')">
                                Delete Record
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('doctor.medical-records.index') }}" 
                    class="text-gray-600 hover:text-gray-800">
                    &larr; Back to Records
                </a>
            </div>
        </div>
    </div>
@endsection