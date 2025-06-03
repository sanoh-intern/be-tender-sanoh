<?php

namespace App\Http\Requests\PersonInCharge;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PicCreateRequest extends FormRequest
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
            'data' => 'array',
            'data.*.job_position' => 'sometimes|string|max:25',
            'data.*.department' => 'sometimes|string|max:25',
            'data.*.pic_name' => 'sometimes|string|max:255',
            'data.*.pic_telp_number_1' => 'sometimes|string|max:13|nullable',
            'data.*.pic_telp_number_2' => 'sometimes|string|max:13|nullable',
            'data.*.pic_email_1' => 'sometimes|string|max:255|email:rfc,static|nullable',
            'data.*.pic_email_2' => 'sometimes|string|max:255|email:rfc,static|nullable',
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
            'data.required' => 'The data field is required.',
            'data.array' => 'The data field must be an array.',
            'data.*.job_position.string' => 'Each job position must be a string.',
            'data.*.job_position.max' => 'Each job position may not be greater than 25 characters.',
            'data.*.department.string' => 'Each department must be a string.',
            'data.*.department.max' => 'Each department may not be greater than 25 characters.',
            'data.*.pic_name.string' => 'Each PIC name must be a string.',
            'data.*.pic_name.max' => 'Each PIC name may not be greater than 255 characters.',
            'data.*.pic_telp_number_1.string' => 'Each first PIC telephone number must be a string.',
            'data.*.pic_telp_number_1.max' => 'Each first PIC telephone number may not be greater than 13 characters.',
            'data.*.pic_telp_number_2.string' => 'Each second PIC telephone number must be a string.',
            'data.*.pic_telp_number_2.max' => 'Each second PIC telephone number may not be greater than 13 characters.',
            'data.*.pic_email_1.string' => 'Each first PIC email must be a string.',
            'data.*.pic_email_1.max' => 'Each first PIC email may not be greater than 255 characters.',
            'data.*.pic_email_1.email' => 'Each first PIC email must be a valid email address.',
            'data.*.pic_email_2.string' => 'Each second PIC email must be a string.',
            'data.*.pic_email_2.max' => 'Each second PIC email may not be greater than 255 characters.',
            'data.*.pic_email_2.email' => 'Each second PIC email must be a valid email address.',
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
