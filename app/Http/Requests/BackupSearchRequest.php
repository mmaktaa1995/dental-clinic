<?php

namespace App\Http\Requests;

use App\Services\Search\Base\SearchRequest;

class BackupSearchRequest extends SearchRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}
