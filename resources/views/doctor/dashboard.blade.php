@extends('layouts.doctor')

@section('header')
    <h2 class="text-xl font-semibold">Doctor Dashboard</h2>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Recent Patients -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Recent Patients</h3>
            <a href="{{ route('doctor.patients.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
        </div>
        <div class="divide-y dark:divide-gray-700">
            @forelse($recentPatients as $record)
                <div class="py-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-medium">{{ $record->patient->user->username }}</span>
                            <p class="text-sm text-gray-500">{{ $record->treatment_date->format('d M Y') }}</p>
                        </div>
                        <a href="{{ route('doctor.records.show', $record) }}" class="text-blue-600 hover:text-blue-800">View</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400">No recent patients</p>
            @endforelse
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Today's Appointments</h3>
            <a href="{{ route('doctor.appointments.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
        </div>
        <div class="divide-y dark:divide-gray-700">
            @forelse($todayAppointments as $appointment)
                <div class="py-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-medium">{{ $appointment->patient->user->username }}</span>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($appointment->status === 'CONFIRMED') bg-green-100 text-green-800
                            @elseif($appointment->status === 'PENDING') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $appointment->status }}
                        </span>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400">No appointments today</p>
            @endforelse
        </div>
    </div>

    <!-- Ongoing Medical Records -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Ongoing Treatments</h3>
            <a href="{{ route('doctor.records.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">View All</a>
        </div>
        <div class="divide-y dark:divide-gray-700">
            @forelse($ongoingTreatments as $record)
                <div class="py-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-medium">{{ $record->patient->user->username }}</span>
                            <p class="text-sm text-gray-500">Last visit: {{ $record->treatment_date->format('d M Y') }}</p>
                        </div>
                        <a href="{{ route('doctor.records.edit', $record) }}" class="text-blue-600 hover:text-blue-800">Update</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 dark:text-gray-400">No ongoing treatments</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Statistics Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <div class="bg-blue-50 dark:bg-blue-900 p-6 rounded-lg">
        <h4 class="text-blue-800 dark:text-blue-100 font-semibold">Total Patients Today</h4>
        <p class="text-2xl font-bold text-blue-900 dark:text-blue-50">{{ $todayPatients }}</p>
    </div>
    
    <div class="bg-green-50 dark:bg-green-900 p-6 rounded-lg">
        <h4 class="text-green-800 dark:text-green-100 font-semibold">Pending Appointments</h4>
        <p class="text-2xl font-bold text-green-900 dark:text-green-50">{{ $pendingAppointments }}</p>
    </div>
    
    <div class="bg-purple-50 dark:bg-purple-900 p-6 rounded-lg">
        <h4 class="text-purple-800 dark:text-purple-100 font-semibold">Follow-ups Required</h4>
        <p class="text-2xl font-bold text-purple-900 dark:text-purple-50">{{ $followUps }}</p>
    </div>
</div>
@endsection