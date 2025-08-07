<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:male,female,other'],
            'date_of_birth' => ['required', 'date'],
            'educational_details' => ['nullable', 'string', 'max:5000'],

            'tenth_certificate.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'twelfth_certificate.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'graduation_certificate.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'masters_certificate.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'tenth_certificate' => 'nullable|array',
            'tenth_certificate.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'twelfth_certificate' => 'nullable|array',
            'twelfth_certificate.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'graduation_certificate' => 'nullable|array',
            'graduation_certificate.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'masters_certificate' => 'nullable|array',
            'masters_certificate.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
            'educational_details' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter a valid email address.',
            'phone.regex' => 'Please enter a valid phone number (10-15 digits).',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            '*.mimes' => 'File must be a PDF, JPG, JPEG, or PNG.',
            '*.max' => 'File size cannot exceed 5MB.',
        ];
    }
}
