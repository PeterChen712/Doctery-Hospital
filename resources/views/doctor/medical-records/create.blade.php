@extends('layouts.doctor')

@section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Buat Rekam Medis</h2>

            <form action="{{ route('doctor.medical-records.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
                @csrf

                <div class="grid grid-cols-2 gap-4">
                    <!-- Patient Selection -->
                    <div class="col-span-2">
                        <label class="block mb-2">Pasien</label>
                        <select name="patient_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih Pasien</option>
                            @forelse($patients as $patient)
                                @php
                                    $lastAppointment = $patient->appointments()
                                        ->where('status', 'CONFIRMED')
                                        ->where('doctor_id', Auth::user()->doctor->doctor_id)
                                        ->whereDate('appointment_date', '<=', now())
                                        ->latest('appointment_date')
                                        ->first();
                                @endphp
                                @if($lastAppointment)
                                    <option value="{{ $patient->patient_id }}"
                                        {{ old('patient_id') == $patient->patient_id ? 'selected' : '' }}>
                                        {{ $patient->user->username }}
                                        @if($patient->allergies && is_object($patient->allergies) && $patient->allergies->isNotEmpty())
                                            - Alergi: {{ implode(', ', $patient->allergies->pluck('name')->toArray()) }}
                                        @endif
                                        (Kunjungan terakhir: {{ $lastAppointment->appointment_date->format('Y-m-d') }})
                                    </option>
                                @endif
                            @empty
                                <option disabled>Tidak ada pasien yang memenuhi syarat</option>
                            @endforelse
                        </select>
                        @error('patient_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">
                            Hanya menampilkan pasien dengan janji temu yang telah dikonfirmasi (hari ini atau sebelumnya)
                        </p>
                    </div>

                    <!-- Symptoms -->
                    <div class="col-span-2">
                        <label class="block mb-2">Gejala</label>
                        <textarea name="symptoms" rows="3" class="w-full border rounded px-3 py-2" required>{{ old('symptoms') }}</textarea>
                        @error('symptoms')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Diagnosis -->
                    <div class="col-span-2">
                        <label class="block mb-2">Diagnosis</label>
                        <textarea name="diagnosis" rows="3" class="w-full border rounded px-3 py-2" required>{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Medical Action -->
                    <div class="col-span-2">
                        <label class="block mb-2">Tindakan Medis</label>
                        <textarea name="medical_action" rows="3" class="w-full border rounded px-3 py-2" required>{{ old('medical_action') }}</textarea>
                        @error('medical_action')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Treatment Date -->
                    <div>
                        <label class="block mb-2">Tanggal Pemeriksaan</label>
                        <input type="datetime-local" name="treatment_date" value="{{ old('treatment_date') }}"
                            class="w-full border rounded px-3 py-2" required>
                        @error('treatment_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Follow-up Date -->
                    <div>
                        <label class="block mb-2">Tanggal Kontrol</label>
                        <input type="date" name="follow_up_date" value="{{ old('follow_up_date') }}"
                            class="w-full border rounded px-3 py-2">
                        @error('follow_up_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lab Results -->
                    <div class="col-span-2">
                        <label class="block mb-2">Hasil Laboratorium (Opsional)</label>
                        <textarea name="lab_results" rows="3" class="w-full border rounded px-3 py-2">{{ old('lab_results') }}</textarea>
                        @error('lab_results')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="col-span-2">
                        <label class="block mb-2">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prescribed Medicines -->
                    <div class="col-span-2">
                        <label class="block mb-2">Resep Obat</label>
                        <div id="medicine-list" class="space-y-4">
                            <div class="medicine-entry grid grid-cols-12 gap-2">
                                <div class="col-span-4">
                                    <select name="medicines[]" class="medicine-select w-full border rounded px-3 py-2" required>
                                        <option value="">Pilih Obat</option>
                                        @foreach($medicines as $medicine)
                                            <option value="{{ $medicine->medicine_id }}" 
                                                {{ $medicine->stock > 0 ? '' : 'disabled' }}
                                                data-stock="{{ $medicine->stock }}">
                                                {{ $medicine->name }}
                                                (Stok: {{ $medicine->stock > 0 ? $medicine->stock : 'Habis' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-span-2">
                                    <input type="number" name="quantities[]" class="w-full border rounded px-3 py-2" 
                                        placeholder="Jumlah" min="1" required>
                                </div>
                                <div class="col-span-3">
                                    <input type="text" name="dosages[]" class="w-full border rounded px-3 py-2" 
                                        placeholder="Dosis" required>
                                </div>
                                <div class="col-span-3">
                                    <input type="text" name="instructions[]" class="w-full border rounded px-3 py-2" 
                                        placeholder="Instruksi" required>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-medicine" 
                            class="mt-2 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                            Tambah Obat
                        </button>
                        @error('medicines')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-span-2">
                        <label class="block mb-2">Status</label>
                        <select name="status" class="w-full border rounded px-3 py-2" required>
                            <option value="PENDING" {{ old('status') == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                            <option value="IN_PROGRESS" {{ old('status') == 'IN_PROGRESS' ? 'selected' : '' }}>IN_PROGRESS</option>
                            <option value="COMPLETED" {{ old('status') == 'COMPLETED' ? 'selected' : '' }}>COMPLETED</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('doctor.medical-records.index') }}"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Buat Rekam Medis
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const medicineList = document.getElementById('medicine-list');
            const addButton = document.getElementById('add-medicine');

            addButton.addEventListener('click', function() {
                const entry = medicineList.querySelector('.medicine-entry').cloneNode(true);
                entry.querySelectorAll('input, select').forEach(input => {
                    input.value = '';
                });
                medicineList.appendChild(entry);
            });

            medicineList.addEventListener('change', function(e) {
                if (e.target.classList.contains('medicine-select')) {
                    const qtyInput = e.target.closest('.medicine-entry').querySelector('input[name="quantities[]"]');
                    const option = e.target.selectedOptions[0];
                    if (option.dataset.stock) {
                        qtyInput.max = option.dataset.stock;
                    }
                }
            });
        });
    </script>
@endsection