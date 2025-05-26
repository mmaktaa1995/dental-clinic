<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Search\UserSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param  UserSearchRequest  $request
     * @return JsonResponse
     */
    public function list(UserSearchRequest $request): JsonResponse
    {
        $userSearch = new UserSearch($request);

        return response()->json(BaseCollection::make($userSearch->getEntries(), UserResource::class));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  UserRequest  $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $user = DB::transaction(function () use ($request) {
            $userData = $request->only(['name', 'username', 'email']);
            $userData['password'] = Hash::make($request->password);
            
            $user = User::create($userData);
            
            // Assign roles if provided
            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
            }
            
            return $user;
        });

        return response()->json([
            'message' => __('app.success'),
            'user' => UserResource::make($user->load('roles'))
        ]);
    }

    /**
     * Display the specified user.
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json(UserResource::make($user->load('roles')));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  UserRequest  $request
     * @param  User  $user
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        DB::transaction(function () use ($request, $user) {
            $userData = $request->only(['name', 'username', 'email']);
            
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);

            // Sync roles if provided
            if ($request->has('roles')) {
                $user->roles()->sync($request->roles);
            }
        });

        return response()->json([
            'message' => __('app.success'),
            'user' => UserResource::make($user->fresh()->load('roles'))
        ]);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'لا يمكن حذف المستخدم الحالي'
            ], 403);
        }

        DB::transaction(function () use ($user) {
            // Detach all roles before deleting
            $user->roles()->detach();
            
            // Delete the user
            $user->delete();
        });

        return response()->json([
            'message' => __('app.success')
        ]);
    }
}
