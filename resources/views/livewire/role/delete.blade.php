<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="fs-14 bold danger" title="{{ __('Delete role') }}">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="fs-14 bold danger">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
            <span>{{ __('Delete') }}</span>
        </button>
    @endif

    <x-admin.form-modal trigger="isModalOpen" title="{{ __('Are you sure you want to delete it?') }}"
                        id="{{ $modalId }}">
        <form wire:submit.prevent="deleteRole">
            <h2>{{ $name }}</h2>

            <input wire:model.defer="roleId"
                   disabled
                   type="number"
                   class="hidden"
                   name="roleId"
                   value="{{ $roleId }}"
            >

            <div>
                <button type="submit" class="danger">
                    <span wire:loading wire:target="deleteRole" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="deleteRole">{{ __('Delete') }}</span>
                </button>
                <button type="button" class="danger alt" @click="isModalOpen = false">
                    {{ __('Cancel') }}
                </button>
            </div>
        </form>

    </x-admin.form-modal>
</div>
