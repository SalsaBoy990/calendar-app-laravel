<header class="page-header">
    <div class="header-content">
        <div class="logo">
            <a href="/" class="brand">
                <img src="{{ url('/images/logo.png') }}" alt="{{ config('app.name', 'Laravel') }}">
                {{ config('app.name') }}
            </a>
        </div>
        @if (Route::has('login'))
            <div class="main-navigation">
                <nav id="main-menu">
                    @auth
                        <a class="{{ request()->routeIs('home') ? 'active' : '' }}"
                           href="{{ url('/home') }}">
                            <i class="fa fa-home" aria-hidden="true"></i>{{ __('Home') }}
                        </a>

                        @role('super-administrator|administrator')
                        <a class="{{ request()->routeIs('calendar') ? 'active' : '' }}"
                           href="{{ route('calendar') }}">
                            <i class="fa fa-calendar" aria-hidden="true"></i>{{ __('Calendar') }}
                        </a>

                        <!-- Worker availabilities link -->
                        <a class="{{ request()->routeIs('workers') ? 'active' : '' }}"
                           href="{{ route('workers') }}">
                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                            {{ __('Availabilities') }}
                        </a>

                        <!-- Manage workers link -->
                        <a class="{{ request()->routeIs('worker.manage') ? 'active' : '' }}"
                           href="{{ route('worker.manage') }}">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            {{ __('Workers') }}
                        </a>

                        <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ url('/admin/dashboard') }}">
                            <i class="fa fa-tachometer" aria-hidden="true"></i>{{ __('Dashboard') }}
                        </a>
                        @endrole


                        <div
                            x-data="dropdownData"
                            class="dropdown-click"
                            @click.outside="hideDropdown"
                        >
                            <a @click="toggleDropdown">
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fa fa-caret-down"></i>
                            </a>

                            <div x-show="openDropdown" class="dropdown-content card padding-0-5">

                                <a class="dropdown-item"
                                   href="{{ route('user.account', auth()->id()) }}"
                                >
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <span>{{ __('My Account') }}</span>
                                </a>


                                <a
                                    class="dropdown-item"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();"
                                >
                                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                                    <span>{{ __('Logout') }}</span>

                                </a>

                                <form
                                    id="logout-form"
                                    action="{{ route('logout') }}"
                                    method="POST"
                                    class="d-none"
                                >
                                    @csrf
                                </form>
                            </div>
                        </div>

                    @else
                        <a href="{{ route('login') }}" class="">{{ __('Log in') }}</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="">{{ __('Register') }}</a>
                        @endif
                    @endauth

                </nav>

                @php
                    $light = __('Light mode');
                    $dark = __('Dark mode');
                @endphp

                <span
                    class="pointer darkmode-toggle"
                    rel="button"
                    @click="toggleDarkMode"
                    x-text="isDarkModeOn() ? '🔆' : '🌒'"
                    :title="isDarkModeOn() ? '{{ $light }}' : '{{ $dark }}'"
                >
                    </span>

                <div x-data="offCanvasMenuData">
                    <button id="main-menu-offcanvas-toggle"
                            @click="toggleOffcanvasMenu()"
                            class="primary alt margin-left-0-5"
                            data-collapse-toggle="navbar-default"
                            type="button"
                            aria-controls="navbar-default"
                            aria-expanded="false"
                    >
                        <span class="sr-only">{{__('Open main menu')}}</span>
                        <i :class="sidenav === true ? 'fa fa-times' : 'fa fa-bars'" aria-hidden="true"></i>
                    </button>
                    <div class="sidenav relative"
                         tabindex="-1"
                         id="main-menu-offcanvas"
                         @click.outside="closeOnOutsideClick()"
                    >
                        <a href="javascript:void(0)"
                           id="main-menu-close-button"
                           @click="closeOffcanvasMenu()"
                           class="close-btn fs-18 absolute topright padding-0-5"
                        >
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </a>

                        <div id="mobile-menu"></div>

                    </div>

                </div>
            </div>
        @endif
    </div>
</header>


