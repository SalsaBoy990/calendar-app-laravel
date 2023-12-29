<div>

    <div class="mini-calendar-menu">
        <nav class="nav-links">
            @auth
                @role('super-administrator|administrator')
                <h1 class="margin-0 fs-16 relative calendar-works">{{ __('Works') }}</h1>
                <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ url('/admin/dashboard') }}">
                    <i class="fa fa-tachometer" aria-hidden="true"></i>{{ __('Dashboard') }}
                </a>

                <a class="{{ request()->routeIs('calendar') ? 'active' : '' }}"
                   href="{{ route('calendar') }}">
                    <i class="fa fa-calendar" aria-hidden="true"></i>{{ __('Works') }}
                </a>

                <!-- Worker availabilities link -->
                <a class="{{ request()->routeIs('workers') ? 'active' : '' }}"
                   href="{{ route('workers') }}">
                    <i class="fa-regular fa-clock" aria-hidden="true"></i>
                    {{ __('Availabilities') }}
                </a>

                <!-- Statistics -->
                <a class="{{ request()->routeIs('statistics') ? 'active' : '' }}"
                   href="{{ route('statistics') }}">
                    <i class="fa fa-line-chart" aria-hidden="true"></i>
                    {{ __('Statistics') }}
                </a>

                @endrole
            @endauth
        </nav>

        <div class="legend-container">
        </div>
    </div>


    <div id="calendar-container" wire:ignore>
        <div id="calendar"></div>
    </div>


    <div x-data="{
        isModalOpen: $wire.entangle('isModalOpen'),
        isRecurring: $wire.entangle('isRecurring')
    }">

        <x-global::form-modal
            trigger="isModalOpen"
            title="{{ $updateId ? (isset($event->client) ? $event->client->name : $clientName) . ' ('. (isset($event->client) ? $event->client->address : $clientAddress) . ')' : __('Add event') }}"
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

                    <!-- Is event recurring? -->
                    <label for="isRecurring">{{ __('Recurring event') }}<span class="text-red">*</span></label>
                    <input type="radio"
                           wire:model="isRecurring"
                           name="isRecurring"
                           value="1"
                    > <span class="padding-right-1">{{ __('Yes') }}</span>
                    <input type="radio"
                           wire:model="isRecurring"
                           name="isRecurring"
                           value="0"
                           checked
                    > <span class="padding-right-1">{{ __('No') }}</span>

                    <div class="{{ $errors->has('isRecurring') ? 'red' : '' }}">
                        {{ $errors->has('isRecurring') ? $errors->first('isRecurring') : '' }}
                    </div>

                    <hr class="divider">

                    <!-- Client id -->
                    <label for="clientId">{{ __('Client') }}<span class="text-red">*</span></label>
                    <select
                        wire:model.defer="clientId"
                        class="{{ $errors->has('clientId') ? 'border border-red' : '' }}"
                        aria-label="{{ __("Select the client") }}"
                        name="clientId"
                    >
                        @if ($clientId === null)
                            <option selected>{{ __("Select the client") }}</option>
                        @endif
                        @foreach ($clients as $client)
                            <option {{ $clientId === $client->id ? "selected": "" }} name="clientId"
                                    value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>

                    <div class="{{ $errors->has('clientId') ? 'error-message' : '' }}">
                        {{ $errors->has('clientId') ? $errors->first('clientId') : '' }}
                    </div>

                    @if ($isRecurring === 0)
                        <!-- REGULAR EVENTS -->
                        <div>
                            <!-- Start date -->
                            <label for="start">{{ __('Start date') }}<span class="text-red">*</span></label>
                            <input
                                wire:model.defer="start"
                                type="datetime-local"
                                class="{{ $errors->has('start') ? 'border border-red' : '' }}"
                                name="start"
                            >

                            <div class="{{ $errors->has('start') ? 'error-message' : '' }}">
                                {{ $errors->has('start') ? $errors->first('start') : '' }}
                            </div>


                            <!-- End date -->
                            <label for="end">{{ __('End date') }}<span class="text-red">*</span></label>
                            <input
                                wire:model.defer="end"
                                type="datetime-local"
                                class="{{ $errors->has('end') ? 'border border-red' : '' }}"
                                name="end"
                            >

                            <div class="{{ $errors->has('end') ? 'error-message' : '' }}">
                                {{ $errors->has('end') ? $errors->first('end') : '' }}
                            </div>
                        </div>
                        <!-- REGULAR EVENTS END -->
                    @else

                        <!-- RECURRING EVENT PROPERTIES -->
                        <div x-show="isRecurring">
                            <div>
                                <!-- Freq -->
                                <label for="frequencyName">{{ __('Frequency') }}<span class="text-red">*</span></label>
                                <select
                                    wire:model.defer="frequencyName"
                                    class="{{ $errors->has('frequencyName') ? 'border border-red' : '' }}"
                                    aria-label="{{ __("Select a repeat frequency") }}"
                                    name="frequencyName"
                                >
                                    <option selected>{{ __("Select repeat frequency") }}</option>
                                    @foreach ($frequencies as $key => $value)
                                        <option
                                            {{ $frequencyName === $key ? "selected": "" }} value="{{ $value }}">{{ $key }}</option>
                                    @endforeach
                                </select>

                                <div class="{{ $errors->has('frequencyName') ? 'error-message' : '' }}">
                                    {{ $errors->has('frequencyName') ? $errors->first('frequencyName') : '' }}
                                </div>
                            </div>


                            <div class="row-padding">

                                <div class="col s6">
                                    <label for="byweekday">{{ __('By Weekday') }}<span class="text-red">*</span></label>
                                    <select
                                        wire:model.defer="byweekday"
                                        class="{{ $errors->has('byweekday') ? 'border border-red' : '' }}"
                                        name="byweekday"
                                    >
                                        <option selected>{{ __("Select a weekday") }}</option>
                                        @foreach ($weekDays as $key => $value)
                                            <option {{ $byweekday === $value ? "selected": "" }} value="{{ $value }}">
                                                {{ $key }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="{{ $errors->has('byweekday') ? 'error-message' : '' }}">
                                        {{ $errors->has('byweekday') ? $errors->first('byweekday') : '' }}
                                    </div>
                                </div>

                                <div class="col s6">
                                    <!-- End recurring date -->
                                    <label for="duration">{{ __('Duration') }}<span class="text-red">*</span></label>
                                    <input
                                        wire:model.defer="duration"
                                        type="time"
                                        class="{{ $errors->has('duration') ? 'border border-red' : '' }}"
                                        name="duration"
                                    >

                                    <div class="{{ $errors->has('duration') ? 'error-message' : '' }}">
                                        {{ $errors->has('duration') ? $errors->first('duration') : '' }}
                                    </div>
                                </div>
                            </div>


                            <!-- Start recurring date -->
                            <label for="dtstart">{{ __('Start recurring date') }}<span class="text-red">*</span></label>
                            <input
                                wire:model.defer="dtstart"
                                type="datetime-local"
                                class="{{ $errors->has('dtstart') ? 'border border-red' : '' }}"
                                name="dtstart"
                            >

                            <div class="{{ $errors->has('dtstart') ? 'error-message' : '' }}">
                                {{ $errors->has('dtstart') ? $errors->first('dtstart') : '' }}
                            </div>


                            <!-- End recurring date -->
                            <label for="until">{{ __('End recurring date') }}</label>
                            <input
                                wire:model.defer="until"
                                type="date"
                                class="{{ $errors->has('until') ? 'border border-red' : '' }}"
                                name="until"
                            >

                            <div class="{{ $errors->has('until') ? 'error-message' : '' }}">
                                {{ $errors->has('until') ? $errors->first('until') : '' }}
                            </div>

                        </div>
                        <!-- RECURRING EVENT PROPERTIES END -->

                    @endif

                    <!-- description -->
                    <label for="description">{{ __('Description (optional)') }}</label>
                    <input
                        wire:model.defer="description"
                        type="text"
                        class="{{ $errors->has('description') ? 'border border-red' : '' }}"
                        name="description"
                    >

                    <div class="{{ $errors->has('description') ? 'error-message' : '' }}">
                        {{ $errors->has('description') ? $errors->first('description') : '' }}
                    </div>

                    <label class="{{ $errors->has('workerIds') ? 'border border-red' : '' }}">
                        {{ __('Assign workers (optional)') }}
                    </label>
                    <div class="checkbox-container">
                        @foreach($workers as $worker)
                            <label for="workerIds">
                                <input wire:model.defer="workerIds"
                                       type="checkbox"
                                       name="workerIds[]"
                                       value="{{ $worker->id }}"
                                >
                                {{ $worker->name }}
                            </label>
                        @endforeach

                        <div class="{{ $errors->has('workerIds') ? 'error-message' : '' }}">
                            {{ $errors->has('workerIds') ? $errors->first('workerIds') : '' }}
                        </div>

                        {{-- var_export($rolePermissions) --}}
                    </div>

                </fieldset>


                <div class="actions">
                    <button type="submit" class="primary">
                        <span wire:loading.delay
                              wire:target="createOrUpdateEvent"
                              class="animate-spin">&#9696;</span>

                        <span wire:loading.remove
                              wire:target="createOrUpdateEvent">
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
                            <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                            {{  __('Delete?') }}
                        </button>
                    @endif

                </div>

            </form>


        </x-global::form-modal>
    </div>

    @if( $updateId !== '' )
        <div x-data="{ isDeleteModalOpen: $wire.entangle('isDeleteModalOpen') }">

            <x-global::form-modal
                trigger="isDeleteModalOpen"
                title="{{ __('Delete event') }}"
                id="{{ $deleteModalId }}"
            >
                <div>
                    <h3 class="h5">{{ $clientName }}</h3>
                    <hr class="divider">

                    <button wire:click="$emit('deleteEventListener')"
                            type="button"
                            class="danger"
                    >
                        {{  __('Confirm delete!') }}
                    </button>

                    <button
                        type="button"
                        class="danger alt"
                        @click="isDeleteModalOpen = false"
                    >
                        {{ __('Cancel') }}
                    </button>

                </div>

            </x-global::form-modal>
        </div>
    @endif

</div>
@push('scripts')
    <!-- include moment and one of the moment-timezone builds -->
    <script src="{{ url('/js/moment.js.min.2.29.4.js') }}"></script>
    <script src="{{ url('/js/moment-timezone.min.5.40.js') }}"></script>
    <!-- rrule library -->
    <script src="{{ url('/js/rrule.2.6.4.min.js') }}"></script>

    <script src="{{ url('/js/fullcalendar.6.1.7.min.js') }}"></script>

    <!-- the rrule-to-fullcalendar connector. must go AFTER the rrule lib -->
    <script src="{{ url('/js/fullcalendar-rrule.6.1.7.min.js') }}"></script>
    <!-- the connector. must go AFTER moment-timezone -->
    <script src="{{ url('/js/fullcalendar.moment-timezone.min.6.1.8.js') }}"></script>

    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('livewire:load', function () {
            var hu = {
                code: 'hu',
                week: {
                    dow: 1,
                    doy: 4, // The week that contains Jan 4th is the first week of the year.
                },
                buttonText: {
                    prev: 'vissza',
                    next: 'előre',
                    today: 'ma',
                    year: 'Év',
                    month: 'Hónap',
                    week: 'Hét',
                    day: 'Nap',
                    list: 'Lista',
                },
                weekText: 'Hét',
                allDayText: 'Egész nap',
                moreLinkText: 'további',
                noEventsText: 'Nincs megjeleníthető esemény',
            };

            {{--console.log(@json( $events ));--}}

            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'Europe/Budapest',
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                locale: hu,
                allDaySlot: false,
                defaultAllDay: false,
                slotMinTime: '00:00:00',
                slotMaxTime: '24:00:00',
                firstDay: 1,
                fixedWeekCount: 5,
                showNonCurrentDates: false,
                nowIndicator: true,
                eventBackgroundColor: '#3F57B9',
                eventBorderColor: '#777',
                editable: true,
                selectable: true,

                // open modal on selecting an area in the calendar view
                select: function (args) {
                @this.eventModal(args);
                },

                // open modal when clicking on an event
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

                // when the events are loaded by FullCalendar, modify html output by adding extended props
                eventDidMount: updateEventData,

            });

            calendar.addEventSource( @js( $events ) )
            calendar.setOption('contentHeight', 600);


            calendar.render();
        });


        function updateEventData(info) {
            // object destructuring
            const {el, event, view} = info;

            // inner flex container of the event
            const container = el.firstChild.firstChild;


            if (view.type === 'dayGridMonth') {
                if (event.extendedProps.client !== null && event.extendedProps.client.name) {
                    // Block events (events that spread on at least 2 days) have a bit different DOM structure
                    const eventTitle = el.childNodes[2];
                    if (eventTitle) {
                        eventTitle.innerText = event.extendedProps.client.name;
                    } else {
                        // this will get the title for block-style events
                        const eventTitle = el.firstChild.firstChild.childNodes[1].firstChild;
                        eventTitle.innerText = event.extendedProps.client.name;
                        console.log(eventTitle)
                    }
                }
            }

            if (view.type === 'timeGridWeek' && container && event.extendedProps && event.allDay === false) {

                if (event.extendedProps.client !== null && event.extendedProps.client.name) {
                    const eventTitle = container.childNodes[1].firstChild;

                    if (event.extendedProps.is_recurring === 1) {
                        const recurringIcon = '<i class="fa fa-refresh margin-left-0-5" aria-hidden="true"></i>';
                        eventTitle.innerHTML = event.extendedProps.client.name + recurringIcon;
                    } else {
                        eventTitle.innerText = event.extendedProps.client.name;
                    }

                }

                if (event.extendedProps.client !== null && event.extendedProps.client.address) {
                    const address = document.createElement('p');
                    const bold = document.createElement('b');

                    bold.innerText = event.extendedProps.client.address;
                    address.classList.add('address');
                    address.appendChild(bold);
                    container.appendChild(address)
                }

                // Adds the optional description
                if (event.extendedProps.description) {
                    const description = document.createElement('p');
                    description.innerText += event.extendedProps.description;
                    description.classList.add('description', 'bold', 'black');
                    container.appendChild(description)
                }

                if (event.extendedProps.workers && event.extendedProps.workers.length > 0) {
                    const workers = event.extendedProps.workers;
                    const bar = document.createElement('div');
                    bar.classList.add('workers-container');

                    for (let i = 0; i < workers.length; i++) {
                        const badge = document.createElement('span');
                        badge.classList.add('badge', 'accent', 'semibold', 'fs-14');
                        badge.innerText = workers[i].name;
                        bar.appendChild(badge);
                    }

                    container.appendChild(bar)
                }

            }


        }

    </script>

@endpush

