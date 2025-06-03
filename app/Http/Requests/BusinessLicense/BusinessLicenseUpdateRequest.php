<?php

namespace App\Http\Requests\BusinessLicense;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BusinessLicenseUpdateRequest extends FormRequest
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
            'business_license_number' => 'sometimes|string|max:25',
            'business_license_file' => 'sometimes|file|mimes:pdf',
            'business_type' => 'sometimes|string',
            'qualification' => 'sometimes|string',
            'sub_classification' => 'sometimes|string',
            'issuing_agency' => 'sometimes|string',
            'issuing_date' => 'sometimes|date',
            'expiry_date' => 'sometimes|date',
        ];
    }

    public function messages(): array
    {
        return [
            'business_license_number.string' => 'The business license number must be a string.',
            'business_license_number.max' => 'The business license number may not be greater than 25 characters.',
            'business_license_file.file' => 'The business license file must be a valid file.',
            'business_license_file.mimes' => 'The business license file must be a PDF.',
            'business_type.string' => 'The business type must be a string.',
            'qualification.string' => 'The qualification must be a string.',
            'sub_classification.string' => 'The sub-classification must be a string.',
            'issuing_agency.string' => 'The issuing agency must be a string.',
            'issuing_date.date' => 'The issuing date must be a valid date.',
            'expiry_date.date' => 'The expiry date must be a valid date.',
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
