@extends('layouts.patient')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">My Appointments</h1>
            <a href="{{ route('patient.appointments.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Schedule New Appointment
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Appointments Grid -->
        <div class="grid gap-6">
            @forelse($appointments as $appointment)
                <div class="bg-white rounded-lg shadow p-6">
                    {{-- Update this section in index.blade.php --}}
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-lg">
                                @if ($appointment->doctor)
                                    Dr. {{ $appointment->doctor->user->username }}
                                @else
                                    No Doctor Assigned
                                @endif
                            </h3>
                            <p class="text-gray-600">
                                @if ($appointment->schedule)
                                    @php
                                        $dayNames = [
                                            'Sunday',
                                            'Monday',
                                            'Tuesday',
                                            'Wednesday',
                                            'Thursday',
                                            'Friday',
                                            'Saturday',
                                        ];
                                        $dayOfWeek = $appointment->schedule->day_of_week;
                                        $scheduleDate = $appointment->schedule->schedule_date;
                                    @endphp
                                    {{ $dayNames[$dayOfWeek] }},
                                    {{ $scheduleDate->format('M d, Y') }} at
                                    {{ \Carbon\Carbon::parse($appointment->schedule->start_time)->format('h:i A') }}
                                @else
                                    Schedule pending
                                @endif
                            </p>
                            <p class="mt-2">{{ $appointment->reason }}</p>
                        </div>
                        <!-- Rest of the code remains the same -->
                    </div>
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('patient.appointments.show', $appointment) }}"
                            class="text-blue-500 hover:text-blue-700">
                            View Details
                        </a>
                        @if ($appointment->status === 'PENDING')
                            <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST"
                                class="inline">
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

        <!-- Pagination -->
        <div class="mt-6">
            {{ $appointments->links() }}
        </div>
    </div>
@endsection
