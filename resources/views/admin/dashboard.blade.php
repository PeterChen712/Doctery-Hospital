

@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-semibold mb-4">Dashboard</h2>

    <!-- User Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-medium">Total Doctors</h3>
            <p class="text-3xl font-bold">{{ $userStats['total_doctors'] }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-medium">Total Patients</h3>
            <p class="text-3xl font-bold">{{ $userStats['total_patients'] }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-medium">Total Admins</h3>
            <p class="text-3xl font-bold">{{ $userStats['total_admins'] }}</p>
        </div>
    </div>

    <!-- Active Doctors -->
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-xl font-semibold mb-4">Active Doctors Today</h3>
        <ul>
            @forelse ($activeDoctors as $doctor)
                <li class="border-b py-2 flex justify-between">
                    <span>{{ $doctor->user->username }}</span>
                    <span class="text-gray-600">{{ $doctor->specialization }}</span>
                </li>
            @empty
                <li>No active doctors today.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection