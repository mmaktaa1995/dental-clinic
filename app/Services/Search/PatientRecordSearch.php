<?php

namespace App\Services\Search;

use App\Http\Requests\PatientRecordsSearchRequest;
use App\Models\PatientRecord;
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
            ->with(['affectedTeeth'])
            ->leftJoin('patients', 'patients.id', 'patient_records.patient_id')
            ->when($this->request->get('type'), function ($query) {
                if ($this->request->get('type') === 'symptoms') {
                    $query->whereNotNull('symptoms');
                }
                if ($this->request->get('type') === 'diagnosis') {
                    $query->whereNotNull('diagnosis');
                }
            })
            ->select('patient_records.*');
    }

    public function applyUserIdFilter(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->where('patients.user_id', auth()->id());
    }
}
