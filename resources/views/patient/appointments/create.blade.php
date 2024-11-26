<!-- resources/views/appointments/create.blade.php -->
@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-semibold mb-4">Request an Appointment</h2>
    
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('patient.appointments.store') }}" method="POST">
        @csrf
        
        <!-- Appointment Date -->
        <div class="mb-4">
            <label for="appointment_date" class="block text-gray-700 font-bold mb-2">Appointment Date:</label>
            <input type="datetime-local" name="appointment_date" id="appointment_date" 
                   class="w-full px-3 py-2 border rounded-lg" required>
        </div>

        <!-- Reason -->
        <div class="mb-4">
            <label for="reason" class="block text-gray-700 font-bold mb-2">Reason for Visit:</label>
            <textarea name="reason" id="reason" rows="3" 
                      class="w-full px-3 py-2 border rounded-lg" required></textarea>
        </div>

        <!-- Symptoms -->
        <div class="mb-4">
            <label for="symptoms" class="block text-gray-700 font-bold mb-2">Describe Your Symptoms:</label>
            <textarea name="symptoms" id="symptoms" rows="3" 
                      class="w-full px-3 py-2 border rounded-lg"></textarea>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Submit Request
            </button>
        </div>
    </form>
</div>
@endsection