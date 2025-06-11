<?php

namespace App\Http\Requests\CompanyProfile;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;

class CompanyProfileUpdateFileRequest extends FormRequest
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
        return $this->permissibleRole('supplier', 'purchasing', 'review');;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tax_id_file' => 'sometimes|file|mimes:pdf',
            'company_photo' => 'sometimes|file|mimes:png,jpg,jpeg',
            'skpp_file' => 'sometimes|file|mimes:pdf',
        ];
    }

    public function messages(): array
    {
        return [
            'tax_id_file.file' => 'The tax ID file must be a valid file.',
            'tax_id_file.mimes' => 'The tax ID file must be a PDF file.',
            'company_photo.file' => 'The company photo must be a valid file.',
            'company_photo.mimes' => 'The company photo must be a file of type: png, jpg, jpeg.',
            'skpp_file.file' => 'The SKPP file must be a valid file.',
            'skpp_file.mimes' => 'The SKPP file must be a PDF file.',
        ];
    }
}
