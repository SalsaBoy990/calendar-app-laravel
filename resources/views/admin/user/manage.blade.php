<x-admin-layout>

    <x-slot name="sidebar">
    </x-slot>

    @section('content')

        <main class="padding-1">
            <nav class="breadcrumb">
                <ol>
                    <li>
                        <a href="{{ url('/home') }}">{{ __('Home') }}</a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>{{ __('Manage Users') }}</li>
                </ol>
            </nav>

            <div class="main-content">

                <!-- Create new user -->
                <livewire:user.create :title="'New user'"
                                      :roles="$roles"
                                      :permissions="$permissions"
                                      :hasSmallButton="false"
                                      :modalId="'m-create-user'">
                </livewire:user.create>

                <table>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
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
                                        <livewire:user.delete :title="'Delete user'"
                                                              :user="$user"
                                                              :hasSmallButton="false"
                                                              :modalId="'m-delete-user-' . $user->id"
                                        >
                                        </livewire:user.delete>

                                        <!-- Update user -->
                                        <livewire:user.edit :title="'Edit user'"
                                                            :user="$user"
                                                            :roles="$roles"
                                                            :permissions="$permissions"
                                                            :hasSmallButton="false"
                                                            :modalId="'m-edit-user-' . $user->id"
                                        >
                                        </livewire:user.edit>
                                    @else
                                        <p class="fs-14 italic">{{__('A szuperadminisztrátor nem törölhető és nem szerkeszthető.')}}</p>
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
