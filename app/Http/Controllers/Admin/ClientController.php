<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $types = Client::$clientTypes;
        $clients = Client::orderBy('name', 'ASC')
            ->with(['events', 'client_detail'])
            ->paginate(Client::RECORDS_PER_PAGE)->withQueryString();


        return view('admin.pages.client.manage')->with([
            'clients' => $clients,
            'clientTypes' => $types
        ]);
    }

}
