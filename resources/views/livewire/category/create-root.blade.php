<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="fs-14 bold primary button margin-top-0"
                title="{{ __('New category') }}">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="fs-14 bold primary button margin-top-0">
            <i class="fa fa-plus" aria-hidden="true"></i>
            {{ __('New category') }}
        </button>
    @endif

    <x-admin.form-modal trigger="isModalOpen" title="{{ $title }}" id="{{ $modalId }}">
        <form wire:submit.prevent="createCategory">

            <fieldset>
                <input wire:model.defer="name"
                       type="text"
                       class="{{ $errors->has('name') ?? 'red' }}"
                       name="name"
                       value=""
                       placeholder="{{ __('category name') }}">

                <div class="{{ $errors->has('name') ?? 'red' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>
            </fieldset>

            <div>
                <button type="submit" class="primary">
                    <span wire:loading wire:target="createCategory" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="createCategory">{{ __('Save') }}</span>
                </button>
                <button type="button" class="alt" @click="isModalOpen = false">
                    {{ __('Cancel') }}
                </button>
            </div>
        </form>

    </x-admin.form-modal>

</div>
