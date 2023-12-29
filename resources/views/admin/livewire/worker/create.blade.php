<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="primary" title="{{ __('New Worker') }}">
            <i class="fa fa-plus"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="primary">
            <i class="fa fa-plus"></i>{{ __('New Worker') }}
        </button>
    @endif

    <x-global::form-modal
        trigger="isModalOpen"
        title="{{ __('Add Worker') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="createWorker">

            <fieldset>
                <!-- Name -->
                <label for="name">{{ __('Name') }}<span class="text-red">*</span></label>
                <input
                    wire:model.defer="name"
                    type="text"
                    class="{{ $errors->has('name') ? 'border border-red' : '' }}"
                    name="name"
                >

                <div class="{{ $errors->has('name') ? 'error-message' : '' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>


                <!-- Email -->
                <label for="email">{{ __('Email') }}</label>
                <input
                    wire:model.defer="email"
                    type="email"
                    class="{{ $errors->has('email') ? 'border border-red' : '' }}"
                    name="name"
                >

                <div class="{{ $errors->has('email') ? 'error-message' : '' }}">
                    {{ $errors->has('email') ? $errors->first('email') : '' }}
                </div>


                <!-- Phone number -->
                <label for="phone">{{ __('Phone number') }}</label>
                <input
                    wire:model.defer="phone"
                    type="text"
                    class="{{ $errors->has('phone') ? 'border border-red' : '' }}"
                    name="phone"
                >

                <div class="{{ $errors->has('phone') ? 'error-message' : '' }}">
                    {{ $errors->has('phone') ? $errors->first('phone') : '' }}
                </div>

                <hr class="divider">

                <!-- Bank Account Name -->
                <label for="bankAccountName">{{ __('Bank account name') }}</label>
                <input
                    wire:model.defer="bankAccountName"
                    type="text"
                    class="{{ $errors->has('bankAccountName') ? 'border border-red' : '' }}"
                    name="bankAccountName"
                >

                <div class="{{ $errors->has('bankAccountName') ? 'error-message' : '' }}">
                    {{ $errors->has('bankAccountName') ? $errors->first('bankAccountName') : '' }}
                </div>


                <!-- Bank Account Number -->
                <label for="bankAccountNumber">{{ __('Bank account number') }}</label>
                <input
                    wire:model.defer="bankAccountNumber"
                    type="text"
                    class="{{ $errors->has('bankAccountNumber') ? 'border border-red' : '' }}"
                    name="bankAccountNumber"
                >

                <div class="{{ $errors->has('bankAccountNumber') ? 'error-message' : '' }}">
                    {{ $errors->has('bankAccountNumber') ? $errors->first('bankAccountNumber') : '' }}
                </div>

            </fieldset>


            <div class="actions">
                <button type="submit" class="primary">
                    <span wire:loading wire:target="createWorker" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="createWorker">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i>
                        {{ __('Save') }}
                    </span>
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

    </x-global::form-modal>
</div>
