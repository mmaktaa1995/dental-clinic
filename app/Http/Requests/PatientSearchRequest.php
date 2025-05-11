<?php

namespace App\Http\Requests;

use App\Services\Search\Base\SearchRequest;

class PatientSearchRequest extends SearchRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'deleted' => ['sometimes', 'boolean'],
            'file_number' => ['sometimes', 'nullable', 'string'],
        ]);
    }
}
