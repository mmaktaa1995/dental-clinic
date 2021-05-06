<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => ['required', 'max:255', 'unique:students,username,' . $this->segment(3), 'regex:/^\S*$/u'],
            'email' => [$this->segment(3) ? 'nullable' : 'required', 'max:255', 'unique:students', 'email'],
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'reg_year' => ['required', 'numeric', 'max:' . date('Y')],
            'gender' => ['required', 'in:male,female'],
            'address' => ['required', 'max:255'],
            'password' => [$this->segment(3) ? 'nullable' : 'required', 'max:255', 'min:8'],
            'mobile_number' => ['required', 'max:50', 'string'],
        ];
    }
}
