<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="data"
    :class="{'dark': darkMode }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    <link href="{{ url('assets/fontawesome-6.4.0/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ url('assets/fontawesome-6.4.0/css/solid.css') }}" rel="stylesheet">
    <link href="{{ url('css/trongate.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('css/app.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ url('site.webmanifest') }}">
    <link rel="mask-icon" href="{{ url('safari-pinned-tab.svg') }}" color="#0d6efd">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Styles, Scripts -->
    @vite(['resources/sass/main.sass', 'resources/js/app.js'])
    @livewireStyles

    @stack('google-charts')
</head>
<body @scroll="setScrollToTop()">

<div class="admin wrapper">

    <x-header></x-header>

    <x-admin.banner/>

    @yield('search')

    <div class="container">

        <div class="admin-content relative">

            <?php if (!isset($sidebar)) {
                $sidebar = null;
            } ?>
            <x-admin.sidebar :sidebar="$sidebar"></x-admin.sidebar>

            @yield('content')

        </div>
    </div>

    <span class="light-gray pointer scroll-to-top-button padding-0-5 round"
          role="button"
          aria-label="{{ __('To the top button') }}"
          title="{{ __('Toggle table of content') }}"
          x-show="scrollTop > 800"
          @click="scrollToTop"
          x-transition
    >
        <i class="fa fa-chevron-up" aria-hidden="true"></i>
    </span>

    <x-footer></x-footer>

</div>

@stack('modals')

@livewireScripts

<!-- To support inline scripts needed for the calendar library
https://laravel-livewire.com/docs/2.x/inline-scripts
-->
@stack('scripts')

<script src="{{ url('/js/prism.js') }}" type="text/javascript"></script>
</body>
</html>
