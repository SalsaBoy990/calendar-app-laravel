<x-admin-layout>

    @section('search')

    @endsection

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
                    <li>{{ __('Dashboard') }}</li>
                </ol>
            </nav>

            <div class="main-content">

                <ul class="dashboard-card-grid">

                    <li class="card padding-1 text-center">
                        <a href="{{ url('/home') }}" class="padding-top-bottom-1">
                            <i class="fa fa-home" aria-hidden="true"></i>{{ __('Home') }}
                        </a>
                    </li>

                    <!-- Custom links -->
                    <li class="card padding-1 text-center">
                        <a class="padding-top-bottom-1" href="{{ route('user.manage') }}">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            {{ __('Manage users') }}</a>
                    </li>

                    <li class="card padding-1 text-center">
                        <a class="padding-top-bottom-1" href="{{ route('role-permission.manage') }}">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                            {{ __('Roles and Permissions') }}</a>
                    </li>
                </ul>
            </div>
        </main>
    @endsection

</x-admin-layout>
