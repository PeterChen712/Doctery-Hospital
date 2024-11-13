{{-- resources/views/admin/medicines/index.blade.php --}}
@extends('layouts.admin')

@section('header')
    <div class="flex justify-between">
        <h2 class="text-xl font-semibold">Medicine Management</h2>
        <a href="{{ route('admin.medicines.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add Medicine</a>
    </div>
@endsection

@section('content')
    {{-- Filters --}}
    <div class="mb-4 bg-white p-4 rounded-lg shadow">
        <form method="GET" class="flex gap-4">
            <select name="status" class="rounded-md">
                <option value="">All Status</option>
                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="out-of-stock" {{ request('status') == 'out-of-stock' ? 'selected' : '' }}>Out of Stock</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
            <input type="text" name="search" placeholder="Search medicines..." value="{{ request('search') }}" class="rounded-md">
            <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded">Filter</button>
        </form>
    </div>

    {{-- Medicines List --}}
    <div class="bg-white rounded-lg shadow">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">Image</th>
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Type</th>
                    <th class="px-6 py-3 text-left">Stock</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($medicines as $medicine)
                    <tr>
                        <td class="px-6 py-4">
                            @if($medicine->image_url)
                                <img src="{{ Storage::url($medicine->image_url) }}" class="w-16 h-16 object-cover rounded">
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $medicine->name }}</td>
                        <td class="px-6 py-4">{{ $medicine->type }}</td>
                        <td class="px-6 py-4">{{ $medicine->stock }}</td>
                        <td class="px-6 py-4">
                            @if(!$medicine->is_available)
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded">Out of Stock</span>
                            @elseif($medicine->expiry_date < now())
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">Expired</span>
                            @else
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Available</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.medicines.edit', $medicine) }}" class="text-blue-600">Edit</a>
                            <form method="POST" action="{{ route('admin.medicines.destroy', $medicine) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $medicines->links() }}
        </div>
    </div>
@endsection