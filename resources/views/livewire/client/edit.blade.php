<div x-data="{
    isModalOpen: $wire.entangle('isModalOpen')
}">

    <button @click="isModalOpen = true" class="fs-14 bold success">
        <i class="fa fa-pencil"></i>{{ __('Edit') }}
    </button>

    <x-admin.form-modal
        trigger="isModalOpen"
        title="{{ __('Edit Client') }}"
        id="{{ $modalId }}"
    >
        <form wire:submit.prevent="updateClient">

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
                <label for="address">{{ __('Address') }}<span class="text-red">*</span></label>
                <input
                    wire:model.defer="address"
                    type="text"
                    class="{{ $errors->has('address') ? 'border border-red' : '' }}"
                    name="address"
                >

                <div class="{{ $errors->has('address') ? 'error-message' : '' }}">
                    {{ $errors->has('address') ? $errors->first('address') : '' }}
                </div>

                <!-- Password -->
                <label for="order">{{ __('Order') }}<span class="text-red">*</span></label>
                <input
                    wire:model.defer="order"
                    type="number"
                    class="{{ $errors->has('order') ? 'border border-red' : '' }}"
                    name="order"
                >

                <div class="{{ $errors->has('order') ? 'error-message' : '' }}">
                    {{ $errors->has('order') ? $errors->first('password') : '' }}
                </div>

                <!-- Role -->
                <label for="type">{{ __('Type') }}<span class="text-red">*</span></label>
                <select
                    wire:model.defer="type"
                    class="{{ $errors->has('type') ? 'border border-red' : '' }}"
                    aria-label="{{ __("Select a client type") }}"
                    name="role"
                    id="role"
                >
                    @foreach ($typesArray as $key => $value)
                        <option {{ $type === $key ? "selected": "" }} name="type" value="{{ $key }}">{{ $value }}</option>
                    @endforeach

                </select>

                <div class="{{ $errors->has('type') ? 'error-message' : '' }}">
                    {{ $errors->has('type') ? $errors->first('type') : '' }}
                </div>

            </fieldset>

            <fieldset>
                <!-- $contactPerson -->
                <label for="contactPerson">{{ __('Contact Person\'s name') }}</label>
                <input
                    wire:model.defer="contactPerson"
                    type="text"
                    class="{{ $errors->has('contactPerson') ? 'border border-red' : '' }}"
                >

                <div class="{{ $errors->has('contactPerson') ? 'error-message' : '' }}">
                    {{ $errors->has('contactPerson') ? $errors->first('contactPerson') : '' }}
                </div>

                <!-- phoneNumber -->
                <label for="phoneNumber">{{ __('Phone number') }}</label>
                <input
                    wire:model.defer="phoneNumber"
                    type="text"
                    class="{{ $errors->has('phoneNumber') ? 'border border-red' : '' }}"
                    name="phoneNumber"
                >

                <div class="{{ $errors->has('phoneNumber') ? 'error-message' : '' }}">
                    {{ $errors->has('phoneNumber') ? $errors->first('phoneNumber') : '' }}
                </div>

                <!-- $email -->
                <label for="email">{{ __('Email') }}</label>
                <input
                    wire:model.defer="email"
                    type="email"
                    class="{{ $errors->has('email') ? 'border border-red' : '' }}"
                >

                <div class="{{ $errors->has('email') ? 'error-message' : '' }}">
                    {{ $errors->has('email') ? $errors->first('email') : '' }}
                </div>


                <!-- $taxNumber -->
                <label for="taxNumber">{{ __('Tax number') }}</label>
                <input
                    wire:model.defer="taxNumber"
                    type="text"
                    class="{{ $errors->has('taxNumber') ? 'border border-red' : '' }}"
                >

                <div class="{{ $errors->has('taxNumber') ? 'error-message' : '' }}">
                    {{ $errors->has('taxNumber') ? $errors->first('taxNumber') : '' }}
                </div>

            </fieldset>


            <div>
                <button type="submit" class="primary">
                    <span wire:loading
                          wire:target="updateClient"
                          class="animate-spin"
                    >&#9696;</span>

                    <span wire:loading.remove
                          wire:target="updateClient"
                    >
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
