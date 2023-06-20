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
                    <li>{{ __('Manage Workers') }}</li>
                </ol>
            </nav>

            <h1 class="h3 margin-top-bottom-0">{{ __('Manage Workers') }}</h1>

            <div class="main-content">

                <!-- Create new worker -->
                <livewire:worker.create title="{{ __('New worker') }}"
                                        :hasSmallButton="false"
                                        :modalId="'m-create-worker'">
                </livewire:worker.create>

                <table>
                    <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($workers as $worker)
                        <tr>
                            <td><b>{{ $worker->name }}</b></td>
                            <td>{{ $worker->email }}</td>
                            <td>{{ $worker->phone }}</td>
                            <td>
                                <div class="flex">

                                    @if( auth()->user()->hasRoles('super-administrator|administrator') )

                                        <!-- Delete user -->
                                        <livewire:worker.delete title="{{ __('Delete worker') }}"
                                                                :worker="$worker"
                                                                :hasSmallButton="false"
                                                                :modalId="'m-delete-worker-' . $worker->id"
                                        >
                                        </livewire:worker.delete>

                                        <!-- Update user -->
                                        <livewire:worker.edit title="{{ __('Edit worker') }}"
                                                              :worker="$worker"
                                                              :hasSmallButton="false"
                                                              :modalId="'m-edit-worker-' . $worker->id"
                                        >
                                        </livewire:worker.edit>
                                    @endif
                                </div>

                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

                {{ $workers->links('components.pagination') }}

            </div>
        </main>
    @endsection

</x-admin-layout>
