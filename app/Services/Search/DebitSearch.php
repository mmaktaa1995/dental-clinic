<?php

declare(strict_types=1);

namespace App\Services\Search;

use App\Http\Requests\DebitsSearchRequest;
use App\Models\Payment;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

/**
 * Service class for searching and filtering debit records.
 */
class DebitSearch extends BaseSearch
{
    public static string $dateColumnFiltered = 'date';

    protected ?int $patientId = null;

    /**
     * Initialize a new DebitSearch instance.
     *
     * @param SearchRequest $request
     */
    public function __construct(SearchRequest $request)
    {
        if ($request->input('patient_id')) {
            $this->patientId = (int)$request->input('patient_id');
        }

        /** @var DebitsSearchRequest $request */
        parent::__construct($request);
    }

    /**
     * Get the base query for searching debits.
     *
     * @return EloquentBuilder|QueryBuilder
     */
    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return Payment::query()
            ->when($this->patientId, $this->applyPatientFilter())
            ->with('patient')
            ->select('payments.*')
            ->when(!$this->patientId, $this->applyNonPatientFilter())
            ->where('remaining_amount', '>', 0)
            ->when(
                $this->request->get('deleted'),
                static fn (EloquentBuilder|QueryBuilder $q) => $q->onlyTrashed()
            );
    }

    /**
     * Apply user ID filter to the query.
     *
     * @param EloquentBuilder|QueryBuilder $query
     * @return EloquentBuilder|QueryBuilder
     */
    public function applyUserIdFilter(
        EloquentBuilder|QueryBuilder $query
    ): EloquentBuilder|QueryBuilder {
        return $query->where('payments.user_id', auth()->id());
    }

    /**
     * Apply patient-specific filters to the query.
     *
     * @return \Closure
     */
    private function applyPatientFilter(): \Closure
    {
        return function (EloquentBuilder|QueryBuilder $query): void {
            $query->where('patient_id', $this->patientId)
                ->with([
                    'visit' => function (EloquentBuilder $query): void {
                        $query->with('payment')
                            ->when(
                                $this->request->get('deleted'),
                                static fn (EloquentBuilder $q) => $q->withTrashed()
                            );
                    }
                ]);
        };
    }

    /**
     * Apply filters for non-patient specific searches.
     *
     * @return \Closure
     */
    private function applyNonPatientFilter(): \Closure
    {
        return function (EloquentBuilder|QueryBuilder $query): void {
            $query->leftJoin('patients', 'patients.id', 'payments.patient_id')
                ->when(
                    $this->query,
                    static fn (EloquentBuilder $q, string $searchTerm) =>
                        $q->where('patients.name', 'like', '%' . $searchTerm . '%')
                );
        };
    }
}
