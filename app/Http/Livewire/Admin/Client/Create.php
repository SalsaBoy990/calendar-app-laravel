<?php

namespace App\Http\Livewire\Admin\Client;

use App\Interface\Repository\ClientRepositoryInterface;
use App\Models\Client;
use App\Support\InteractsWithBanner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class Create extends Component
{
    use InteractsWithBanner;
    use AuthorizesRequests;


    /**
     * @var ClientRepositoryInterface
     */
    private ClientRepositoryInterface $clientRepository;


    // used by blade / alpinejs
    public string $modalId;
    public bool $isModalOpen;

    // inputs
    public string $name;
    public string $address;
    public string $type;
    public array $typesArray;

    // client details
    public ?string $contactPerson;
    public ?string $phoneNumber;
    public ?string $email;
    public ?string $taxNumber;

    protected array $rules = [
        'name' => ['required', 'string', 'max:255', 'unique:clients'],
        'address' => ['required', 'string', 'max:255'],
        'type' => ['required', 'string', 'in:company,private person'],

        'contactPerson' => ['nullable', 'string', 'max:255'],
        'phoneNumber' => ['nullable', 'string', 'max:255'],
        'email' => ['nullable', 'email', 'max:255'],
        'taxNumber' => ['nullable', 'string', 'max:255'],
    ];


    /**
     * @return void
     */
    public function mount()
    {
        $this->modalId = 'm-new-client';
        $this->isModalOpen = false;

        $this->name = '';
        $this->address = '';
        $this->type = '';

        $this->contactPerson = null;
        $this->phoneNumber = null;
        $this->email = null;
        $this->taxNumber = null;

        $this->typesArray = Client::$clientTypes;

    }


    /**
     * @param  ClientRepositoryInterface  $clientRepository
     * @return void
     */
    public function boot(ClientRepositoryInterface $clientRepository): void
    {
        $this->clientRepository = $clientRepository;
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function render()
    {
        return view('admin.livewire.client.create');
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createClient()
    {
        $this->authorize('create', Client::class);

        // validate user input
        $this->validate();

        DB::transaction(
            function () {

                $details = [];
                // populate details if the props are set
                if (isset($this->contactPerson)) {
                    $details['contact_person'] = strip_tags($this->contactPerson);
                }

                if (isset($this->phoneNumber)) {
                    $details['phone_number'] = strip_tags($this->phoneNumber);
                }

                if (isset($this->email)) {
                    $details['email'] = strip_tags($this->email);
                }

                if (isset($this->taxNumber)) {
                    $details['tax_number'] = strip_tags($this->taxNumber);
                }

                $clientData = [
                    'name' => strip_tags($this->name),
                    'address' => strip_tags($this->address),
                    'type' => strip_tags($this->type),
                ];

                $this->clientRepository->createClient($clientData, $details);
            },
            2
        );


        $this->banner(__('Successfully created the client ":name"!', ['name' => strip_tags($this->name)]));

        return redirect()->route('client.manage');
    }

}
