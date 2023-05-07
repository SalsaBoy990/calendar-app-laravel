<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="fs-14 bold primary" title="{{ __('New Permission') }}">
            <i class="fa fa-plus"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="fs-14 bold primary">
            <i class="fa fa-plus"></i>{{ __('New Permission') }}
        </button>
    @endif

    <x-admin.form-modal
        trigger="isModalOpen"
        title="{{ __('Add Permission') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="createPermission">

            <fieldset>
                <!-- Name -->
                <label for="name">{{ __('Name') }}</label>
                <input
                    wire:model.defer="name"
                    type="text"
                    class="{{ $errors->has('name') ? 'input-error' : '' }}"
                    name="name"
                    value=""
                >

                <div class="{{ $errors->has('name') ? 'red' : '' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>


                <!-- Email -->
                <label for="slug">{{ __('Slug (should be unique)') }}</label>
                <input
                    wire:model.defer="slug"
                    type="text"
                    class="{{ $errors->has('slug') ? 'input-error' : '' }}"
                    name="slug"
                    value=""
                >

                <div class="{{ $errors->has('slug') ? 'red' : '' }}">
                    {{ $errors->has('slug') ? $errors->first('slug') : '' }}
                </div>

                <label class="{{ $errors->has('permissionRoles') ? 'border border-red' : '' }}">
                    {{ __('Assign roles') }}
                </label>
                <div class="checkbox-container">
                    @foreach($allRoles as $role)
                        <label for="roles">
                            <input wire:model="permissionRoles"
                                   type="checkbox"
                                   value="{{ $role->id }}"
                            >
                            {{ $role->name }}
                        </label>
                    @endforeach

                    <div class="{{ $errors->has('permissionRoles') ? 'red' : '' }}">
                        {{ $errors->has('permissionRoles') ? $errors->first('permissionRoles') : '' }}
                    </div>

                    {{-- var_export($permissionRoles) --}}
                </div>

            </fieldset>


            <div>
                <button type="submit" class="primary">
                    <span wire:loading wire:target="createPermission" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="createPermission">{{ __('Save') }}</span>
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
