<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="fs-14 bold info" title="{{ __('Edit entity') }}">
            <i class="fa fa-pencil" aria-hidden="true"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="fs-14 bold info">
            <i class="fa fa-pencil" aria-hidden="true"></i>
            <span>{{ __('Edit') }}</span>
        </button>
    @endif

    <x-admin.form-modal
        trigger="isModalOpen"
        title="{{ __('Edit entity') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="updateCategory">
            <h2>{{ $name }}</h2>
            <fieldset>
                <label for="name">{{ __('Entity name') }}</label>
                <input wire:model.defer="name"
                       type="text"
                       class="{{ $errors->has('name') ?? 'red' }}"
                       name="name"
                       value=""
                       placeholder="{{ __('entity name') }}"
                >

                <div
                    class="{{ $errors->has('name') ?? 'danger fs-14 text-red-dark' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>
            </fieldset>

            <div>
                <button type="submit" class="primary">
                    <span wire:loading wire:target="updateCategory" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="updateCategory">{{ __('Update') }}</span>
                </button>
                <button type="button" class="primary alt" @click="isModalOpen = false">
                    {{ __('Cancel') }}
                </button>
            </div>
        </form>

    </x-admin.form-modal>
</div>
