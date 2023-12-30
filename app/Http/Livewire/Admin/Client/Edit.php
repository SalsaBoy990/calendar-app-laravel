<?php

namespace App\Http\Livewire\Admin\Client;

use App\Interface\Repository\ClientRepositoryInterface;
use App\Models\Client;
use App\Models\ClientDetail;
use App\Support\InteractsWithBanner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class Edit extends Component
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

    public Client $client;
    public int $clientId;

    // inputs
    public string $name;
    public string $address;
    public string $type;
    public array $typesArray;

    // client details
    public ?string $contactPerson = null;
    public ?string $phoneNumber = null;
    public ?string $email = null;
    public ?string $taxNumber = null;

    protected array $rules = [
        'name' => ['required', 'string', 'max:255'],
        'address' => ['required', 'string', 'max:255'],
        'type' => ['required', 'in:company,private person'],

        'contactPerson' => ['nullable', 'string', 'max:255'],
        'phoneNumber' => ['nullable', 'string', 'max:255'],
        'email' => ['nullable', 'email', 'max:255'],
        'taxNumber' => ['nullable', 'string', 'max:255'],
    ];


    /**
     * @param  ClientRepositoryInterface  $clientRepository
     * @return void
     */
    public function boot(ClientRepositoryInterface $clientRepository): void
    {
        $this->clientRepository = $clientRepository;
    }


    /**
     * @param  Client  $client
     * @return void
     */
    public function mount(Client $client)
    {
        $this->modalId = '';
        $this->isModalOpen = false;

        $this->client = $client;

        $this->name = $this->client->name ?? '';
        $this->address = $this->client->address ?? '';
        $this->type = $this->client->type;

        $this->typesArray = Client::$clientTypes;

        if ($this->client->client_detail !== null) {
            $this->contactPerson = $this->client->client_detail->contact_person ?? null;
            $this->phoneNumber = $this->client->client_detail->phone_number ?? null;
            $this->email = $this->client->client_detail->email ?? null;
            $this->taxNumber = $this->client->client_detail->tax_number ?? null;
        }


    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function render()
    {
        return view('admin.livewire.client.edit');
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateClient()
    {
        $this->authorize('update', [Client::class, $this->client]);

        // validate user input
        $this->validate();

        DB::transaction(
            function () {

                $client = [
                    'name' => strip_tags(trim($this->name)),
                    'address' => strip_tags(trim($this->address)),
                    'type' => strip_tags(trim($this->type)),
                ];

                $details = [];
                // populate details if we have user input
                if (isset($this->contactPerson)) {
                    $details['contact_person'] = strip_tags(trim($this->contactPerson));
                }

                if (isset($this->phoneNumber)) {
                    $details['phone_number'] = strip_tags(trim($this->phoneNumber));
                }

                if (isset($this->email)) {
                    $details['email'] = strip_tags(trim($this->email));
                }

                if (isset($this->taxNumber)) {
                    $details['tax_number'] = strip_tags(trim($this->taxNumber));
                }

                $this->clientRepository->updateClient($this->client, $client, $details,
                    $this->client->client_detail_id);
            },
            2
        );


        $this->banner(__('Successfully updated the client ":name"!', ['name' => strip_tags($this->name)]));

        return redirect()->route('client.manage');
    }

}
