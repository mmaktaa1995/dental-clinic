<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Requests\RoleSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Search\RoleSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * Display a listing of the roles.
     *
     * @param  RoleSearchRequest  $request
     * @return JsonResponse
     */
    public function list(RoleSearchRequest $request): JsonResponse
    {
        $roleSearch = new RoleSearch($request);

        return response()->json(BaseCollection::make($roleSearch->getEntries(), RoleResource::class));
    }

    /**
     * Store a newly created role in storage.
     *
     * @param  RoleRequest  $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {
        $role = DB::transaction(function () use ($request) {
            $role = Role::create($request->only(['name', 'slug']));
            
            // Assign permissions if provided
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }
            
            return $role;
        });

        return response()->json([
            'message' => __('app.success'),
            'role' => RoleResource::make($role->load('permissions'))
        ]);
    }

    /**
     * Display the specified role.
     *
     * @param  Role  $role
     * @return JsonResponse
     */
    public function show(Role $role): JsonResponse
    {
        return response()->json(RoleResource::make($role->load('permissions')));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  RoleRequest  $request
     * @param  Role  $role
     * @return JsonResponse
     */
    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        DB::transaction(function () use ($request, $role) {
            $role->update($request->only(['name', 'slug']));

            // Sync permissions if provided
            if ($request->has('permissions')) {
                $role->permissions()->sync($request->permissions);
            }
        });

        return response()->json([
            'message' => __('app.success'),
            'role' => RoleResource::make($role->fresh()->load('permissions'))
        ]);
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  Role  $role
     * @return JsonResponse
     */
    public function destroy(Role $role): JsonResponse
    {
        // Prevent deleting the admin role
        if ($role->slug === 'admin') {
            return response()->json([
                'message' => 'لا يمكن حذف دور المسؤول'
            ], 403);
        }

        DB::transaction(function () use ($role) {
            // Detach all permissions before deleting
            $role->permissions()->detach();
            
            // Delete the role
            $role->delete();
        });

        return response()->json([
            'message' => __('app.success')
        ]);
    }

    /**
     * Get all available permissions.
     *
     * @return JsonResponse
     */
    public function permissions(): JsonResponse
    {
        $permissions = Permission::orderBy('name')->get();
        return response()->json($permissions);
    }

    /**
     * Get all available permissions.
     *
     * @return JsonResponse
     */
    public function listRoles(): JsonResponse
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }
}
