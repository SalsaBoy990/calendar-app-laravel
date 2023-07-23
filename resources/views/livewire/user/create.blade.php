<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="primary" title="{{ __('New User') }}">
            <i class="fa fa-plus"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="primary">
            <i class="fa fa-plus"></i>{{ __('New User') }}
        </button>
    @endif

    <x-admin.form-modal
        trigger="isModalOpen"
        title="{{ __('Add User') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="createUser">

            <fieldset>
                <!-- Name -->
                <label for="name">{{ __('Name') }}<span class="text-red">*</span></label>
                <input
                    wire:model.defer="name"
                    type="text"
                    class="{{ $errors->has('name') ? 'border border-red' : '' }}"
                    name="name"
                >

                <div class="{{ $errors->has('name') ? 'error-message' : '' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>


                <!-- Email -->
                <label for="email">{{ __('Email') }}<span class="text-red">*</span></label>
                <input
                    wire:model.defer="email"
                    type="email"
                    class="{{ $errors->has('email') ? 'border border-red' : '' }}"
                    name="name"
                >

                <div class="{{ $errors->has('email') ? 'error-message' : '' }}">
                    {{ $errors->has('email') ? $errors->first('email') : '' }}
                </div>

                <!-- Password -->
                <label for="password">{{ __('Password') }}<span class="text-red">*</span></label>
                <input
                    wire:model.defer="password"
                    type="text"
                    class="{{ $errors->has('password') ? 'border border-red' : '' }}"
                    name="password"
                >

                <div class="{{ $errors->has('password') ? 'error-message' : '' }}">
                    {{ $errors->has('password') ? $errors->first('password') : '' }}
                </div>

                <!-- Role -->
                <label for="role">{{ __('Role') }}<span class="text-red">*</span></label>
                <select
                    wire:model.defer="role"
                    class="{{ $errors->has('role') ? 'border border-red' : '' }}"
                    aria-label="{{ __("Select a role") }}"
                    name="role"
                    id="role"
                >

                    <option selected>{{ __("Select the role") }}</option>

                    @foreach ($rolesArray as $key => $value)
                        <option name="role" value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>

                <div class="{{ $errors->has('role') ? 'error-message' : '' }}">
                    {{ $errors->has('role') ? $errors->first('role') : '' }}
                </div>

            </fieldset>


            <div class="actions">
                <button type="submit" class="primary">
                    <span wire:loading wire:target="createUser" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="createUser">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        {{ __('Save') }}
                    </span>
                </button>

                <button
                    type="button"
                    class="alt"
                    @click="isModalOpen = false"
                >
                    {{ __('Cancel') }}
                </button>
            </div>

        </form>

    </x-admin.form-modal>
</div>
