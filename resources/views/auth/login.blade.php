@extends('auth.layouts.public')

@section('content')

    <main class="card content-600">
        <div class="header">
            <h1 class="h3 text-white">{{ __('Login') }}</h1>
        </div>
        <div class="padding-1-5">
            @php $error = session('login_error') @endphp
            @isset($error)
                <div class="alert error" role="alert">
                    {{ $error }}
                </div>
            @endisset

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <label for="email">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="@error('email') border border-red @enderror" name="email"
                       value="{{ old('email') }}" required autocomplete="email" autofocus>

                @error('email')
                <span role="alert"><strong class="text-red fs-14">{{ $message }}</strong></span>
                @enderror

                <label for="password">{{ __('Password') }}</label>
                <input id="password" type="password" class="@error('password') border border-red @enderror"
                       name="password"
                       required autocomplete="current-password">

                @error('password')
                <span role="alert"><strong class="text-red fs-14">{{ $message }}</strong></span>
                @enderror

                <div class="inline-block margin-top-1 margin-bottom-1">
                    <input type="checkbox" name="remember" id="remember"
                           class="margin-left-0" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" style="display: inline">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <div class="bar">
                    <button type="submit" class="primary margin-top-1">
                        {{ __('Login') }}
                    </button>

                    @if (Route::has('password.request'))
                        <a class="button primary alt margin-top-1" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>

                <hr class="divider">

                {{-- Login with Facebook --}}
                <div class="">
                    <a class="button primary" href="{{ route('facebook.redirect') }}"
                       style="background: #3B5499; color: #ffffff; padding: 10px; width: 100%; display: block;">
                        <i class="fa-brands fa-facebook-f"></i> Login with Facebook
                    </a>
                </div>

            </form>
        </div>
    </main>

@endsection
