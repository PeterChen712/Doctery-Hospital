<!-- resources/views/patient/profile/edit.blade.php -->

@extends('layouts.patient')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Your Profile</h2>

                    <!-- Include Cropper.js CSS -->
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

                    <form method="POST" action="{{ route('patient.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Username -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Username
                            </label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('username')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Date of Birth
                            </label>
                            <input type="date" name="date_of_birth"
                                value="{{ old('date_of_birth', optional($patient->date_of_birth)->format('Y-m-d')) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Phone Number
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
                                Address
                            </label>
                            <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                New Password
                            </label>
                            <input type="password" name="password"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="text-gray-500 text-sm">Leave blank if you don't want to change the password.</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Confirm New Password
                            </label>
                            <input type="password" name="password_confirmation"
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                            @endif

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
                            <a href="{{ route('patient.profile.show') }}"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-400">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Profile
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

            // Handle file input change
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

            // Handle crop button click
            cropButton.addEventListener('click', function() {
                if (cropper) {
                    const canvas = cropper.getCroppedCanvas({
                        width: 300,
                        height: 300,
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high'
                    });

                    // Convert to base64 with proper MIME type prefix
                    const base64Image = canvas.toDataURL('image/jpeg', 0.8);
                    croppedAvatarInput.value =
                    base64Image; // This will include "data:image/jpeg;base64," prefix

                    // Update preview
                    avatarPreview.src = base64Image;
                    avatarPreviewContainer.style.display = 'block';
                    avatarCropContainer.style.display = 'none';
                }
            });

            // Handle edit button click
            editAvatarButton.addEventListener('click', function() {
                initCropper(avatarPreview.src);
            });

            // Handle change image button click
            if (changeAvatarButton) {
                changeAvatarButton.addEventListener('click', function() {
                    avatarInput.click();
                });
            }

            // Handle form submission
            // Update the form submission part in patient profile edit.blade.php:

            // Handle form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);

                // Show loading state
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.disabled = true;
                submitButton.innerHTML = 'Updating...';

                // If we have a cropped image, use it
                if (croppedAvatarInput.value) {
                    formData.set('cropped_image', croppedAvatarInput.value);
                }

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
                        } else if (data.errors) {
                            // Handle validation errors
                            Object.keys(data.errors).forEach(field => {
                                const errorElement = document.querySelector(`[name="${field}"]`)
                                    .parentElement.querySelector('.text-red-600');
                                if (errorElement) {
                                    errorElement.textContent = data.errors[field][0];
                                }
                            });
                            // Reset submit button
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

            // Update the crop button handler:
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

            // Clean up on page unload
            window.addEventListener('beforeunload', function() {
                if (cropper) {
                    cropper.destroy();
                }
                if (croppedImageBlob) {
                    URL.revokeObjectURL(avatarPreview.src);
                }
            });
        });
    </script>
@endsection
