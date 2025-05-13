<?php

namespace App\Http\Requests\IntegrityPact;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;

class IntegrityPactUpdateRequest extends FormRequest
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
            'integrity_pact_file' => 'required|file|mimes:pdf',
            'integrity_pact_desc' => 'sometimes|string|max:255',
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
            'integrity_pact_file.required' => 'The integrity pact file is required.',
            'integrity_pact_file.file' => 'The integrity pact file must be a valid file.',
            'integrity_pact_file.mimes' => 'The integrity pact file must be a PDF.',
            'integrity_pact_desc.string' => 'The description must be a string.',
            'integrity_pact_desc.max' => 'The description may not be greater than 255 characters.',
        ];
    }
}
