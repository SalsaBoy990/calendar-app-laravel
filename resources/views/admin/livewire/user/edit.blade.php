<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="info margin-top-0" title="{{ __('Edit User') }}">
            <i class="fa fa-pencil"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="info margin-top-0">
            <i class="fa fa-pencil"></i>{{ __('Edit') }}
        </button>
    @endif

    <x-global::form-modal
        trigger="isModalOpen"
        title="{{ __('Edit User') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="updateUser">

            <fieldset>
                <!-- Name -->
                <label for="name">{{ __('Name') }}<span class="text-red">*</span></label>
                <input
                    wire:model.defer="name"
                    type="text"
                    class="{{ $errors->has('name') ? 'border border-red' : '' }}"
                    name="name"
                    id="name"
                    value=""
                >

                <div class="{{ $errors->has('name') ? 'error-message' : '' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>


                <!-- Email -->
                <label for="email">{{ __('Email (can not be changed)') }}</label>
                <input
                    wire:model.defer="email"
                    type="email"
                    name="email"
                    id="email"
                    value=""
                    readonly
                >

                <div class="{{ $errors->has('email') ? 'error-message' : '' }}">
                    {{ $errors->has('email') ? $errors->first('email') : '' }}
                </div>

                <!-- Password -->
                <label for="password">{{ __('New Password (optional)') }}</label>
                <input
                    wire:model.defer="password"
                    type="text"
                    class="{{ $errors->has('password') ? 'border border-red' : '' }}"
                    name="password"
                    id="password"
                    value=""
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
                    @if($role === null)
                        <option selected>{{ __("Select the role") }}</option>
                    @endif

                    @foreach ($rolesArray as $key => $value)
                        <option {{ $role === $key ? "selected": "" }} value="{{ $key }}">{{ $value }}</option>
                    @endforeach

                </select>

                <div class="{{ $errors->has('role') ? 'error-message' : '' }}">
                    {{ $errors->has('role') ? $errors->first('role') : '' }}
                </div>

            </fieldset>


            <div class="actions">
                <button type="submit" class="primary">
                    <span wire:loading wire:target="updateUser" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="updateUser">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        {{ __('Save') }}
                    </span>
                </button>

                <button
                    type="button"
                    class="alt primary"
                    @click="isModalOpen = false"
                >
                    {{ __('Cancel') }}
                </button>
            </div>

        </form>

    </x-global::form-modal>
</div>

