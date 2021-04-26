<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstructorRequest extends FormRequest
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
            'first_name' => ['required', 'max:255'],
            'last_name' => ['required', 'max:255'],
            'reg_year' => ['required', 'numeric', 'max:' . date('Y')],
            'gender' => ['required', 'in:male,female'],
            'address' => ['required', 'max:255'],
            'mobile_number' => ['required', 'max:50', 'string'],
        ];
    }
}
