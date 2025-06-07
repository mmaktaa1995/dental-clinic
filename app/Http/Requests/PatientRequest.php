<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    /**
     * Get the patient ID from the route if it exists.
     *
     * @return integer|string
     */
    protected function getPatientId()
    {
        $patient = $this->route('patient');
        return $patient ? (is_object($patient) ? $patient->id : $patient) : 'NULL';
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return boolean
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
        $patientId = $this->getPatientId();

        return [
            'file_number' => [
                $patientId !== 'NULL' ? 'sometimes' : 'required',
                'unique:patients,file_number,' . $patientId
            ],
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:patients,name,' . $patientId . ',id,user_id,' . auth()->id()
            ],
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
            'diagnosis.*.teeth_ids' => ['nullable', 'array'],
            'diagnosis.*.teeth_ids.*' => ['numeric' , 'exists:teeth,id'],
        ];
    }
}
