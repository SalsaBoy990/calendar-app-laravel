<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="danger margin-top-0" title="{{ __('Delete user') }}">
            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="danger margin-top-0">
            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
            <span>{{ __('Delete') }}</span>
        </button>
    @endif

    <x-global::form-modal trigger="isModalOpen" title="{{ __('Are you sure you want to delete it?') }}"
                        id="{{ $modalId }}">
        <form wire:submit.prevent="deleteUser">
            <h2 class="h3">{{ $name }}</h2>
            <hr class="divider">

            <label for="userId" class="sr-only">{{ __('User Id') }}</label>
            <input wire:model.defer="userId"
                   disabled
                   type="number"
                   class="hidden"
                   name="userId"
                   id="userId"
                   value="{{ $userId }}"
            >

            <div class="actions">
                <button type="submit" class="danger">
                    <span wire:loading wire:target="deleteUser" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="deleteUser">{{ __('Delete') }}</span>
                </button>
                <button type="button" class="danger alt" @click="isModalOpen = false">
                    {{ __('Cancel') }}
                </button>
            </div>
        </form>

    </x-global::form-modal>
</div>
