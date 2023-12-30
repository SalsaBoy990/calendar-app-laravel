<footer class="page-footer">
    <div class="footer-content">
        <nav>
            <?php ?>
            @auth
                <a href="{{ url('/admin/dashboard') }}" class="">{{ __('Dashboard') }}</a>
            @else
                <a href="{{ route('login') }}" class="">{{ __('Log in') }}</a>
            @endauth
            <?php ?>
        </nav>
        <small>&copy; {{ __('2023 SzlaVi Cleaning Team. All rights reserved!') }}</small>
    </div>
</footer>
