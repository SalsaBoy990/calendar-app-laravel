<div>
    <div id="calendar-container" wire:ignore>
        <div id="calendar"></div>
    </div>


    <div x-data="{ isModalOpen: $wire.entangle('isModalOpen') }">


        <x-admin.form-modal
            trigger="isModalOpen"
            title="{{ __('Add event') }}"
            id="{{ $modalId }}"
        >
            <form wire:submit.prevent="createOrUpdateEvent">

                <fieldset>
                    @if($updateId)
                        <!-- Update id -->
                        <input
                            wire:model.defer="updateId"
                            type="text"
                            class="hidden"
                            name="updateId"
                            value="{{ $updateId }}"
                            readonly
                        >
                    @endif


                    <!-- Title -->
                    <label for="title">{{ __('Title') }}</label>
                    <input
                        wire:model.defer="title"
                        type="text"
                        class="{{ $errors->has('title') ? 'border border-red' : '' }}"
                        name="title"
                    >

                    <div class="{{ $errors->has('title') ? 'red' : '' }}">
                        {{ $errors->has('title') ? $errors->first('title') : '' }}
                    </div>


                    <!-- Address -->
                    <label for="address">{{ __('Address') }}</label>
                    <input
                        wire:model.defer="address"
                        type="text"
                        class="{{ $errors->has('address') ? 'border border-red' : '' }}"
                        name="address"
                    >

                    <div class="{{ $errors->has('address') ? 'red' : '' }}">
                        {{ $errors->has('address') ? $errors->first('address') : '' }}
                    </div>


                    <!-- Start date -->
                    <label for="start">{{ __('Start date') }}</label>
                    <input
                        wire:model.defer="start"
                        type="datetime-local"
                        class="{{ $errors->has('start') ? 'border border-red' : '' }}"
                        name="start"
                    >

                    <div class="{{ $errors->has('start') ? 'red' : '' }}">
                        {{ $errors->has('start') ? $errors->first('start') : '' }}
                    </div>


                    <!-- End date -->
                    <label for="end">{{ __('End date') }}</label>
                    <input
                        wire:model.defer="end"
                        type="datetime-local"
                        class="{{ $errors->has('end') ? 'border border-red' : '' }}"
                        name="end"
                    >

                    <div class="{{ $errors->has('end') ? 'red' : '' }}">
                        {{ $errors->has('end') ? $errors->first('end') : '' }}
                    </div>


                    <!-- description -->
                    <label for="description">{{ __('Description') }}</label>
                    <input
                        wire:model.defer="description"
                        type="text"
                        class="{{ $errors->has('description') ? 'border border-red' : '' }}"
                        name="description"
                    >

                    <div class="{{ $errors->has('description') ? 'red' : '' }}">
                        {{ $errors->has('description') ? $errors->first('description') : '' }}
                    </div>

                    <!-- Status -->
                    <label for="status">{{ __('Status') }}</label>
                    <select
                        wire:model.defer="status"
                        class="{{ $errors->has('status') ? 'border border-red' : '' }}"
                        aria-label="{{ __("Select a status") }}"
                        name="status"
                        id="status"
                    >

                        @foreach ($statusArray as $key => $value)
                            @if($status === '' && $key === 'opened')
                                <option selected value="{{ $key }}">{{ $value }}</option>
                            @else
                                <option {{ $status === $key ? "selected": "" }} value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach

                    </select>
                    <div class="{{ $errors->has('status') ? 'red' : '' }}">
                        {{ $errors->has('status') ? $errors->first('status') : '' }}
                    </div>


                </fieldset>


                <div>
                    <button type="submit" class="primary">
                        <span wire:loading wire:target="createOrUpdateEvent" class="animate-spin">&#9696;</span>
                        <span wire:loading.remove wire:target="createOrUpdateEvent">{{ __('Save') }}</span>
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

</div>
@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js'></script>
    <script>
        document.addEventListener('livewire:load', function () {
            const calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'local', // the default (unnecessary to specify)
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                locale: '{{ 'hu' ?? config('app.locale') }}',
                allDaySlot: false,
                slotMinTime: '08:00:00',
                slotMaxTime: '20:00:00',
                firstDay: 1,
                fixedWeekCount: 5,
                showNonCurrentDates: false,
                nowIndicator: true,




                editable: true,

                selectable: true,
                select: function (args) {
                @this.eventModal(args);
                },

                eventClick: function (eventClickInfo) {
                @this.eventModal(eventClickInfo);
                },

                // resize all-day events
                eventResize: function (info) {
                @this.eventChange(info.event);
                },

                // drag and drop events
                eventDrop: function (info) {
                @this.eventChange(info.event);
                },
            });

            calendar.addEventSource( @json( $events ) )
            calendar.setOption('contentHeight', 600);

            calendar.render();
        });

    </script>

@endpush

