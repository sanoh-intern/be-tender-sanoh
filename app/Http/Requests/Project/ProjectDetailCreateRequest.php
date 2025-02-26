<?php

namespace App\Http\Requests\Project;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectDetailCreateRequest extends FormRequest
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
            'project_header_id' => 'required|integer',
            'supplier_id' => 'integer',
            'proposal_attach' => 'file',
            'proposal_total_amount' => 'integer|min:0',
            'proposal_status' => 'nullable|boolean',
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
            'project_header_id.required' => 'The project header ID is required.',
            'project_header_id.integer' => 'The project header ID must be an integer.',
            'supplier_id.integer' => 'The supplier ID must be an integer.',
            'proposal_attach.file' => 'The proposal attachment must be a file.',
            'proposal_total_amount.integer' => 'The proposal total amount must be an integer.',
            'proposal_total_amount.min' => 'The proposal total amount must be at least 0.',
            'proposal_status.required' => 'The proposal status is required.',
            'proposal_status.boolean' => 'The proposal status must be true or false.',
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
