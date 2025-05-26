<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['exists:roles,id'],
        ];

        if ($this->isMethod('POST')) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
            $rules['password'] = ['required', 'string', 'min:8'];
        } else {
            $rules['email'] = [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user->id),
            ];
            $rules['password'] = ['sometimes', 'nullable', 'string', 'min:8'];
        }

        return $rules;
    }
}
