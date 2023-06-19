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
                    <li>{{ __('Manage Roles and Permissions') }}</li>
                </ol>
            </nav>

            <div class="main-content">

                @php
                    $activeTab = session('flash.activeTab') ?? 'Roles';
                @endphp

                <div x-data="tabsData( @js($activeTab) )" class="border border-40 round">

                    <div class="bar">
                        <a id="RolesTrigger"
                           href="javascript:void(0)"
                           class="bar-item tab-switcher"
                           @click="switchTab('Roles')"
                           :class="{'red': tabId === 'Roles'}"
                        >
                            {{ __('Roles') }}
                        </a>

                        <a id="PermissionsTrigger"
                           href="javascript:void(0)"
                           class="bar-item tab-switcher"
                           @click="switchTab('Permissions')"
                           :class="{'red': tabId === 'Permissions'}"
                        >
                            {{ __('Permissions') }}
                        </a>
                    </div>

                    <div id="Roles" class="box tabs animate-opacity">

                        <h1 class="h2">{{ __('Manage roles') }}</h1>

                        <!-- Create role -->
                        <livewire:role.create title="{{ __('New role') }}"
                                              :permissions="$permissions"
                                              :hasSmallButton="false"
                                              :modalId="'m-create-role'"
                        >
                        </livewire:role.create>

                        <table>
                            <thead>
                            <tr>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Slug') }}</th>
                                <th>{{ __('Permissions') }}</th>
                                <th>{{ __('Actions') }}</th>
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
                                            <livewire:role.delete title="{{ __('Delete role') }}"
                                                                  :role="$role"
                                                                  :hasSmallButton="false"
                                                                  :modalId="'m-delete-role-' . $role->id"
                                            >
                                            </livewire:role.delete>

                                            <!-- Update role -->
                                            <livewire:role.edit title="{{ __('Edit role') }}"
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


                        <h1 class="h2">{{ __('Manage permissions') }}</h1>

                        <!-- Create role -->
                        <livewire:permission.create title="{{ __('New permission') }}" :roles="$roles" :hasSmallButton="false"
                                                    :modalId="'m-create-permission'">
                        </livewire:permission.create>

                        <table>
                            <thead>
                            <tr>
                                <th>{{ __('Permission') }}</th>
                                <th>{{ __('Slug') }}</th>
                                <th>{{ __('Roles') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $permission)
                                <tr>
                                    <td><b>{{ $permission->name }}</b></td>
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
                                            <livewire:permission.delete title="{{ __('Delete permission') }}"
                                                                        :permission="$permission"
                                                                        :hasSmallButton="false"
                                                                        :modalId="'m-delete-permission-' . $permission->id"
                                            >
                                            </livewire:permission.delete>

                                            <!-- Update role -->
                                            <livewire:permission.edit title="{{ __('Edit permission') }}"
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

