@auth

    <div
        x-data="dropdownData"
        role="button"
        aria-label="Legördülő menu"
        class="dropdown-click user-dropdown-menu {{ $className }}"
        @click.outside="hideDropdown"
    >
        <a @click="toggleDropdown">
            <i class="fa fa-user fs-14" aria-hidden="true"></i>
            <span>{{ Auth::user()->name ?? '' }}</span>
            <i class="fa fa-caret-down"></i>
        </a>

        <div x-show="openDropdown" x-cloak class="dropdown-content card padding-0-5">

            <a class="dropdown-item margin-bottom-1 {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}"
            >
                <i class="fa fa-tachometer" aria-hidden="true"></i>
                <span>{{ __('Dashboard') }}</span>
            </a>


            <a class="dropdown-item margin-bottom-1 {{ request()->routeIs('user.account') ? 'active' : '' }}"
               href="{{ route('user.account', auth()->id()) }}"
            >
                <i class="fa fa-user" aria-hidden="true"></i>
                <span>{{ __('My Account') }}</span>
            </a>

            <a
                id="user-dropdown-logout-trigger"
                class="dropdown-item margin-bottom-1"
                href="#"
                role="button"
            >
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                <span>{{ __('Logout') }}</span>

            </a>

            <form
                id="user-dropdown-logout"
                action="{{ route('logout') }}"
                method="POST"
                class="d-none"
            >
                @csrf
            </form>
        </div>
    </div>

@else
    <div class="user-dropdown-menu {{ $className }}">

    </div>
@endauth
