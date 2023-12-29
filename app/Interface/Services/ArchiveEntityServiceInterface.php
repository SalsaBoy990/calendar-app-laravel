<?php

namespace App\Interface\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface ArchiveEntityServiceInterface
{
    /**
     * @param string $modelClass
     * @param array|null $with
     *
     * @return Collection
     */
    public function listAllEntities(string $modelClass, ?array $with): Collection;


    /**
     * @param string $modelClass
     * @param array|null $with
     *
     * @return Collection
     */
    public function listTrashedEntities(string $modelClass, ?array $with = []): Collection;


    /**
     * @param string $modelClass
     * @param int $perPage
     * @param string $pageName custom pagination page query parameter name
     * @param array|null $with
     *
     * @return LengthAwarePaginator
     */
    public function paginateTrashedEntities(string $modelClass, int $perPage, string $pageName = 'page', ?array $with = []): LengthAwarePaginator;


    /**
     * @param string $modelClass
     * @param int $perPage
     * @param string $column
     * @param string $keyword
     * @param string $pageName
     * @param array|null $with
     *
     * @return LengthAwarePaginator
     */
    public function paginateFilteredTrashedEntities(string $modelClass, int $perPage, string $column, string $keyword, string $pageName = 'page', ?array $with = []): LengthAwarePaginator;


    /**
     * @param string $modelClass
     * @param array|null $with
     *
     * @return Collection
     */
    public function listNonTrashedEntities(string $modelClass, ?array $with): Collection;


    /**
     * @param Model $model
     *
     * @return bool
     */
    public function softDeleteEntity(Model $model): bool;


    /**
     * @param string $modelClass
     *
     * @return bool|null
     */
    public function restoreTrashedEntities(string $modelClass): bool|null;


    /**
     * @param string $modelClass
     * @param array $ids
     * @return bool|null
     */
    public function restoreSelectedTrashedEntities(string $modelClass, array $ids): bool|null;


    /**
     * @param Model $model
     *
     * @return bool|null
     */
    public function restoreTrashedEntity(Model $model): bool|null;


    /**
     * @param Model $model
     *
     * @return bool|null
     */
    public function destroyEntity(Model $model): bool|null;


    /**
     * @param string $modelClass
     *
     * @return bool|null
     */
    public function destroyAllTrashedEntities(string $modelClass): bool|null;


    /**
     * @param string $modelClass
     * @param array $ids
     * @return bool|null
     */
    public function destroySelectedTrashedEntities(string $modelClass, array $ids): bool|null;

}
