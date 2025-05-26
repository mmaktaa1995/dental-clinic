<?php

namespace App\Http\Requests;

use App\Services\Search\Base\SearchRequest;

class UserSearchRequest extends SearchRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        return array_merge($rules, [
            'role' => ['sometimes', 'string'],
            'email' => ['sometimes', 'string'],
        ]);
    }
}
