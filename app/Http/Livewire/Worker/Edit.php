<?php

namespace App\Http\Livewire\Worker;

use App\Models\Worker;
use App\Support\InteractsWithBanner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component {
    use InteractsWithBanner;
    use AuthorizesRequests;

    // used by blade / alpinejs
    public $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public string $name;
    public string $email;
    public string $phone;
    public Worker $worker;
    public int $workerId;

    protected array $rules = [
        'name'  => [ 'required', 'string', 'max:255' ],
        'email' => [ 'nullable', 'string' ],
        'phone' => [ 'nullable', 'string' ],
    ];

    public function mount(
        string $modalId,
        Worker $worker,
        bool $hasSmallButton = false
    ) {
        $this->modalId        = $modalId;
        $this->isModalOpen    = false;
        $this->hasSmallButton = $hasSmallButton || false;

        $this->worker   = $worker;
        $this->workerId = $this->worker->id;
        $this->name     = $this->worker->name;
        $this->email     = $this->worker->email;
        $this->phone    = $this->worker->phone;
    }


    public function render() {
        return view( 'livewire.worker.edit' );
    }

    public function updateWorker() {
        $this->authorize( 'update', [ Worker::class, $this->worker ] );

        // validate user input
        $this->validate();

        DB::transaction(
            function () {
                $this->worker->update( [
                    'name'  => htmlspecialchars( $this->name ),
                    'email' => htmlspecialchars( $this->email ),
                    'phone' => htmlspecialchars( $this->phone ),
                ] );
                $this->worker->save();
            },
            2
        );


        $this->banner( __('Successfully updated the worker ":name"!', ['name' => htmlspecialchars( $this->name )] ) );

        return redirect()->route( 'worker.manage' );
    }

}
