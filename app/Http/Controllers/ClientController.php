<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $this->authorize( 'viewAny', User::class );

        $types = Client::$clientTypes;
        $clients = Client::orderBy( 'name', 'ASC' )
                         ->with( [ 'events', 'client_detail' ] )
                         ->paginate( Client::RECORDS_PER_PAGE )->withQueryString();


        return view( 'admin.client.manage' )->with( [
            'clients' => $clients,
            'clientTypes' => $types
        ] );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( Request $request ) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show( Client $client ) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Client $client ) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( Request $request, Client $client ) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Client $client ) {
        //
    }
}
