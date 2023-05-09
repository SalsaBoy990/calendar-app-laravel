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
                        <a href="{{ url('/home') }}">{{ __('Home') }}</a>
                    </li>
                    <li>
                        <span>/</span>
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

                    <button type="submit" class="primary">{{ __("Update") }}
                    </button>

                </form>

                <hr>

                <h2 class="h3">Delete account</h2>

                <div class="panel danger text-red-dark border border-red-dark">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                    This action cannot be undone. It will permanently erase your account with all of your data.
                </div>

                <div x-data="modalData">

                    <button @click="openModal()" class="danger">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                        <span>{{ __('Delete account?') }}</span>
                    </button>

                    <x-admin.form-modal trigger="modal"
                                        title="{{ __('Are you sure you want to delete your account?') }}"
                                        id="delete-account-{{$user->id}}">

                        <form action="{{ route('user.destroy', $user->id ) }}"
                              method="POST"
                              enctype="application/x-www-form-urlencoded"
                              accept-charset="UTF-8"
                              autocomplete="off"
                        >
                            @csrf
                            @method('DELETE')

                            <h2>{{ __('Delete account') }}</h2>
                            <p>This action cannot be undone.</p>

                            <div>
                                <button type="submit" class="danger">{{ __('Delete account') }}</button>
                                <button type="button" class="danger alt" @click="closeModal()">
                                    {{ __('Cancel') }}
                                </button>
                            </div>

                        </form>

                    </x-admin.form-modal>
                </div>


            </div>
        </main>
    @endsection

</x-admin-layout>
