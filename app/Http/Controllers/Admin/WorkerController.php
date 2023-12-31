<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interface\Repository\WorkerRepositoryInterface;
use App\Models\Worker;
use App\Support\InteractsWithBanner;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    use InteractsWithBanner;

    /**
     * @var WorkerRepositoryInterface
     */
    private WorkerRepositoryInterface $workerRepository;


    /**
     * @param  WorkerRepositoryInterface  $workerRepository
     */
    public function __construct(WorkerRepositoryInterface $workerRepository)
    {
        $this->workerRepository = $workerRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $this->authorize('viewAny', Worker::class);

        return view('admin.pages.worker.manage')->with([
            'workers' => $this->workerRepository->getPaginatedWorkers(),
        ]);
    }
}
