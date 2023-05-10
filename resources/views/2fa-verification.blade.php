@extends('layouts.public')

@section('content')

    <main class="card content-600">
        <div class="header">
            <h1 class="h2 text-white">{{ __('Two-factor authentication') }}</h1>
        </div>
        <div class="padding-1-5">
            <form method="POST" action="{{ route('2fa.post') }}">
                @csrf

                <p class="text-center">
                    We sent code to email
                    : {{ substr(auth()->user()->email, 0, 5) . '******' . substr(auth()->user()->email,  -2) }}
                </p>


                @if ($message = Session::get('success'))
                    <div class="panel success text-green-dark border border-green-dark relative">
                        <p><strong class="margin-0">{{ $message }}</strong></p>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="panel danger text-red-dark border border-red-dark relative">
                        <p><strong class="margin-0">{{ $message }}</strong></p>
                    </div>
                @endif

                <label for="code">Code</label>
                <input id="code"
                       type="number"
                       class="@error('code') border border-red @enderror"
                       name="code"
                       value="{{ old('code') }}"
                       required
                       autocomplete="code"
                       autofocus
                >

                @error('code')
                <span role="alert"><strong class="text-red fs-14">{{ $message }}</strong></span>
                @enderror


                <div class="bar margin-top-1">
                    <button type="submit" class="primary margin-top-1">
                        {{ __('Login') }}
                    </button>

                    <a class="button primary alt margin-top-1" href="{{ route('2fa.resend') }}">
                        {{ __('Resend Code?') }}</a>

                </div>

            </form>
        </div>
    </main>

@endsection


