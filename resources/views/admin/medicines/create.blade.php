{{-- resources/views/admin/medicines/create.blade.php --}}
@extends('layouts.admin')


@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <h2 class="text-xl font-semibold">Add New Medicine</h2>
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <form action="{{ route('admin.medicines.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select name="type"
                                class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="REGULAR" {{ old('type') == 'REGULAR' ? 'selected' : '' }}>Regular</option>
                                <option value="CONTROLLED" {{ old('type') == 'CONTROLLED' ? 'selected' : '' }}>Controlled
                                </option>
                            </select>
                            @error('type')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
                            <input type="number" name="stock" value="{{ old('stock') }}"
                                class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('stock')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                                class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Manufacturer</label>
                            <input type="text" name="manufacturer" value="{{ old('manufacturer') }}"
                                class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('manufacturer')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <input type="text" name="category" value="{{ old('category') }}"
                                class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('category')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                            <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                                class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            @error('expiry_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Medicine Image</label>
                            <input type="file" name="image" id="avatar" accept="image/*" class="hidden">
                            <button type="button" id="change-avatar-button"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                Select Image
                            </button>

                            <div id="avatar-preview-container" class="hidden mt-4">
                                <img id="avatar-preview" src="#" class="max-w-sm mb-2">
                                <button type="button" id="edit-avatar-button"
                                    class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                    Edit Image
                                </button>
                            </div>

                            <div id="avatar-crop-container" class="hidden mt-4">
                                <img id="avatar-image" src="#">
                                <button type="button" id="crop-button"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md mt-2 hover:bg-blue-600">
                                    Crop Image
                                </button>
                            </div>

                            <input type="hidden" name="cropped_image" id="cropped-image">
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="description" rows="4"
                                class="rounded-md w-full border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('admin.medicines.index') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                            Create Medicine
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatar');
            const avatarImage = document.getElementById('avatar-image');
            const avatarPreview = document.getElementById('avatar-preview');
            const avatarCropContainer = document.getElementById('avatar-crop-container');
            const avatarPreviewContainer = document.getElementById('avatar-preview-container');
            const cropButton = document.getElementById('crop-button');
            const editAvatarButton = document.getElementById('edit-avatar-button');
            const changeAvatarButton = document.getElementById('change-avatar-button');
            const form = document.querySelector('form');
            let cropper;
            let croppedImageBlob;

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
                });
            }

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

            cropButton.addEventListener('click', function() {
                if (cropper) {
                    cropper.getCroppedCanvas({
                        width: 300,
                        height: 300
                    }).toBlob(function(blob) {
                        croppedImageBlob = blob;
                        const url = URL.createObjectURL(blob);
                        avatarPreview.src = url;
                        avatarPreviewContainer.style.display = 'block';
                        avatarCropContainer.style.display = 'none';
                    }, 'image/jpeg', 0.8);
                }
            });

            editAvatarButton.addEventListener('click', function() {
                initCropper(avatarPreview.src);
            });

            if (changeAvatarButton) {
                changeAvatarButton.addEventListener('click', function() {
                    avatarInput.click();
                });
            }

                        // Handle form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                
                if (croppedImageBlob) {
                    // Convert blob to base64
                    const reader = new FileReader();
                    reader.readAsDataURL(croppedImageBlob);
                    reader.onloadend = function() {
                        formData.set('cropped_image', reader.result);
                        submitForm(formData);
                    }
                } else {
                    submitForm(formData);
                }
            });
            
            function submitForm(formData) {
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
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
                    } else if (data.errors) {
                        // Handle validation errors
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.querySelector(`[name="${key}"]`)
                                .closest('div')
                                .querySelector('.text-red-500');
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the medicine');
                });
            }
        });
    </script>
@endsection
