@extends('layouts.doctor')

@section('content')
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-2xl font-bold mb-6">Detail Rekam Medis</h2>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="font-semibold">Pasien:</label>
                        <p>{{ $medicalRecord->patient->user->username }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="font-semibold">Tanggal Pemeriksaan:</label>
                        <p>{{ $medicalRecord->treatment_date->format('Y-m-d H:i') }}</p>
                    </div>

                    <div class="mb-4 col-span-2">
                        <label class="font-semibold">Gejala:</label>
                        <p>{{ $medicalRecord->symptoms }}</p>
                    </div>

                    <div class="mb-4 col-span-2">
                        <label class="font-semibold">Diagnosis:</label>
                        <p>{{ $medicalRecord->diagnosis }}</p>
                    </div>

                    <div class="mb-4 col-span-2">
                        <label class="font-semibold">Tindakan Medis:</label>
                        <p>{{ $medicalRecord->medical_action }}</p>
                    </div>

                    @if($medicalRecord->lab_results)
                    <div class="mb-4 col-span-2">
                        <label class="font-semibold">Hasil Laboratorium:</label>
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
                        <label class="font-semibold">Tanggal Kontrol:</label>
                        <p>{{ $medicalRecord->follow_up_date->format('Y-m-d') }}</p>
                    </div>
                    @endif

                    <div class="col-span-2">
                        <label class="font-semibold">Resep Obat:</label>
                        <ul class="list-disc ml-5 mt-2">
                            @foreach($medicalRecord->medicines as $medicine)
                                <li>{{ $medicine->name }}</li>
                            @endforeach
                        </ul>
                    </div>

                    @if($medicalRecord->notes)
                    <div class="col-span-2">
                        <label class="font-semibold">Catatan:</label>
                        <p>{{ $medicalRecord->notes }}</p>
                    </div>
                    @endif
                </div>

                @if(Auth::user()->doctor->doctor_id === $medicalRecord->creator_doctor_id)
                    <div class="mt-6 flex gap-2">
                        <a href="{{ route('doctor.medical-records.edit', $medicalRecord) }}" 
                            class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('doctor.medical-records.destroy', $medicalRecord) }}" 
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?')">
                                Hapus
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('doctor.medical-records.index') }}" 
                    class="text-gray-600 hover:text-gray-800">
                    &larr; Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
@endsection