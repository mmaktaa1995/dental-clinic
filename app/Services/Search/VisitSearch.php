<?php

namespace App\Services\Search;

use App\Http\Requests\PaymentSearchRequest;
use App\Models\Payment;
use App\Models\Visit;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class VisitSearch extends BaseSearch
{
    public static string $dateColumnFiltered = 'date';
    protected ?int $patient_id = null;

    public function __construct(SearchRequest $request)
    {
        if ($request->input('patient_id')) {
            $this->patient_id = $request->input('patient_id');
        }
        /** @var PaymentSearchRequest $request */
        parent::__construct($request);
    }

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return Visit::when($this->patient_id, function ($query) {
            $query->where('visits.patient_id', $this->patient_id);
        })
            ->with(['patient', 'payment'])
            ->select("visits.*")
            ->when(!$this->patient_id, function ($query) {
                $query->leftJoin('patients', 'patients.id', 'visits.patient_id')
                    ->when($this->query, function ($query) {
                        $query->where('patients.name', 'like', '%' . $this->query . '%');
                    });
            })
            ->when($this->request->get('deleted'), fn($query) => $query->onlyTrashed());
    }

    public function applyUserIdFilter(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->where('visits.user_id', auth()->id());
    }

    protected function applySqlSort(EloquentBuilder|QueryBuilder $query): QueryBuilder|EloquentBuilder
    {
        if (!($this->order['by'] ?? false)) {
            return $query;
        }
        if ($this->order['by'] === 'amount') {
            $query->leftJoin('payments', 'payments.visit_id', 'visits.id');
            return $query->orderBy('payments.amount', $this->order['desc'] ? 'desc' : 'asc');
        }

        return $query->orderBy($this->order['by'], $this->order['desc'] ? 'desc' : 'asc');
    }
}
