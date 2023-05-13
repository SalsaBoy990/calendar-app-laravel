<div>
    <div id="calendar-container" wire:ignore>
        <div id="calendar"></div>
    </div>


    <div x-data="{ isModalOpen: $wire.entangle('isModalOpen') }">

        <x-admin.form-modal
            trigger="isModalOpen"
            title="{{ $updateId ? $event->title . ' ('. $event->address . ')' : __('Add event') }}"
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
                            <option {{ $status === $key ? "selected": "" }} value="{{ $key }}">{{ $value }}</option>
                        @endforeach

                    </select>
                    <div class="{{ $errors->has('status') ? 'red' : '' }}">
                        {{ $errors->has('status') ? $errors->first('status') : '' }}
                    </div>


                        <label class="{{ $errors->has('workerIds') ? 'border border-red' : '' }}">
                            {{ __('Assign workers') }}
                        </label>
                        <div class="checkbox-container">
                            @foreach($workers as $worker)
                                <label for="workerIds">
                                    <input wire:model="workerIds"
                                           type="checkbox"
                                           name="workerIds[]"
                                           value="{{ $worker->id }}"
                                    >
                                    {{ $worker->name }}
                                </label>
                            @endforeach

                            <div class="{{ $errors->has('workerIds') ? 'red' : '' }}">
                                {{ $errors->has('workerIds') ? $errors->first('workerIds') : '' }}
                            </div>

                            {{-- var_export($rolePermissions) --}}
                        </div>


                </fieldset>


                <div>
                    <button type="submit" class="primary">
                        <span wire:loading wire:target="createOrUpdateEvent" class="animate-spin">&#9696;</span>
                        <span wire:loading.remove wire:target="createOrUpdateEvent">
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

                    @if( $updateId !== '' )
                        <button wire:click="$emit('openDeleteEventModal')"
                                type="button"
                                class="danger"
                        >
                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                            {{  __('Delete?') }}
                        </button>
                    @endif

                </div>

            </form>


        </x-admin.form-modal>
    </div>

    @if( $updateId !== '' )
        <div x-data="{ isDeleteModalOpen: $wire.entangle('isDeleteModalOpen') }">

            <x-admin.form-modal
                trigger="isDeleteModalOpen"
                title="{{ __('Delete event') }}"
                id="{{ $deleteModalId }}"
            >
                <div>
                    <h3 class="h5">{{ $event->title }}</h3>

                    <button wire:click="$emit('deleteEventListener')"
                            type="button"
                            class="danger"
                    >
                        {{  __('Confirm delete!') }}
                    </button>

                    <button
                        type="button"
                        class="alt"
                        @click="isDeleteModalOpen = false"
                    >
                        {{ __('Cancel') }}
                    </button>

                </div>

            </x-admin.form-modal>
        </div>
    @endif

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
                allDaySlot: true,
                slotMinTime: '08:00:00',
                slotMaxTime: '20:00:00',
                firstDay: 1,
                fixedWeekCount: 5,
                showNonCurrentDates: false,
                nowIndicator: true,
                eventBackgroundColor: '#3F57B9',


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

                eventDidMount: function (info) {
                    // object destructuring
                    const { el, event, view } = info;

                    // inner flex container of the event
                    const container = el.firstChild.firstChild;

                    if (view.type === 'timeGridWeek' && container && event.extendedProps && event.allDay === false) {

                        if (event.extendedProps.address) {
                            const address = document.createElement('p');
                            const bold = document.createElement('b');

                            bold.innerText = event.extendedProps.address;
                            address.classList.add('address');
                            address.appendChild(bold);
                            container.appendChild(address)
                        }


                        const description = document.createElement('p');

                        if (event.end !== null) {
                            const startTimestamp = new Date(event.start).getTime();
                            const endTimestamp = new Date(event.end).getTime();

                            // Calculate duration in hours
                            const duration = (endTimestamp - startTimestamp) / (60*60*1000);

                            description.innerText += duration + 'ó | ';
                            // container.appendChild(durationParagraph);

                        }


                        if (event.extendedProps.description) {
                            // const description = document.createElement('p');
                            description.innerText += event.extendedProps.description;
                            description.classList.add('description');
                            container.appendChild(description)
                        }

                        if (event.extendedProps.users && event.extendedProps.users.length > 0) {
                            const users = event.extendedProps.users;
                            const bar = document.createElement('div');
                            bar.classList.add('workers-container');

                            for (let i = 0; i < users.length; i++) {
                                const badge = document.createElement('span');
                                badge.classList.add('badge', 'accent');
                                badge.innerText = users[i].name;
                                bar.appendChild(badge);
                            }

                            container.appendChild(bar)
                        }

                    }


                }


            });

            calendar.addEventSource( @json( $events ) )
            calendar.setOption('contentHeight', 600);



            calendar.render();
        });

    </script>

@endpush

