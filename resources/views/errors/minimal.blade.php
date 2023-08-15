<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ url('site.webmanifest') }}">
    <link rel="mask-icon" href="{{ url('safari-pinned-tab.svg') }}" color="#0d6efd">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Scripts -->
    @vite(['resources/sass/main.sass'])

</head>
<body class="error-page">
<div class="relative gray-10 error-page-content">
    <div class="absolute middle center content-800 margin-left-right-auto">
        <div class="text-center">
            <div class="logo">
                <a href="/" class="brand">
                    <img src="{{ url('/images/szlavi-cleaning-team.png') }}" alt="{{ config('app.name', 'Laravel') }}">
                </a>
            </div>

            <h1 class="fs-64 text-gray-40 text-center margin-bottom-0">
                @yield('code')
            </h1>

            <div class="fs-24 text-gray-60 margin-bottom-1-5">
                @yield('message')
            </div>

            <a href="/" class="button primary">{{ __('Front page') }}</a>
        </div>
    </div>
</div>
</body>
</html>
