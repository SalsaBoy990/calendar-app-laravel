<aside>
    <header>
        <h3 class="text-white">{{ __('Table of Content') }}</h3>
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
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.manage') ? 'active' : '' }}"
                           href="{{ route('user.manage') }}"
                        >
                            <i class="fa fa-user" aria-hidden="true"></i>
                            {{ __('Manage users') }}</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('role-permission.manage') ? 'active' : '' }}"
                           href="{{ route('role-permission.manage') }}"
                        >
                            <i class="fa fa-lock" aria-hidden="true"></i>
                            {{ __('Roles and Permissions') }}</a>
                    </li>

                    <!-- Custom links END -->
                    <?php ?>
                    <li class="nav-item dropdown">
                        <a
                            id="navbarDropdown"
                            class="nav-link dropdown-toggle"
                            href="#"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            v-pre
                        >
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a
                                class="dropdown-item"
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
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</aside>
