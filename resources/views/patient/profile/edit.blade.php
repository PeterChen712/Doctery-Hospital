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

                            @if ($user->profile_image)
                                <!-- Existing Profile Image -->
                                <div class="mb-4">
                                    <img class="w-20 h-20 mb-3 rounded-full shadow-lg"
                                        src="{{ route('avatar.show', Auth::user()->user_id) }}" alt="{{ Auth::user()->username }}">
                                    <button type="button" id="change-avatar-button"
                                        class="bg-gray-500 text-white px-4 py-2 rounded-md mt-2">
                                        Change Image
                                    </button>
                                </div>
                            @else
                                <!-- File Input -->
                                <input type="file" name="profile_image" id="avatar" accept="image/*" class="mt-2">
                            @endif

                            <!-- Image Preview Container -->
                            <div id="avatar-preview-container" style="display: none;">
                                <img id="avatar-preview" src="#" alt="Avatar Preview"
                                    class="w-32 h-32 rounded-full mb-2">
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

                            <input type="hidden" id="cropped-avatar" name="cropped_avatar">

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
                e.preventDefault(); // Prevent default form submission

                const formData = new FormData(form);

                if (croppedImageBlob) {
                    // Remove the original profile_image if any
                    formData.delete('profile_image');
                    // Append the cropped image blob
                    formData.append('profile_image', croppedImageBlob, 'profile_image.jpg');
                }

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            return response.json();
                        }
                    })
                    .then(data => {
                        if (data && data.errors) {
                            // Handle validation errors
                            console.error(data.errors);
                            // Display errors to the user if needed
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });
    </script>
@endsection
