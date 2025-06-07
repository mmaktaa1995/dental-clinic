<?php

namespace App\Services\Search\Base;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseSearch
{
    public static string $dateColumnFiltered = 'created_at';
    protected int $perPage = 10;
    protected int $page = 1;
    protected array $order;
    protected ?string $query = null;

    public function __construct(protected SearchRequest $request)
    {
        $this->page = $request->get('page');
        $this->perPage = $request->get('per_page');
        $this->query = $request->get('query');

        $order = $request->input('order');
        if (!is_array($order)) {
            $order = ['by' => null, 'desc' => false];
        }
        $this->order = $order;
    }

    public function getEntries(): LengthAwarePaginator
    {
        $query = $this->getQuery();
        $query = $this->applySqlPagination($query);

        return $query;
    }

    public function getQuery(): EloquentBuilder|QueryBuilder
    {
        $query = $this->getBaseQuery();
//        $query = $this->applySelectColumns($query);
        $query = $this->applyUserIdFilter($query);
        $query = $this->applyDatesFilter($query);
        $query = $this->applySqlSort($query);

        return $query;
    }

    abstract protected function getBaseQuery(): EloquentBuilder|QueryBuilder;

    public function applyUserIdFilter(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->where('user_id', '=', \Auth::id());
    }

    /**
     * Apply date range filtering to the query.
     *
     * @param EloquentBuilder|QueryBuilder $query
     * @return EloquentBuilder|QueryBuilder
     */
    public function applyDatesFilter(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        $fromDate = $this->request->get('from_date');
        $toDate = $this->request->get('to_date');
        $date = $this->request->get('date');
        $dateColumn = static::$dateColumnFiltered;

        return $query->when(
            $toDate && !$fromDate,
            fn ($q) => $q->whereDate($dateColumn, '<=', $toDate)
        )->when(
            $toDate && $fromDate,
            fn ($q) => $q->whereDate($dateColumn, '>=', $fromDate)
                        ->whereDate($dateColumn, '<=', $toDate)
        )->when(
            $date,
            fn ($q) => $q->whereDate($dateColumn, $date)
        );
    }

    protected function applySqlSort(QueryBuilder|EloquentBuilder $query): QueryBuilder|EloquentBuilder
    {
        if (!($this->order['by'] ?? false)) {
            return $query;
        }

        return $query->orderBy($this->order['by'], $this->order['desc'] ? 'desc' : 'asc');
    }

    protected function applySqlPagination(QueryBuilder|EloquentBuilder $query): LengthAwarePaginator
    {
        return $query->paginate($this->perPage);
    }

    /**
     * Apply column selection to the query.
     *
     * @param EloquentBuilder|QueryBuilder $query
     * @param array|string                 $columns
     * @return QueryBuilder|EloquentBuilder
     */
    protected function applySelectColumns(
        EloquentBuilder|QueryBuilder $query,
        array|string $columns = ['*']
    ): QueryBuilder|EloquentBuilder {
        return $query->select($columns);
    }
}
