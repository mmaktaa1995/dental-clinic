<?php

namespace App\Services\Search;

use App\Http\Requests\PatientSearchRequest;
use App\Models\Patient;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class PatientApiListSearch extends PatientSearch
{
   protected function applySelectColumns($query, $columns = ['*']): QueryBuilder|EloquentBuilder
   {
       return parent::applySelectColumns($query, ['id', 'name']);
   }
}
