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

            <div class="main-content">

                <!-- Create new user -->
                <livewire:client.create></livewire:client.create>

                <table>
                    <thead>
                    <tr>
                        <th>{{ __('Client') }}</th>
                        <th>{{ __('Details') }}</th>
                        <th>{{ __('Tax number') }}</th>
                        <th>{{ __('Order') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clients as $client)
                        <tr>
                            <td>
                                <b>{{ $client->name }}</b>
                                <span class="fs-12 bold badge gray-60">{{ $client->type }}</span>
                                <br>
                                {{ $client->address }}
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
                            <td>{{ $client->order }}</td>
                            <td>
                                <div class="flex">
                                    <!-- Delete user -->
                                    <livewire:client.delete :title="'Delete client'"
                                                            :client="$client"
                                                            :modalId="'m-delete-client-' . $client->id"
                                    >
                                    </livewire:client.delete>

                                    <!-- Update user -->
                                    <livewire:client.edit :title="'Edit client'"
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
