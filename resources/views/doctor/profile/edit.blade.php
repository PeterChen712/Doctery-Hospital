<!-- resources/views/doctor/profile/edit.blade.php -->
@extends('layouts.doctor')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Profil</h2>

                    <!-- Include Cropper.js CSS -->
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

                    <form method="POST" action="{{ route('doctor.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Username -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Pengguna
                            </label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('username')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Alamat
                            </label>
                            <textarea name="address" rows="3"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Specialization -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Spesialisasi
                            </label>
                            <input type="text" name="specialization"
                                value="{{ old('specialization', $doctor->specialization) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('specialization')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- License Number -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nomor Lisensi
                            </label>
                            <input type="text" name="license_number"
                                value="{{ old('license_number', $doctor->license_number) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('license_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profile Image Upload with Cropping -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Profile Image
                            </label>

                            <!-- Current Image Preview -->
                            @if ($user->profile_image)
                                <div class="mb-4">
                                    <img class="w-32 h-32 rounded-full shadow-lg object-cover"
                                        src="{{ route('avatar.show', $user->user_id) }}" alt="{{ $user->username }}">
                                </div>
                            @endifLima, Peru. Skype. Hello. 

                            <!-- File Input -->
                            <input type="file" name="profile_image" id="avatar" accept="image/*" class="hidden">
                            <button type="button" id="change-avatar-button"
                                class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                                {{ $user->profile_image ? 'Change Image' : 'Select Image' }}
                            </button>

                            <!-- Image Preview Container -->
                            <div id="avatar-preview-container" style="display: none;">
                                <img id="avatar-preview" src="#" alt="Avatar Preview"
                                    class="w-32 h-32 rounded-full mb-2 object-cover">
                                <button type="button" id="edit-avatar-button"
                                    class="bg-gray-500 text-white px-4 py-2 rounded-md">
                                    Edit Image
                                </button>
                            </div>

                            <!-- Image Crop Container -->
                            <div id="avatar-crop-container" style="display: none;">
                                <img id="avatar-image" src="#" alt="Avatar Image">
                                <button type="button" id="crop-button"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md mt-2">
                                    Crop
                                </button>
                            </div>

                            <input type="hidden" id="cropped-avatar" name="cropped_image">
                            @error('profile_image')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('doctor.profile.show') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-400">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Perbarui Profil
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
                    autoCropArea: 1,
                    responsive: true,
                    cropBoxResizable: true,
                    background: true,
                    guides: true,
                    highlight: true,
                    cropBoxMovable: true,
                    dragMode: 'move',
                    ready: function() {
                        this.cropper.crop();
                    }
                });
            }

            if (avatarInput) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (/^image\/\w+/.test(file.type)) {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                initCropper(event.target.result);
                            };
                            reader.readAsDataURL(file);
                        } else {
                            alert('Please select a valid image file.');
                            avatarInput.value = '';
                        }
                    }
                });
            }

            cropButton.addEventListener('click', function() {
                if (cropper) {
                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                        fillColor: '#fff',
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high',
                    });

                    const base64Image = canvas.toDataURL('image/jpeg', 0.8);
                    croppedAvatarInput.value = base64Image;

                    avatarPreview.src = base64Image;
                    avatarPreviewContainer.style.display = 'block';
                    avatarCropContainer.style.display = 'none';
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

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);

                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = 'Updating...';

                fetch(form.action, {
                        method: 'POST',
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
                            Object.keys(data.errors).forEach(field => {
                                const errorElement = document.querySelector(`[name="${field}"]`)
                                    .parentElement.querySelector('.text-red-600');
                                if (errorElement) {
                                    errorElement.textContent = data.errors[field][0];
                                }
                            });
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                        alert('An error occurred while updating your profile. Please try again.');
                    });
            });
        });
    </script>
@endsection
