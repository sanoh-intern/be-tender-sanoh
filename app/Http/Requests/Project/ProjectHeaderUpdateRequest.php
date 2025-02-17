<?php

namespace App\Http\Requests\Project;

use App\Trait\AuthorizationRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectHeaderUpdateRequest extends FormRequest
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
        return $this->permissibleRole('admin-purchasing', 'admin-presdir');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_name' => 'string',
            'project_status' => 'string|in:Ongoing,Final',
            'project_type' => 'string|in:Public,Private',
            'project_description' => 'string',
            'project_attach' => 'file',
            'project_winner' => 'string',
            'registration_status' => 'string|in:Open,Closed',
            'registration_due_at' => 'date',
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
            'project_name.string' => 'The project name must be a valid string.',
            'project_status.string' => 'The project status must be a valid string.',
            'project_status.in' => 'The project status must be either Ongoing or Final.',
            'project_type.string' => 'The project type must be a valid string.',
            'project_type.in' => 'The project type must be either Public or Private.',
            'project_description.string' => 'The project description must be a valid string.',
            'project_attach.file' => 'The project attachment must be a valid file.',
            'project_winner.string' => 'The project winner must be a valid string.',
            'registration_status.string' => 'The registration status must be a valid string.',
            'registration_status.in' => 'The registration status must be either Open or Closed.',
            'registration_due_at.date' => 'The registration due date must be a valid date.',
            'invite_email.array' => 'The invite email must be a valid array.',
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
