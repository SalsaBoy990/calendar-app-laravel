<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="fs-14 bold primary" title="{{ __('New User') }}">
            <i class="fa fa-plus"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="fs-14 bold primary">
            <i class="fa fa-plus"></i>{{ __('New') }}
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
                <label for="name">{{ __('Name') }}</label>
                <input
                    wire:model.defer="name"
                    type="text"
                    class="{{ $errors->has('name') ? 'input-error' : 'input-default' }}"
                    name="name"
                    value=""
                >

                <div class="{{ $errors->has('name') ? 'red' : 'gray-80' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>


                <!-- Email -->
                <label for="email">{{ __('Email') }}</label>
                <input
                    wire:model.defer="email"
                    type="email"
                    class="{{ $errors->has('email') ? 'input-error' : 'input-default' }}"
                    name="name"
                    value=""
                >

                <div class="{{ $errors->has('email') ? 'red' : 'gray-80' }}">
                    {{ $errors->has('email') ? $errors->first('email') : '' }}
                </div>

                <!-- Password -->
                <label for="password">{{ __('Password') }}</label>
                <input
                    wire:model.defer="password"
                    type="text"
                    class="{{ $errors->has('password') ? 'input-error' : 'input-default' }}"
                    name="password"
                    value=""
                >

                <div class="{{ $errors->has('password') ? 'red' : 'gray-80' }}">
                    {{ $errors->has('password') ? $errors->first('password') : '' }}
                </div>

                <!-- Role -->
                <label for="role">{{ __('Role') }}</label>
                <select
                    wire:model.defer="role"
                    class="{{ $errors->has('role') ? 'input-error' : 'input-default' }}"
                    aria-label="{{ __("Select a role") }}"
                    name="role"
                    id="role"
                >

                    <option selected>{{ __("Select the role") }}</option>

                    @foreach ($roles as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach

                </select>

                <div class="{{ $errors->has('role') ? 'red' : 'gray-80' }}">
                    {{ $errors->has('role') ? $errors->first('role') : '' }}
                </div>


                <!-- Permissions -->
                <label class="{{ $errors->has('userPermissions') ? 'border border-red' : '' }}">
                    {{ __('Assign permissions') }}
                </label>
                <div class="checkbox-container">
                    @foreach($allPermissions as $permission)
                        <label for="userPermissions">
                            <input wire:model="userPermissions"
                                   type="checkbox"
                                   value="{{ $permission->id }}"
                            >
                            {{ $permission->name }}
                        </label>
                    @endforeach

                    <div class="{{ $errors->has('userPermissions') ? 'red' : '' }}">
                        {{ $errors->has('userPermissions') ? $errors->first('userPermissions') : '' }}
                    </div>
                </div>

            </fieldset>


            <div>
                <button type="submit" class="primary">
                    <span wire:loading wire:target="createUser" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="createUser">{{ __('Save') }}</span>
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
