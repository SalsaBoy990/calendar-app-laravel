<x-admin-layout>

    @section('content')

        <main class="padding-1">
            <nav class="breadcrumb">
                <ol>
                    <li>
                        <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>{{ __('Manage Clients') }}</li>
                </ol>
            </nav>

            <h1 class="h3 margin-top-bottom-0">{{ __('Manage clients') }}</h1>

            <div class="main-content">


                <!-- Create new user -->
                <livewire:client.create></livewire:client.create>

                <table>
                    <thead>
                    <tr class="fs-14">
                        <th>{{ __('Client') }}</th>
                        <th>{{ __('Details') }}</th>
                        <th>{{ __('Tax number') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>
                                <b>{{ $client->name }}</b>
                                <br>
                                <span class="fs-12 semibold badge gray-60">{{ $clientTypes[$client->type] }}</span>
                                <br>
                                <address>{{ $client->address }}</address>
                            </td>
                            <td>
                                @if (isset($client->client_detail))
                                    <div>{{$client->client_detail->contact_person ?? '' }}</div>
                                    <div>{{ $client->client_detail->phone_number ?? '' }}</div>
                                    <div>{{ $client->client_detail->email ?? '' }}</div>
                                @endif

                            </td>
                            <td>
                                @if (isset($client->client_detail))
                                    <div>{{ $client->client_detail->tax_number ?? '-' }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="flex flex-row flex-wrap">
                                    <!-- Delete user -->
                                    <livewire:client.delete title="{{ __('Delete client') }}"
                                                            :client="$client"
                                                            :modalId="'m-delete-client-' . $client->id"
                                    >
                                    </livewire:client.delete>

                                    <!-- Update user -->
                                    <livewire:client.edit title="{{ __('Edit client') }}"
                                                          :client="$client"
                                                          :modalId="'m-edit-client-' . $client->id"
                                    >
                                    </livewire:client.edit>

                                </div>

                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $clients->links('components.pagination') }}

            </div>
        </main>
    @endsection

</x-admin-layout>
