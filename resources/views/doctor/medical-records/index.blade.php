@extends('layouts.admin')

@section('header')
    <h2 class="text-xl font-semibold">Medical Records</h2>
@endsection

@section('content')
    {{-- Search Filters --}}
    <div class="mb-4 bg-white p-4 rounded-lg shadow">
        <form method="GET" class="flex gap-4">
            <input type="text" name="patient" placeholder="Search patient..." value="{{ request('patient') }}" 
                class="rounded-md">
            <input type="date" name="date" value="{{ request('date') }}" class="rounded-md">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
        </form>
    </div>

    {{-- Records Table --}}
    <div class="bg-white rounded-lg shadow">
        <div class="p-4 border-b flex justify-between">
            <h3 class="text-lg font-semibold">Patient Records</h3>
            <a href="{{ route('doctor.medical-records.create') }}" 
                class="bg-blue-500 text-white px-4 py-2 rounded">Add Record</a>
        </div>
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Patient</th>
                    <th class="px-6 py-3 text-left">Treatment Date</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($records as $record)
                    <tr>
                        <td class="px-6 py-4">{{ $record->patient->user->username }}</td>
                        <td class="px-6 py-4">{{ $record->treatment_date }}</td>
                        <td class="px-6 py-4">{{ $record->status }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('doctor.medical-records.show', $record) }}" 
                                class="text-blue-600">View</a>
                            @if(Auth::user()->doctor->doctor_id === $record->creator_doctor_id)
                                <a href="{{ route('doctor.medical-records.edit', $record) }}" 
                                    class="text-yellow-600">Edit</a>
                                <form method="POST" action="{{ route('doctor.medical-records.destroy', $record) }}" 
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600" 
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $records->links() }}
        </div>
    </div>
@endsection