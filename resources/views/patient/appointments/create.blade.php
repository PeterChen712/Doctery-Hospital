<!-- resources/views/appointments/create.blade.php -->
@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-semibold mb-4">Request an Appointment</h2>
    <form action="{{ route('appointments.store') }}" method="POST">
        @csrf

        <!-- Symptoms Input -->
        <div class="mb-4">
            <label for="symptoms" class="block text-gray-700 font-bold mb-2">Describe Your Symptoms:</label>
            <textarea name="symptoms" id="symptoms" rows="5" class="w-full px-3 py-2 border rounded-lg" required></textarea>
        </div>

        <!-- Preferred Date (Optional) -->
        <div class="mb-4">
            <label for="preferred_date" class="block text-gray-700 font-bold mb-2">Preferred Appointment Date:</label>
            <input type="date" name="preferred_date" id="preferred_date" class="w-full px-3 py-2 border rounded-lg">
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Submit Request</button>
        </div>
    </form>
</div>
@endsection