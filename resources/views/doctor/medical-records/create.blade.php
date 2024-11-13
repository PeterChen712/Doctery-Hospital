@extends('layouts.admin')

@section('header')
    <h2 class="text-xl font-semibold">Create Medical Record</h2>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('doctor.medical-records.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block mb-2">Patient</label>
                    <select name="patient_id" class="rounded-md w-full">
                        @foreach($patients as $patient)
                            <option value="{{ $patient->patient_id }}">
                                {{ $patient->user->username }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Treatment Date</label>
                    <input type="datetime-local" name="treatment_date" class="rounded-md w-full">
                    @error('treatment_date')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Symptoms</label>
                    <textarea name="symptoms" class="rounded-md w-full"></textarea>
                    @error('symptoms')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Diagnosis</label>
                    <textarea name="diagnosis" class="rounded-md w-full"></textarea>
                    @error('diagnosis')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Medical Action</label>
                    <textarea name="medical_action" class="rounded-md w-full"></textarea>
                    @error('medical_action')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Lab Results</label>
                    <textarea name="lab_results" class="rounded-md w-full"></textarea>
                    @error('lab_results')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Medicines</label>
                    <select name="medicine_ids[]" multiple class="rounded-md w-full" size="5">
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->medicine_id }}">
                                {{ $medicine->name }} (Stock: {{ $medicine->stock }})
                            </option>
                        @endforeach
                    </select>
                    @error('medicine_ids')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-2">Status</label>
                    <select name="status" class="rounded-md w-full">
                        <option value="PENDING">Pending</option>
                        <option value="IN_PROGRESS">In Progress</option>
                        <option value="COMPLETED">Completed</option>
                    </select>
                    @error('status')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Create Record
                </button>
            </div>
        </form>
    </div>
@endsection