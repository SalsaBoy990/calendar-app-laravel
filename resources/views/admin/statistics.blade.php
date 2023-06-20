<x-admin-layout>

    @section('content')
        <main class="padding-1">
            <h1 class="h2 margin-top-0 text-center">{{ __('Work statistics by clients') }}</h1>

            <div class="main-content">
                @auth
                    @role('super-administrator|administrator')
                    <livewire:statistics.widget></livewire:statistics.widget>
                    @endrole
                @endauth
            </div>
        </main>
    @endsection

</x-admin-layout>
