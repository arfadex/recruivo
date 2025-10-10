<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
     */
    public function rules(): array
    {
        \Log::info('UpdateProfileRequest validation rules', [
            'user_id' => $this->user()?->id,
            'request_data' => $this->all(),
            'files' => $this->allFiles()
        ]);

        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $this->user()->id,
            'location' => 'sometimes|nullable|string|max:255',
            'phone' => 'sometimes|nullable|string|max:20',
            'profile_summary' => 'sometimes|nullable|string|max:1000',
            'resume' => 'sometimes|nullable|file|mimes:pdf,doc,docx|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email address is already taken.',
            'email.email' => 'Please provide a valid email address.',
            'name.max' => 'The name may not be greater than 255 characters.',
            'location.max' => 'The location may not be greater than 255 characters.',
            'phone.max' => 'The phone number may not be greater than 20 characters.',
            'profile_summary.max' => 'The profile summary may not be greater than 1000 characters.',
        ];
    }
}
