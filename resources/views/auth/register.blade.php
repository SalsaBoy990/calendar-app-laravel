@extends('layouts.public')

@section('content')
    <main class="card content-600">

        <div class="header">
            <h1 class="h2 text-white">{{ __('Register') }}</h1>
        </div>

        <div class="padding-1-5">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <label for="name">{{ __('Name') }}</label>
                <input
                    id="name"
                    type="text"
                    class="@error('name') border border-dark-red @enderror"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    autofocus
                >

                @error('name')
                <span role="alert">
                    <strong class="text-red fs-14">{{ $message }}</strong>
                </span>
                @enderror

                <label for="email">{{ __('Email Address') }}</label>
                <input
                    id="email"
                    type="email"
                    class="@error('email') border border-dark-red @enderror"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                >

                @error('email')
                <span role="alert"><strong class="text-red fs-14">{{ $message }}</strong></span>
                @enderror

                <label for="password">{{ __('Password') }}</label>
                <input
                    id="password"
                    type="password"
                    class="@error('password') border border-dark-red @enderror"
                    name="password"
                    required
                    autocomplete="new-password"
                >

                @error('password')
                <span role="alert"><strong class="text-red fs-14">{{ $message }}</strong></span>
                @enderror

                <label for="password-confirm">{{ __('Confirm Password') }}</label>
                <input
                    id="password-confirm"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                >

                <button type="submit" class="primary margin-top-1-5">{{ __('Register') }}</button>
            </form>
        </div>

    </main>
@endsection
