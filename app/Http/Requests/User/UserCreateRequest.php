<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_photo' => 'nullable|image',
            'email' => 'required|email:rfc,static|max:225',
            'password' => 'required|min:8',
            'role' => 'array',
            'role.*' => 'required|string|in:1,2,3',
            'remember_token' => 'nullable',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'company_photo.image' => 'The company photo must be an image.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 225 characters.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'role.array' => 'The role must be an array.',
            'role.*.required' => 'Each role is required.',
            'role.*.string' => 'Each role must be a string.',
            'role.*.in' => 'Each role must be one of the following: Supplier, Admin-Purchasing, President.',
            'remember_token.nullable' => 'The remember token field is optional.',
        ];
    }
}
