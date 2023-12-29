@extends('admin.layouts.admin')

@section('content')
    <main class="padding-1">
        <h1 class="h2 margin-top-0 text-center">{{ __('Work statistics by clients') }}</h1>

        <div>
            @auth
                @role('super-administrator|administrator')
                <livewire:admin.statistics.widget></livewire:admin.statistics.widget>
                @endrole
            @endauth
        </div>
    </main>
@endsection

