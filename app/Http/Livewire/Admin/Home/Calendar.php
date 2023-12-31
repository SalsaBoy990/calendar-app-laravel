<?php

namespace App\Http\Livewire\Admin\Home;

use App\Interface\Repository\ClientRepositoryInterface;
use App\Interface\Repository\EventRepositoryInterface;
use App\Interface\Services\DateTimeServiceInterface;
use App\Models\Event;
use App\Models\Worker;
use App\Support\InteractsWithBanner;
use DateTimeInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Redirector;

class Calendar extends Component
{
    use InteractsWithBanner;

    // used by blade / alpinejs
    /**
     * @var string
     */
    public string $modalId;


    /**
     * @var string
     */
    public string $deleteModalId;


    /**
     * @var bool
     */
    public bool $isModalOpen;


    /**
     * @var bool
     */
    public bool $isDeleteModalOpen;


    // inputs
    /**
     * id for new event
     *
     * @var int|null
     */
    public ?int $newId;


    /**
     * id for existing event
     *
     * @var int|null
     */
    public ?int $updateId;


    /**
     * Event model entity
     *
     * @var Event|null
     */
    public ?Event $event;


    /**
     * start and end is for regular events
     * @var string
     */
    public string $start;


    /**
     * @var string|null
     */
    public ?string $end;


    /**
     * @var int
     */
    public int $isRecurring;


    /**
     * @var string
     */
    public string $duration;


    // basic event properties
    // all types of events can have these props
    /**
     * @var string
     */
    public string $description;


    /**
     * @var string
     */
    public string $status;


    /**
     * @var string|null
     */
    public ?string $backgroundColor;


    /**
     * @var array
     */
    public array $statusArray;


    /**
     * @var Collection
     */
    public Collection $workers;


    /**
     * @var array
     */
    public array $workerIds;


    /**
     * @var array
     */
    public array $statusColors;


    /**
     * @var int|null
     */
    public ?int $clientId;


    /**
     * @var Collection
     */
    public Collection $clients;


    /**
     * @var string
     */
    public string $clientName;


    /**
     * @var string
     */
    public string $clientAddress;


    // for recurring events (by recurrence rules)
    /**
     * @var string
     */
    private string $frequency;


    /**
     * @var array
     */
    public array $frequencies;


    /**
     * @var string
     */
    public string $frequencyName;


    /**
     * @var string
     */
    public string $dtstart;


    /**
     * @var string
     */
    public string $until;


    /**
     * @var string
     */
    public string $byweekday;


    /**
     * @var array
     */
    public array $weekDays;


    /**
     * @var int
     */
    private int $interval;


    /**
     * @var array
     */
    public array $rrule;


    // Event list as collection
    /**
     * @var Collection
     */
    public Collection $events;


    /**
     * @var EventRepositoryInterface
     */
    private EventRepositoryInterface $eventRepository;


    /**
     * @var ClientRepositoryInterface
     */
    private ClientRepositoryInterface $clientRepository;


    /**
     * @var DateTimeServiceInterface
     */
    private DateTimeServiceInterface $dateTimeService;

    /**
     * dynamically set rules based on event type (recurring or regular)
     * @return array[]
     */
    protected function rules(): array
    {
        // shared property validation rules
        $rules = [
            'updateId' => ['nullable', 'integer', 'min:1'],
            'workerIds' => ['array'],
            'clientId' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ];

        // non-recurring
        if ($this->isRecurring === 0) {
            $rules['start'] = ['required', 'string', 'max:255'];
            $rules['end'] = ['nullable', 'string', 'max:255'];

        } else {
            // recurring
            $rules['frequencyName'] = ['required', 'string'];
            $rules['byweekday'] = ['required', 'string'];
            $rules['dtstart'] = ['required', 'string'];
            $rules['until'] = ['nullable', 'string'];
            $rules['duration'] = ['required', 'string'];

        }
        return $rules;

    }


    /**
     * listen to frontend calendar events, bind them with backend methods with Livewire (ajax requests)
     *
     * @var string[]
     */
    protected $listeners = [
        'deleteEventListener' => 'deleteEvent',
        'openDeleteEventModal' => 'openDeleteEventModal',
        'closeEventModal' => 'closeEventModal',
    ];


    /**
     * @param  EventRepositoryInterface  $eventRepository
     * @param  ClientRepositoryInterface  $clientRepository
     * @param  DateTimeServiceInterface  $dateTimeService
     * @return void
     */
    public function boot(
        EventRepositoryInterface $eventRepository,
        ClientRepositoryInterface $clientRepository,
        DateTimeServiceInterface $dateTimeService
    ): void {
        $this->eventRepository = $eventRepository;
        $this->clientRepository = $clientRepository;
        $this->dateTimeService = $dateTimeService;
    }


    /**
     * Mount life-cycle hook of the livewire component
     * @return void
     */
    public function mount(): void
    {
        $this->initializeProperties();
        $this->workers = Worker::all();
    }


    /**
     * @return void
     */
    public function updatedIsModalOpen(): void
    {
        $this->initializeProperties();
    }

    /**
     * @return void
     */
    public function initializeProperties(): void
    {
        // Alpine
        $this->modalId = 'event-modal';
        $this->deleteModalId = 'delete-event-modal';
        $this->isDeleteModalOpen = false;
        $this->isModalOpen = false;
        $this->isRecurring = 0;

        // Entity properties init
        $this->start = '';
        $this->end = null;
        $this->description = '';
        $this->status = 'opened';
        $this->backgroundColor = null;
        $this->byweekday = '';
        $this->frequencyName = '';
        $this->dtstart = '';
        $this->until = '';
        $this->rrule = [];
        $this->duration = '';
        $this->interval = 1;

        //
        $this->allDay = false;
        $this->newId = null;
        $this->updateId = null;
        $this->event = null;
        $this->clientId = null;
        $this->clientName = '';
        $this->clientAddress = '';

        // statuses
        $this->statusArray = [
            'pending' => 'Pending',
            'opened' => 'Opened',
            'completed' => 'Completed',
            'closed' => 'Closed'
        ];

        // weekdays
        $this->weekDays = [
            __('Sunday') => 'Su',
            __('Monday') => 'Mo',
            __('Tuesday') => 'Tu',
            __('Wednesday') => 'We',
            __('Thursday') => 'Th',
            __('Friday') => 'Fr',
            __('Saturday') => 'Sa',
        ];

        // bi-weekly or other recurrences can be created by setting the interval property (interval=2 -> every second week/month...)
        $this->frequencies = [
            'Hetente' => 'weekly',
            'Kéthetente' => '2-weekly',
            'Háromhetente' => '3-weekly',
            'Négyhetente' => '4-weekly'
        ];

        $this->workerIds = [];

        // default background color palette by statuses
        $this->statusColors = [
            'pending' => '#025370',
            'opened' => '#c90000',
            'completed' => '#0f5d2a',
            'closed' => '#62626b'
        ];

        $this->clients = $this->clientRepository->getAllClients();
    }


    /**
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        /* Also query soft-deleted clients (needed for the event view) */
        $this->events = $this->eventRepository->getEvents();

        return view('admin.livewire.home.calendar');
    }


    /**
     * @param $event
     *
     * @return RedirectResponse|void
     * @throws \Exception
     */
    public function eventChange($event)
    {
        $changedEvent = null;

        foreach ($this->events as $singleEvent) {
            if ($singleEvent->id === (int) $event['id']) {
                $changedEvent = $singleEvent;
            }
        }

        if ($changedEvent === null) {
            $this->banner(__('Event does not exists!'), 'danger');

            return redirect()->route('calendar');
        }


        if (!$changedEvent->is_recurring) {
            // input 'Y-m-d\TH:i:sP', output: 'Y-m-d H:i:s'
            $changedEvent->start = $this->dateTimeService->convertFromLocalToUtc($event['start'], Event::TIMEZONE,
                false,
                DateTimeInterface::ATOM);

            if (Arr::exists($event, 'end')) {
                // input 'Y-m-d\TH:i:sP', output: 'Y-m-d H:i:s'
                $changedEvent->end = $this->dateTimeService->convertFromLocalToUtc($event['end'], Event::TIMEZONE,
                    false,
                    DateTimeInterface::ATOM);
            }
            $changedEvent->save();

        } else {
            // always use the uuid column here (which is the 'id')!
            $eventId = $changedEvent->id;
            $this->updateId = (int) $eventId;
            $this->event = $this->eventRepository->getEventById($eventId);

            if ($this->checkIfEventExists() === null) {
                $this->banner(__('Event does not exists!'), 'danger');
                return redirect()->route('calendar');
            }

            // Update the time part only
            // otherwise it would change the start date of the recurring event
            $newRules = $this->event->rrule;

            // input 'Y-m-d\TH:i:s', output: 'Y-m-d H:i:s'
            $newRules['dtstart'] = $this->dateTimeService->convertFromLocalToUtc($event['start'], Event::TIMEZONE,
                false,
                DateTimeInterface::ATOM, 'Y-m-d\TH:i:s\Z');

            // On resize, overwrite the duration field (the right way with DateTime class etc.)
            if (Arr::exists($event, 'start') && Arr::exists($event, 'end')) {

                // input 'Y-m-d H:i:s', output: 'Y-m-d H:i:s'
                $start = Event::convertFromLocalToUtc($event['start'], Event::TIMEZONE, true);

                if ($start === false) {
                    $start = Event::convertFromLocalToUtc($event['start'], Event::TIMEZONE, true, 'Y-m-d\\TH:i:sP');
                }

                // input 'Y-m-d H:i:s', output: 'Y-m-d H:i:s'
                $end = Event::convertFromLocalToUtc($event['end'], Event::TIMEZONE, true);

                if ($end === false) {
                    $end = Event::convertFromLocalToUtc($event['end'], Event::TIMEZONE, true, 'Y-m-d\\TH:i:sP');
                }

                $difference = $end->diff($start);
                $newDuration = $difference->format("%H:%I:%S");
                $this->event->duration = $newDuration;

                // change weekday if we moved the event to another day of the week
                $newRules['byweekday'] = substr($start->format('D'), 0, -1);
            }

            $this->event->rrule = $newRules;
            $this->event->save();
        }

    }


    /**
     * Opens modal, fills up livewire class properties for the form modal
     *
     * @param  array  $args
     *
     * @return RedirectResponse|void
     * @throws \Exception
     */
    public function eventModal(array $args)
    {

        // existing event update
        if (array_key_exists('event', $args)) {
            $args = $args['event'];
            $this->updateId = (int) $args['id'];
            $this->setCurrentEvent();

            if ($this->checkIfEventExists() === null) {
                $this->banner(__('Event does not exists!'), 'danger');

                return redirect()->route('calendar');
            }

            $this->initializeExistingPropertiesForModal();
        }

        $this->initializePropertiesFromArgs($args);
    }


    /**
     * Create new or update existing event
     *
     * @return Redirector
     */
    public function createOrUpdateEvent(): Redirector
    {
        $this->validate();

        DB::transaction(
            function () {

                // all event have these
                $eventProps = [
                    'description' => $this->description,
                ];

                // if we have an id, update existing event
                if (isset($this->updateId)) {

                    $eventEntity = $this->getCurrentEvent();
                    if ($eventEntity === null) {
                        $this->banner(__('Event does not exists!'), 'danger');

                        return redirect()->route('calendar');
                    }

                    $this->setEventProperties($eventProps);

                    $eventEntity = $this->eventRepository->updateEvent($eventEntity, $eventProps, $this->workerIds);
                    $eventEntity->save();
                    // refresh would also refresh relations, but client will be null here, because
                    // it does not query trashed (soft-deleted) clients as relations
                    // $eventEntity->refresh();

                    $this->banner(__('Successfully updated the event ":name"!',
                        ['name' => htmlspecialchars($eventEntity->client->name)]));
                } else {
                    $this->setEventProperties($eventProps);

                    $eventEntity = $this->eventRepository->createEvent($eventProps, $this->workerIds);
                    $eventEntity->save();
                    // $eventEntity->refresh();

                    $this->banner(__('Successfully created the event ":name"!',
                        ['name' => htmlspecialchars($eventEntity->client->name)]));
                }
            },
            2
        );

        // Need to clear previous event data
        $this->initializeProperties();
        return redirect()->route('calendar');
    }


    /**
     * Delete the selected event
     *
     * @return Redirector|null
     */
    public function deleteEvent(): ?Redirector
    {

        // if we have an id, delete existing event
        if (isset($this->updateId)) {

            $event = $this->getCurrentEvent();
            if ($event === null) {
                $this->banner(__('Event does not exists!'), 'danger');
                return redirect()->route('calendar');
            }

            $title = $event->client->name;

            // delete role, rollback transaction if fails
            DB::transaction(
                function () use ($event) {
                    $this->eventRepository->deleteEvent($event);
                },
                2
            );

            // reset loaded event properties for the modal
            $this->initializeProperties();

            $this->banner(__('Successfully deleted the event ":name"!', ['name' => htmlspecialchars($title)]));
        }

        return redirect()->route('calendar');
    }


    /**
     * Show delete modal
     *
     * @return void
     */
    public function openDeleteEventModal(): void
    {
        $this->isDeleteModalOpen = true;
    }


    /**
     * Reset selected event properties when closing the modal
     * @return void
     */
    public function closeEventModal(): void
    {
        $this->initializeProperties();
    }


    /**
     * Check if the event is properly loaded / exists
     * @return bool
     */
    private function checkIfEventExists(): bool
    {
        return $this->event === null;
    }


    /**
     * Set the recurring / normal event-specific properties
     *
     * @param $eventProps
     *
     * @return void
     * @throws \Exception
     */
    private function setEventProperties(&$eventProps): void
    {

        // recurring event props
        if ($this->isRecurring === 1) {
            $eventProps['is_recurring'] = 1;

            if ($this->byweekday !== '') {
                $this->rrule['byweekday'] = $this->byweekday;
            }

            $this->setFrequencyNameAndInterval();

            $this->rrule['freq'] = $this->frequencyName;
            $this->rrule['interval'] = $this->interval;


            if ($this->dtstart !== '') {
                // Fullcalendar returns inconsistent formats. FUCK YOU fullcalendar!
                // The format can be either 'Y-m-d H:i:s', or 'Y-m-d\TH:i'
                // Datetime need to be saved with the letters T and Z, so that it is recognized by fullcalendar as UTC,
                // and will be converted to local timezone using moment.js
                $this->rrule['dtstart'] = $this->dateTimeService->convertFromLocalToUtc($this->dtstart, Event::TIMEZONE,
                    false,
                    'Y-m-d H:i:s', 'Y-m-d\TH:i:s\Z');


                if ($this->rrule['dtstart'] === false) {
                    $this->rrule['dtstart'] = $this->dateTimeService->convertFromLocalToUtc($this->dtstart,
                        Event::TIMEZONE, false,
                        'Y-m-d\TH:i', 'Y-m-d\TH:i:s\Z');
                }
            }

            if ($this->until !== '') {
                $this->rrule['until'] = $this->until;
            }

            if ($this->duration !== '') {
                $eventProps['duration'] = $this->duration;
            }

            if (!empty($this->rrule)) {
                $eventProps['rrule'] = $this->rrule;
            }
        } else {
            // regular events; input 'Y-m-d H:i:s', output: 'Y-m-d H:i:s'
            $eventProps['start'] = $this->dateTimeService->convertFromLocalToUtc($this->start, Event::TIMEZONE);
            // input 'Y-m-d H:i:s', output: 'Y-m-d H:i:s'
            $eventProps['end'] = $this->dateTimeService->convertFromLocalToUtc($this->end, Event::TIMEZONE);
        }

        // If a client need to be associated with the event
        if ($this->clientId !== 0) {
            // color is from status or it is custom
            $eventProps['client_id'] = $this->clientId;
        }

    }


    /**
     * Set current event for the modal
     *
     * @return void
     */
    private function setCurrentEvent(): void
    {
        foreach ($this->events as $event) {
            if ($event->id === (int) $this->updateId) {
                $this->event = $this->eventRepository->getEventById($this->updateId);
                break;
            }
        }
    }


    /**
     * Get current event and return the entity
     *
     * @return Model|Event|null
     */
    private function getCurrentEvent(): Model|Event|null
    {
        foreach ($this->events as $event) {
            if ($event->id === (int) $this->updateId) {
                return $this->eventRepository->getEventById($this->updateId);
            }
        }

        return null;
    }


    /**
     * Get the color from the default palette (based on the event status)
     * @return string|null
     */
    private function getBackgroundColorFromStatus(): ?string
    {
        foreach ($this->statusColors as $key => $value) {
            if ($this->status === $key) {
                return $value;
            }
        }
        return null;
    }


    /**
     * Check if the user-supplied color is different from the default status colors
     * @return bool
     */
    private function isBackgroundColorCustom(): bool
    {
        foreach ($this->statusColors as $key => $value) {
            if ($this->backgroundColor === $value) {
                return false;
            }
        }

        return true;
    }


    /**
     * Initialize properties from event object for the modal
     * @return void
     * @throws \Exception
     */
    private function initializeExistingPropertiesForModal(): void
    {
        $this->workerIds = $this->event
            ->workers()
            ->get()
            ->pluck(['id'])
            ->toArray();

        $this->description = $this->event->description;
        $this->frequencyName = $this->event->rrule['freq'] ?? '';
        $this->byweekday = $this->event->rrule['byweekday'] ?? '';
        $this->until = $this->event->rrule['until'] ?? '';
        $this->interval = $this->event->rrule['interval'] ?? 1;
        $this->duration = $this->event->duration ?? '';
        $this->isRecurring = $this->event->is_recurring ?? 0;
        $this->clientId = 0;

        if (isset($this->event->rrule['dtstart'])) {
            // input 'Y-m-d\TH:i:s\Z', output: 'Y-m-d H:i:s'
            $this->dtstart = $this->dateTimeService->convertFromUtcToLocal($this->event->rrule['dtstart'],
                Event::TIMEZONE, false,
                'Y-m-d\TH:i:s\Z');
        } else {
            $this->dtstart = '';
        }

        $this->setFrequencyName();

        if (isset($this->event->client)) {
            $this->clientId = $this->event->client->id;
            $this->clientName = $this->event->client->name;
            $this->clientAddress = $this->event->client->address;
        }
    }


    /**
     * Initialize properties for modal from arguments coming from client-side (from FullCalendar)
     *
     * @param  array  $args
     *
     * @return void
     * @throws \Exception
     */
    private function initializePropertiesFromArgs(array $args): void
    {
        // only for non-recurring events
        if ($this->isRecurring === 0) {
            // datetime-local (input 'Y-m-d\TH:i:s.uP', output: 'Y-m-d H:i:s')
            $this->start = isset($this->event->start) ?
                $this->event->start->setTimezone(Event::TIMEZONE) :
                $this->dateTimeService->convertFromUtcToLocal($args['start'], Event::TIMEZONE, false,
                    'Y-m-d\TH:i:s.uP');

            if (isset($args['end'])) {
                // input 'Y-m-d\TH:i:s.uP', output: 'Y-m-d H:i:s'
                $this->end = isset($this->event->end) ?
                    $this->event->end->setTimezone(Event::TIMEZONE) :
                    $this->dateTimeService->convertFromUtcToLocal($args['end'], Event::TIMEZONE, false,
                        'Y-m-d\TH:i:s.uP');

            } else {
                $this->end = $this->event->end->setTimezone(Event::TIMEZONE) ?? null;
            }
        }


        if ($this->dtstart === '') {
            /* Need to set dtstart for the modal for recurring events */
            $this->dtstart = isset($this->event->start) ?
                $this->event->start->setTimezone(Event::TIMEZONE) :
                $this->dateTimeService->convertFromUtcToLocal($args['start'], Event::TIMEZONE, false,
                    'Y-m-d\TH:i:s.uP');
        }

        $this->isModalOpen = true;

    }


    /**
     * @return void
     */
    private function setFrequencyNameAndInterval(): void
    {
        switch ($this->frequencyName) {
            case 'weekly':
                $this->frequencyName = 'weekly';
                $this->interval = 1;
                break;
            case '2-weekly':
                $this->frequencyName = 'weekly';
                $this->interval = 2;
                break;
            case '3-weekly':
                $this->frequencyName = 'weekly';
                $this->interval = 3;
                break;
            case '4-weekly':
                $this->frequencyName = 'weekly';
                $this->interval = 4;
                break;
            default:
                $this->interval = 1;
        }
    }


    /**
     * @return void
     */
    private function setFrequencyName(): void
    {
        if ($this->frequencyName === 'weekly') {
            if ($this->interval === 1) {
                $this->frequencyName = 'weekly';
            } else {
                if ($this->interval === 2) {
                    $this->frequencyName = '2-weekly';
                } else {
                    if ($this->interval === 3) {
                        $this->frequencyName = '3-weekly';
                    }
                }
            }
        } else {
            if ($this->frequencyName === 'monthly') {
                $this->frequencyName = 'monthly';
            } else {
                $this->frequencyName = 'weekly';
            }
        }
    }
}
