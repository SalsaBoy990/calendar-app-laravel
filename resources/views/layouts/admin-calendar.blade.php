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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ url('css/trongate.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('css/app.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('css/prism.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Styles, Scripts -->
    @vite(['resources/sass/main.sass', 'resources/js/app.js'])
    @livewireStyles

</head>
<body @scroll="setScrollToTop()">

<div class="admin wrapper">

    <x-admin.banner/>


    <div class="container padding-top-0">

        <div class="admin-content admin-nosidebar relative">

            @yield('content')

        </div>
    </div>

    <span class="light-gray pointer scroll-to-top-button padding-0-5 round"
          role="button"
          title="{{ __('To the top button') }}"
          aria-label="{{ __('To the top button') }}"
          x-show="scrollTop > 800"
          @click="scrollToTop"
          x-transition
    >
        <i class="fa fa-chevron-up" aria-hidden="true"></i>
    </span>

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

