<!-- resources/views/doctor/prescriptions/create.blade.php -->
@extends('layouts.doctor')

@section('header')
    <h2 class="text-xl font-semibold">Create New Prescription</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
        <form action="{{ route('doctor.prescriptions.store') }}" method="POST">
            @csrf

            <!-- Patient Selection -->
            <div class="mb-4">
                <label for="patient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient</label>
                <select name="patient_id" id="patient_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Select Patient</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->patient_id }}">
                            {{ $patient->user->username }} - {{ $patient->user->email }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Medical Record Selection -->
            <div class="mb-4">
                <label for="record_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medical Record</label>
                <select name="record_id" id="record_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Select Medical Record</option>
                </select>
                @error('record_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Medicines Section -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Medicines</label>
                <div id="medicines-container">
                    <div class="medicine-entry bg-gray-50 dark:bg-gray-700 p-4 rounded-md mb-2">
                        <div class="grid grid-cols-2 gap-4 mb-2">
                            <div>
                                <select name="medicines[0][medicine_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Select Medicine</option>
                                    @foreach($medicines as $medicine)
                                        <option value="{{ $medicine->medicine_id }}">
                                            {{ $medicine->name }} (Stock: {{ $medicine->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <input type="text" name="medicines[0][dosage]" placeholder="Dosage"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="medicines[0][frequency]" placeholder="Frequency (e.g. 3x daily)"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <input type="text" name="medicines[0][duration]" placeholder="Duration (e.g. 7 days)"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-medicine" class="mt-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                    Add Another Medicine
                </button>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                <textarea name="notes" id="notes" rows="3" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-2">
                <a href="{{ route('doctor.prescriptions.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Create Prescription
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let medicineCount = 1;
        const container = document.getElementById('medicines-container');
        const addButton = document.getElementById('add-medicine');
        
        addButton.addEventListener('click', function() {
            const template = document.querySelector('.medicine-entry').cloneNode(true);
            const inputs = template.querySelectorAll('input, select');
            
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace('[0]', `[${medicineCount}]`));
                input.value = '';
            });
            
            container.appendChild(template);
            medicineCount++;
        });

        // Dynamic medical records based on patient selection
        const patientSelect = document.getElementById('patient_id');
        const recordSelect = document.getElementById('record_id');

        patientSelect.addEventListener('change', function() {
            const patientId = this.value;
            if (patientId) {
                fetch(`/api/patients/${patientId}/medical-records`)
                    .then(response => response.json())
                    .then(records => {
                        recordSelect.innerHTML = '<option value="">Select Medical Record</option>';
                        records.forEach(record => {
                            recordSelect.innerHTML += `<option value="${record.record_id}">
                                ${new Date(record.treatment_date).toLocaleDateString()} - ${record.diagnosis}
                            </option>`;
                        });
                    });
            } else {
                recordSelect.innerHTML = '<option value="">Select Medical Record</option>';
            }
        });
    });
</script>
@endpush
@endsection