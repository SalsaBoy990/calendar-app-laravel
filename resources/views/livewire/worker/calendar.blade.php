<div>

    <div class="mini-calendar-menu">
        <nav class="nav-links">
            @auth
                @role('super-administrator|administrator')
                <a class="{{ request()->routeIs('calendar') ? 'active' : '' }}"
                   href="{{ route('calendar') }}">
                    <i class="fa fa-calendar" aria-hidden="true"></i>{{ __('Works') }}
                </a>

                <!-- Worker availabilities link -->
                <a class="{{ request()->routeIs('workers') ? 'active' : '' }}"
                   href="{{ route('workers') }}">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    {{ __('Availabilities') }}
                </a>

                <!-- Statistics -->
                <a class="{{ request()->routeIs('statistics') ? 'active' : '' }}"
                   href="{{ route('statistics') }}">
                    <i class="fa fa-line-chart" aria-hidden="true"></i>
                    {{ __('Statistics') }}
                </a>

                <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   href="{{ url('/admin/dashboard') }}">
                    <i class="fa fa-tachometer" aria-hidden="true"></i>{{ __('Dashboard') }}
                </a>
                @endrole
            @endauth
        </nav>

        <div>

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


    <div x-data="{ isModalOpen: $wire.entangle('isModalOpen') }">

        <x-admin.form-modal
            trigger="isModalOpen"
            title="{{ $updateId ? $availability->worker->name : __('Add availability') }}"
            id="{{ $modalId }}"
        >
            <form wire:submit.prevent="createOrUpdateAvailability">

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
                    <!-- Address -->

                    <!-- Start date -->
                    <label for="start">{{ __('Start date') }}<span class="text-red">*</span></label>
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
                    <label for="end">{{ __('End date') }}<span class="text-red">*</span></label>
                    <input
                        wire:model.defer="end"
                        type="datetime-local"
                        class="{{ $errors->has('end') ? 'border border-red' : '' }}"
                        name="end"
                    >

                    <div class="{{ $errors->has('end') ? 'red' : '' }}">
                        {{ $errors->has('end') ? $errors->first('end') : '' }}
                    </div>


                    <!-- Status -->
                    <label for="selectedWorkerId">{{ __('Attach to worker') }}<span class="text-red">*</span></label>
                    <select
                        wire:model.defer="selectedWorkerId"
                        class="{{ $errors->has('status') ? 'border border-red' : '' }}"
                        aria-label="{{ __("Select a worker") }}"
                        name="selectedWorkerId"
                        id="selectedWorkerId"
                    >
                        @if( $selectedWorkerId === null )
                            <option selected>{{ __("Select a worker") }}</option>
                        @endif
                        @foreach ($workers as $worker)
                            <option {{ $selectedWorkerId === $worker->id ? "selected": "" }} value="{{ $worker->id }}">
                                {{ $worker->name }}
                            </option>
                        @endforeach

                    </select>
                    <div class="{{ $errors->has('selectedWorkerId') ? 'red' : '' }}">
                        {{ $errors->has('selectedWorkerId') ? $errors->first('selectedWorkerId') : '' }}
                    </div>

                </fieldset>


                <div>
                    <button type="submit" class="primary">
                        <span wire:loading wire:target="createOrUpdateAvailability" class="animate-spin">&#9696;</span>
                        <span wire:loading.remove wire:target="createOrUpdateAvailability">
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
                        <button wire:click="$emit('openDeleteAvailabilityModal')"
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
                title="{{ __('Delete availability') }}"
                id="{{ $deleteModalId }}"
            >
                <div>
                    <h3 class="h5">{{ $availability->worker->name }}</h3>

                    <button wire:click="$emit('deleteAvailabilityListener')"
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

            const calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                timeZone: 'local', // the default (unnecessary to specify)
                initialView: 'timeGridWeek',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                locale: hu,
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
                select: function (args) {
                @this.availabilityModal(args);
                },

                eventClick: function (eventClickInfo) {
                @this.availabilityModal(eventClickInfo);
                },

                // resize all-day events
                eventResize: function (info) {
                @this.availabilityChange(info.event);
                },

                // drag and drop events
                eventDrop: function (info) {
                @this.availabilityChange(info.event);
                },

                eventDidMount: function (info) {
                    // object destructuring
                    const {el, event, view} = info;

                    // inner flex container of the event
                    const container = el.firstChild.firstChild;

                    if (view.type === 'timeGridWeek' && container && event.extendedProps && event.allDay === false) {

                        if (event.extendedProps.worker !== undefined) {
                            const worker = event.extendedProps.worker;
                            const eventTitle = container.childNodes[1].firstChild;
                            eventTitle.innerText = worker.name;
                        }

                    }

                }


            });


            calendar.addEventSource( @json( $availabilities ) )
            calendar.setOption('contentHeight', 600);


            calendar.render();
        })
        ;

    </script>

@endpush


