<footer class="page-footer public-footer">
    <div class="footer-content">
        <div class="logo margin-bottom-1">
            <a href="/" class="brand">
                <img src="{{ url('/images/logo.png') }}" alt="{{ config('app.name', 'Laravel') }}">
            </a>
        </div>

        <nav class="flex flex-row justify-center">
            <a class="{{ request()->routeIs('blog.index') ? 'active' : '' }}"
               href="{{ url('/') }}">
                <i class="fa fa-home margin-right-0-5" aria-hidden="true"></i>{{ __('Home') }}</a>

            @auth
                <a href="{{ url('/admin/dashboard') }}" class="">{{ __('Dashboard') }}</a>
            @endauth
            <?php ?>
        </nav>

        <small class="text-gray-40 fs-12">&copy; 2023 {{ config('app.name', 'Laravel') }}
            . {{ __('All rights reserved!') }}</small>
    </div>
</footer>
