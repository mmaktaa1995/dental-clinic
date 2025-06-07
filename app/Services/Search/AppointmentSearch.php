<?php

namespace App\Services\Search;

use App\Models\Appointment;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class AppointmentSearch extends BaseSearch
{
    public static string $dateColumnFiltered = 'date';
    protected ?string $startDate = null;
    protected ?string $endDate = null;
    protected ?int $patientId = null;
    protected ?string $notes = null;

    /**
     * AppointmentSearch constructor.
     *
     * @param SearchRequest|array $request
     */
    public function __construct($request)
    {
        if (is_array($request)) {
            // Handle array of filters for export
            $this->startDate = $request['startDate'] ?? null;
            $this->endDate = $request['endDate'] ?? null;
            $this->patientId = $request['patient_id'] ?? null;
            $this->notes = $request['notes'] ?? null;
            $this->query = $request['query'] ?? null;
            $this->request = new SearchRequest();
        } else {
            /** @var AppointmentSearchRequest $request */
            parent::__construct($request);
            $this->startDate = $request->input('startDate');
            $this->endDate = $request->input('endDate');
            $this->patientId = $request->input('patient_id');
            $this->notes = $request->input('notes');
        }
    }

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return Appointment::query()
            ->where('user_id', auth()->id())
            ->whereDate('date', ">=", $this->startDate)
            ->whereDate('date', "<=", $this->endDate);
    }
}
