{{-- resources/views/patient/medical-records/prescriptions.blade.php --}}
@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">My Prescriptions</h1>

    <div class="grid gap-6">
        @forelse($prescriptions as $record)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="mb-4 border-b pb-4">
                    <h2 class="font-semibold text-lg">Visit Date: {{ $record->treatment_date->format('M d, Y') }}</h2>
                    <p class="text-gray-600">Dr. {{ $record->doctor->user->username }}</p>
                </div>

                @foreach($record->medicalRecordMedicines as $prescription)
                    <div class="mb-4 last:mb-0">
                        <h3 class="font-semibold">{{ $prescription->medicine->name }}</h3>
                        <p class="text-gray-600 text-sm">Dosage: {{ $prescription->dosage }}</p>
                        @if($prescription->instructions)
                            <p class="text-gray-600 text-sm mt-2">Instructions: {{ $prescription->instructions }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">No prescriptions found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $prescriptions->links() }}
    </div>
</div>
@endsection