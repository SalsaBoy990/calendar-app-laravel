@extends('layouts.public')

@section('content')
    <main class="card content-600">
        <div class="header">
            <h1 class="h3 text-white">{{ __('Reset Password') }}</h1>
        </div>
        <div class="padding-1-5">

            @if (session('status'))
                <div class="alert success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <label for="email">{{ __('Email Address') }}</label>
                <input
                    type="email"
                    class="form-control @error('email') border border-dark-red @enderror"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    autofocus
                >

                @error('email')
                <span role="alert">
                    <strong class="text-red fs-14">{{ $message }}</strong>
                </span>
                @enderror

                <button type="submit" class="primary">{{ __('Send Password Reset Link') }}</button>
            </form>
        </div>
    </main>
@endsection
