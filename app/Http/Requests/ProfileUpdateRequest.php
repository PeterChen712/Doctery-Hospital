<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user()->user_id, 'user_id')],
            'phone_number' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string', 'max:255'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
        ];

        // Add role-specific validation rules
        if ($this->user()->hasRole('doctor')) {
            $rules += [
                'specialization' => ['required', 'string', 'max:255'],
                'license_number' => ['required', 'string', 'max:50'],
                'education' => ['required', 'string'],
                'experience' => ['required', 'string'],
                'consultation_fee' => ['required', 'numeric', 'min:0']
            ];
        } elseif ($this->user()->hasRole('patient')) {
            $rules += [
                'date_of_birth' => ['required', 'date'],
                'blood_type' => ['required', 'string', 'max:5'],
                'allergies' => ['nullable', 'string'],
                'medical_history' => ['nullable', 'string'],
                'emergency_contact' => ['required', 'string', 'max:255']
            ];
        }

        return $rules;
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Username is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already taken',
            'phone_number.required' => 'Phone number is required',
            'address.required' => 'Address is required',
            'profile_image.image' => 'File must be an image',
            'profile_image.max' => 'Image size should not exceed 2MB',
            'specialization.required' => 'Specialization is required for doctors',
            'license_number.required' => 'License number is required for doctors',
            'date_of_birth.required' => 'Date of birth is required for patients',
            'blood_type.required' => 'Blood type is required for patients',
            'emergency_contact.required' => 'Emergency contact is required for patients'
        ];
    }
}