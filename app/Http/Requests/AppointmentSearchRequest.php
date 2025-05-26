<?php

namespace App\Http\Requests;

use App\Services\Search\Base\SearchRequest;

class AppointmentSearchRequest extends SearchRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'startDate' => ['sometimes', 'nullable', 'date'],
            'endDate' => ['sometimes', 'nullable', 'date', 'after_or_equal:startDate'],
            'patient_id' => ['sometimes', 'nullable', 'exists:patients,id'],
        ]);
    }
}
