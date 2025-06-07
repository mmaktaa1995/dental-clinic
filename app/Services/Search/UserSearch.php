<?php

namespace App\Services\Search;

use App\Http\Requests\UserSearchRequest;
use App\Models\User;
use App\Services\Search\Base\BaseSearch;
use App\Services\Search\Base\SearchRequest;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class UserSearch extends BaseSearch
{
    protected ?string $email = null;
    protected ?string $role = null;

    /**
     * UserSearch constructor.
     *
     * @param SearchRequest|array $request
     */
    public function __construct($request)
    {
        if (is_array($request)) {
            // Handle array of filters for export
            $this->email = $request['email'] ?? null;
            $this->query = $request['query'] ?? null;
            $this->role = $request['role'] ?? null;
            $this->request = new SearchRequest();
        } else {
            /** @var UserSearchRequest $request */
            parent::__construct($request);
            $this->email = $request->input('email');
            $this->role = $request->input('role');
        }
    }

    public function getBaseQuery(): EloquentBuilder|QueryBuilder
    {
        return User::query()
            ->with('roles.permissions')
            ->when($this->email, fn($query) => $query->where('email', 'like', '%' . $this->email . '%'))
            ->when($this->query, fn($query) => $query->where('name', 'like', '%' . $this->query . '%'))
            ->when($this->role, function ($query) {
                return $query->whereHas('roles', function ($q) {
                    $q->where('slug', $this->role);
                });
            });
    }

    // Override to remove user_id filter since users don't have a user_id column
    public function applyUserIdFilter(EloquentBuilder|QueryBuilder $query): EloquentBuilder|QueryBuilder
    {
        return $query;
    }
}
