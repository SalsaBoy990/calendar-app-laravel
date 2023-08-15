<?php

namespace App\Trait;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 *
 */
trait HasRolesAndPermissions
{

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'users_permissions');
    }

    /**
     * @param ...$roles
     *
     * @return bool
     */
    public function hasRole(...$roles): bool
    {
        foreach ($roles as $role) {
            if (isset($this->role) && $this->role->slug === $role) {
                return true;
            }
        }

        return false;

    }

    /**
     * Check if user has one of the roles from the array
     *
     * @param  string  $roles  format: 'super-admin|admin'
     *
     * @return bool
     */
    public function hasRoles(string $roles): bool
    {
        $rolesArray = [];
        // multiple roles from middleware arguments
        if (str_contains($roles, '|')) {
            $rolesTemp = explode('|', $roles);

            foreach ($rolesTemp as $role) {
                $rolesArray[] = $role;
            }
        } else {
            // only one role supplied through middleware
            $roleSlug = $roles;
            $rolesArray[] = $roleSlug;
        }


        foreach ($rolesArray as $role) {
            if (isset($this->role) && $this->role->slug === $role) {
                return true;
            }
        }

        return false;

    }


    /**
     * Checks if the user have the permission
     *
     * @param  string  $permissionName
     *
     * @return bool
     */
    public function hasPermissionTo(string $permissionName = ''): bool
    {
        $permission = $this->getPermissionBySlug($permissionName);

        if ($permission === null) {
            return false;
        }

        return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
    }


    /**
     * Check if the user’s permissions contain the given permission
     *
     * @param  Permission  $permission
     *
     * @return bool
     */
    public function hasPermission(Permission $permission): bool
    {
        return (bool) $this->permissions->where('slug', $permission->slug)->count();
    }


    /**
     * Get the permission entity by the slug value
     *
     * @param  string  $slug
     *
     * @return Permission|null
     */
    protected function getPermissionBySlug(string $slug): ?Permission
    {
        return Permission::where('slug', $slug)->firstOrFail();
    }


    /**
     * Get the role entity by the slug value
     *
     * @param  string  $slug
     *
     * @return Role|null
     */
    protected function getRoleBySlug(string $slug): ?Role
    {
        return Role::where('slug', $slug)->firstOrFail();
    }

    /**
     * Get the role entity by the slug value
     * User should have onl one role!
     *
     */
    public function deleteUserRole(): void
    {
        $this->role()->dissociate();
    }


    /**
     * This enables us to check if a user has permission through its role
     *
     * @param  Permission  $permission
     *
     * @return bool
     */
    public function hasPermissionThroughRole(Permission $permission): bool
    {
        foreach ($permission->roles as $role) {
            if ($this->role->id === $role->id) {
                return true;
            }
        }
        return false;
    }


    /**
     * Get all permissions based on an array
     *
     * @param  array  $permissions
     *
     * @return mixed
     */
    public function getAllPermissionsByArray(array $permissions): mixed
    {
        return Permission::whereIn('slug', $permissions)->get();
    }


    /**
     * Get all permissions
     *
     * @return mixed
     */
    public function getAllPermissions(): mixed
    {
        return Permission::all();
    }


    /**
     *  Save the permissions for the current user
     *
     * @param  array  $permissionsArray
     *
     * @return $this
     */
    public function givePermissionsTo(array $permissionsArray): static
    {

        // slug of all permissions the user currently has
        $permissionSlugs = $this->permissions()->pluck('slug')->toArray();

        // These are the missing permissions (by slug) that are needed to be added to the users' permissions
        $newPermissionSlugs = array_diff($permissionsArray, $permissionSlugs);
        // No need to save if the user already has these permissions
        if (empty($newPermissionSlugs)) {
            return $this;
        }

        $newPermissions = $this->getAllPermissionsByArray($newPermissionSlugs);

        // Save the missing permissions in the pivot table
        $this->permissions()->saveMany($newPermissions);

        return $this;
    }


    /**
     * TODO
     * Delete all permissions of the user
     *
     * @param  mixed  ...$permissions
     *
     * @return $this
     */
    public function deletePermissions(...$permissions)
    {
        $permissions = $this->getAllPermissions($permissions);
        $this->permissions()->detach($permissions);

        return $this;
    }

    /**
     * NOT USED (no users_permissions pivot table)
     * @param  array  $permissions
     *
     * @return HasRolesAndPermissions
     */
    public function refreshPermissions(array $permissions)
    {
        $this->permissions()->detach();
        $this->permissions()->sync($permissions);
        return $this;
    }


}
