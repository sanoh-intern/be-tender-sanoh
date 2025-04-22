<?php

namespace App\Http\Requests\CompanyProfile;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'bp_code' => 'sometimes|required|string|max:25',
            'tax_id' => 'sometimes|required|string|max:25',
            'company_name' => 'sometimes|required|string|max:255',
            'company_status' => 'sometimes|required|string|max:25',
            'company_description' => 'sometimes|required|string',
            'company_photo' => 'sometimes|required|string|max:255',
            'company_url' => 'sometimes|required|string|max:255',
            'business_field' => 'sometimes|required|string|max:255',
            'sub_business_field' => 'sometimes|required|string|max:255',
            'product' => 'sometimes|required|string|max:255',
            'adr_line_1' => 'sometimes|required|string|max:255',
            'adr_line_2' => 'sometimes|required|string|max:255',
            'adr_line_3' => 'sometimes|required|string|max:255',
            'adr_line_4' => 'sometimes|required|string|max:255',
            'province' => 'sometimes|required|string|max:25',
            'city' => 'sometimes|required|string|max:25',
            'postal_code' => 'sometimes|required|string|max:25',
            'company_phone_1' => 'sometimes|required|string|max:25',
            'company_phone_2' => 'sometimes|required|string|max:25',
            'company_fax_1' => 'sometimes|required|string|max:25',
            'company_fax_2' => 'sometimes|required|string|max:25',
        ];
    }

    public function messages(): array
    {
        return [
            'bp_code.required' => 'BP Code is required.',
            'bp_code.string' => 'BP Code must be a valid string.',
            'bp_code.max' => 'BP Code cannot exceed 25 characters.',
            'tax_id.required' => 'Tax ID is required.',
            'tax_id.string' => 'Tax ID must be a valid string.',
            'tax_id.max' => 'Tax ID cannot exceed 25 characters.',
            'company_name.required' => 'Company Name is required.',
            'company_name.string' => 'Company Name must be a valid string.',
            'company_name.max' => 'Company Name cannot exceed 255 characters.',
            'company_status.required' => 'Company Status is required.',
            'company_status.string' => 'Company Status must be a valid string.',
            'company_status.max' => 'Company Status cannot exceed 25 characters.',
            'company_description.string' => 'Company Description must be a valid string.',
            'company_photo.required' => 'Company Photo is required.',
            'company_photo.string' => 'Company Photo must be a valid string.',
            'company_photo.max' => 'Company Photo cannot exceed 255 characters.',
            'company_url.required' => 'Company URL is required.',
            'company_url.string' => 'Company URL must be a valid string.',
            'company_url.max' => 'Company URL cannot exceed 255 characters.',
            'business_field.required' => 'Business Field is required.',
            'business_field.string' => 'Business Field must be a valid string.',
            'business_field.max' => 'Business Field cannot exceed 255 characters.',
            'sub_business_field.required' => 'Sub Business Field is required.',
            'sub_business_field.string' => 'Sub Business Field must be a valid string.',
            'sub_business_field.max' => 'Sub Business Field cannot exceed 255 characters.',
            'product.required' => 'Product is required.',
            'product.string' => 'Product must be a valid string.',
            'product.max' => 'Product cannot exceed 255 characters.',
            'adr_line_1.required' => 'Address Line 1 is required.',
            'adr_line_1.string' => 'Address Line 1 must be a valid string.',
            'adr_line_1.max' => 'Address Line 1 cannot exceed 255 characters.',
            'adr_line_2.required' => 'Address Line 2 is required.',
            'adr_line_2.string' => 'Address Line 2 must be a valid string.',
            'adr_line_2.max' => 'Address Line 2 cannot exceed 255 characters.',
            'adr_line_3.required' => 'Address Line 3 is required.',
            'adr_line_3.string' => 'Address Line 3 must be a valid string.',
            'adr_line_3.max' => 'Address Line 3 cannot exceed 255 characters.',
            'adr_line_4.required' => 'Address Line 4 is required.',
            'adr_line_4.string' => 'Address Line 4 must be a valid string.',
            'adr_line_4.max' => 'Address Line 4 cannot exceed 255 characters.',
            'province.required' => 'Province is required.',
            'province.string' => 'Province must be a valid string.',
            'province.max' => 'Province cannot exceed 25 characters.',
            'city.required' => 'City is required.',
            'city.string' => 'City must be a valid string.',
            'city.max' => 'City cannot exceed 25 characters.',
            'postal_code.required' => 'Postal Code is required.',
            'postal_code.string' => 'Postal Code must be a valid string.',
            'postal_code.max' => 'Postal Code cannot exceed 25 characters.',
            'company_phone_1.required' => 'Company Phone 1 is required.',
            'company_phone_1.string' => 'Company Phone 1 must be a valid string.',
            'company_phone_1.max' => 'Company Phone 1 cannot exceed 25 characters.',
            'company_phone_2.required' => 'Company Phone 2 is required.',
            'company_phone_2.string' => 'Company Phone 2 must be a valid string.',
            'company_phone_2.max' => 'Company Phone 2 cannot exceed 25 characters.',
            'company_fax_1.required' => 'Company Fax 1 is required.',
            'company_fax_1.string' => 'Company Fax 1 must be a valid string.',
            'company_fax_1.max' => 'Company Fax 1 cannot exceed 25 characters.',
            'company_fax_2.required' => 'Company Fax 2 is required.',
            'company_fax_2.string' => 'Company Fax 2 must be a valid string.',
            'company_fax_2.max' => 'Company Fax 2 cannot exceed 25 characters.',
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
