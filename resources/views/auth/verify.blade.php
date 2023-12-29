@extends('auth.layouts.public')

@section('content')
    <main class="card content-600">
        <div class="header">
            <h1 class="h3 text-white">{{ __('Verify Your Email Address') }}</h1>
        </div>
        <div class="padding-1-5">

            @if (session('resent'))
                <div class="alert success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            {{ __('Before proceeding, please check your email for a verification link.') }}
            {{ __('If you did not receive the email') }},
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="primary">{{ __('click here to request another') }}</button>
            </form>
        </div>
    </main>

@endsection
