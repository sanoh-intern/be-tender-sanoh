<?php

namespace App\Http\Requests\User;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserCreateRequest extends FormRequest
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. AuthorizationRole = for checking permissible user role
     */
    use AuthorizationRole;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->permissibleRole('super-admin');
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
            'company_name' => 'required|string|max:255',
            'tax_id' => 'required|string|max:25',
            'email' => 'required|unique:user,email|email:rfc,static|max:225',
            'password' => 'required|min:8',
            'role' => 'required|integer',
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
            'company_name.required' => 'The company name field is required.',
            'company_name.string' => 'The company name must be a string.',
            'company_name.max' => 'The company name may not be greater than 255 characters.',
            'tax_id.required' => 'The tax ID field is required.',
            'tax_id.string' => 'The tax ID must be a string.',
            'tax_id.max' => 'The tax ID may not be greater than 25 characters.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'The email has already been taken.',
            'email.email' => 'The email must be a valid email address.',
            'email.max' => 'The email may not be greater than 225 characters.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'role.array' => 'The role must be an array.',
            'role.*.required' => 'Each role is required.',
            'role.*.string' => 'Each role must be a string.',
            'role.*.in' => 'Each role must be one of the following: 1, 2, 3.',
            'remember_token.nullable' => 'The remember token field is optional.',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Please Fill Input Field with Valid Data',
                'error' => $validator->errors(),
            ], 403)
        );
    }
}
