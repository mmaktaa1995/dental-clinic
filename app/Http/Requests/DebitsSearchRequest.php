<?php

namespace App\Http\Requests;

use App\Services\Search\Base\SearchRequest;

class DebitsSearchRequest extends SearchRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        return array_merge($rules, [
            'deleted' => ['sometimes', 'boolean'],
            'patient_id' => ['sometimes', 'numeric', 'exists:patients,id'],
        ]);
    }
}
