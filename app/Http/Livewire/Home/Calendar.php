<?php

namespace App\Http\Livewire\Home;

use App\Models\Client;
use App\Models\Event;
use App\Models\Worker;
use App\Support\InteractsWithBanner;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Redirector;

class Calendar extends Component
{

    use InteractsWithBanner;

    // used by blade / alpinejs
    public string $modalId;
    public string $deleteModalId;
    public bool $isModalOpen;
    public bool $isDeleteModalOpen;

    // inputs
    // uuid for new event
    public string $newId;

    // uui for existing event
    public string $updateId;

    // Event model entity
    public ?Event $event;

    // start and end is for regular events
    public string $start;
    public ?string $end;


    public int $isRecurring;
    public string $duration;

    // basic event properties
    // all types of events can have these props
    public string $description;
    public string $status;
    public ?string $backgroundColor;

    public array $statusArray;
    public Collection $workers;
    public array $workerIds;
    public array $statusColors;
    public ?int $clientId;
    public Collection $clients;

    // for recurring events (by recurrence rules)
    private string $frequency;
    public array $frequencies;
    public string $frequencyName;

    public string $dtstart;
    public string $until;
    public string $byweekday;
    public array $weekDays;
    private int $interval;
    public array $rrule;

    // Event list as collection
    public Collection $events;


    // dynamically set rules based on event type (recurring or regular)
    protected function rules()
    {

        // shared property validation rules
        $rules = [
            'updateId' => ['nullable', 'uuid', 'max:255'],
            'workerIds' => ['array'],
            'clientId' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
        ];

        // non-recurring
        if ($this->isRecurring === 0) {
            $rules['start'] = ['required', 'string', 'max:255'];
            $rules['end'] = ['nullable', 'string', 'max:255'];

            return $rules;

        } else {
            // recurring
            $rules['frequencyName'] = ['required', 'string'];
            $rules['byweekday'] = ['required', 'string'];
            $rules['dtstart'] = ['required', 'string'];
            $rules['until'] = ['nullable', 'string'];
            $rules['duration'] = ['required', 'string'];

            return $rules;

        }

    }


    // listen to frontend calendar events, bind them with backend methods with Livewire (ajax requests)
    protected $listeners = [
        'deleteEventListener' => 'deleteEvent',
        'openDeleteEventModal' => 'openDeleteEventModal',
        'closeEventModal' => 'closeEventModal',
    ];


    // Mount life-cycle hook of the livewire component
    public function mount()
    {
        $this->initializeProperties();

        $this->workers = Worker::all();
    }

    public function updatedIsModalOpen()
    {
        $this->initializeProperties();
    }

    public function initializeProperties()
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
        $this->frequency = '';
        $this->frequencyName = '';
        $this->dtstart = '';
        $this->until = '';
        $this->rrule = [];
        $this->duration = '';
        $this->interval = 1;

        //
        $this->allDay = false;
        $this->newId = '';
        $this->updateId = '';
        $this->event = null;
        $this->clientId = null;

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
            'Havonta' => 'monthly'
        ];

        $this->workerIds = [];

        // default background color palette by statuses
        $this->statusColors = [
            'pending' => '#025370',
            'opened' => '#c90000',
            'completed' => '#0f5d2a',
            'closed' => '#62626b'
        ];

        $this->clients = Client::with('client_detail')->get();
    }


    /**
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        $this->events = Event::with(['workers', 'client'])->get();

        return view('livewire.home.calendar');
    }


    /**
     * @param $event
     *
     * @return RedirectResponse|void
     */
    public function eventChange($event)
    {
        $changedEvent = null;

        foreach ($this->events as $singleEvent) {
            if ($singleEvent->id === $event['id']) {
                $changedEvent = $singleEvent;
            }
        }

        if ($changedEvent === null) {
            $this->banner(__('Event does not exists!'), 'danger');

            return redirect()->route('calendar');
        }

        $tformat = DateTimeInterface::ATOM;
        $tz = new DateTimeZone('Europe/Budapest');

        if (!$changedEvent->is_recurring) {
            $start = DateTime::createFromFormat($tformat, $event['start'], $tz);
            $changedEvent->start = $start->format('Y-m-d H:i:s');

            if (Arr::exists($event, 'end')) {
                $end = DateTime::createFromFormat($tformat, $event['end'], $tz);
                $changedEvent->end = $end->format('Y-m-d H:i:s');
            }
            $changedEvent->save();

        } else {
            // always use the uuid column here (which is the 'id')!
            $eventId = $changedEvent->id;
            $this->updateId = $eventId;
            $this->event = Event::where('id', '=', $eventId)->first();

            if ($this->checkIfEventExists() === null) {
                $this->banner(__('Event does not exists!'), 'danger');
                return redirect()->route('calendar');
            }

            // Update the time part only
            // otherwise it would change the start date of the recurring event
            $newRules = $this->event->rrule;
            $newStart = $newRules['dtstart'];

            // get the existing date part
            $newStart = substr($newStart, 0, -5);
            // get the new time part
            $newTime = date("H:i", strtotime($event['start']));

            $newStart .= $newTime;
            $newRules['dtstart'] = $newStart;


            // On resize, overwrite the duration field (the right way with DateTime class etc.)
            if (Arr::exists($event, 'start') && Arr::exists($event, 'end')) {
                $start = DateTime::createFromFormat($tformat, $event['start'], $tz);
                $end = DateTime::createFromFormat($tformat, $event['end'], $tz);

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
     */
    public function eventModal(array $args)
    {

        // existing event update
        if (array_key_exists('event', $args)) {
            $args = $args['event'];
            $this->updateId = $args['id'];
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
                if ($this->updateId !== '') {

                    $eventEntity = $this->getCurrentEvent();
                    if ($eventEntity === null) {
                        $this->banner(__('Event does not exists!'), 'danger');

                        return redirect()->route('calendar');
                    }

                    $this->setEventProperties($eventProps);

                    $eventEntity->update($eventProps);

                    $eventEntity->workers()->sync($this->workerIds);
                    $eventEntity->save();
                    $eventEntity->refresh();

                    $this->banner(__('Successfully updated the event ":name"!',
                        ['name' => htmlspecialchars($eventEntity->client->name)]));
                } else {

                    $eventProps['id'] = Str::uuid();

                    $this->setEventProperties($eventProps);

                    $eventEntity = Event::create($eventProps);

                    $eventEntity->workers()->sync($this->workerIds);
                    $eventEntity->save();
                    $eventEntity->refresh();

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
        if ($this->updateId !== '') {

            $event = $this->getCurrentEvent();
            if ($event === null) {
                $this->banner(__('Event does not exists!'), 'danger');

                return redirect()->route('calendar');
            }

            $title = $event->client->name;

            // delete role, rollback transaction if fails
            DB::transaction(
                function () use ($event) {
                    $event->delete();
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
     */
    private function setEventProperties(&$eventProps): void
    {

        // recurring event props
        if ($this->isRecurring === 1) {
            if ($this->byweekday !== '') {
                $this->rrule['byweekday'] = $this->byweekday;
            }

            $this->setFrequencyAndInterval();
            $this->rrule['freq'] = $this->frequency;
            $this->rrule['interval'] = $this->interval;


            if ($this->dtstart !== '') {
                $this->rrule['dtstart'] = $this->dtstart;
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
            // regular events
            $eventProps['start'] = $this->start;
            $eventProps['end'] = $this->end;
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
            if ($event->id === $this->updateId) {
                $this->event = $event;
                break;
            }
        }
    }


    /**
     * Get current event and return the entity
     *
     * @return Event|null
     */
    private function getCurrentEvent(): ?Event
    {
        foreach ($this->events as $event) {
            if ($event->id === $this->updateId) {
                return $event;
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
     */
    private function initializeExistingPropertiesForModal(): void
    {
        $this->workerIds = $this->event
            ->workers()
            ->get()
            ->pluck(['id'])
            ->toArray();

        $this->description = $this->event->description;

        $this->frequency = $this->event->rrule['freq'] ?? '';
        $this->byweekday = $this->event->rrule['byweekday'] ?? '';
        $this->dtstart = $this->event->rrule['dtstart'] ?? '';
        $this->until = $this->event->rrule['until'] ?? '';
        $this->interval = $this->event->rrule['interval'] ?? 1;
        $this->duration = $this->event->duration ?? '';
        $this->isRecurring = $this->event->is_recurring ?? 0;
        $this->clientId = 0;

        $this->setFrequencyName();

        if (isset($this->event->client)) {
            $this->clientId = $this->event->client->id;
        }
    }


    /**
     * Initialize properties for modal from arguments coming from client-side (from FullCalendar)
     *
     * @param  array  $args
     *
     * @return void
     */
    private function initializePropertiesFromArgs(array $args): void
    {
        // only for non-recurring events
        if ($this->isRecurring === 0) {

            // datetime-local
            $this->start = date("Y-m-d\TH:i:s", strtotime($this->event->start ?? $args['start']));

            if (isset($args['end'])) {
                $this->end = date("Y-m-d\TH:i:s", strtotime($this->event->end ?? $args['end']));
            } else {
                $this->end = $this->event->end ?? null;
            }
        }

        $this->isModalOpen = true;

    }

    private function setFrequencyAndInterval()
    {

        switch ($this->frequencyName) {
            case 'weekly':
                $this->frequency = 'weekly';
                $this->interval = 1;
                break;
            case '2-weekly':
                $this->frequency = 'weekly';
                $this->interval = 2;
                break;
            case '3-weekly':
                $this->frequency = 'weekly';
                $this->interval = 3;
                break;
            case 'monthly':
                $this->frequency = 'monthly';
                $this->interval = 1;
                break;
            default:
                $this->interval = 1;
        }
    }


    private function setFrequencyName()
    {
        if ($this->frequency === 'weekly') {
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
            if ($this->frequency === 'monthly') {
                $this->frequencyName = 'monthly';
            } else {
                $this->frequencyName = 'weekly';
            }
        }
    }
}
