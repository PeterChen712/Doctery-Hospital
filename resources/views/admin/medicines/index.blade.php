{{-- resources/views/admin/medicines/index.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Medicine Management</h2>
            <a href="{{ route('admin.medicines.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Medicine
            </a>
        </div>

        {{-- Filters --}}
        <div class="mb-6 bg-white p-4 rounded-lg shadow">
            <form method="GET" class="flex gap-4">
                <select name="status"
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">All Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="out-of-stock" {{ request('status') == 'out-of-stock' ? 'selected' : '' }}>Out of Stock
                    </option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                <input type="text" name="search" placeholder="Search medicines..." value="{{ request('search') }}"
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <button type="submit"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-150">
                    Filter
                </button>
            </form>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Medicines List --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($medicines as $medicine)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($medicine->image)
                                    <img src="data:image/jpeg;base64,{{ base64_encode($medicine->image) }}"
                                        alt="{{ $medicine->name }}" class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $medicine->name }}</div>
                                <div class="text-sm text-gray-500">{{ $medicine->manufacturer }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-sm {{ $medicine->type === 'CONTROLLED' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }} rounded-full">
                                    {{ $medicine->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $medicine->stock }}</div>
                                <div class="text-sm text-gray-500">Units</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if (!$medicine->is_available)
                                    <span class="px-2 py-1 text-sm bg-red-100 text-red-800 rounded-full">Out of Stock</span>
                                @elseif($medicine->expiry_date < now())
                                    <span
                                        class="px-2 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full">Expired</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-sm bg-green-100 text-green-800 rounded-full">Available</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.medicines.edit', ['medicine' => $medicine->medicine_id]) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                <form method="POST"
                                    action="{{ route('admin.medicines.destroy', ['medicine' => $medicine->medicine_id]) }}"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No medicines found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="px-6 py-4 bg-gray-50">
                {{ $medicines->links() }}
            </div>
        </div>
    </div>
@endsection
