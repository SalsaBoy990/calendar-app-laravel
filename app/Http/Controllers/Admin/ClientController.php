<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interface\Repository\ClientRepositoryInterface;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * @var ClientRepositoryInterface
     */
    public ClientRepositoryInterface $clientRepository;


    /**
     *
     */
    public function __construct(ClientRepositoryInterface $clientRepository) {
        $this->clientRepository = $clientRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $types = $this->clientRepository->getClientTypes();
        $clients = $this->clientRepository->getPaginatedClients();


        return view('admin.pages.client.manage')->with([
            'clients' => $clients,
            'clientTypes' => $types
        ]);
    }

}
