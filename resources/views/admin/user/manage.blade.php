<x-admin-layout>

    <x-slot name="sidebar">

        <div class="padding-1">
            It is the unknown we fear when we look upon death and darkness, nothing more.
        </div>

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
                <livewire:user.create :title="'New user'" :hasSmallButton="false" :modalId>
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
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles()->get(['name'])[0]->name }}</td>
                            <td>
                                <div class="flex">
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
                                                          :hasSmallButton="false"
                                                          :modalId="'m-edit-user-' . $user->id"
                                    >
                                    </livewire:user.edit>
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
