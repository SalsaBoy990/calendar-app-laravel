<?php

namespace App\Services;

use App\Interface\Services\RolePermissionServiceInterface;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class RolePermissionService implements RolePermissionServiceInterface
{

    /**
     * @return Collection
     */
    public function getRolesWithPermissions(): Collection
    {
        return Role::with('permissions')->get();
    }


    /**
     * @return Collection
     */
    public function getPermissionsWithRoles(): Collection
    {
        return Permission::with('roles')->get();
    }


    /**
     * @param  int  $roleId
     * @return Role
     */
    public function getRoleById(int $roleId): Role
    {
        return Role::where('id', $roleId)->firstOrFail();
    }


    /**
     * @param  string  $slug
     * @return Role
     */
    public function getRoleBySlug(string $slug): Role
    {
        return Role::where('slug', $slug)->firstOrFail();
    }


    /**
     * @param  Role  $role
     * @param  User  $user
     * @return void
     */
    public function associateRoleWithUser(Role $role, User $user): void
    {
        $user->role()->associate($role);
    }


    /**
     * @param  User  $user
     * @return void
     */
    public function deleteUserRole(User $user): void
    {
        $user->role()->dissociate();
    }


    /**
     * @param  array  $data
     * @return Role
     */
    public function createRole(array $data): Role
    {
        return Role::create($data);
    }


    /**
     * @param  array  $data
     * @return Permission
     */
    public function createPermission(array $data): Permission
    {
        return Permission::create($data);
    }


    /**
     * @param  Role  $role
     * @param  array  $permissions
     * @return bool
     */
    public function syncPermissionsToRole(Role $role, array $permissions): bool
    {
        $role->permissions()->sync($permissions);
        return $role->save();
    }


    /**
     * @param  Permission  $permission
     * @param  array  $roles
     * @return bool
     */
    public function syncRolesToPermission(Permission $permission, array $roles): bool
    {
        $permission->roles()->sync($roles);
        return $permission->save();
    }


    /**
     * @param  Role  $role
     * @param  array  $data
     * @return bool|null
     */
    public function updateRole(Role $role, array $data): bool|null
    {
        return $role->update($data);
    }


    /**
     * @param  Permission  $permission
     * @param  array  $data
     * @return bool|null
     * @throws \Throwable
     */
    public function updatePermission(Permission $permission, array $data): bool|null
    {
        return $permission->updateOrFail($data);
    }


    /**
     * @param  Role  $role
     * @return bool|null
     * @throws \Throwable
     */
    public function deleteRole(Role $role): bool|null
    {
        return $role->deleteOrFail();
    }


    /**
     * @param  Permission  $permission
     * @return bool|null
     * @throws \Throwable
     */
    public function deletePermission(Permission $permission): bool|null
    {
        return $permission->deleteOrFail();
    }


    /**
     * @param  Collection  $collection
     * @return array
     */
    public function getRelatedIds(Collection $collection): array {
        $array = [];
        foreach($collection as $item) {
            $array[] = $item->id;
        }
        return $array;
    }
}
