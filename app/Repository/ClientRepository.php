<?php

namespace App\Repository;

use App\Interface\Repository\ClientRepositoryInterface;
use App\Models\Client;
use App\Models\ClientDetail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientRepository implements ClientRepositoryInterface
{

    /**
     * @return LengthAwarePaginator
     */
    public function getPaginatedClients(): LengthAwarePaginator
    {
        return Client::orderBy('name', 'ASC')
            ->with(['events', 'client_detail'])
            ->paginate(Client::RECORDS_PER_PAGE)->withQueryString();
    }


    /**
     * @return array
     */
    public function getClientTypes(): array
    {
        return Client::$clientTypes;
    }


    /**
     * @param  array  $client
     * @param  array  $details
     * @return Client
     */
    public function createClient(array $client, array $details): Client
    {
        if (!empty($details)) {
            // we need to create a new ClientDetail record
            $newClientDetails = new ClientDetail($details);
            $newClientDetails->save();

            // refresh data
            $newClientDetails->refresh();
        }

        $newClient = Client::create(array_merge($client,
            ['client_detail_id' => $newClientDetails->id ?? null])); // store the client detail id);
        $newClient->save();

        return $newClient;
    }


    /**
     * @param  Client  $client
     * @param  array  $clientData
     * @param  array  $detailsData
     * @param  int|null  $clientDetailId
     * @return bool|null
     */
    public function updateClient(
        Client $client,
        array $clientData,
        array $detailsData,
        ?int $clientDetailId = null
    ): bool|null {
        // if user sets detail fields and we don't have a client detail record yet
        if (!empty($detailsData) && $clientDetailId === null) {

            $detailsData['client_id'] = $client->id;
            $newClientDetails = new ClientDetail($detailsData);
            $newClientDetails->save();

            // refresh properties
            $newClientDetails->refresh();

            // set client detail id for the client to be saved
            $clientData['client_detail_id'] = $newClientDetails->id;

        } else {
            $client->client_detail()->update($detailsData);
        }

        $client->update($clientData);

        return true;
    }


    /**
     * @param  Client  $client
     * @return bool|null
     */
    public function deleteClient(Client $client): bool|null
    {
        $client->delete();
        $client->client_detail()->delete();
        return true;
    }

    public function getAllClients(): Collection
    {
        return Client::with('client_detail')->get();
    }
}
