<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
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
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ];

        if ($this->isMethod('POST')) {
            $rules['name'] = ['required', 'string', 'max:255', 'unique:roles'];
            $rules['slug'] = ['sometimes', 'string', 'max:255', 'unique:roles'];
        } else {
            $rules['name'] = [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($this->role->id),
            ];
            $rules['slug'] = [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('roles')->ignore($this->role->id),
            ];
        }

        return $rules;
    }
}
