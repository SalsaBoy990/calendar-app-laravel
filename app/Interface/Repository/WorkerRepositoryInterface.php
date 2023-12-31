<?php

namespace App\Interface\Repository;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface WorkerRepositoryInterface
{
    /**
     * @return LengthAwarePaginator
     */
    public function getPaginatedWorkers(): LengthAwarePaginator;


    /**
     * @return Collection
     */
    public function getAllWorkers(): Collection;


    /**
     * @return Collection
     */
    public function getAllWorkerAvailabilities(): Collection;


    /**
     * @return Collection
     */
    public function getAllWorkerAvailabilitiesWithWorker(): Collection;


    /**
     * @param  int  $id
     * @return Model
     */
    public function getWorkerAvailabilityById(int $id): Model;

}
