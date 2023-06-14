<aside>
    <header>
        <h3 class="text-white h5">{{ __('Table of Content') }}</h3>
    </header>
    <div class="sidebar-content">

        <!-- Custom content goes here -->
        <?php if ( isset( $sidebar ) ) { ?>

        {{ $sidebar }}

        <?php } ?><!-- Custom content goes here END -->

        <div class="padding-1">
            <ul class="navbar-nav margin-top-0 padding-left-0 no-bullets">
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <!-- Custom links -->

                    @role('super-administrator|administrator')
                    <!-- Event calendar link -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}"
                           href="{{ route('calendar') }}"
                        >
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            <span>{{ __('Manage events') }}</span>
                        </a>
                    </li>

                    <!-- Worker availabilities link -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('workers') ? 'active' : '' }}"
                           href="{{ route('workers') }}"
                        >
                            <i class="fa fa-hourglass-start" aria-hidden="true"></i>
                            <span>{{ __('Worker availabilities') }}</span></a>
                    </li>

                    <!-- Manage users link -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.manage') ? 'active' : '' }}"
                           href="{{ route('user.manage') }}"
                        >
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>{{ __('Manage users') }}</span>
                        </a>
                    </li>

                    <!-- Manage clients link -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.manage') ? 'active' : '' }}"
                           href="{{ route('client.manage') }}"
                        >
                            <i class="fa fa-address-card" aria-hidden="true"></i>
                            <span>{{ __('Manage clients') }}</span>
                        </a>
                    </li>
                    @endrole

                    @role('super-administrator')
                    <!-- Role/Permissions link -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('role-permission.manage') ? 'active' : '' }}"
                           href="{{ route('role-permission.manage') }}"
                        >
                            <i class="fa fa-lock" aria-hidden="true"></i>
                            <span>{{ __('Roles and Permissions') }}</span>
                        </a>
                    </li>
                    @endrole

                    <!-- Account link -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.account') ? 'active' : '' }}"
                           href="{{ route('user.account', auth()->id()) }}"
                        >
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>{{ __('My Account') }}</span>
                        </a>
                    </li>

                    <!-- Custom links END -->
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();"
                        >
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            {{ __('Logout') }}
                        </a>

                        <form
                            id="logout-form"
                            action="{{ route('logout') }}"
                            method="POST"
                            class="hide"
                        >
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</aside>
