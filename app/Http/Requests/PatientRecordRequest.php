<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRecordRequest extends FormRequest
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
            'record_date' => ['required' , 'date'],
            'symptoms' => ['required_without:diagnosis' , 'string'],
            'diagnosis' => ['required_without:symptoms' , 'string'],
            'teeth' => ['nullable' , 'array'],
            'teeth.*' => ['numeric' , 'exists:teeth,id'],
        ];
    }
}
