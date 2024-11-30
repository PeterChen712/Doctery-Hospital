<!-- resources/views/patient/view-doctor/show.blade.php -->
@extends('layouts.patient')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <a href="{{ url()->previous() }}" class="inline-flex items-center mb-6 text-gray-600 hover:text-gray-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back
        </a>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Profile Header -->
            <div class="relative h-48 bg-gradient-to-r from-blue-500 to-blue-600">
                <div class="absolute bottom-0 left-0 right-0 px-6 py-4 transform translate-y-1/2">
                    <div class="flex items-center">
                        @if($doctor->user->profile_image)
                            <img class="w-24 h-24 rounded-full border-4 border-white shadow-lg object-cover" 
                                 src="{{ route('avatar.show', $doctor->user->user_id) }}" 
                                 alt="{{ $doctor->user->username }}">
                        @else
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-600 font-medium text-2xl">
                                    {{ substr($doctor->user->username, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div class="ml-6">
                            <h1 class="text-2xl font-bold text-white">Dr. {{ $doctor->user->username }}</h1>
                            <p class="text-blue-100">{{ $doctor->specialization }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="px-6 py-12 mt-12">
                <!-- About -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">About</h2>
                    <div class="prose dark:prose-invert">
                        <p class="text-gray-600 dark:text-gray-300">Specialist in {{ $doctor->specialization }} with extensive experience in patient care and treatment.</p>
                    </div>
                </div>

                <!-- Experience -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Experience</h2>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-600 dark:text-gray-300">{{ $doctor->experience }}</p>
                    </div>
                </div>

                <!-- Education -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Education</h2>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-600 dark:text-gray-300">{{ $doctor->education }}</p>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Consultation Information</h2>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Consultation Fee</label>
                                <p class="mt-1 text-gray-900 dark:text-white">Rp{{ number_format($doctor->consultation_fee, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Availability</label>
                                <p class="mt-1 text-gray-900 dark:text-white">Check appointment schedule for available times</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection