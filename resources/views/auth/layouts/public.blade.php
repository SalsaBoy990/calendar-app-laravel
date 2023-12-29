<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="data"
    :class="{'dark': darkMode }"
    class="overflow-x-hidden"
>
<head>
    <script>
        document.querySelector('html').classList.add(localStorage.getItem('darkMode') === 'true' ? 'dark' : 'light')
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @yield('seo')

    <link href="{{ url('assets/fontawesome-6.4.0/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ url('assets/fontawesome-6.4.0/css/solid.css') }}" rel="stylesheet">
    <link href="{{ url('assets/fontawesome-6.4.0/css/brands.css') }}" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ url('site.webmanifest') }}">
    <link rel="mask-icon" href="{{ url('safari-pinned-tab.svg') }}" color="#0d6efd">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Scripts -->
    @vite(['resources/sass/main.sass', 'resources/js/app.js'])
</head>
<body @scroll="setScrollToTop()" class="overflow-x-hidden">

<x-public::header></x-public::header>


<div class="public wrapper">

    <div class="public-content container relative">

        @yield('content')

    </div>

    <span class="light-gray pointer scroll-to-top-button padding-0-5 round"
          role="button"
          aria-label="{{ __('To the top button') }}"
          title="{{ __('To the top button') }}"
          x-show="scrollTop > 800"
          @click="scrollToTop"
          x-transition
    >
        <i class="fa fa-chevron-up" aria-hidden="true"></i>
    </span>

    <x-public::footer></x-public::footer>

</div>

@stack('scripts')
</body>
</html>
