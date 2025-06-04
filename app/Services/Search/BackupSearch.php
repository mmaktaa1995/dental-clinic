<?php

namespace App\Services\Search;

use App\Http\Requests\BackupSearchRequest;
use App\Services\BackupService;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BackupSearch extends BaseSearch
{
    public static string $dateColumnFiltered = 'created_at';
    protected ?string $filename = null;
    protected ?string $fromDate = null;
    protected ?string $toDate = null;
    protected BackupService $backupService;

    /**
     * Constructor
     *
     * @param SearchRequest|array $request
     * @param BackupService $backupService
     */
    public function __construct($request, BackupService $backupService)
    {
        if (is_array($request)) {
            // Handle array of filters for export
            $this->filename = $request['filename'] ?? null;
            $this->query = $request['query'] ?? null;
            $this->fromDate = $request['from_date'] ?? null;
            $this->toDate = $request['to_date'] ?? null;
            $this->request = new SearchRequest();
        } else {
            /** @var BackupSearchRequest $request */
            parent::__construct($request);
            $this->filename = $request->input('filename');
            $this->fromDate = $request->input('from_date');
            $this->toDate = $request->input('to_date');
        }

        $this->backupService = $backupService;
    }

    /**
     * Override the getEntries method to handle non-database backups
     *
     * @return LengthAwarePaginator
     */
    public function getEntries(): LengthAwarePaginator
    {
        // Get all backups from the service
        $backups = $this->backupService->listBackups();
        
        // Convert to collection for filtering
        $collection = collect($backups);
        
        // Apply filters
        $filtered = $this->applyFilters($collection);
        
        // Apply sorting
        $sorted = $this->applySort($filtered);
        
        // Apply pagination
        return $this->applyPagination($sorted);
    }

    /**
     * Apply filters to the collection
     *
     * @param Collection $collection
     * @return Collection
     */
    protected function applyFilters(Collection $collection): Collection
    {
        // Filter by filename if provided
        if ($this->filename) {
            $collection = $collection->filter(function ($backup) {
                return str_contains(strtolower($backup['filename']), strtolower($this->filename));
            });
        }
        
        // Filter by query (search in filename)
        if ($this->query) {
            $collection = $collection->filter(function ($backup) {
                return str_contains(strtolower($backup['filename']), strtolower($this->query));
            });
        }
        
        // Filter by date range
        if ($this->fromDate) {
            $collection = $collection->filter(function ($backup) {
                return strtotime($backup['created_at']) >= strtotime($this->fromDate);
            });
        }
        
        if ($this->toDate) {
            $collection = $collection->filter(function ($backup) {
                return strtotime($backup['created_at']) <= strtotime($this->toDate);
            });
        }
        
        return $collection;
    }

    /**
     * Apply sorting to the collection
     *
     * @param Collection $collection
     * @return Collection
     */
    protected function applySort(Collection $collection): Collection
    {
        $orderBy = $this->order['by'] ?? 'created_at';
        $orderDesc = $this->order['desc'] ?? true;
        
        return $collection->sortBy($orderBy, SORT_REGULAR, $orderDesc);
    }

    /**
     * Apply pagination to the collection
     *
     * @param Collection $collection
     * @return LengthAwarePaginator
     */
    protected function applyPagination(Collection $collection): LengthAwarePaginator
    {
        $page = $this->page ?? 1;
        $perPage = $this->perPage ?? 10;
        
        return new LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url()]
        );
    }

    /**
     * This method is required by the abstract parent class but not used
     * since we're handling non-database entities
     *
     * @return EloquentBuilder|QueryBuilder
     */
    protected function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        // This is not used for backups since they're not stored in the database
        // But we need to implement it as it's required by the abstract parent class
        return DB::table('users')->whereRaw('1=0'); // Empty query
    }

    /**
     * Override the applyUserIdFilter method since backups don't have user_id
     *
     * @param EloquentBuilder|QueryBuilder $query
     * @return EloquentBuilder|QueryBuilder
     */
    public function applyUserIdFilter(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        // No user filter for backups
        return $query;
    }
}
