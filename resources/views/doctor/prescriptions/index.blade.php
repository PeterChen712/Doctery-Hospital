<!-- resources/views/doctor/prescriptions/index.blade.php -->
@extends('layouts.doctor')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="text-xl font-semibold">Prescriptions</h2>
        <a href="{{ route('doctor.prescriptions.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
            New Prescription
        </a>
    </div>
@endsection

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <form action="{{ route('doctor.prescriptions.index') }}" method="GET" class="flex gap-4">
                <input type="date" name="date" value="{{ request('date') }}"
                    class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <select name="status" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">All Status</option>
                    <option value="PENDING" {{ request('status') === 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="PROCESSING" {{ request('status') === 'PROCESSING' ? 'selected' : '' }}>Processing</option>
                    <option value="COMPLETED" {{ request('status') === 'COMPLETED' ? 'selected' : '' }}>Completed</option>
                    <option value="CANCELLED" {{ request('status') === 'CANCELLED' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Filter
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Patient
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Medicines
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    @forelse ($prescriptions as $prescription)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $prescription->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $prescription->patient->user->username }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    {{ $prescription->medicines->count() }} items
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($prescription->status === 'COMPLETED') bg-green-100 text-green-800
                                    @elseif($prescription->status === 'PENDING') bg-yellow-100 text-yellow-800
                                    @elseif($prescription->status === 'PROCESSING') bg-blue-100 text-blue-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $prescription->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <a href="{{ route('doctor.prescriptions.show', $prescription) }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                                    View
                                </a>
                                @if($prescription->status === 'PENDING')
                                    <a href="{{ route('doctor.prescriptions.edit', $prescription) }}"
                                       class="text-green-600 hover:text-green-900 dark:hover:text-green-400">
                                        Edit
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No prescriptions found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $prescriptions->links() }}
        </div>
    </div>
@endsection