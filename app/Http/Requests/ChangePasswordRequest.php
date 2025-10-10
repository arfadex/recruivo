<?php

namespace App\Http\Requests;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
        $user = $this->user();
        // Extract username from email (part before @)
        $username = $user && $user->email ? explode('@', $user->email)[0] : null;

        return [
            'current_password' => 'required|string',
            'password' => ['required', 'string', 'confirmed', new StrongPassword($username)],
            'password_confirmation' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Current password is required.',
            'password.required' => 'New password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.required' => 'Password confirmation is required.',
        ];
    }
}
