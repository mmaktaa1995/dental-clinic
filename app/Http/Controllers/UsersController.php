<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserSearchRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Search\UserSearch;
use App\Services\SensitiveOperationsLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Get list of users",
     *     description="Returns a paginated list of users",
     *     security={{"bearerAuth": {}}},
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UserResource")),
     *             @OA\Property(property="links", type="object"),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
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
    /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     summary="Create a new user",
     *     description="Creates a new user with the given data",
     *     security={{"bearerAuth": {}}},
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(UserRequest $request): JsonResponse
    {
        // Log the attempt to create a user
        SensitiveOperationsLogger::attempt('create', 'user', null, [
            'email' => $request->email,
            'username' => $request->username,
            'roles' => $request->roles ?? []
        ]);
        
        try {
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
            
            // Log successful user creation
            SensitiveOperationsLogger::success('create', 'user', $user->id, [
                'email' => $user->email,
                'username' => $user->username,
                'roles' => $user->roles->pluck('slug')->toArray()
            ]);
    
            return response()->json([
                'message' => __('app.success'),
                'user' => UserResource::make($user->load('roles'))
            ]);
        } catch (\Exception $e) {
            // Log failed user creation
            SensitiveOperationsLogger::failure('create', 'user', null, [
                'email' => $request->email,
                'username' => $request->username,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
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
        // Log the attempt to update a user
        SensitiveOperationsLogger::attempt('update', 'user', $user->id, [
            'current_email' => $user->email,
            'new_email' => $request->email,
            'password_changed' => $request->filled('password'),
            'roles_changed' => $request->has('roles')
        ]);
        
        try {
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
            
            $user->refresh()->load('roles');
            
            // Log successful user update
            SensitiveOperationsLogger::success('update', 'user', $user->id, [
                'email' => $user->email,
                'username' => $user->username,
                'roles' => $user->roles->pluck('slug')->toArray()
            ]);
    
            return response()->json([
                'message' => __('app.success'),
                'user' => UserResource::make($user)
            ]);
        } catch (\Exception $e) {
            // Log failed user update
            SensitiveOperationsLogger::failure('update', 'user', $user->id, [
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        // Log the attempt to delete a user
        SensitiveOperationsLogger::attempt('delete', 'user', $user->id, [
            'email' => $user->email,
            'username' => $user->username
        ]);
        
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            // Log failed deletion attempt (trying to delete self)
            SensitiveOperationsLogger::failure('delete', 'user', $user->id, [
                'reason' => 'Attempted to delete own account',
                'email' => $user->email
            ]);
            
            return response()->json([
                'message' => 'لا يمكن حذف المستخدم الحالي'
            ], 403);
        }

        try {
            // Store user info before deletion for logging
            $userInfo = [
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
                'roles' => $user->roles->pluck('slug')->toArray()
            ];
            
            DB::transaction(function () use ($user) {
                // Detach all roles before deleting
                $user->roles()->detach();
                
                // Delete the user
                $user->delete();
            });
            
            // Log successful user deletion
            SensitiveOperationsLogger::success('delete', 'user', $userInfo['id'], $userInfo);
    
            return response()->json([
                'message' => __('app.success')
            ]);
        } catch (\Exception $e) {
            // Log failed user deletion
            SensitiveOperationsLogger::failure('delete', 'user', $user->id, [
                'error' => $e->getMessage(),
                'email' => $user->email
            ]);
            
            throw $e;
        }
    }
}
