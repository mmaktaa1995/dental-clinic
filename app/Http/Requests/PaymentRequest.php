<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
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
        return [
            'patient_id' => ['required', 'exists:patients,id'],
            'payment_id' => ['nullable', 'exists:payments,id'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric'],
            'remaining_amount' => ['nullable', 'numeric'],
            'teeth_ids' => ['nullable', 'array'],
            'teeth_ids.*' => ['numeric' , 'exists:teeth,id'],
        ];
    }
}
