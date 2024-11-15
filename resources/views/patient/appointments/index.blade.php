@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">My Appointments</h1>
        <a href="{{ route('patient.appointments.create') }}" 
           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Schedule New Appointment
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6">
        @forelse($appointments as $appointment)
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-lg">Dr. {{ $appointment->doctor->user->username }}</h3>
                        <p class="text-gray-600">{{ $appointment->schedule->day }} at {{ $appointment->appointment_date->format('h:i A') }}</p>
                        <p class="mt-2">{{ $appointment->reason }}</p>
                    </div>
                    <div>
                        <span class="px-3 py-1 rounded-full text-sm
                            @if($appointment->status === 'CONFIRMED') bg-green-100 text-green-800
                            @elseif($appointment->status === 'PENDING') bg-yellow-100 text-yellow-800
                            @elseif($appointment->status === 'CANCELLED') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $appointment->status }}
                        </span>
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('patient.appointments.show', $appointment) }}" 
                       class="text-blue-500 hover:text-blue-700">
                        View Details
                    </a>
                    @if($appointment->status === 'PENDING')
                        <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="text-red-500 hover:text-red-700 ml-4">
                                Cancel
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">No appointments found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $appointments->links() }}
    </div>
</div>
@endsection