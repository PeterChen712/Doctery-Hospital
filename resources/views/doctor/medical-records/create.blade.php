@extends('layouts.doctor')

@section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Create Medical Record</h2>

            <form action="{{ route('doctor.medical-records.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block mb-2">Patient</label>
                        <select name="patient_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->patient_id }}" 
                                    {{ old('patient_id') == $patient->patient_id ? 'selected' : '' }}>
                                    {{ $patient->user->username }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Symptoms</label>
                        <textarea name="symptoms" rows="3" 
                                  class="w-full border rounded px-3 py-2" required>{{ old('symptoms') }}</textarea>
                        @error('symptoms')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Diagnosis</label>
                        <textarea name="diagnosis" rows="3" 
                                  class="w-full border rounded px-3 py-2" required>{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Medical Action</label>
                        <textarea name="medical_action" rows="3" 
                                  class="w-full border rounded px-3 py-2" required>{{ old('medical_action') }}</textarea>
                        @error('medical_action')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Treatment Date</label>
                        <input type="datetime-local" name="treatment_date" 
                               value="{{ old('treatment_date') }}" 
                               class="w-full border rounded px-3 py-2" required>
                        @error('treatment_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Status</label>
                        <select name="status" class="w-full border rounded px-3 py-2" required>
                            <option value="PENDING" {{ old('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                            <option value="IN_PROGRESS" {{ old('status') == 'IN_PROGRESS' ? 'selected' : '' }}>In Progress</option>
                            <option value="COMPLETED" {{ old('status') == 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Follow-up Date</label>
                        <input type="date" name="follow_up_date" 
                               value="{{ old('follow_up_date') }}" 
                               class="w-full border rounded px-3 py-2">
                        @error('follow_up_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Lab Results (Optional)</label>
                        <textarea name="lab_results" rows="3" 
                                  class="w-full border rounded px-3 py-2">{{ old('lab_results') }}</textarea>
                        @error('lab_results')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Notes (Optional)</label>
                        <textarea name="notes" rows="3" 
                                  class="w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Prescribed Medicines</label>
                        <select name="medicine_ids[]" multiple class="w-full border rounded px-3 py-2" required>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->medicine_id }}" 
                                    {{ in_array($medicine->medicine_id, old('medicine_ids', [])) ? 'selected' : '' }}>
                                    {{ $medicine->name }} (Stock: {{ $medicine->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('medicine_ids')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('doctor.medical-records.index') }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Create Record
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection