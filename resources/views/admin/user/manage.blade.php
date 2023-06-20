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
                    <li>{{ __('Manage Users') }}</li>
                </ol>
            </nav>

            <h1 class="h3 margin-top-bottom-0">{{ __('Manage users') }}</h1>

            <div class="main-content">

                <!-- Create new user -->
                <livewire:user.create title="{{ __('New user') }}"
                                      :roles="$roles"
                                      :permissions="$permissions"
                                      :hasSmallButton="false"
                                      :modalId="'m-create-user'">
                </livewire:user.create>

                <table>
                    <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td><b>{{ $user->name }}</b></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ isset($user->role) ? $user->role->name : '' }}</td>
                            <td>
                                <div class="flex">

                                    @if(! $user->hasRoles('super-administrator') || auth()->user()->hasRoles('super-administrator') )

                                        <!-- Delete user -->
                                        <livewire:user.delete title="{{ __('Delete user') }}"
                                                              :user="$user"
                                                              :hasSmallButton="false"
                                                              :modalId="'m-delete-user-' . $user->id"
                                        >
                                        </livewire:user.delete>

                                        <!-- Update user -->
                                        <livewire:user.edit title="{{ __('Edit user') }}"
                                                            :user="$user"
                                                            :roles="$roles"
                                                            :permissions="$permissions"
                                                            :hasSmallButton="false"
                                                            :modalId="'m-edit-user-' . $user->id"
                                        >
                                        </livewire:user.edit>
                                    @else
                                        <p class="fs-14 italic">{{ __('Super-admin cannot be deleted or edited here.') }}</p>
                                    @endif
                                </div>

                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </main>
    @endsection

</x-admin-layout>
