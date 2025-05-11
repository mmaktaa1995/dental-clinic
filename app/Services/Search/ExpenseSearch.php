<?php

namespace App\Services\Search;

use App\Models\Expense;
use App\Services\Search\Base\BaseSearch;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ExpenseSearch extends BaseSearch
{
    public static string $dateColumnFiltered = 'created_at';

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return Expense::query()
            ->when($this->query, fn($query) => $query->where('name', 'like', '%' . $this->query . '%'));
    }
}
