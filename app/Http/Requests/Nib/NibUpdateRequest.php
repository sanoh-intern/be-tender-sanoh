<?php

namespace App\Http\Requests\Nib;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class NibUpdateRequest extends FormRequest
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
        return $this->permissibleRole('supplier');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nib_number' => 'sometimes|max:13',
            'nib_file' => 'sometimes|file|mimes:pdf',
            'issuing_agency' => 'sometimes|string',
            'issuing_date' => 'sometimes|date',
            'investment_status' => 'sometimes|in:Done,In Progress',
            'kbli' => 'sometimes',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nib_number.max' => 'The NIB number must not exceed 13 characters.',
            'nib_file.file' => 'The NIB file must be a valid file.',
            'nib_file.mimes' => 'The NIB file must be a PDF.',
            'issuing_agency.string' => 'The issuing agency must be a string.',
            'issuing_date.date' => 'The issuing date must be a valid date.',
            'investment_status.in' => 'The investment status must be either "Done" or "In Progress".',
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
