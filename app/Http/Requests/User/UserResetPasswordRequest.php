<?php

namespace App\Http\Requests\User;

use Illuminate\Validation\Rule;
use App\Trait\AuthorizationRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserResetPasswordRequest extends FormRequest
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
        return Auth::guest() || $this->permissibleRole('supplier', 'purchasing', 'review');;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => [
                Rule::when(
                    Auth::guest(), // true if no authenticated user (i.e., guest)
                    ['required', 'string', 'max:25'],
                    ['sometimes', 'string', 'max:25']
                ),
            ],
            'new_password' => 'required|string|min:8|',
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'Token is required.',
            'token.max' => 'Token may not be greater than 6 characters.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least 8 characters.',
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
