<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    <button @click="isModalOpen = true" class="danger margin-top-0">
        <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
        <span>{{ __('Delete') }}</span>
    </button>

    <x-global::form-modal trigger="isModalOpen" title="{{ __('Are you sure you want to delete this client?') }}"
                        id="{{ $modalId }}">
        <form wire:submit.prevent="deleteClient">
            <h2 class="h3">{{ $name }}</h2>
            <hr class="divider">

            <input wire:model.defer="clientId"
                   disabled
                   type="number"
                   class="hidden"
            >

            <div class="actions">
                <button type="submit" class="danger">
                    <span wire:loading wire:target="deleteClient" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="deleteClient">{{ __('Delete') }}</span>
                </button>
                <button type="button" class="danger alt" @click="isModalOpen = false">
                    {{ __('Cancel') }}
                </button>
            </div>
        </form>

    </x-global::form-modal>
</div>
