<?php

namespace App\Services\Search;

use App\Http\Requests\PatientSearchRequest;
use App\Models\PatientFile;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class FileSearch extends BaseSearch
{
    public static string $dateColumnFiltered = 'created_at';
    protected ?int $patient_id = null;

    public function __construct(SearchRequest $request)
    {
        if ($request->input('patient_id')) {
            $this->patient_id = $request->input('patient_id');
        }
        /** @var PatientSearchRequest $request */
        parent::__construct($request);
    }

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return PatientFile::when($this->patient_id, function ($query) {
            $query->where('patient_id', $this->patient_id);
        })
            ->leftJoin('patients', 'patients.id', 'patient_files.patient_id')
            ->select("patient_files.*");
    }

    public function applyUserIdFilter(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->where('patients.user_id', auth()->id());
    }
}
