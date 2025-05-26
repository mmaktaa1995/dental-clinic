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
    protected ?string $name = null;
    protected ?string $gender = null;
    protected ?string $fromDate = null;
    protected ?string $toDate = null;
    
    /**
     * PatientSearch constructor.
     *
     * @param SearchRequest|array $request
     */
    public function __construct($request)
    {
        if (is_array($request)) {
            // Handle array of filters for export
            $this->fileNumber = $request['file_number'] ?? null;
            $this->name = $request['name'] ?? null;
            $this->query = $request['query'] ?? null;
            $this->gender = $request['gender'] ?? null;
            $this->fromDate = $request['from_date'] ?? null;
            $this->toDate = $request['to_date'] ?? null;
            $this->request = new PatientSearchRequest();
        } else {
            /** @var PatientSearchRequest $request */
            parent::__construct($request);
            $this->fileNumber = $request->input('file_number');
            $this->name = $request->input('name');
            $this->gender = $request->input('gender');
            $this->fromDate = $request->input('from_date');
            $this->toDate = $request->input('to_date');
        }
    }

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return Patient::query()
            ->when($this->fileNumber, fn($query) => $query->where('file_number', 'like', '%' . $this->fileNumber . '%'))
            ->when($this->name, fn($query) => $query->where('name', 'like', '%' . $this->name . '%'))
            ->when($this->query, fn($query) => $query->where('name', 'like', '%' . $this->query . '%'))
            ->when($this->gender, fn($query) => $query->where('gender', $this->gender))
            ->when($this->fromDate, fn($query) => $query->whereDate('created_at', '>=', $this->fromDate))
            ->when($this->toDate, fn($query) => $query->whereDate('created_at', '<=', $this->toDate))
            ->when($this->request && $this->request->get('deleted'), fn($query) => $query->onlyTrashed());
    }
}
