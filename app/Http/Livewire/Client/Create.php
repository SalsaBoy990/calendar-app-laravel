<?php

namespace App\Http\Livewire\Client;

use App\Models\Client;
use App\Models\ClientDetail;
use App\Support\InteractsWithBanner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;


class Create extends Component {
    use InteractsWithBanner;
    use AuthorizesRequests;

    // used by blade / alpinejs
    public string $modalId;
    public bool $isModalOpen;

    // inputs
    public string $name;
    public string $address;
    public string $type;
    public int $order;
    public array $typesArray;

    // client details
    public ?string $contactPerson;
    public ?string $phoneNumber;
    public ?string $email;
    public ?string $taxNumber;

    protected array $rules = [
        'name'    => [ 'required', 'string', 'max:255', 'unique:clients' ],
        'address' => [ 'required', 'string', 'max:255' ],
        'type'    => [ 'required', 'string', 'in:company,private person' ],
        'order'   => [ 'required', 'integer' ],

        'contactPerson' => [ 'nullable', 'string', 'max:255' ],
        'phoneNumber'   => [ 'nullable', 'string', 'max:255' ],
        'email'         => [ 'nullable', 'email', 'max:255' ],
        'taxNumber'     => [ 'nullable', 'string', 'max:255' ],
    ];

    public function mount() {
        $this->modalId     = 'm-new-client';
        $this->isModalOpen = false;

        $this->name    = '';
        $this->address = '';
        $this->type    = '';
        $this->order   = 0;

        $this->contactPerson = null;
        $this->phoneNumber   = null;
        $this->email         = null;
        $this->taxNumber     = null;

        $this->typesArray = [
            'company'        => 'Company',
            'private person' => 'Private Person',
        ];

    }


    public function render() {
        return view( 'livewire.client.create' );
    }

    public function createClient() {
        $this->authorize( 'create', Client::class );

        // validate user input
        $this->validate();

        DB::transaction(
            function () {

                $details = [];
                // populate details if the props are set
                if ( isset( $this->contactPerson ) ) {
                    $details['contact_person'] = strip_tags( $this->contactPerson );
                }

                if ( isset( $this->phoneNumber ) ) {
                    $details['phone_number'] = strip_tags( $this->phoneNumber );
                }

                if ( isset( $this->email ) ) {
                    $details['email'] = strip_tags( $this->email );
                }

                if ( isset( $this->taxNumber ) ) {
                    $details['tax_number'] = strip_tags( $this->taxNumber );
                }
                if ( ! empty( $details ) ) {
                    // we need to create a new ClientDetail record
                    $newClientDetails = new ClientDetail( $details );
                    $newClientDetails->save();

                    // refresh data
                    $newClientDetails->refresh();
                }


                $newClient = Client::create( [
                    'name'             => strip_tags( $this->name ),
                    'address'          => strip_tags( $this->address ),
                    'type'             => strip_tags( $this->type ),
                    'order'            => $this->order,
                    'client_detail_id' => $newClientDetails->id ?? null // store the client detail id
                ] );

                $newClient->save();

            },
            2
        );


        $this->banner( 'Successfully created the client "' . strip_tags( $this->name ) . '"!' );

        return redirect()->route( 'client.manage' );
    }

}
