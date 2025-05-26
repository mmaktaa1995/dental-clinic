<?php

namespace App\Http\Requests;

use App\Services\Search\Base\SearchRequest;

class RoleSearchRequest extends SearchRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'permission' => ['sometimes', 'nullable', 'string'],
        ]);
    }
}
