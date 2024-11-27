<!-- resources/views/admin/appointments/index.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-semibold mb-4">Manage Appointments</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Patient</th>
                    <th class="px-6 py-3 text-left">Doctor</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($appointments as $appointment)
                <tr>
                    <td class="px-6 py-4">{{ $appointment->appointment_date->format('M d, Y h:i A') }}</td>
                    <td class="px-6 py-4">{{ $appointment->patient->user->username }}</td>
                    <td class="px-6 py-4">
                        {{ $appointment->doctor ? $appointment->doctor->user->username : 'Not Assigned' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-sm
                            @if($appointment->status === 'CONFIRMED') bg-green-100 text-green-800
                            @elseif($appointment->status === 'PENDING') bg-yellow-100 text-yellow-800
                            @elseif($appointment->status === 'CANCELLED') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $appointment->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.appointments.show', $appointment) }}" 
                           class="text-blue-600 hover:text-blue-900">View Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
</div>
@endsection