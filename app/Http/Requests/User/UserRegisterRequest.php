<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterRequest extends FormRequest
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
            'tax_id' => 'required|string|max:25',
            'company_name' => 'required|string|max:255',
            'email' => 'required|unique:user,email|email:rfc,static|max:225',
        ];
    }

    public function messages(): array
    {
        return [
            'tax_id.required' => 'The tax ID is required.',
            'tax_id.string' => 'The tax ID must be a string.',
            'tax_id.max' => 'The tax ID may not be greater than 25 characters.',
            'company_name.required' => 'The company name is required.',
            'company_name.string' => 'The company name must be a string.',
            'company_name.max' => 'The company name may not be greater than 255 characters.',
            'email.required' => 'The email address is required.',
            'email.unique' => 'The email address has already been taken.',
            'email.email' => 'The email address must be a valid email format.',
            'email.max' => 'The email address may not be greater than 225 characters.',
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
