<?php

namespace App\Http\Livewire\Worker;

use App\Support\InteractsWithBanner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\Worker;

class Create extends Component
{
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
    public string $bankAccountNumber;
    public string $bankAccountName;

    protected array $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['nullable', 'email', 'max:255', 'unique:workers'],
        'phone' => ['nullable', 'string'],
        'bankAccountNumber' => ['nullable', 'string'],
        'bankAccountName' => ['nullable', 'string'],
    ];

    public function mount(bool $hasSmallButton = false)
    {
        $this->modalId = 'm-new-worker';
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton || false;

        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->bankAccountNumber = '';
        $this->bankAccountName = '';
    }


    public function render()
    {
        return view('livewire.worker.create');
    }

    public function createWorker()
    {
        $this->authorize('create', Worker::class);

        // validate user input
        $this->validate();

        DB::transaction(
            function () {
                $newWorker = Worker::create([
                    'name' => htmlspecialchars($this->name),
                    'email' => $this->email !== '' ? trim(htmlspecialchars($this->email)) : null,
                    'phone' => htmlspecialchars($this->phone),
                    'bank_account_number' => trim(htmlspecialchars($this->bankAccountNumber)),
                    'bank_account_name' => trim(htmlspecialchars($this->bankAccountName)),
                ]);

                $newWorker->save();
            },
            2
        );


        $this->banner(__('Successfully created the worker ":name"!', ['name' => htmlspecialchars($this->name)]));

        return redirect()->route('worker.manage');
    }

}
