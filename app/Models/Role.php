<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperRole
 */
class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * The users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Assign a permission to the role.
     *
     * @param string|Permission $permission
     * @return void
     */
    public function assignPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }

        $this->permissions()->syncWithoutDetaching($permission);
    }

    /**
     * Remove a permission from the role.
     *
     * @param string|Permission $permission
     * @return void
     */
    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }

        $this->permissions()->detach($permission);
    }

    /**
     * Check if the role has a specific permission.
     *
     * @param string $permission
     * @return boolean
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Check if the role has any of the given permissions.
     *
     * @param array $permissions
     * @return boolean
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->permissions()->whereIn('slug', $permissions)->exists();
    }

    /**
     * Check if the role has all of the given permissions.
     *
     * @param array $permissions
     * @return boolean
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return $this->permissions()->whereIn('slug', $permissions)->count() === count($permissions);
    }
}
