<x-admin-layout>

    @section('search')

    @endsection

    <x-slot name="sidebar">

        <div class="padding-1">
            It is the unknown we fear when we look upon death and darkness, nothing more.
        </div>

    </x-slot>

    @section('content')

        <main class="padding-1">
            <nav class="breadcrumb">
                <ol>
                    <li>
                        <a href="{{ url('/home') }}">{{ __('Home') }}</a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>{{ __('Page name') }}</li>
                </ol>
            </nav>

            <div class="main-content">
                Main content goes here.
            </div>
        </main>
    @endsection

</x-admin-layout>
