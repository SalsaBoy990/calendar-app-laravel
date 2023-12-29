<div wire:ignore x-data="{
    isModalOpen: $wire.entangle('isModalOpen'),
    permission: $wire.entangle('rolePermissions'),
    init(){
        let input = new TomSelect(this.$refs.selectPermissions, {
            plugins: ['remove_button'],
            onChange: (value) => this.permission = value,
            items: this.permission
        });
    }
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="primary margin-top-0" title="{{ __('New Role') }}">
            <i class="fa fa-plus"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="primary margin-top-0">
            <i class="fa fa-plus"></i>{{ __('New role') }}
        </button>
    @endif

    <x-global::form-modal
        trigger="isModalOpen"
        title="{{ __('Add Role') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="createRole">

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

                <label for="rolePermissions" class="{{ $errors->has('rolePermissions') ? 'border border-red' : '' }}">
                    {{ __('Assign permissions') }}
                </label>
                <select x-ref="selectPermissions" id="rolePermissions" class="selectPermissions" x-model="permission" multiple="multiple">
                    @foreach($allPermissions as $permission)
                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                    @endforeach
                </select>

                <div class="checkbox-container">
                    @foreach($allPermissions as $permission)
                        <label for="permissions" class="hide">
                            <input wire:model="rolePermissions"
                                   type="checkbox"
                                   name="rolePermissions[]"
                                   value="{{ $permission->id }}"
                            >
                            {{ $permission->name }}
                        </label>
                    @endforeach

                    <div class="{{ $errors->has('rolePermissions') ? 'error-message' : '' }}">
                        {{ $errors->has('rolePermissions') ? $errors->first('rolePermissions') : '' }}
                    </div>
                </div>

            </fieldset>


            <div class="actions">
                <button type="submit" class="primary">
                    <span wire:loading wire:target="createRole" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="createRole">
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
