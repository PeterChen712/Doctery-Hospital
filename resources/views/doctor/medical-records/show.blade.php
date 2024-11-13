@extends('layouts.admin')

@section('header')
    <h2 class="text-xl font-semibold">Medical Record Details</h2>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="font-semibold">Patient:</label>
                <p>{{ $medicalRecord->patient->user->username }}</p>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Treatment Date:</label>
                <p>{{ $medicalRecord->treatment_date }}</p>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Symptoms:</label>
                <p>{{ $medicalRecord->symptoms }}</p>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Diagnosis:</label>
                <p>{{ $medicalRecord->diagnosis }}</p>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Medical Action:</label>
                <p>{{ $medicalRecord->medical_action }}</p>
            </div>

            <div class="mb-4">
                <label class="font-semibold">Status:</label>
                <p>{{ $medicalRecord->status }}</p>
            </div>

            <div class="col-span-2">
                <label class="font-semibold">Prescribed Medicines:</label>
                <ul class="list-disc ml-5">
                    @foreach($medicalRecord->medicines as $medicine)
                        <li>{{ $medicine->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        @if(Auth::user()->doctor->doctor_id === $medicalRecord->creator_doctor_id)
            <div class="mt-4 flex gap-2">
                <a href="{{ route('doctor.medical-records.edit', $medicalRecord) }}" 
                    class="bg-yellow-500 text-white px-4 py-2 rounded">Edit</a>
                <form method="POST" action="{{ route('doctor.medical-records.destroy', $medicalRecord) }}" 
                    class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded" 
                        onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        @endif
    </div>
@endsection