@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">My Prescriptions</h1>

    <div class="grid gap-6">
        @forelse($records as $record)
            @foreach($record->prescriptions as $prescription)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-lg">{{ $prescription->medicine_name }}</h3>
                            <p class="text-gray-600">Prescribed by {{ $record->doctor->user->username }}</p>
                            <p class="text-gray-600">{{ $record->treatment_date->format('M d, Y') }}</p>
                            
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">Dosage:</p>
                                <p>{{ $prescription->dosage }}</p>
                            </div>

                            @if($prescription->instructions)
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600">Instructions:</p>
                                    <p>{{ $prescription->instructions }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @empty
            <p class="text-gray-500 text-center py-8">No prescriptions found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $records->links() }}
    </div>
</div>
@endsection