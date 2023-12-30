@extends('admin.layouts.admin')

@section('content')

    <main class="padding-1">
        <nav class="breadcrumb breadcrumb-left">
            <ol>
                <li>
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li>
                    <i class="fa-solid fa-angle-right"></i>
                </li>
                <li>{{ __('Manage Clients') }}</li>
            </ol>
        </nav>

        <h1 class="h3 margin-top-bottom-0">{{ __('Manage clients') }}</h1>

        <div class="main-content">


            <!-- Create new user -->
            <livewire:admin.client.create></livewire:admin.client.create>

            @if($clients->count() > 0)
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
                                    <livewire:admin.client.delete title="{{ __('Delete client') }}"
                                                                  :client="$client"
                                                                  :modalId="'m-delete-client-' . $client->id"
                                    >
                                    </livewire:admin.client.delete>

                                    <!-- Update user -->
                                    <livewire:admin.client.edit title="{{ __('Edit client') }}"
                                                                :client="$client"
                                                                :modalId="'m-edit-client-' . $client->id"
                                    >
                                    </livewire:admin.client.edit>

                                </div>

                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>
                {{ $clients->links('global.components.pagination', [ 'pageName' => 'page']) }}

            @else
                <p>{{ __('There are no clients yet.') }}</p>
            @endif

        </div>
    </main>
@endsection

