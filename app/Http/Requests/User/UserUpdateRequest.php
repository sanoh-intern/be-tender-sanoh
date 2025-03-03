<?php

namespace App\Http\Requests\User;

use App\Trait\AuthorizationRole;
use App\Trait\ResponseApi;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * -------TRAIT---------
     * Mandatory:
     * 1. AuthorizationRole = for checking permissible user role
     * 2. ResponseApi = Response api should use ResponseApi trait template
     */
    use AuthorizationRole, ResponseApi;

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
            'id_tax' => 'nullable|string',
            'account_status' => 'nullable|string|in:1,0',
            'company_name' => 'nullable|string',
            'email' => 'nullable|string|email:rfc,strict',
            'password' => 'nullable|string|min:8',
            'role' => 'nullable|int',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        return $this->returnResponseApi(
            false,
            'Please Fill Input Field with Valid Data',
            $validator->errors(),
            403
        );
    }
}
