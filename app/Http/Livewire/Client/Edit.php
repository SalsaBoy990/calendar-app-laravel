<?php

namespace App\Http\Livewire\Client;

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


    public function render()
    {
        return view('livewire.client.edit');
    }

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

                // if user sets detail fields and we don't have a client detail record yet
                if (!empty($details) && $this->client->client_detail_id === null) {

                    $details['client_id'] = $this->client->id;
                    $newClientDetails = new ClientDetail($details);
                    $newClientDetails->save();

                    // refresh properties
                    $newClientDetails->refresh();

                    // set client detail id for the client to be saved
                    $client['client_detail_id'] = $newClientDetails->id;

                } else {
                    $this->client->client_detail()->update($details);
                }

                $this->client->update($client);

            },
            2
        );


        $this->banner(__('Successfully updated the client ":name"!', ['name' => strip_tags($this->name)]));

        return redirect()->route('client.manage');
    }

}
