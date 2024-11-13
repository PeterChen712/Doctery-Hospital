
@extends('layouts.admin')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Treatments -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Treatments</h3>
            <div class="divide-y">
                @foreach($treatments as $treatment)
                    <div class="py-3">
                        <span class="font-medium">{{ $treatment->doctor->user->username }}</span>
                        <span class="text-sm text-gray-500 ml-2">{{ $treatment->treatment_date->format('d M Y') }}</span>
                        <div class="mt-1 text-sm">
                            @foreach($treatment->prescriptions as $prescription)
                                <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs mr-2">
                                    {{ $prescription->medicine->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Upcoming Appointments</h3>
            <div class="divide-y">
                @foreach($upcomingAppointments as $appointment)
                    <div class="py-3">
                        <span class="font-medium">Dr. {{ $appointment->doctor->user->username }}</span>
                        <span class="text-sm text-gray-500 ml-2">
                            {{ $appointment->appointment_date->format('d M Y H:i') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection