<?php

namespace App\Interface\Repository;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     * @return LengthAwarePaginator
     */
    public function getPaginatedUsersWithRoles(): LengthAwarePaginator;


    /**
     * @param  array  $data
     * @return User
     */
    public function createUser(array $data): User;


    /**
     * @param  User  $user
     * @return bool
     */
    public function deleteUser(User $user): bool;


    /**
     * @param  User  $user
     * @param  array  $data
     * @return bool
     */
    public function updateUser(User $user, array $data): bool;

}
