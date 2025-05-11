<?php

namespace App\Services\Search;

use App\Http\Requests\PatientSearchRequest;
use App\Models\Patient;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class PatientSearch extends BaseSearch
{
    protected ?string $fileNumber = null;
    public function __construct(SearchRequest $request)
    {
        /** @var PatientSearchRequest $request */
        parent::__construct($request);
        $this->fileNumber = $request->input('file_number');
    }

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return Patient::query()
            ->when($this->fileNumber, fn($query) => $query->where('file_number', 'like', '%' . $this->fileNumber . '%'))
            ->when($this->query, fn($query) => $query->where('name', 'like', '%' . $this->query . '%'))
            ->when($this->request->get('deleted'), fn($query) => $query->onlyTrashed());
    }
}
