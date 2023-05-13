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
                        <a href="{{ url('/home') }}">
                            <i class="fa fa-home" aria-hidden="true"></i>{{ __('Home') }}
                        </a>

                        @role('super-administrator|administrator')
                        <a href="{{ route('calendar') }}">
                            <i class="fa fa-calendar" aria-hidden="true"></i>{{ __('Calendar') }}
                        </a>
                        <a href="{{ url('/admin/dashboard') }}">
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
                                {{ Auth::user()->name }}
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

                <button id="main-menu-offcanvas-toggle" class="primary alt margin-left-0-5" data-collapse-toggle="navbar-default" type="button"
                        aria-controls="navbar-default" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
            </div>
        @endif
    </div>
</header>

<div class="sidenav relative" tabindex="-1" id="main-menu-offcanvas">
    <a href="javascript:void(0)" id="main-menu-close-button" class="close-btn fs-18 absolute topright padding-1">
        <i class="fa fa-times" aria-hidden="true"></i>
    </a>

    <div id="mobile-menu"></div>

</div>
