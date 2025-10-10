<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyProfileRequest extends FormRequest
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
        return [
            'name' => 'sometimes|string|max:255',
            'tagline' => 'sometimes|nullable|string|max:255',
            'location' => 'sometimes|nullable|string|max:255',
            'website_url' => 'sometimes|nullable|url|max:255',
            'linkedin_url' => 'sometimes|nullable|url|max:255',
            'email' => 'sometimes|nullable|email|max:255',
            'size' => 'sometimes|nullable|string|max:50',
            'founded_year' => 'sometimes|nullable|integer|min:1800|max:' . date('Y'),
            'mission' => 'sometimes|nullable|string',
            'culture' => 'sometimes|nullable|string',
            'logo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.max' => 'The company name may not be greater than 255 characters.',
            'tagline.max' => 'The tagline may not be greater than 255 characters.',
            'location.max' => 'The location may not be greater than 255 characters.',
            'website_url.url' => 'Please provide a valid website URL.',
            'linkedin_url.url' => 'Please provide a valid LinkedIn URL.',
            'email.email' => 'Please provide a valid email address.',
            'size.max' => 'The company size may not be greater than 50 characters.',
            'founded_year.integer' => 'The founded year must be a valid year.',
            'founded_year.min' => 'The founded year must be after 1800.',
            'founded_year.max' => 'The founded year cannot be in the future.',
        ];
    }
}
