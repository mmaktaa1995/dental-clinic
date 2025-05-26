<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmailNotification;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'admin',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }
    
    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    
    /**
     * Assign a role to the user.
     *
     * @param string|Role $role
     * @return void
     */
    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }
        
        $this->roles()->syncWithoutDetaching($role);
    }
    
    /**
     * Remove a role from the user.
     *
     * @param string|Role $role
     * @return void
     */
    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }
        
        $this->roles()->detach($role);
    }
    
    /**
     * Check if user has a specific role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    /**
     * Check if user has any of the given roles
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->exists();
    }

    /**
     * Check if user has all of the given roles
     *
     * @param array $roles
     * @return bool
     */
    public function hasAllRoles(array $roles): bool
    {
        return $this->roles()->whereIn('slug', $roles)->count() === count($roles);
    }
    
    /**
     * Check if the user is an admin.
     * 
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin || $this->hasRole('admin');
    }
    
    /**
     * Check if the user has a specific permission through any of their roles.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if the user has any of the given permissions through any of their roles.
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasAnyPermission($permissions)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if the user has all of the given permissions through any of their roles.
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        // Create a set of all permissions the user has through their roles
        $userPermissions = collect();
        
        foreach ($this->roles as $role) {
            $rolePermissions = $role->permissions->pluck('slug')->toArray();
            $userPermissions = $userPermissions->concat($rolePermissions);
        }
        
        $userPermissions = $userPermissions->unique();
        
        // Check if all required permissions are in the user's permission set
        foreach ($permissions as $permission) {
            if (!$userPermissions->contains($permission)) {
                return false;
            }
        }
        
        return true;
    }
}
