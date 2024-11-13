
@extends('layouts.admin')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Patients -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Recent Patients</h3>
            <div class="divide-y">
                @foreach($recentPatients as $record)
                    <div class="py-3">
                        <span class="font-medium">{{ $record->patient->user->username }}</span>
                        <span class="text-sm text-gray-500 ml-2">{{ $record->treatment_date->format('d M Y') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Today's Appointments</h3>
            <div class="divide-y">
                @foreach($todayAppointments as $appointment)
                    <div class="py-3">
                        <span class="font-medium">{{ $appointment->patient->user->username }}</span>
                        <span class="text-sm text-gray-500 ml-2">{{ $appointment->appointment_date->format('H:i') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection