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

    <x-admin.head.tinymce-config/>

</head>
<body @scroll="setScrollToTop()">

<div class="admin wrapper">

    <x-header></x-header>

    <x-admin.banner/>

    @yield('search')

    <div class="container">

        <div class="admin-content relative">

            <?php if ( ! isset( $sidebar ) ) {
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
