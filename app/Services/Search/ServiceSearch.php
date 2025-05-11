<?php

namespace App\Services\Search;

use App\Http\Requests\PatientSearchRequest;
use App\Models\PatientFile;
use App\Models\Service;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ServiceSearch extends BaseSearch
{
    public static string $dateColumnFiltered = 'created_at';

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return Service::query()
            ->when($this->query, fn($query) => $query->where('name', 'like', '%' . $this->query . '%'));
    }
}
