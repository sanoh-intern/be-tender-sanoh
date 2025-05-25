<?php

namespace App\Http\Requests\Verification;

use Illuminate\Validation\Rule;
use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VerificationApproveRequest extends FormRequest
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
        return $this->permissibleRole('purchasing', 'presdir');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:Accepted,Declined',
            'bp_code' => Rule::when($this->input('status') === 'Accepted', ['required', 'string']),
            'message' => Rule::when($this->input('status') === 'Declined', ['required', 'string', 'max:255']),
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
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be either Accepted or Declined.',
            'bp_code.required' => 'BP Code is required when status is Accepted.',
            'bp_code.string' => 'BP Code must be a string.',
            'message.required' => 'Message is required when status is Declined.',
            'message.string' => 'Message must be a string.',
            'message.max' => 'Message may not be greater than 255 characters.',
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
