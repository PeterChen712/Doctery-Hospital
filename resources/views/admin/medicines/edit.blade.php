{{-- resources/views/admin/medicines/edit.blade.php --}}
@extends('layouts.admin')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-6">Edit Medicine</h2>

            <form action="{{ route('admin.medicines.update', $medicine) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name', $medicine->name) }}" 
                               class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="REGULAR" {{ old('type', $medicine->type) == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                            <option value="CONTROLLED" {{ old('type', $medicine->type) == 'CONTROLLED' ? 'selected' : '' }}>Controlled</option>
                        </select>
                        @error('type')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                        <input type="number" name="stock" value="{{ old('stock', $medicine->stock) }}" 
                               class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('stock')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $medicine->price) }}" 
                               class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('price')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Manufacturer</label>
                        <input type="text" name="manufacturer" value="{{ old('manufacturer', $medicine->manufacturer) }}" 
                               class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('manufacturer')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <input type="text" name="category" value="{{ old('category', $medicine->category) }}" 
                               class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('category')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date', $medicine->expiry_date->format('Y-m-d')) }}" 
                               class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('expiry_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Medicine Image</label>
                        
                        @if($medicine->image)
                            <div class="mb-4">
                                <img src="data:image/jpeg;base64,{{ base64_encode($medicine->image) }}" 
                                     alt="{{ $medicine->name }}" 
                                     class="w-32 h-32 object-cover rounded-lg mb-2">
                                <button type="button" id="change-image-btn"
                                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                    Change Image
                                </button>
                            </div>
                        @endif

                        <input type="file" name="image" id="image-input" accept="image/*" class="hidden">
                        
                        <div id="image-preview-container" class="hidden">
                            <img id="image-preview" src="#" class="max-w-sm mb-2">
                            <button type="button" id="edit-image-btn"
                                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                Edit Image
                            </button>
                        </div>

                        <div id="image-crop-container" class="hidden">
                            <img id="crop-image" src="#">
                            <button type="button" id="crop-btn"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md mt-2 hover:bg-blue-600">
                                Crop Image
                            </button>
                        </div>

                        <input type="hidden" name="cropped_image" id="cropped-image">
                        @error('image')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4" 
                                  class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('description', $medicine->description) }}</textarea>
                        @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('admin.medicines.index') }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Update Medicine
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image-input');
    const cropImage = document.getElementById('crop-image');
    const imagePreview = document.getElementById('image-preview');
    const cropContainer = document.getElementById('image-crop-container');
    const previewContainer = document.getElementById('image-preview-container');
    const cropBtn = document.getElementById('crop-btn');
    const editImageBtn = document.getElementById('edit-image-btn');
    const changeImageBtn = document.getElementById('change-image-btn');
    const form = document.querySelector('form');
    let cropper;

    function initCropper(imageUrl) {
        cropImage.src = imageUrl;
        cropContainer.classList.remove('hidden');
        previewContainer.classList.add('hidden');

        if (cropper) {
            cropper.destroy();
        }

        cropper = new Cropper(cropImage, {
            aspectRatio: 1,
            viewMode: 1,
            minCropBoxWidth: 100,
            minCropBoxHeight: 100,
        });
    }

    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    initCropper(event.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    cropBtn?.addEventListener('click', function() {
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300
            });
            
            const croppedDataUrl = canvas.toDataURL('image/jpeg');
            document.getElementById('cropped-image').value = croppedDataUrl;
            
            imagePreview.src = croppedDataUrl;
            previewContainer.classList.remove('hidden');
            cropContainer.classList.add('hidden');
    }
    });

    editImageBtn?.addEventListener('click', function() {
        initCropper(imagePreview.src);
    });

    if (changeImageBtn) {
        changeImageBtn.addEventListener('click', () => imageInput.click());
    }
});
</script>
@endsection