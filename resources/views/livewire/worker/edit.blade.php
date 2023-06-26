<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    @if ($hasSmallButton)
        <button @click="isModalOpen = true" class="fs-14 bold success" title="{{ __('Edit Worker') }}">
            <i class="fa fa-pencil"></i>
        </button>
    @else
        <button @click="isModalOpen = true" class="fs-14 bold success">
            <i class="fa fa-pencil"></i>{{ __('Edit') }}
        </button>
    @endif

    <x-admin.form-modal
        trigger="isModalOpen"
        title="{{ __('Edit Worker') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="updateWorker">

            <fieldset>
                <!-- Name -->
                <label for="name">{{ __('Name') }}<span class="text-red">*</span></label>
                <input
                    wire:model.defer="name"
                    type="text"
                    class="{{ $errors->has('name') ? 'border border-red' : '' }}"
                    name="name"
                    value=""
                >

                <div class="{{ $errors->has('name') ? 'error-message' : '' }}">
                    {{ $errors->has('name') ? $errors->first('name') : '' }}
                </div>


                <!-- Email -->
                <label for="email">{{ __('Email') }}</label>
                <input
                    wire:model.defer="email"
                    type="email"
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
                    value=""
                >

                <div class="{{ $errors->has('phone') ? 'error-message' : '' }}">
                    {{ $errors->has('phone') ? $errors->first('phone') : '' }}
                </div>

                <hr>

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


            <div>
                <button type="submit" class="primary">
                    <span wire:loading wire:target="updateUser" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="updateUser">
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

    </x-admin.form-modal>
</div>

