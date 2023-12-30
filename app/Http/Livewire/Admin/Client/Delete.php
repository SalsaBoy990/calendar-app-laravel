<?php

namespace App\Http\Livewire\Admin\Client;

use App\Interface\Repository\ClientRepositoryInterface;
use App\Models\Client;
use App\Support\InteractsWithBanner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
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
    public int $clientId;
    private Client $client;
    public string $name;


    protected array $rules = [
        'clientId' => 'required|int|min:1',
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
     * @param  string  $modalId
     * @param  Client  $client
     * @return void
     */
    public function mount(string $modalId, Client $client)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->client = $client;
        $this->clientId = intval($this->client->id);
        $this->name = strip_tags($client->name);
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function render()
    {
        return view('admin.livewire.client.delete');
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function deleteClient()
    {
        $this->client = Client::findOrFail($this->clientId);

        $this->authorize('delete', [Client::class, $this->client]);

        // validate user input
        $this->validate();

        // save category, rollback transaction if fails
        DB::transaction(
            function () {
                $this->clientRepository->deleteClient($this->client);
            },
            2
        );


        $this->banner(__('The client with the name ":name" was successfully deleted.',
            ['name' => strip_tags($this->name)]));

        return redirect()->route('client.manage');
    }
}
