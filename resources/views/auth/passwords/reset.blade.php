@extends('auth.layouts.public')

@section('content')

    <main class="card content-600">
        <div class="header">
            <h1 class="h3 text-white">{{ __('Reset Password') }}</h1>
        </div>
        <div class="padding-1-5">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <label for="email">{{ __('Email Address') }}</label>
                <input
                    type="email"
                    class="@error('email') border border-dark-red @enderror"
                    name="email"
                    id="email"
                    value="{{ $email ?? old('email') }}"
                    required
                    autocomplete="email"
                    autofocus
                >
                @error('email')
                <span role="alert">
                    <strong class="text-red fs-14">{{ $message }}</strong>
                </span>
                @enderror


                <label for="password">{{ __('Password') }}</label>
                <input
                    type="password"
                    class="@error('password') border border-dark-red @enderror"
                    name="password"
                    id="password"
                    required
                    autocomplete="new-password"
                >
                @error('password')
                <span role="alert">
                    <strong class="text-red fs-14">{{ $message }}</strong>
                </span>
                @enderror


                <label for="password-confirm">{{ __('Confirm Password') }}</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password-confirm"
                    required
                    autocomplete="new-password"
                >


                <button type="submit" class="primary">{{ __('Reset Password') }}</button>
            </form>
        </div>
    </main>

@endsection
