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
            'file_number' => [$this->segment(4) ? 'sometimes' : 'required', 'unique:patients,file_number,' . $this->segment(4)],
            'name' => ['required', 'string', 'max:255'],
            'age' => ['nullable', 'numeric', 'gt:0'],
            'gender' => ['nullable', 'numeric', 'in:1,2'],
            'phone' => ['nullable', 'numeric'],
            'mobile' => ['nullable', 'numeric'],
            // Payment details
            'date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric'],
            'total_amount' => ['nullable', 'numeric'],

            // Patient records
            'symptoms' => ['nullable', 'array'],
            'symptoms.*.record_date' => ['required', 'date'],
            'symptoms.*.symptoms' => ['required', 'string'],
            'diagnosis' => ['nullable', 'array'],
            'diagnosis.*.record_date' => ['required', 'date'],
            'diagnosis.*.diagnosis' => ['required', 'string'],
        ];
    }
}
