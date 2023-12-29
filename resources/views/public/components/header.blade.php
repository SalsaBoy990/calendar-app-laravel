<header class="page-header">
    <div class="header-content">
        <div class="logo">
            <a href="/" class="brand">
                <img src="{{ url('/images/logo.png') }}" alt="{{ config('app.name', 'Laravel') }}">
            </a>
        </div>
        @if (Route::has('login'))
            <div class="main-navigation">
                <nav id="main-menu">

                    <a class="{{ request()->routeIs('blog.index') ? 'active' : '' }}"
                       href="{{ url('/') }}">
                        <i class="fa fa-home" aria-hidden="true"></i>{{ __('Home') }}
                    </a>

                    @guest
                    <a class="{{ request()->routeIs('login') ? 'active' : '' }}"
                       href="{{ route('login') }}">
                        <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                        {{ __('Login') }}
                    </a>

                    <a class="{{ request()->routeIs('register') ? 'active' : '' }}"
                       href="{{ route('register') }}">
                        <i class="fa fa-user-alt" aria-hidden="true"></i>{{ __('Register') }}
                    </a>
                    @endguest

                    @auth
                        <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           href="{{ route('dashboard') }}">
                            <i class="fa fa-tachometer" aria-hidden="true"></i>{{ __('Dashboard') }}
                        </a>


                    <div
                        x-data="dropdownData"
                        class="dropdown-click"
                        @click.outside="hideDropdown"
                    >
                        <a class="fs-16" @click="toggleDropdown">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fa fa-caret-down"></i>
                        </a>

                        <div x-show="openDropdown" x-cloak class="dropdown-content card padding-0-5">

                            <a class="fs-16 dropdown-item"
                               href="{{ route('user.account', auth()->id()) }}"
                            >
                                <i class="fa fa-user" aria-hidden="true"></i>
                                <span>{{ __('My Account') }}</span>
                            </a>


                            <a
                                id="logout-form-admin-header-trigger"
                                class="fs-16 dropdown-item"
                                href="#"
                                role="button"
                                onclick="triggerLogout('logout-form-admin-header')"
                            >
                                <i class="fa fa-sign-out" aria-hidden="true"></i>
                                <span>{{ __('Logout') }}</span>

                            </a>

                            <form
                                id="logout-form-admin-header"
                                action="{{ route('logout') }}"
                                method="POST"
                                class="d-none"
                            >
                                @csrf
                                @method('post')
                            </form>
                        </div>
                    </div>
                    @endauth

                </nav>

                @php
                    $light = __('Light mode');
                    $dark = __('Dark mode');
                @endphp
                <button
                    class="darkmode-toggle margin-top-0"
                    rel="button"
                    @click="toggleDarkMode"
                    x-text="isDarkModeOn() ? '🔆' : '🌒'"
                    :title="isDarkModeOn() ? '{{ $light }}' : '{{ $dark }}'"
                >
                </button>

                <div x-data="offCanvasMenuData">
                    <div class="flex flex-row">
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
                    </div>
                    <div class="sidenav relative"
                         tabindex="-1"
                         id="main-menu-offcanvas"
                         @click.outside="closeOnOutsideClick()"
                    >
                        <a href="#"
                           role="button"
                           aria-label="{{ __('Close sidebar') }}"
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


