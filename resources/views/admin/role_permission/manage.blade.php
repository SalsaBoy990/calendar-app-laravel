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
                    <li>{{ __('Manage Roles and Permissions') }}</li>
                </ol>
            </nav>

            <div class="main-content">

                @php
                    $activeTab = session('flash.activeTab') ?? 'Roles';
                @endphp

                <div x-data="tabsData( @js($activeTab) )" class="border round">

                    <div class="bar">
                        <a id="RolesTrigger" href="javascript:void(0)" class="bar-item tab-switcher"
                           @click="switchTab('Roles')" :class="{'red': tabId === 'Roles'}">{{ __('Roles') }}</a>
                        <a id="PermissionsTrigger" href="javascript:void(0)" class="bar-item tab-switcher"
                           @click="switchTab('Permissions')"
                           :class="{'red': tabId === 'Permissions'}">{{ __('Permissions') }}</a>
                    </div>

                    <div id="Roles" class="box tabs animate-opacity">

                        <h1 class="h2">Manage roles</h1>

                        <!-- Create role -->
                        <livewire:role.create :title="'New role'" :permissions="$permissions" :hasSmallButton="false"
                                              :modalId="'m-create-role'">
                        </livewire:role.create>

                        <table>
                            <thead>
                            <tr>
                                <th>Role</th>
                                <th>Slug</th>
                                <th>Permissions</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td><strong>{{ $role->name }}</strong></td>
                                    <td>{{ $role->slug }}</td>
                                    <td>
                                        @if($role->permissions->count() > 0)
                                            @foreach($role->permissions as $rolePermission)
                                                <span class="badge fs-14 gray-60">{{ $rolePermission->name }}</span>
                                            @endforeach
                                        @else
                                            <p class="fs-14">{{__('No associated permissions.')}}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex">
                                            <!-- Delete role -->
                                            <livewire:role.delete :title="'Delete role'"
                                                                  :role="$role"
                                                                  :hasSmallButton="false"
                                                                  :modalId="'m-delete-role-' . $role->id"
                                            >
                                            </livewire:role.delete>

                                            <!-- Update role -->
                                            <livewire:role.edit :title="'Edit role'"
                                                                :role="$role"
                                                                :permissions="$permissions"
                                                                :hasSmallButton="false"
                                                                :modalId="'m-edit-role-' . $role->id"
                                            >
                                            </livewire:role.edit>

                                        </div>

                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="Permissions" class="box tabs animate-opacity">


                        <h1 class="h2">Manage permissions</h1>

                        <!-- Create role -->
                        <livewire:permission.create :title="'New permission'" :roles="$roles" :hasSmallButton="false"
                                                    :modalId="'m-create-permission'">
                        </livewire:permission.create>

                        <table>
                            <thead>
                            <tr>
                                <th>Permission</th>
                                <th>Slug</th>
                                <th>Roles</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->slug }}</td>
                                    <td>
                                        @if($permission->roles->count() > 0)
                                            @foreach($permission->roles as $permissionRole)
                                                <span class="badge fs-14 gray-60">{{ $permissionRole->name }}</span>
                                            @endforeach
                                        @else
                                            <p class="fs-14">{{__('No associated roles.')}}</p>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="flex">
                                            <!-- Delete role -->
                                            <livewire:permission.delete :title="'Delete permission'"
                                                                        :permission="$permission"
                                                                        :hasSmallButton="false"
                                                                        :modalId="'m-delete-permission-' . $permission->id"
                                            >
                                            </livewire:permission.delete>

                                            <!-- Update role -->
                                            <livewire:permission.edit :title="'Edit permission'"
                                                                      :permission="$permission"
                                                                      :roles="$roles"
                                                                      :hasSmallButton="false"
                                                                      :modalId="'m-edit-permission-' . $permission->id"
                                            >
                                            </livewire:permission.edit>

                                        </div>

                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>


                    </div>
                </div>


            </div>
        </main>
    @endsection

</x-admin-layout>

