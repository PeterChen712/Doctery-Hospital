{{-- resources/views/admin/medicines/create.blade.php --}}
@extends('layouts.admin')

@section('header')
    <h2 class="text-xl font-semibold">Add New Medicine</h2>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <form action="{{ route('admin.medicines.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="rounded-md w-full">
                @error('name')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Type</label>
                <select name="type" class="rounded-md w-full">
                    <option value="REGULAR" {{ old('type') == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                    <option value="CONTROLLED" {{ old('type') == 'CONTROLLED' ? 'selected' : '' }}>Controlled</option>
                </select>
                @error('type')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Stock</label>
                <input type="number" name="stock" value="{{ old('stock') }}" class="rounded-md w-full">
                @error('stock')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="rounded-md w-full">
                @error('price')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Manufacturer</label>
                <input type="text" name="manufacturer" value="{{ old('manufacturer') }}" class="rounded-md w-full">
                @error('manufacturer')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Category</label>
                <input type="text" name="category" value="{{ old('category') }}" class="rounded-md w-full">
                @error('category')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Expiry Date</label>
                <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" class="rounded-md w-full">
                @error('expiry_date')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="mb-4">
                <label class="block mb-2">Image</label>
                <input type="file" name="image">
                @error('image')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="col-span-2">
                <label class="block mb-2">Description</label>
                <textarea name="description" rows="4" class="rounded-md w-full">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Medicine</button>
        </div>
    </form>
</div>
@endsection