<?php

namespace App\Services\Search;

use App\Http\Requests\PatientRecordsSearchRequest;
use App\Http\Requests\PatientSearchRequest;
use App\Models\PatientRecord;
use App\Models\Payment;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class PatientRecordSearch extends BaseSearch
{
    public static string $dateColumnFiltered = 'record_date';
    protected ?int $patient_id = null;

    public function __construct(SearchRequest $request)
    {
        if ($request->input('patient_id')) {
            $this->patient_id = $request->input('patient_id');
        }
        /** @var PatientRecordsSearchRequest $request */
        parent::__construct($request);
    }

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return PatientRecord::when($this->patient_id, function ($query) {
                $query->where('patient_id', $this->patient_id);
            })
            ->leftJoin('patients', 'patients.id', 'patient_records.patient_id')
            ->select('patient_records.*');
    }

    public function applyUserIdFilter(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->where('patients.user_id', auth()->id());
    }
}
