<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="fs-14 bold primary" title="{{ __('New entity') }}">
            <i class="fa fa-plus"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="fs-14 bold primary">
            <i class="fa fa-plus"></i>{{ __('New') }}
        </button>
    @endif

    <x-admin.form-modal
        trigger="isModalOpen"
        title="{{ __('Add new entity') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="createCategory">

            <fieldset>
                <label for="name">{{ __('Category name') }}</label>
                <input
                    wire:model.defer="name"
                    type="text"
                    class="{{ $errors->has('name') ?? 'red' }}"
                    name="name"
                    value=""
                >

                <div class="{{ $errors->has('name') ?? 'red' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>
            </fieldset>

            <input
                wire:model.defer="categoryId"
                disabled
                type="number"
                class="hidden"
                name="categoryId"
                value="{{ $categoryId }}"
            >

            <div>
                <button type="submit" class="primary">
                    <span wire:loading wire:target="createCategory" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="createCategory">{{ __('Save') }}</span>
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
