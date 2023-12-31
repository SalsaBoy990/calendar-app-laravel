<?php

namespace App\Repository;

use App\Interface\Repository\WorkerRepositoryInterface;
use App\Models\Event;
use App\Models\Worker;
use App\Models\WorkerAvailability;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class WorkerRepository implements WorkerRepositoryInterface
{


    public function getPaginatedWorkers(): LengthAwarePaginator
    {
        Worker::orderBy('created_at', 'DESC')
            //->with('worker_availabilities')
            ->paginate(Worker::RECORDS_PER_PAGE)
            ->withQueryString();
    }

    public function getAllWorkers(): Collection
    {
        return Worker::all();
    }

    public function getAllWorkerAvailabilities(): Collection
    {
        // TODO: Implement getAllWorkerAvailabilities() method.
    }


    public function getAllWorkerAvailabilitiesWithWorker(): Collection
    {
        return WorkerAvailability::with('worker')->get();
    }


    public function getWorkerAvailabilityById(int $id): Model
    {
        return WorkerAvailability::where('id', $id)->first();
    }

}
