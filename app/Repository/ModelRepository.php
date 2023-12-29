<?php

namespace App\Repository;

use App\Interface\Repository\ModelRepositoryInterface;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * Manage entity CRUD
 */
class ModelRepository implements ModelRepositoryInterface
{
    private const PREFIX = "\App\Models\\";

    /**
     * @param string $modelClass
     * @param int $perPage
     * @param string $pageName
     *
     * @return LengthAwarePaginator
     */
    public function paginateEntities(string $modelClass, int $perPage, string $pageName = 'page'): LengthAwarePaginator
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }
        return $modelClass::orderBy('created_at', 'DESC')
            ->paginate($perPage, ['*'], $pageName);
    }


    /**
     * @param string $modelClass
     * @param array $data
     * @return Model
     */
    public function createEntity(string $modelClass, array $data): Model
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }

        return $modelClass::create($data);
    }


    /**
     * @param Model $model
     * @return bool
     * @throws \Throwable
     */
    public function deleteEntity(Model $model): bool
    {
        return $model->deleteOrFail();

    }


    /**
     * @param Model $model
     * @param array $data
     * @return bool
     * @throws \Throwable
     */
    public function updateEntity(Model $model, array $data): bool
    {
        return $model->updateOrFail($data);
    }

    /**
     * @param string $modelClass
     * @param array $ids
     * @return bool
     */
    public function deleteSelectedEntities(string $modelClass, array $ids): bool
    {
        $modelClass = self::PREFIX . $modelClass;
        if (!class_exists($modelClass)) {
            throw new InvalidArgumentException();
        }

        $integerIds = [];
        foreach ($ids as $id) {
            $integerIds[] = intval($id);
        }

        $collection = $modelClass::whereIn('id', $integerIds)->get();

        DB::transaction(function () use ($collection) {
            foreach ($collection as $model) {
                $model->deleteOrFail();
            }
        });

        return true;
    }
}
