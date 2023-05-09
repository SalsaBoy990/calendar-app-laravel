<x-admin-layout>

    <x-slot name="sidebar">

        <div class="padding-1">
            It is the unknown we fear when we look upon death and darkness, nothing more.
        </div>

    </x-slot>

    @section('content')

        <main class="padding-1">
            <nav class="breadcrumb">
                <ol>
                    <li>
                        <a href="{{ url('/home') }}">{{ __('My Account') }}</a>
                    </li>
                    <li>
                        <span>/</span>
                    </li>
                    <li>{{ __('My Account') }}</li>
                </ol>
            </nav>

            <div class="main-content">

                <h1 class="fs-24">{{ __('My Account') }}</h1>

                <form action="{{ route('user.update', $user->id ) }}"
                      method="POST"
                      enctype="application/x-www-form-urlencoded"
                      accept-charset="UTF-8"
                      autocomplete="off"
                >
                    @method("PUT")
                    @csrf

                    <x-admin.validation-errors/>

                    <fieldset>
                        <!-- Name -->
                        <label for="name">{{ __('Name') }}</label>
                        <input
                            type="text"
                            class="{{ $errors->has('name') ? 'border border-red' : '' }}"
                            name="name"
                            value="{{ $user->name ?? old('name') }}"
                        >

                        <div class="{{ $errors->has('name') ? 'red' : '' }}">
                            {{ $errors->has('name') ? $errors->first('name') : '' }}
                        </div>


                        <!-- Email -->
                        <label for="email">{{ __('Email (can not be changed)') }}</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ $user->email }}"
                            readonly
                        >

                        <div class="{{ $errors->has('email') ? 'red' : '' }}">
                            {{ $errors->has('email') ? $errors->first('email') : '' }}
                        </div>

                        <!-- Password -->
                        <label for="password">{{ __('New Password (optional)') }}</label>
                        <input
                            type="text"
                            class="{{ $errors->has('password') ? 'border border-red' : '' }}"
                            name="password"
                            value="{{ old('password') }}"
                        >

                        <div class="{{ $errors->has('password') ? 'red' : '' }}">
                            {{ $errors->has('password') ? $errors->first('password') : '' }}
                        </div>

                        <div class="checkbox-container">
                            <label for="enable2fa">
                                <input name="enable2fa"
                                       type="checkbox"
                                       value="1"
                                    {{ old('enable2fa', $user->enable_2fa !== 0 ? 'checked' : '') }}
                                >
                                {{ __('Enable Two Factor Authentication') }}
                            </label>

                            <div class="{{ $errors->has('enable2fa') ? 'red' : '' }}">
                                {{ $errors->has('enable2fa') ? $errors->first('enable2fa') : '' }}
                            </div>
                        </div>


                    </fieldset>


                    <div>
                        <button type="submit" class="primary">{{ __("Update") }}
                        </button>

                        <a href="{{ route('user.account', $user->id)}}"
                           class="button alt bg-white">{{ __('Cancel') }}</a>
                    </div>
                </form>


            </div>
        </main>
    @endsection

</x-admin-layout>
