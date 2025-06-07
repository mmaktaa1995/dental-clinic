<?php

namespace App\Services\Search;

use App\Http\Requests\RoleSearchRequest;
use App\Models\Role;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class RoleSearch extends BaseSearch
{
    protected ?string $permission = null;

    public function __construct(SearchRequest $request)
    {
        /** @var RoleSearchRequest $request */
        parent::__construct($request);
        $this->permission = $request->input('permission');
    }

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return Role::query()
            ->with('permissions')
            ->when($this->query, fn($query) => $query->where('name', 'like', '%' . $this->query . '%')
                ->orWhere('slug', 'like', '%' . $this->query . '%'))
            ->when($this->permission, function ($query) {
                return $query->whereHas('permissions', function ($q) {
                    $q->where('slug', $this->permission);
                });
            });
    }

    // Override to remove user_id filter since roles don't have a user_id column
    public function applyUserIdFilter(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query;
    }
}
