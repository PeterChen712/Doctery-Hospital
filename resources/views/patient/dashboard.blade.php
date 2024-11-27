@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">
            {{ session('warning') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Medical History -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Medical Records</h2>
            <div class="space-y-4">
                @forelse($medicalRecords as $record)
                    <div class="border-b pb-4">
                        @if($record->treatment_date)
                            <p class="font-medium">{{ $record->treatment_date->format('M d, Y') }}</p>
                        @endif
                        @if($record->doctor && $record->doctor->user)
                            <p class="text-gray-600">Doctor: Dr. {{ $record->doctor->user->username }}</p>
                        @endif
                        <p class="text-gray-600">Diagnosis: {{ $record->diagnosis ?? 'Not specified' }}</p>
                        <p class="text-gray-600">Treatment: {{ $record->medical_action ?? 'Not specified' }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">No medical records found.</p>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Upcoming Appointments</h2>
            <div class="space-y-4">
                @forelse($appointments as $appointment)
                    <div class="border-b pb-4">
                        @if($appointment->doctor && $appointment->doctor->user)
                            <p class="font-medium">Dr. {{ $appointment->doctor->user->username }}</p>
                        @endif
                        @if($appointment->schedule)
                            <p class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($appointment->schedule->schedule_date)->format('M d, Y') }}
                                at {{ \Carbon\Carbon::parse($appointment->schedule->start_time)->format('h:i A') }}
                            </p>
                            <p class="text-sm text-gray-500">Status: {{ $appointment->status }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">No upcoming appointments.</p>
                @endforelse
            </div>
        </div>

        <!-- Prescriptions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Prescriptions</h2>
            <div class="space-y-4">
                @forelse($prescriptions as $record)
                    <div class="border-b pb-4">
                        @if($record->treatment_date)
                            <p class="font-medium">{{ $record->treatment_date->format('M d, Y') }}</p>
                        @endif
                        @if($record->prescriptions)
                            @foreach($record->prescriptions as $prescription)
                                <div class="ml-4 mt-2">
                                    <p class="text-gray-600">Medicine: {{ $prescription->medicine_name }}</p>
                                    <p class="text-gray-600">Dosage: {{ $prescription->dosage }}</p>
                                    <p class="text-gray-600">Instructions: {{ $prescription->instructions }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">No prescriptions found.</p>
                @endforelse
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Notifications</h2>
            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div class="border-b pb-4 {{ $notification->read_at ? 'opacity-50' : '' }}">
                        <p class="font-medium">{{ $notification->title }}</p>
                        <p class="text-sm">{{ json_decode($notification->data)->message ?? '' }}</p>
                        <p class="text-sm text-gray-500">{{ $notification->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">No notifications.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

