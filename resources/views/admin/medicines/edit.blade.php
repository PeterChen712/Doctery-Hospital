<!-- resources/views/patient/profile/edit.blade.php -->
@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Medicine</h2>

                    <form method="POST" action="{{ route('admin.medicines.update', $medicine->medicine_id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Medicine Name
                            </label>
                            <input type="text" name="name" value="{{ old('name', $medicine->name) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Type
                            </label>
                            <select name="type"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="REGULAR" {{ old('type', $medicine->type) == 'REGULAR' ? 'selected' : '' }}>
                                    Regular</option>
                                <option value="CONTROLLED"
                                    {{ old('type', $medicine->type) == 'CONTROLLED' ? 'selected' : '' }}>Controlled
                                </option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Stock
                            </label>
                            <input type="number" name="stock" value="{{ old('stock', $medicine->stock) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('stock')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Price
                            </label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $medicine->price) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Manufacturer -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Manufacturer
                            </label>
                            <input type="text" name="manufacturer"
                                value="{{ old('manufacturer', $medicine->manufacturer) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('manufacturer')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Category
                            </label>
                            <input type="text" name="category" value="{{ old('category', $medicine->category) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Expiry Date -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Expiry Date
                            </label>
                            <input type="date" name="expiry_date"
                                value="{{ old('expiry_date', $medicine->expiry_date->format('Y-m-d')) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('expiry_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Medicine Image Upload with Cropping -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Medicine Image
                            </label>

                            {{-- Current Image Preview --}}
                            @if ($medicine->image)
                                <div class="mb-4">
                                    <img src="{{ route('admin.medicines.image', $medicine->medicine_id) }}"
                                        alt="{{ $medicine->name }}" class="w-32 h-32 object-cover rounded-lg">
                                </div>
                            @endif

                            <!-- File Input -->
                            <input type="file" name="image" id="avatar" accept="image/*" class="hidden">
                            <button type="button" id="change-avatar-button"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                {{ $medicine->image ? 'Change Image' : 'Select Image' }}
                            </button>

                            <!-- Image Preview Container -->
                            <div id="avatar-preview-container" style="display: none;">
                                <img id="avatar-preview" src="#" alt="Medicine Preview"
                                    class="w-32 h-32 object-cover rounded-lg mb-2">
                                <button type="button" id="edit-avatar-button"
                                    class="bg-gray-500 text-white px-4 py-2 rounded-md">
                                    Edit Image
                                </button>
                            </div>

                            <!-- Image Crop Container -->
                            <div id="avatar-crop-container" style="display: none;">
                                <img id="avatar-image" src="#" alt="Medicine Image">
                                <button type="button" id="crop-button"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md mt-2">
                                    Crop
                                </button>
                            </div>

                            <input type="hidden" id="cropped-avatar" name="cropped_image">
                            @error('image')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea name="description" rows="4"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>{{ old('description', $medicine->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.medicines.index') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-400">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Medicine
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Include Cropper.js JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const avatarInput = document.getElementById('avatar');
            const avatarImage = document.getElementById('avatar-image');
            const avatarPreview = document.getElementById('avatar-preview');
            const avatarCropContainer = document.getElementById('avatar-crop-container');
            const avatarPreviewContainer = document.getElementById('avatar-preview-container');
            const cropButton = document.getElementById('crop-button');
            const editAvatarButton = document.getElementById('edit-avatar-button');
            const changeAvatarButton = document.getElementById('change-avatar-button');
            const croppedAvatarInput = document.getElementById('cropped-avatar');
            const form = document.querySelector('form');
            let cropper;
            let croppedImageBlob;

            // Initialize Cropper
            function initCropper(imageUrl) {
                avatarImage.src = imageUrl;
                avatarCropContainer.style.display = 'block';
                avatarPreviewContainer.style.display = 'none';

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(avatarImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    minCropBoxWidth: 100,
                    minCropBoxHeight: 100,
                    ready() {
                        // Enable cropping when ready
                        this.cropper.crop();
                    }
                });
            }

            // File Input Change Handler
            if (avatarInput) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && /^image\//.test(file.type)) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            initCropper(event.target.result);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        alert('Please select a valid image file.');
                    }
                });
            }

            // Crop Button Handler
            cropButton.addEventListener('click', function() {
                if (cropper) {
                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high'
                    });

                    // Convert canvas to base64 immediately
                    const base64Image = canvas.toDataURL('image/jpeg', 0.8);
                    croppedAvatarInput.value = base64Image;

                    // Update preview
                    avatarPreview.src = base64Image;
                    avatarPreviewContainer.style.display = 'block';
                    avatarCropContainer.style.display = 'none';

                    // Also store as blob for later use
                    canvas.toBlob((blob) => {
                        croppedImageBlob = blob;
                    }, 'image/jpeg', 0.8);
                }
            });

            // Edit Button Handler
            editAvatarButton.addEventListener('click', function() {
                if (avatarPreview.src) {
                    initCropper(avatarPreview.src);
                }
            });

            // Change Avatar Button Handler
            if (changeAvatarButton) {
                changeAvatarButton.addEventListener('click', function() {
                    avatarInput.click();
                });
            }

            // Form Submit Handler
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);

                // If we have a cropped image, use it
                if (croppedAvatarInput.value) {
                    formData.set('cropped_image', croppedAvatarInput.value);
                }

                // Submit form
                submitForm(formData);
            });

            // Form Submission Function

            function submitForm(formData) {
                fetch(form.action, {
                        method: 'POST',
                        body: formData // Remove headers when sending FormData
                    })
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                            return;
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.href = data.redirect;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while saving the medicine');
                    });
            }

            // Clean up on page unload
            window.addEventListener('unload', function() {
                if (cropper) {
                    cropper.destroy();
                }
                // Clean up any object URLs
                if (croppedImageBlob) {
                    URL.revokeObjectURL(URL.createObjectURL(croppedImageBlob));
                }
            });
        });
    </script>
@endsection
