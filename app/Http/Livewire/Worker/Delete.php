<?php

namespace App\Http\Livewire\Worker;

use App\Models\Worker;
use App\Support\InteractsWithBanner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    use InteractsWithBanner;
    use AuthorizesRequests;

    // used by blade / alpinejs
    public string $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public int $workerId;
    private Worker $user;
    public string $name;


    protected array $rules = [
        'workerId' => 'required|int|min:1',
    ];

    public function mount(string $modalId, $worker, bool $hasSmallButton = false)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;
        $this->worker = $worker;
        $this->workerId = $worker->id;
        $this->name = $worker->name;
    }


    public function render()
    {
        return view('livewire.worker.delete');
    }


    public function deleteWorker()
    {
        $worker = Worker::findOrFail($this->workerId);

        $this->authorize('delete', [Worker::class, $worker]);

        // validate user input
        $this->validate();

        // save category, rollback transaction if fails
        DB::transaction(
            function () use ($worker) {
                $worker->delete();
            },
            2
        );


        $this->banner(__('The worker with the name ":name" was successfully deleted.',
            ['name' => htmlspecialchars($this->name)]));
        return redirect()->route('worker.manage');
    }
}
