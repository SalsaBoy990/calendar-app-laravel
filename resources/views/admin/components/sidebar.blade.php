<aside>
    <header>
        <h3 class="text-white fs-18">{{ Auth::user()->name }}</h3>
    </header>
    <div class="sidebar-content">

        <!-- Custom content goes here -->
        <?php if (isset($sidebar)) { ?>

        {{ $sidebar }}

        <?php } ?><!-- Custom content goes here END -->

        <div class="padding-top-bottom-1">
            <ul class="navbar-nav margin-top-0 padding-left-right-0 no-bullets">
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
                            <i class="fa-regular fa-clock" aria-hidden="true"></i>
                            <span>{{ __('Worker availabilities') }}</span></a>
                    </li>

                    <!-- Manage workers link -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('worker.manage') ? 'active' : '' }}"
                           href="{{ route('worker.manage') }}"
                        >
                            <i class="fa-solid fa-person-digging" aria-hidden="true"></i>
                            <span>{{ __('Manage workers') }}</span>
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

                    <!-- Get worked hours statistics link -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('statistics') ? 'active' : '' }}"
                           href="{{ route('statistics') }}"
                        >
                            <i class="fa fa-line-chart" aria-hidden="true"></i>
                            <span>{{ __('Statistics') }}</span>
                        </a>
                    </li>
                    @endrole

                    @role('super-administrator')
                    <!-- Manage users link -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.manage') ? 'active' : '' }}"
                           href="{{ route('user.manage') }}"
                        >
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>{{ __('Manage users') }}</span>
                        </a>
                    </li>

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
                            id="logout-form-admin-sidebar-trigger"
                            class="nav-link"
                            href="#"
                            role="button"
                        >
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            {{ __('Logout') }}
                        </a>

                        <form
                            id="logout-form-admin-sidebar"
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
