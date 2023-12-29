@extends('auth.layouts.public')

@section('content')
    <main class="card content-600">
        <div class="header">
            <h1 class="h3 text-white">{{ __('Confirm Password') }}</h1>
        </div>
        <div class="padding-1-5">
            {{ __('Please confirm your password before continuing.') }}

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <label for="password">{{ __('Password') }}</label>
                <input
                    id="password"
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    required
                    autocomplete="current-password"
                >

                @error('email')
                <span role="alert">
                    <strong class="text-red fs-14">{{ $message }}</strong>
                </span>
                @enderror

                <button type="submit" class="primary">
                    {{ __('Confirm Password') }}
                </button>

                @if (Route::has('password.request'))
                    <a class="button primary alt margin-top-1" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif

            </form>
        </div>
    </main>

@endsection
