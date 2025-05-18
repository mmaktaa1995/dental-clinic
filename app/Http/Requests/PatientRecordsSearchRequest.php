<?php

namespace App\Http\Requests;

use App\Services\Search\Base\SearchRequest;

class PatientRecordsSearchRequest extends SearchRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'type' => ['nullable', 'string', 'in:diagnosis,symptoms'],
        ]);
    }
}
