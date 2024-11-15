@extends('layouts.patient')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Medical History -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Medical Actions</h2>
            <div class="space-y-4">
                @forelse($medicalRecords as $record)
                    <div class="border-b pb-4">
                        <p class="font-medium">{{ $record->treatment_date->format('M d, Y') }}</p>
                        <p class="text-gray-600">Doctor: Dr. {{ $record->doctor->user->username }}</p>
                        <p class="text-gray-600">Diagnosis: {{ $record->diagnosis }}</p>
                        <p class="text-gray-600">Medical Action: {{ $record->medical_action }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">No medical actions recorded.</p>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Upcoming Appointments</h2>
            <div class="space-y-4">
                @forelse($appointments as $appointment)
                    <div class="border-b pb-4">
                        <p class="font-medium">Dr. {{ $appointment->doctor->user->username }}</p>
                        <p class="text-sm text-gray-500">{{ $appointment->appointment_date->format('M d, Y h:i A') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">No upcoming appointments.</p>
                @endforelse
            </div>
        </div>

        <!-- Active Prescriptions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Prescribed Medicines</h2>
            <div class="space-y-4">
                @forelse($prescriptions as $prescription)
                    <div class="border-b pb-4">
                        <p class="font-medium">{{ $prescription->medicine_name }}</p>
                        <p class="text-gray-600">Dosage: {{ $prescription->dosage }}</p>
                        <p class="text-gray-600">Instructions: {{ $prescription->instructions }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">No prescribed medicines.</p>
                @endforelse
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4">Notifications</h2>
            <div class="space-y-4">
                @forelse($notifications as $notification)
                    <div class="border-b pb-4 {{ $notification->is_read ? 'opacity-50' : '' }}">
                        <p class="font-medium">{{ $notification->title }}</p>
                        <p class="text-sm">{{ $notification->message }}</p>
                        <p class="text-sm text-gray-500">{{ $notification->scheduled_for->format('M d, Y h:i A') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500">No notifications.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
