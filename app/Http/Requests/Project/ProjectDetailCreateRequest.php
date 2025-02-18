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
            'proposal_total_amount' => 'integer',
            'proposal_status' => 'string',
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
            'project_header_id.required' => 'Project header ID is required.',
            'project_header_id.integer' => 'Project header ID must be an integer.',
            'supplier_id.integer' => 'Supplier ID must be an integer.',
            'proposal_attach.file' => 'Proposal attachment must be a file.',
            'proposal_total_amount.required' => 'Proposal total amount is required.',
            'proposal_total_amount.integer' => 'Proposal total amount must be an integer.',
            'proposal_status.string' => 'Proposal status must be a string.',
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
