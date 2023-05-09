<!doctype html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="data"
    :class="{'dark': darkMode }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ url('css/trongate.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ url('css/app.css') }}" rel="stylesheet" type="text/css"/>
    <!-- Scripts -->
    @vite(['resources/sass/main.sass', 'resources/js/app.js'])
</head>
<body>

<x-header></x-header>

<x-admin.banner/>

<div class="public wrapper">

    <div class="public-content container relative">

        @yield('content')

    </div>

    <x-footer></x-footer>

</div>

</body>
</html>
