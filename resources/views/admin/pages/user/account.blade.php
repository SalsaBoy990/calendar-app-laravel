@extends('admin.layouts.admin')

@section('content')

    <main class="padding-1">
        <nav class="breadcrumb breadcrumb-left">
            <ol>
                <li>
                    <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                </li>
                <li>
                    <i class="fa-solid fa-angle-right"></i>
                </li>
                <li>{{ __('My Account') }}</li>
            </ol>
        </nav>

        <div class="main-content">

            <h1 class="h2 margin-0">{{ __('My Account') }}</h1>

            <form action="{{ route('user.update', $user->id ) }}"
                  method="POST"
                  enctype="application/x-www-form-urlencoded"
                  accept-charset="UTF-8"
                  autocomplete="off"
            >
                @method("PUT")
                @csrf

                <x-global::validation-errors/>

                <fieldset>
                    <!-- Name -->
                    <label for="name">{{ __('Name') }}<span class="text-red">*</span></label>
                    <input
                        type="text"
                        class="{{ $errors->has('name') ? 'border border-red' : '' }}"
                        name="name"
                        id="name"
                        value="{{ $user->name ?? old('name') }}"
                    >

                    <div class="{{ $errors->has('name') ? 'error-message' : '' }}">
                        {{ $errors->has('name') ? $errors->first('name') : '' }}
                    </div>


                    <!-- Email -->
                    <label for="email">{{ __('Email (can not be changed)') }}</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ $user->email }}"
                        readonly
                    >

                    <div class="{{ $errors->has('email') ? 'error-message' : '' }}">
                        {{ $errors->has('email') ? $errors->first('email') : '' }}
                    </div>

                    <!-- Password -->
                    <label for="password">{{ __('New Password (optional)') }}</label>
                    <input
                        type="text"
                        class="{{ $errors->has('password') ? 'border border-red' : '' }}"
                        name="password"
                        id="password"
                        value="{{ old('password') }}"
                    >

                    <div class="{{ $errors->has('password') ? 'error-message' : '' }}">
                        {{ $errors->has('password') ? $errors->first('password') : '' }}
                    </div>

                    <div class="checkbox-container">
                        <label for="enable2fa">
                            <input name="enable2fa"
                                   id="enable2fa"
                                   type="checkbox"
                                   value="1"
                                {{ old('enable2fa', $user->enable_2fa !== 0 ? 'checked' : '') }}
                            >
                            {{ __('Enable Two Factor Authentication') }}
                        </label>

                        <div class="{{ $errors->has('enable2fa') ? 'error-message' : '' }}">
                            {{ $errors->has('enable2fa') ? $errors->first('enable2fa') : '' }}
                        </div>
                    </div>


                </fieldset>

                <button type="submit" class="primary"><i class="fa-regular fa-floppy-disk margin-right-0-5"></i>{{ __("Save changes") }}</button>
            </form>

            <hr>

            <h2 class="h3">{{ __('Delete account') }}</h2>

            <div class="alert danger">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                {{ __('This action cannot be undone. It will permanently erase your account with all of your data.') }}
            </div>

                <div x-data="modalData">

                    <button @click="openModal()" class="danger">
                        <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                        <span>{{ __('Delete account?') }}</span>
                    </button>

                    <x-global::form-modal trigger="modal"
                                          title="{{ __('Are you sure you want to delete your account?') }}"
                                          id="delete-account-{{$user->id}}">

                        <form action="{{ route('user.account.delete', $user->id ) }}"
                              method="POST"
                              enctype="application/x-www-form-urlencoded"
                              accept-charset="UTF-8"
                              autocomplete="off"
                        >
                            @csrf
                            @method('DELETE')

                            <h2 class="h3">{{ __('Delete account') }}</h2>
                            <hr class="divider">
                            <p>{{ __('Type in your password to delete your account. This action cannot be undone. ') }}</p>


                            <!-- Password -->
                            <label for="password-2">{{ __('Password') }}<span class="text-red">*</span></label>
                            <input
                                type="text"
                                name="password"
                                id="password-2"
                                value=""
                            >

                            <div class="actions">
                                <button type="submit" class="danger">{{ __('Delete account') }}</button>
                                <button type="button" class="danger alt" @click="closeModal()">
                                    {{ __('Cancel') }}
                                </button>
                            </div>

                        </form>

                    </x-global::form-modal>
                </div>

        </div>

    </main>
@endsection
