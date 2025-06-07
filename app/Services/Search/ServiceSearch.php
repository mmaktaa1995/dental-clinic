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
    protected ?string $name = null;
    protected ?float $minPrice = null;
    protected ?float $maxPrice = null;

    /**
     * ServiceSearch constructor.
     *
     * @param SearchRequest|array $request
     */
    public function __construct($request)
    {
        if (is_array($request)) {
            // Handle array of filters for export
            $this->name = $request['name'] ?? null;
            $this->query = $request['query'] ?? null;
            $this->minPrice = $request['min_price'] ?? null;
            $this->maxPrice = $request['max_price'] ?? null;
            $this->request = new SearchRequest();
        } else {
            parent::__construct($request);
            $this->name = $request->input('name');
            $this->minPrice = $request->input('min_price');
            $this->maxPrice = $request->input('max_price');
        }
    }

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return Service::query()
            ->when($this->name, fn($query) => $query->where('name', 'like', '%' . $this->name . '%'))
            ->when($this->query, fn($query) => $query->where('name', 'like', '%' . $this->query . '%'))
            ->when($this->minPrice, fn($query) => $query->where('price', '>=', $this->minPrice))
            ->when($this->maxPrice, fn($query) => $query->where('price', '<=', $this->maxPrice));
    }
}
