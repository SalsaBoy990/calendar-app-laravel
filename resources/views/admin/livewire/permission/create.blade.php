<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="primary margin-top-0" title="{{ __('New Permission') }}">
            <i class="fa fa-plus"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="primary margin-top-0">
            <i class="fa fa-plus"></i>{{ __('New Permission') }}
        </button>
    @endif

    <x-global::form-modal
        trigger="isModalOpen"
        title="{{ __('Add Permission') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="createPermission">

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
                <label for="slug">{{ __('Slug (should be unique)') }}<span class="text-red">*</span></label>
                <input
                    wire:model.defer="slug"
                    type="text"
                    class="{{ $errors->has('slug') ? 'border border-red' : '' }}"
                    name="slug"
                    id="slug"
                    value=""
                >

                <div class="{{ $errors->has('slug') ? 'error-message' : '' }}">
                    {{ $errors->has('slug') ? $errors->first('slug') : '' }}
                </div>

                <label class="{{ $errors->has('permissionRoles') ? 'border border-red' : '' }}">
                    {{ __('Assign roles (optional)') }}
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

                    <div class="{{ $errors->has('permissionRoles') ? 'error-message' : '' }}">
                        {{ $errors->has('permissionRoles') ? $errors->first('permissionRoles') : '' }}
                    </div>

                    {{-- var_export($permissionRoles) --}}
                </div>

            </fieldset>


            <div class="actions">
                <button type="submit" class="primary">
                    <span wire:loading wire:target="createPermission" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="createPermission">
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
