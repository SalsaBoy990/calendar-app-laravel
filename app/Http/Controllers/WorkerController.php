<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Support\InteractsWithBanner;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    use InteractsWithBanner;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Worker::class);

        $workers = Worker::orderBy('created_at', 'DESC')
                         //->with('worker_availabilities')
                         ->paginate( 3 )
                         ->withQueryString();

        return view('admin.worker.manage')->with([
            'workers' => $workers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Worker $worker)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Worker $worker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Worker $worker)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Worker $worker)
    {
        //
    }
}
