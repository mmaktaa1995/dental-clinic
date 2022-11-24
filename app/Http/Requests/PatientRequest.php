<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
            'file_number' => [$this->segment(3) ? 'sometimes' : 'required', 'unique:patients,file_number,' . $this->segment(3)],
            'name' => ['required', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric'],
            'age' => ['nullable', 'numeric'],
            'phone' => ['nullable', 'numeric'],
            'mobile' => ['nullable', 'numeric'],
        ];
    }
}
