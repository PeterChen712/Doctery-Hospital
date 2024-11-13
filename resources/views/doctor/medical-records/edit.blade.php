@extends('layouts.admin')

@section('header')
    <h2 class="text-xl font-semibold">Edit Medical Record</h2>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('doctor.medical-records.update', $medicalRecord) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block mb-2">Patient</label>
                    <select name="patient_id" class="rounded-md w-full">
                        @foreach($patients as $patient)
                            <option value="{{ $patient->patient_id }}" 
                                {{ $medicalRecord->patient_id == $patient->patient_id ? 'selected' : '' }}>
                                {{ $patient->user->username }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Treatment Date</label>
                    <input type="datetime-local" name="treatment_date" 
                        value="{{ $medicalRecord->treatment_date }}" class="rounded-md w-full">
                    @error('treatment_date')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <!-- Other fields similar to create form but with old values -->

                <div class="mb-4">
                    <label class="block mb-2">Medicines</label>
                    <select name="medicine_ids[]" multiple class="rounded-md w-full" size="5">
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->medicine_id }}"
                                {{ $medicalRecord->medicines->contains($medicine->medicine_id) ? 'selected' : '' }}>
                                {{ $medicine->name }} (Stock: {{ $medicine->stock }})
                            </option>
                        @endforeach
                    </select>
                    @error('medicine_ids')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Update Record
                </button>
            </div>
        </form>
    </div>
@endsection