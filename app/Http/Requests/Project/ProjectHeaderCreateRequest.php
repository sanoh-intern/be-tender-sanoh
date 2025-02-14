<?php

namespace App\Http\Requests\Project;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectHeaderCreateRequest extends FormRequest
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
        return $this->permissibleRole('Admin-Purchasing');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_name' => 'required|string',
            'project_type' => 'required|string',
            'project_description' => 'string',
            'project_attach' => 'file',
            'registration_due_at' => 'required|date',
            'invite_email' => 'array',
            'invite_email.*' => 'email:rfc,strict',
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
            'project_name.required' => 'The project name is required.',
            'project_name.string' => 'The project name must be a string.',
            'project_type.required' => 'The project type is required.',
            'project_type.string' => 'The project type must be a string.',
            'project_description.string' => 'The project description must be a string.',
            'project_attach.file' => 'The project attachment must be a file.',
            'registration_due_at.required' => 'The registration due date is required.',
            'registration_due_at.date' => 'The registration due date must be a valid date.',
            'invite_email.array' => 'The invite email must be an array.',
            'invite_email.*.email' => 'Each invite email must be a valid email address.',
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
