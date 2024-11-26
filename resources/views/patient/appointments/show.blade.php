@extends('layouts.patient')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <h1 class="text-2xl font-bold">Appointment Details</h1>
                        <span
                            class="px-3 py-1 rounded-full text-sm
                        @if ($appointment->status === 'CONFIRMED') bg-green-100 text-green-800
                        @elseif($appointment->status === 'PENDING') bg-yellow-100 text-yellow-800
                        @elseif($appointment->status === 'CANCELLED') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                            {{ $appointment->status }}
                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-gray-600">Doctor</h3>
                            <p class="font-semibold">
                                @if($appointment->doctor)
                                    Dr. {{ $appointment->doctor->user->username }}
                                @else
                                    Not yet assigned
                                @endif
                            </p>
                        </div>

                        <div>
                            <h3 class="text-gray-600">Schedule</h3>
                            <p class="font-semibold">
                                @if($appointment->schedule)
                                    {{ $appointment->schedule->day }} at {{ $appointment->appointment_date->format('h:i A') }}
                                @else
                                    {{ $appointment->appointment_date->format('l, F j, Y \a\t h:i A') }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <h3 class="text-gray-600">Reason for Visit</h3>
                            <p>{{ $appointment->reason }}</p>
                        </div>

                        @if ($appointment->symptoms)
                            <div>
                                <h3 class="text-gray-600">Symptoms</h3>
                                <p>{{ $appointment->symptoms }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 flex justify-end gap-4">
                        <a href="{{ route('patient.appointments.index') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Back to List
                        </a>

                        @if ($appointment->status === 'PENDING')
                            <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                    Cancel Appointment
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection