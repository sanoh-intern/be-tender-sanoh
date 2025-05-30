<?php

namespace App\Http\Requests\PersonInCharge;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PicUpdateRequest extends FormRequest
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
            'job_position' => 'sometimes|string|max:25',
            'department' => 'sometimes|string|max:25',
            'pic_name' => 'sometimes|string|max:255',
            'pic_telp_number_1' => 'sometimes|string|max:13',
            'pic_telp_number_2' => 'sometimes|string|max:13',
            'pic_email_1' => 'sometimes|string|max:255|email:rfc,static',
            'pic_email_2' => 'sometimes|string|max:255|email:rfc,static',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'job_position.string' => 'The job position must be a string.',
            'job_position.max' => 'The job position may not be greater than 25 characters.',
            'department.string' => 'The department must be a string.',
            'department.max' => 'The department may not be greater than 25 characters.',
            'pic_name.string' => 'The PIC name must be a string.',
            'pic_name.max' => 'The PIC name may not be greater than 255 characters.',
            'pic_telp_number_1.string' => 'The first PIC telephone number must be a string.',
            'pic_telp_number_1.max' => 'The first PIC telephone number may not be greater than 13 characters.',
            'pic_telp_number_2.string' => 'The second PIC telephone number must be a string.',
            'pic_telp_number_2.max' => 'The second PIC telephone number may not be greater than 13 characters.',
            'pic_email_1.string' => 'The first PIC email must be a string.',
            'pic_email_1.max' => 'The first PIC email may not be greater than 255 characters.',
            'pic_email_1.email' => 'The first PIC email must be a valid email address.',
            'pic_email_2.string' => 'The second PIC email must be a string.',
            'pic_email_2.max' => 'The second PIC email may not be greater than 255 characters.',
            'pic_email_2.email' => 'The second PIC email must be a valid email address.',
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
