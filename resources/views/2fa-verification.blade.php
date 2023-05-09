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
                    <div x-data="alertData" x-show="openAlert"
                         class="panel success text-green-dark border border-green-dark relative">
                        <span @click="hideAlert()" class="close-button fs-18 white-transparent topright">&times;</span>
                        <p><strong class="margin-0">{{ $message }}</strong></p>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div x-data="alertData" x-show="openAlert"
                         class="panel danger text-red-dark border border-red-dark relative">
                        <span @click="hideAlert()" class="close-button fs-18 white-transparent topright">&times;</span>
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


