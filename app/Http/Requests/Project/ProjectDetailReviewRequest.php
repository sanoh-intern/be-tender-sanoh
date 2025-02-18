<?php

namespace App\Http\Requests\Project;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;

class ProjectDetailReviewRequest extends FormRequest
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
        return $this->permissibleRole('admin-purchasing','admin-presdir');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'proposal_comment' => 'required|string',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'proposal_comment.required' => 'The proposal comment is required.',
            'proposal_comment.string' => 'The proposal comment must be a string.',
        ];
    }
}
