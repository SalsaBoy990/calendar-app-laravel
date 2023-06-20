<x-admin-layout>

    @section('content')

        <main class="padding-1">
            <h1 class="h2 margin-top-0 text-center">{{ __('Dashboard') }}</h1>

            <div class="main-content">

                @auth
                    <ul class="dashboard-card-grid">

                        @role('super-administrator|administrator')
                        <!-- Custom links -->
                        <!-- Manage workers link -->
                        <li class="card text-center">
                            <a class="card-link" href="{{ route('worker.manage') }}">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                {{ __('Manage workers') }}
                            </a>
                        </li>

                        <!-- Event calendar link -->
                        <li class="card text-center">
                            <a class="card-link" href="{{ route('calendar') }}">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                                <span>{{ __('Manage events') }}</span>
                            </a>
                        </li>

                        <!-- Worker availabilities link -->
                        <li class="card text-center">
                            <a class="card-link" href="{{ route('workers') }}">
                                <i class="fa fa-clock-o" aria-hidden="true"></i>
                                <span>{{ __('Worker availabilities') }}</span>
                            </a>
                        </li>

                        <!-- Manage clients link -->
                        <li class="card text-center">
                            <a class="card-link" href="{{ route('client.manage') }}">
                                <i class="fa fa-address-card" aria-hidden="true"></i>
                                <span>{{ __('Manage clients') }}</span>
                            </a>
                        </li>

                        <li class="card text-center">
                            <a href="{{ route('statistics') }}" class="card-link">
                                <i class="fa fa-line-chart" aria-hidden="true"></i>{{ __('Statistics') }}
                            </a>
                        </li>
                        @endrole

                        @role('super-administrator')
                        <!-- Manage users link -->
                        <li class="card text-center">
                            <a class="card-link" href="{{ route('user.manage') }}">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                {{ __('Manage users') }}
                            </a>
                        </li>


                        <!-- Manage roles and permissions link -->
                        <li class="card text-center">
                            <a class="card-link" href="{{ route('role-permission.manage') }}">
                                <i class="fa fa-lock" aria-hidden="true"></i>
                                {{ __('Roles and Permissions') }}
                            </a>
                        </li>
                        @endrole

                        <!-- Account link -->
                        <li class="card text-center">
                            <a class="card-link" href="{{ route('user.account', auth()->id()) }}">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <span>{{ __('My Account') }}</span>
                            </a>
                        </li>

                        <!-- Logout link -->
                        <li class="card text-center">
                            <a class="card-link"
                               href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                    document.getElementById('logout-form-dashboard').submit();"
                            >
                                <i class="fa fa-sign-out" aria-hidden="true"></i>
                                {{ __('Logout') }}
                            </a>
                            <form
                                id="logout-form-dashboard"
                                action="{{ route('logout') }}"
                                method="POST"
                                class="hide"
                            >
                                @csrf
                            </form>
                        </li>

                    </ul>
                @endauth
            </div>
        </main>
    @endsection

</x-admin-layout>
