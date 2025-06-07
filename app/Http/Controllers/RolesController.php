<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Requests\RoleSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Search\RoleSearch;
use App\Services\SensitiveOperationsLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * Display a listing of the roles.
     *
     * @param  RoleSearchRequest $request
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
     * @param  RoleRequest $request
     * @return JsonResponse
     */
    public function store(RoleRequest $request): JsonResponse
    {
        // Log the attempt to create a role
        SensitiveOperationsLogger::attempt('create', 'role', null, [
            'name' => $request->name,
            'slug' => $request->slug,
            'permissions' => $request->permissions ?? []
        ]);

        try {
            $role = DB::transaction(function () use ($request) {
                $role = Role::create($request->only(['name', 'slug']));

                // Assign permissions if provided
                if ($request->has('permissions')) {
                    $role->permissions()->sync($request->permissions);
                }

                return $role;
            });

            // Log successful role creation
            SensitiveOperationsLogger::success('create', 'role', $role->id, [
                'name' => $role->name,
                'slug' => $role->slug,
                'permissions' => $role->permissions->pluck('name')->toArray()
            ]);

            return response()->json([
                'message' => __('app.success'),
                'role' => RoleResource::make($role->load('permissions'))
            ]);
        } catch (\Exception $e) {
            // Log failed role creation
            SensitiveOperationsLogger::failure('create', 'role', null, [
                'name' => $request->name,
                'slug' => $request->slug,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Display the specified role.
     *
     * @param  Role $role
     * @return JsonResponse
     */
    public function show(Role $role): JsonResponse
    {
        return response()->json(RoleResource::make($role->load('permissions')));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  RoleRequest $request
     * @param  Role        $role
     * @return JsonResponse
     */
    public function update(RoleRequest $request, Role $role): JsonResponse
    {
        // Log the attempt to update a role
        SensitiveOperationsLogger::attempt('update', 'role', $role->id, [
            'current_name' => $role->name,
            'current_slug' => $role->slug,
            'new_name' => $request->name,
            'new_slug' => $request->slug,
            'permissions_changed' => $request->has('permissions')
        ]);

        try {
            DB::transaction(function () use ($request, $role) {
                $role->update($request->only(['name', 'slug']));

                // Sync permissions if provided
                if ($request->has('permissions')) {
                    $role->permissions()->sync($request->permissions);
                }
            });

            $role->refresh()->load('permissions');

            // Log successful role update
            SensitiveOperationsLogger::success('update', 'role', $role->id, [
                'name' => $role->name,
                'slug' => $role->slug,
                'permissions' => $role->permissions->pluck('name')->toArray()
            ]);

            return response()->json([
                'message' => __('app.success'),
                'role' => RoleResource::make($role)
            ]);
        } catch (\Exception $e) {
            // Log failed role update
            SensitiveOperationsLogger::failure('update', 'role', $role->id, [
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * @param  Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role): JsonResponse
    {
        // Log the attempt to delete a role
        SensitiveOperationsLogger::attempt('delete', 'role', $role->id, [
            'name' => $role->name,
            'slug' => $role->slug
        ]);

        // Prevent deleting the admin role
        if ($role->slug === 'admin') {
            // Log failed deletion attempt (trying to delete admin role)
            SensitiveOperationsLogger::failure('delete', 'role', $role->id, [
                'reason' => 'Attempted to delete admin role',
                'name' => $role->name,
                'slug' => $role->slug
            ]);

            return response()->json([
                'message' => 'لا يمكن حذف دور المسؤول'
            ], 403);
        }

        try {
            // Store role info before deletion for logging
            $roleInfo = [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
                'permissions' => $role->permissions->pluck('name')->toArray()
            ];

            DB::transaction(function () use ($role) {
                // Detach all permissions before deleting
                $role->permissions()->detach();

                // Delete the role
                $role->delete();
            });

            // Log successful role deletion
            SensitiveOperationsLogger::success('delete', 'role', $roleInfo['id'], $roleInfo);

            return response()->json([
                'message' => __('app.success')
            ]);
        } catch (\Exception $e) {
            // Log failed role deletion
            SensitiveOperationsLogger::failure('delete', 'role', $role->id, [
                'error' => $e->getMessage(),
                'name' => $role->name,
                'slug' => $role->slug
            ]);

            throw $e;
        }
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
