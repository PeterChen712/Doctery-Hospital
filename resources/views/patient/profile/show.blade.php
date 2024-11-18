@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
            <!-- Profile Header -->
            <div class="relative">
                <div class="h-32 bg-gradient-to-r from-blue-500 to-blue-600 rounded-t-lg"></div>
                <div class="absolute bottom-0 left-0 w-full transform translate-y-1/2 flex justify-center">
                    @if(Auth::user()->profile_image)
                        <img src="{{ route('avatar.show', Auth::user()->user_id) }}" 
                             alt="Profile Image"
                             class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg">
                    @else
                        <div class="w-32 h-32 rounded-full border-4 border-white dark:border-gray-800 shadow-lg bg-gray-200 flex items-center justify-center">
                            <span class="text-4xl font-bold text-gray-600">
                                {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Profile Content -->
            <div class="p-6 pt-20">
                <!-- Basic Information -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->username }}</h2>
                    <p class="text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b pb-2">Contact Details</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Phone Number</label>
                            <p class="text-gray-900 dark:text-white">{{ Auth::user()->phone_number ?? 'Not set' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Address</label>
                            <p class="text-gray-900 dark:text-white">{{ Auth::user()->address ?? 'Not set' }}</p>
                        </div>
                    </div>

                    <!-- Medical Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b pb-2">Medical Information</h3>
                        @if(Auth::user()->patient)
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Date of Birth</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ Auth::user()->patient->date_of_birth ? Auth::user()->patient->date_of_birth->format('M d, Y') : 'Not set' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Blood Type</label>
                                <p class="text-gray-900 dark:text-white">
                                    {{ Auth::user()->patient->blood_type ?? 'Not set' }}
                                </p>
                            </div>
                        @else
                            <p class="text-gray-500">No medical information available</p>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('patient.profile.edit') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm">
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection