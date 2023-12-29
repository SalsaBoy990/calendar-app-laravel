<?php

namespace App\Services;

use App\Interface\Services\ArchiveEntityServiceInterface;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;


/**
 * Manages archive (soft-deletes) for entities
 */
class ArchiveEntityService implements ArchiveEntityServiceInterface
{
    private const PREFIX = "\App\Models\\";

    /**
     * @param string $modelClass
     * @param array|null $with
     *
     * @return Collection
     */
    public function listAllEntities(string $modelClass, array|null $with): Collection
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }

        $query = $modelClass::withTrashed();

        if ($with !== null) {
            $query->with($with);
        }

        return $query->get();
    }


    /**
     * @param string $modelClass
     * @param array|null $with
     *
     * @return Collection
     */
    public function listTrashedEntities(string $modelClass, array|null $with = []): Collection
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }

        $query = $modelClass::onlyTrashed();

        if ($with !== null) {
            $query->with($with);
        }

        return $query->get();
    }


    /**
     * @param string $modelClass
     * @param int $perPage
     * @param string $pageName custom pagination page query parameter name
     * @param array|null $with
     *
     * @return LengthAwarePaginator
     */
    public function paginateTrashedEntities(string $modelClass, int $perPage, string $pageName = 'page', ?array $with = []): LengthAwarePaginator
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }

        $query = $modelClass::onlyTrashed();

        if ($with !== null) {
            $query->with($with);
        }

        return $query->orderBy('id', 'DESC')->paginate($perPage, ['*'], $pageName)->withQueryString();
    }


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
    public function paginateFilteredTrashedEntities(string $modelClass, int $perPage, string $column, string $keyword, string $pageName = 'page', ?array $with = []): LengthAwarePaginator
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }

        $query = $modelClass::onlyTrashed();

        if ($with !== null) {
            $query->with($with);
        }

        /* has the search keyword in name */
        if ($keyword !== '') {
            $query->where($column, 'LIKE', '%' . $keyword . '%');
        }

        return $query->orderBy('id', 'DESC')->paginate($perPage, ['*'], $pageName)->withQueryString();
    }


    /**
     * @param string $modelClass
     * @param array|null $with
     *
     * @return Collection
     */
    public function listNonTrashedEntities(string $modelClass, array|null $with): Collection
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }

        if ($with !== null) {
            return $modelClass::with($with)->all();
        }

        return $modelClass::all();
    }


    /**
     * @param Model $model
     *
     * @return bool
     */
    public function softDeleteEntity(Model $model): bool
    {
        return $model->forceDelete();
    }


    /**
     * @param string $modelClass
     *
     * @return bool|null
     */
    public function restoreTrashedEntities(string $modelClass): bool|null
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }

        return $modelClass::onlyTrashed()->restore();
    }


    /**
     * @param Model $model
     *
     * @return bool|null
     */
    public function restoreTrashedEntity(Model $model): bool|null
    {
        return $model->restore();
    }


    /**
     * @param string $modelClass
     * @param array $ids
     * @return bool|null
     */
    public function restoreSelectedTrashedEntities(string $modelClass, array $ids): bool|null
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }

        $integerIds = [];
        foreach($ids as $id) {
            $integerIds[] = intval($id);
        }

        $collection = $modelClass::withTrashed()->whereIn('id', $integerIds)->get();

        DB::transaction(function() use ($collection) {
            foreach($collection as $model) {
                $model->restore();
            }
        });

        return true;
    }


    /**
     * @param Model $model
     *
     * @return bool|null
     */
    public function destroyEntity(Model $model): bool|null
    {
        return $model->forceDelete();
    }


    /**
     * @param string $modelClass
     *
     * @return bool|null
     */
    public function destroyAllTrashedEntities(string $modelClass): bool|null
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }

        return $modelClass::onlyTrashed()->forceDelete();
    }


    /**
     * @param string $modelClass
     * @param array $ids
     * @return bool|null
     */
    public function destroySelectedTrashedEntities(string $modelClass, array $ids): bool|null
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }

        $integerIds = [];
        foreach($ids as $id) {
            $integerIds[] = intval($id);
        }

        $collection = $modelClass::withTrashed()->whereIn('id', $integerIds)->get();

        DB::transaction(function() use ($collection) {
            foreach($collection as $model) {
                $model->forceDelete();
            }
        });

        return true;
    }
}
