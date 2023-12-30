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
                <li>{{ __('Manage Workers') }}</li>
            </ol>
        </nav>

        <h1 class="h3 margin-top-bottom-0">{{ __('Manage Workers') }}</h1>

        <div class="main-content">

            <!-- Create new worker -->
            <livewire:admin.worker.create title="{{ __('New worker') }}"
                                          :hasSmallButton="false"
                                          :modalId="'m-create-worker'">
            </livewire:admin.worker.create>

            @if($workers->count() > 0)
                <table>
                    <thead>
                    <tr class="fs-14">
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Bank info') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($workers as $worker)
                        <tr>
                            <td><b>{{ $worker->name }}</b><br>{{ $worker->email }}</td>
                            <td>{{ $worker->phone }}</td>
                            <td>
                                {{ $worker->bank_account_name }}
                                <br>
                                <b>{{ $worker->bank_account_number }}</b>
                            </td>
                            <td>
                                <div class="flex flex-row flex-wrap">

                                    @if( auth()->user()->hasRoles('super-administrator|administrator') )

                                        <!-- Delete user -->
                                        <livewire:admin.worker.delete title="{{ __('Delete worker') }}"
                                                                      :worker="$worker"
                                                                      :hasSmallButton="false"
                                                                      :modalId="'m-delete-worker-' . $worker->id"
                                        >
                                        </livewire:admin.worker.delete>

                                        <!-- Update user -->
                                        <livewire:admin.worker.edit title="{{ __('Edit worker') }}"
                                                                    :worker="$worker"
                                                                    :hasSmallButton="false"
                                                                    :modalId="'m-edit-worker-' . $worker->id"
                                        >
                                        </livewire:admin.worker.edit>
                                    @endif
                                </div>

                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $workers->links('global.components.pagination', [ 'pageName' => 'page']) }}
            @else
                <p>{{ __('There are no workers yet.') }}</p>
            @endif

        </div>
    </main>
@endsection

