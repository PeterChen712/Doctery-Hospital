{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.admin')

@section('header')
    <h2 class="text-xl font-semibold">Create New User</h2>
@endsection

<style>
    /* Include Cropper.js CSS */
    @import url('https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css');
</style>

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Username Field -->
                        <div class="mb-4">
                            <label class="block mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username') }}"
                                class="rounded-md shadow-sm border-gray-300 w-full">
                            @error('username')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label class="block mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="rounded-md shadow-sm border-gray-300 w-full">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="mb-4">
                            <label class="block mb-2">Password</label>
                            <input type="password" name="password" class="rounded-md shadow-sm border-gray-300 w-full">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="mb-4">
                            <label class="block mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="rounded-md shadow-sm border-gray-300 w-full">
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-4">
                            <label class="block mb-2">Role</label>
                            <select name="role" class="rounded-md shadow-sm border-gray-300 w-full">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profile Image Upload with Cropping -->
                        <div class="mb-4">
                            <label class="block mb-2">Profile Image</label>

                            <!-- Image Preview Container -->
                            <div id="avatar-preview-container" style="display: none;">
                                <img id="avatar-preview" src="#" alt="Avatar Preview"
                                    class="w-32 h-32 rounded-full mb-2">
                                <button type="button" id="edit-avatar-button"
                                    class="bg-gray-500 text-white px-4 py-2 rounded-md">Edit Image</button>
                            </div>

                            <!-- Image Crop Container -->
                            <div id="avatar-crop-container" style="display: none;">
                                <img id="avatar-image" src="#" alt="Avatar Image">
                                <button type="button" id="crop-button"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md mt-2">Crop</button>
                            </div>

                            <!-- File Input -->
                            <input type="file" name="profile_image" id="avatar" accept="image/*" class="mt-2">
                            <input type="hidden" id="cropped-avatar" name="cropped_avatar">

                            @error('profile_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">Create User</button>
                            <a href="{{ route('admin.users.index') }}"
                                class="ml-4 bg-gray-500 text-white px-4 py-2 rounded-md">Cancel</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Include Cropper.js JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const avatarInput = document.getElementById('avatar');
        const avatarImage = document.getElementById('avatar-image');
        const avatarPreview = document.getElementById('avatar-preview');
        const avatarCropContainer = document.getElementById('avatar-crop-container');
        const avatarPreviewContainer = document.getElementById('avatar-preview-container');
        const croppedAvatarInput = document.getElementById('cropped-avatar');
        const cropButton = document.getElementById('crop-button');
        const editAvatarButton = document.getElementById('edit-avatar-button');
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
            });
        }

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

        cropButton.addEventListener('click', function() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300
                });

                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    avatarPreview.src = url;
                    avatarPreviewContainer.style.display = 'block';
                    avatarCropContainer.style.display = 'none';

                    // Convert blob to base64 string
                    const reader = new FileReader();
                    reader.onloadend = function() {
                        croppedAvatarInput.value = reader.result;
                    };
                    reader.readAsDataURL(blob);
                }, 'image/jpeg', 0.8);
            }
        });

        editAvatarButton.addEventListener('click', function() {
            initCropper(avatarPreview.src);
        });

        // Handle form submission
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            if (croppedAvatarInput.value === '' && avatarInput.value !== '') {
                e.preventDefault(); // Prevent form submission until cropping is done
                if (cropper) {
                    cropper.getCroppedCanvas({
                        width: 300,
                        height: 300
                    }).toBlob(function(blob) {
                        const reader = new FileReader();
                        reader.onloadend = function() {
                            croppedAvatarInput.value = reader.result;
                            form
                        .submit(); // Submit the form after getting the cropped image
                        };
                        reader.readAsDataURL(blob);
                    }, 'image/jpeg', 0.8);
                } else {
                    alert('Please crop the image before submitting.');
                }
            }
        });
    });
</script>
