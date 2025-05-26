<?php

namespace App\Services\Search;

use App\Models\Expense;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ExpenseSearch extends BaseSearch
{
    public static string $dateColumnFiltered = 'date';
    protected ?string $title = null;
    protected ?float $minAmount = null;
    protected ?float $maxAmount = null;
    protected ?string $expenseDate = null;
    
    /**
     * ExpenseSearch constructor.
     *
     * @param SearchRequest|array $request
     */
    public function __construct($request)
    {
        if (is_array($request)) {
            // Handle array of filters for export
            $this->title = $request['title'] ?? null;
            $this->query = $request['query'] ?? null;
            $this->minAmount = $request['min_amount'] ?? null;
            $this->maxAmount = $request['max_amount'] ?? null;
            $this->expenseDate = $request['date'] ?? null;
            $this->request = new SearchRequest();
        } else {
            parent::__construct($request);
            $this->title = $request->input('title');
            $this->minAmount = $request->input('min_amount');
            $this->maxAmount = $request->input('max_amount');
            $this->expenseDate = $request->input('date');
        }
    }
    
    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return Expense::query()
            ->when($this->title, fn($query) => $query->where('title', 'like', '%' . $this->title . '%'))
            ->when($this->query, fn($query) => $query->where('title', 'like', '%' . $this->query . '%')
                ->orWhere('description', 'like', '%' . $this->query . '%'))
            ->when($this->minAmount, fn($query) => $query->where('amount', '>=', $this->minAmount))
            ->when($this->maxAmount, fn($query) => $query->where('amount', '<=', $this->maxAmount))
            ->when($this->expenseDate, fn($query) => $query->whereDate('date', $this->expenseDate));
    }
}
