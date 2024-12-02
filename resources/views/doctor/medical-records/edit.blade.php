@extends('layouts.doctor')

@section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Ubah Rekam Medis</h2>

            <form action="{{ route('doctor.medical-records.update', $medicalRecord) }}" method="POST" 
                  class="bg-white rounded-lg shadow p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block mb-2">Pasien</label>
                        <select name="patient_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih Pasien</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->patient_id }}" 
                                    {{ old('patient_id', $medicalRecord->patient_id) == $patient->patient_id ? 'selected' : '' }}>
                                    {{ $patient->user->username }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Gejala</label>
                        <textarea name="symptoms" rows="3" 
                                class="w-full border rounded px-3 py-2" required>{{ old('symptoms', $medicalRecord->symptoms) }}</textarea>
                        @error('symptoms')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Diagnosis</label>
                        <textarea name="diagnosis" rows="3" 
                                class="w-full border rounded px-3 py-2" required>{{ old('diagnosis', $medicalRecord->diagnosis) }}</textarea>
                        @error('diagnosis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Tindakan Medis</label>
                        <textarea name="medical_action" rows="3" 
                                class="w-full border rounded px-3 py-2" required>{{ old('medical_action', $medicalRecord->medical_action) }}</textarea>
                        @error('medical_action')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Hasil Laboratorium</label>
                        <textarea name="lab_results" rows="3" 
                                class="w-full border rounded px-3 py-2">{{ old('lab_results', $medicalRecord->lab_results) }}</textarea>
                        @error('lab_results')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Tanggal Pemeriksaan</label>
                        <input type="datetime-local" name="treatment_date" 
                               class="w-full border rounded px-3 py-2"
                               value="{{ old('treatment_date', $medicalRecord->treatment_date->format('Y-m-d\TH:i')) }}" 
                               required>
                        @error('treatment_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2">Tanggal Kontrol</label>
                        <input type="date" name="follow_up_date" 
                               class="w-full border rounded px-3 py-2"
                               value="{{ old('follow_up_date', optional($medicalRecord->follow_up_date)->format('Y-m-d')) }}">
                        @error('follow_up_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Status</label>
                        <select name="status" class="w-full border rounded px-3 py-2" required>
                            @foreach(['PENDING', 'IN_PROGRESS', 'COMPLETED'] as $status)
                                <option value="{{ $status }}" 
                                    {{ old('status', $medicalRecord->status) === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Catatan</label>
                        <textarea name="notes" rows="3" 
                                class="w-full border rounded px-3 py-2">{{ old('notes', $medicalRecord->notes) }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block mb-2">Resep Obat</label>
                        <select name="medicine_ids[]" multiple class="w-full border rounded px-3 py-2" required>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->medicine_id }}"
                                    {{ in_array($medicine->medicine_id, old('medicine_ids', $medicalRecord->medicines->pluck('medicine_id')->toArray())) ? 'selected' : '' }}>
                                    {{ $medicine->name }} (Stok: {{ $medicine->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('medicine_ids')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex gap-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        Perbarui Rekam Medis
                    </button>
                    <a href="{{ route('doctor.medical-records.index') }}" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection