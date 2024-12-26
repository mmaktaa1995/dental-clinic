<?php

namespace App\Services\Search;

use App\Http\Requests\DebitsSearchRequest;
use App\Http\Requests\PatientSearchRequest;
use App\Models\Payment;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class DebitSearch extends BaseSearch
{
    public static string $dateColumnFiltered = 'date';
    protected ?int $patient_id = null;

    public function __construct(SearchRequest $request)
    {
        if ($request->input('patient_id')) {
            $this->patient_id = $request->input('patient_id');
        }
        /** @var DebitsSearchRequest $request */
        parent::__construct($request);
    }

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return Payment::when($this->patient_id, function ($query) {
                $query->where('patient_id', $this->patient_id)
                    ->with(['visit' => function ($query) {
                        $query->with('payment')->when($this->request->get('deleted'), fn($query) => $query->withTrashed());
                    }]);
            })
            ->with('patient')
            ->select("payments.*")
            ->when(!$this->patient_id, function ($query) {
                $query->leftJoin('patients', 'patients.id', 'payments.patient_id')
                    ->when($this->query, function ($query) {
                        $query->where('patients.name', 'like', '%' . $this->query . '%');
                    });
            })
            ->where('remaining_amount', '>', 0)
            ->when($this->request->get('deleted'), fn($query) => $query->onlyTrashed());
    }

    public function applyUserIdFilter(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query->where('payments.user_id', auth()->id());
    }
}
