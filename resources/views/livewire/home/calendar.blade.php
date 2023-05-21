<div>

    <div class="mini-calendar-menu">
        <nav class="nav-links">
            @auth
                @role('super-administrator|administrator')
                <a class="{{ request()->routeIs('calendar') ? 'active' : '' }}"
                   href="{{ route('calendar') }}">
                    <i class="fa fa-calendar" aria-hidden="true"></i>{{ __('Calendar') }}
                </a>

                <!-- Worker availabilities link -->
                <a class="{{ request()->routeIs('workers') ? 'active' : '' }}"
                   href="{{ route('workers') }}">
                    <i class="fa fa-hourglass-start" aria-hidden="true"></i>
                    {{ __('Workers') }}
                </a>

                <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ url('/admin/dashboard') }}">
                    <i class="fa fa-tachometer" aria-hidden="true"></i>{{ __('Dashboard') }}
                </a>
                @endrole
            @endauth
        </nav>

        <div class="legend-container">
            <ul class="legend no-bullets padding-0">
                @foreach($statusColors as $name => $value)
                    <li>
                        <div class="color-box" style="background-color: {{ $value }}"></div>
                        <span>{{ $name }}</span>
                    </li>
                @endforeach

            </ul>
            @php
                $light = __('Light mode');
                $dark = __('Dark mode');
            @endphp

            <span
                class="pointer darkmode-toggle"
                rel="button"
                @click="toggleDarkMode"
                x-text="isDarkModeOn() ? '🔆' : '🌒'"
                :title="isDarkModeOn() ? '{{ $light }}' : '{{ $dark }}'"
            >
                    </span>
        </div>
    </div>


    <div id="calendar-container" wire:ignore>
        <div id="calendar"></div>
    </div>


    <div x-data="{
        isModalOpen: $wire.entangle('isModalOpen'),
        isRecurring: $wire.entangle('isRecurring')
    }">

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

                    <!-- Is event recurring? -->
                    <label for="isRecurring">{{ __('Recurring event') }}</label>
                    <input type="radio"
                           wire:model="isRecurring"
                           name="isRecurring"
                           value="1"
                    > Yes<br>
                    <input type="radio"
                           wire:model="isRecurring"
                           name="isRecurring"
                           value="0"
                           checked
                    > No

                    <div class="{{ $errors->has('isRecurring') ? 'red' : '' }}">
                        {{ $errors->has('isRecurring') ? $errors->first('isRecurring') : '' }}
                    </div>


                    <!-- Title -->
                    <label for="title">{{ __('Title') }}<span class="text-red">*</span></label>
                    <input
                        wire:model.defer="title"
                        type="text"
                        class="{{ $errors->has('title') ? 'border border-red' : '' }}"
                        name="title"
                    >

                    <div class="{{ $errors->has('title') ? 'error-message' : '' }}">
                        {{ $errors->has('title') ? $errors->first('title') : '' }}
                    </div>


                    <!-- Address -->
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

                            <!-- Freq -->
                            <label for="frequency">{{ __('Frequency') }}</label>
                            <select
                                wire:model.defer="frequency"
                                class="{{ $errors->has('frequency') ? 'border border-red' : '' }}"
                                aria-label="{{ __("Select a repeat frequency") }}"
                                name="frequency"
                            >
                                @foreach ($frequencies as $freq)
                                    <option
                                        {{ $frequency === $freq ? "selected": "" }} value="{{ $freq }}">{{ $freq }}</option>
                                @endforeach
                            </select>

                            <div class="{{ $errors->has('frequency') ? 'error-message' : '' }}">
                                {{ $errors->has('frequency') ? $errors->first('frequency') : '' }}
                            </div>


                            <label for="byweekday">{{ __('By Weekday') }}</label>
                            <select
                                wire:model.defer="byweekday"
                                class="{{ $errors->has('byweekday') ? 'border border-red' : '' }}"
                                aria-label="{{ __("Select a weekday") }}"
                                name="byweekday"
                            >
                                @foreach ($weekDays as $key => $value)
                                    <option
                                        {{ $byweekday === $value ? "selected": "" }} value="{{ $value }}">{{ $key }}</option>
                                @endforeach
                            </select>

                            <div class="{{ $errors->has('byweekday') ? 'error-message' : '' }}">
                                {{ $errors->has('byweekday') ? $errors->first('byweekday') : '' }}
                            </div>


                            <!-- Start recurring date -->
                            <label for="dtstart">{{ __('Start recurring date') }}</label>
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


                            <!-- End recurring date -->
                            <label for="duration">{{ __('Duration') }}</label>
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


                    <!-- Status -->
                    <div class="row-padding">
                        <div class="col s6">
                            <label for="status">{{ __('Status') }}<span class="text-red">*</span></label>
                            <select
                                wire:model.defer="status"
                                class="{{ $errors->has('status') ? 'border border-red' : '' }}"
                                aria-label="{{ __("Select a status") }}"
                                name="status"
                                id="status"
                            >

                                @foreach ($statusArray as $key => $value)
                                    <option
                                        {{ $status === $key ? "selected": "" }} value="{{ $key }}">{{ $value }}</option>
                                @endforeach

                            </select>
                            <div class="{{ $errors->has('status') ? 'error-message' : '' }}">
                                {{ $errors->has('status') ? $errors->first('status') : '' }}
                            </div>
                        </div>

                        <div class="col s6">
                            <label for="backgroundColor">{{ __('Background color (optional)') }}</label>
                            <input type="color"
                                   wire:model="backgroundColor"
                                   id="backgroundColor"
                                   name="backgroundColor"
                                   value="#e66465"
                            >

                            <div class="{{ $errors->has('backgroundColor') ? 'error-message' : '' }}">
                                {{ $errors->has('backgroundColor') ? $errors->first('backgroundColor') : '' }}
                            </div>
                        </div>
                    </div>

                    <label class="{{ $errors->has('workerIds') ? 'border border-red' : '' }}">
                        {{ __('Assign workers (optional)') }}
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

                        <div class="{{ $errors->has('workerIds') ? 'error-message' : '' }}">
                            {{ $errors->has('workerIds') ? $errors->first('workerIds') : '' }}
                        </div>

                        {{-- var_export($rolePermissions) --}}
                    </div>


                </fieldset>


                <div>
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
    <!-- rrule library -->
    <script src='https://cdn.jsdelivr.net/npm/rrule@2.6.4/dist/es5/rrule.min.js'></script>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js'></script>

    <!-- the rrule-to-fullcalendar connector. must go AFTER the rrule lib -->
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/rrule@6.1.7/index.global.min.js'></script>

    <script>
        document.addEventListener('livewire:load', function () {

            console.log(@json( $events ));

            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'local', // the default
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: '{{ 'hu' ?? config('app.locale') }}',
                allDaySlot: false,
                defaultAllDay: false,
                slotMinTime: '08:00:00',
                slotMaxTime: '20:00:00',
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
                eventDidMount: function (info) {
                    // object destructuring
                    const {el, event, view} = info;

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
                            const duration = (endTimestamp - startTimestamp) / (60 * 60 * 1000);

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

            calendar.addEventSource( @js( $events ) )
            calendar.setOption('contentHeight', 600);


            calendar.render();
        });

    </script>

@endpush

