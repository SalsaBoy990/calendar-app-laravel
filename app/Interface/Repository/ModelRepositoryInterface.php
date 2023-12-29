<?php

namespace App\Interface\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface ModelRepositoryInterface
{

    /**
     * @param string $modelClass
     * @param int $perPage
     * @param string $pageName
     * @return LengthAwarePaginator
     */
    public function paginateEntities(string $modelClass, int $perPage, string $pageName = 'page'): LengthAwarePaginator;


    /**
     * @param  string  $modelClass
     * @param  array  $data
     * @return Model
     */
    public function createEntity(string $modelClass, array $data): Model;


    /**
     * @param  Model  $model
     * @return bool
     */
    public function deleteEntity(Model $model): bool;


    /**
     * @param string $modelClass
     * @param array $ids
     * @return bool
     */
    public function deleteSelectedEntities(string $modelClass, array $ids): bool;


    /**
     * @param  Model  $model
     * @param  array  $data
     * @return bool
     */
    public function updateEntity(Model $model, array $data): bool;

}
