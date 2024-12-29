<?php

namespace App\Services\Search;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class PatientApiListSearch extends PatientSearch
{
    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return parent::getBaseQuery()->select(['id', 'name']);
    }
}
