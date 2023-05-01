<footer class="page-footer">
    <div class="footer-content">
        <nav>
            <?php ?>
            @auth
                <a href="{{ url('/home') }}" class="">{{ __('Home') }}</a>
            @else
                <a href="{{ route('login') }}" class="">{{ __('Log in') }}</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="">{{ __('Register') }}</a>
                @endif
            @endauth
            <?php ?>
        </nav>
        <small>&copy; 2023 Gulácsi András. All rights reserved!</small>
    </div>
</footer>
