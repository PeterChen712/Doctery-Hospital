<!-- resources/views/doctor/patients/index.blade.php -->
@extends('layouts.doctor')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">Patient List</h2>
        <div class="flex gap-2">
            <form action="{{ route('doctor.patients.index') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Search patients..." 
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Search
                </button>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Patient Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Phone
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Last Visit
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse ($patients as $patient)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $patient->username }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $patient->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $patient->phone_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $patient->medicalRecords->sortByDesc('created_at')->first()?->created_at?->format('d M Y') ?? 'No visits' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('doctor.patients.show', $patient) }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No patients found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $patients->links() }}
        </div>
    </div>
@endsection