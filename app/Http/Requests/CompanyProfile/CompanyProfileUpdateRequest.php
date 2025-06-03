<?php

namespace App\Http\Requests\CompanyProfile;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CompanyProfileUpdateRequest extends FormRequest
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
        return $this->permissibleRole('supplier', 'purchasing', 'review');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'bp_code' => [
                Rule::prohibitedIf($this->permissibleRole('supplier') === true),
                'sometimes',
                'string',
                'max:25'
            ],
            'tax_id' => 'sometimes|string|max:25',
            'tax_id_file' => 'sometimes|file|mimes:pdf',
            'company_name' => 'sometimes|string|max:255',
            'company_status' => 'sometimes|string|max:25',
            'company_description' => 'sometimes|string',
            'company_photo' => 'sometimes|file|mimes:png,jpg,jpeg',
            'company_url' => 'sometimes|string|max:255',
            'business_field' => 'sometimes|string|max:255',
            'sub_business_field' => 'sometimes|string|max:255',
            'product' => 'sometimes|string|max:255',
            'adr_line_1' => 'sometimes|string|max:255',
            'adr_line_2' => 'sometimes|string|max:255',
            'adr_line_3' => 'sometimes|string|max:255',
            'adr_line_4' => 'sometimes|string|max:255',
            'province' => 'sometimes|string|max:25',
            'city' => 'sometimes|string|max:25',
            'postal_code' => 'sometimes|string|max:25',
            'company_phone_1' => 'sometimes|string|max:25',
            'company_phone_2' => 'sometimes|string|max:25',
            'company_fax_1' => 'sometimes|string|max:25',
            'company_fax_2' => 'sometimes|string|max:25',
            'skpp_file' => 'sometimes|file|mimes:pdf',
        ];
    }

    public function messages(): array
    {
        return [
            'bp_code.prohibited' => 'BP Code is not allowed for suppliers.',
            'bp_code.string' => 'BP Code must be a string.',
            'bp_code.max' => 'BP Code may not be greater than 25 characters.',

            'tax_id.string' => 'Tax ID must be a string.',
            'tax_id.max' => 'Tax ID may not be greater than 25 characters.',

            'tax_id_file.file' => 'Tax ID File must be a file.',
            'tax_id_file.mimes' => 'Tax ID File must be a PDF file.',

            'company_name.string' => 'Company Name must be a string.',
            'company_name.max' => 'Company Name may not be greater than 255 characters.',

            'company_status.string' => 'Company Status must be a string.',
            'company_status.max' => 'Company Status may not be greater than 25 characters.',

            'company_description.string' => 'Company Description must be a string.',

            'company_photo.file' => 'Company Photo must be a file.',
            'company_photo.mimes' => 'Company Photo must be a file of type: png, jpg, jpeg.',

            'company_url.string' => 'Company URL must be a string.',
            'company_url.max' => 'Company URL may not be greater than 255 characters.',

            'business_field.string' => 'Business Field must be a string.',
            'business_field.max' => 'Business Field may not be greater than 255 characters.',

            'sub_business_field.string' => 'Sub Business Field must be a string.',
            'sub_business_field.max' => 'Sub Business Field may not be greater than 255 characters.',

            'product.string' => 'Product must be a string.',
            'product.max' => 'Product may not be greater than 255 characters.',

            'adr_line_1.string' => 'Address Line 1 must be a string.',
            'adr_line_1.max' => 'Address Line 1 may not be greater than 255 characters.',

            'adr_line_2.string' => 'Address Line 2 must be a string.',
            'adr_line_2.max' => 'Address Line 2 may not be greater than 255 characters.',

            'adr_line_3.string' => 'Address Line 3 must be a string.',
            'adr_line_3.max' => 'Address Line 3 may not be greater than 255 characters.',

            'adr_line_4.string' => 'Address Line 4 must be a string.',
            'adr_line_4.max' => 'Address Line 4 may not be greater than 255 characters.',

            'province.string' => 'Province must be a string.',
            'province.max' => 'Province may not be greater than 25 characters.',

            'city.string' => 'City must be a string.',
            'city.max' => 'City may not be greater than 25 characters.',

            'postal_code.string' => 'Postal Code must be a string.',
            'postal_code.max' => 'Postal Code may not be greater than 25 characters.',

            'company_phone_1.string' => 'Company Phone 1 must be a string.',
            'company_phone_1.max' => 'Company Phone 1 may not be greater than 25 characters.',

            'company_phone_2.string' => 'Company Phone 2 must be a string.',
            'company_phone_2.max' => 'Company Phone 2 may not be greater than 25 characters.',

            'company_fax_1.string' => 'Company Fax 1 must be a string.',
            'company_fax_1.max' => 'Company Fax 1 may not be greater than 25 characters.',

            'company_fax_2.string' => 'Company Fax 2 must be a string.',
            'company_fax_2.max' => 'Company Fax 2 may not be greater than 25 characters.',

            'skpp_file.file' => 'SKPP File must be a file.',
            'skpp_file.mimes' => 'SKPP File must be a PDF file.',
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
