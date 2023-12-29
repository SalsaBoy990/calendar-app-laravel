<?php

namespace App\Http\Livewire\Admin\Worker;

use App\Models\Event;
use App\Models\Worker;
use App\Models\WorkerAvailability;
use App\Support\InteractsWithBanner;
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
    // uuid for new availability
    public string $newId;

    // uui for existing availability
    public string $updateId;
    public ?WorkerAvailability $availability;

    // basic availability properties
    public string $start;
    public string $end;

    // not used currently
    public string $description;

    public Collection $workers;
    public Collection $availabilities;
    public ?int $selectedWorkerId;


    // Is all-day event?
    public bool $allDay;


    protected array $rules = [
        'updateId' => ['nullable', 'uuid', 'max:255'],
        'selectedWorkerId' => ['required', 'int', 'min:1'],
        'start' => ['required', 'string', 'max:255'],
        'end' => ['nullable', 'string'],
    ];

    protected $listeners = [
        'deleteAvailabilityListener' => 'deleteAvailability',
        'openDeleteAvailabilityModal' => 'openDeleteAvailabilityModal',
        'closeAvailabilityModal' => 'closeAvailabilityModal',
    ];

    public function mount()
    {
        $this->initializeProperties();

        // query workers
        $this->workers = Worker::all();

        $this->selectedWorkerId = null;

    }

    public function updatedIsModalOpen()
    {
        $this->initializeProperties();
    }

    public function initializeProperties()
    {
        // Alpine
        $this->modalId = 'worker-modal';
        $this->deleteModalId = 'delete-worker-modal';
        $this->isDeleteModalOpen = false;
        $this->isModalOpen = false;

        // Entity properties
        $this->start = '';
        $this->end = '';

        //
        $this->allDay = false;
        $this->newId = '';
        $this->updateId = '';
        $this->availability = null;
        $this->selectedWorkerId = null;

    }


    /**
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application
    {
        $this->availabilities = WorkerAvailability::with('worker')->get();

        return view('admin.livewire.worker.calendar');
    }


    public function availabilityChange($availability): void
    {
        $changedAvailability = WorkerAvailability::where('id', $availability['id'])->first();
        $changedAvailability->start = WorkerAvailability::convertFromLocalToUtc($availability['start'], WorkerAvailability::TIMEZONE, false,
            'Y-m-d\\TH:i:sP');

        if (Arr::exists($availability, 'end')) {
            $changedAvailability->end = WorkerAvailability::convertFromLocalToUtc($availability['end'], WorkerAvailability::TIMEZONE, false,
                'Y-m-d\\TH:i:sP');
        }

        $changedAvailability->save();
    }


    /**
     * Opens modal, fills up livewire class properties for the form modal
     *
     * @param  array  $args
     * @return RedirectResponse|void
     */
    public function availabilityModal(array $args)
    {

        // existing event update flattening
        if (array_key_exists('event', $args)) {
            $args = $args['event'];
            $this->updateId = $args['id'];

            foreach ($this->availabilities as $availability) {
                if ($availability->id === $this->updateId) {
                    $this->availability = $availability;
                }
            }

            if ($this->checkIfAvailabilityExists()) {
                $this->banner(__('Worker availability does not exists!'), 'danger');

                return redirect()->route('workers');
            }

            $this->selectedWorkerId = $this->availability->worker->id ?? null;
        }

        $this->allDay = $args['allDay'];
        if ($this->allDay === false) {

            $this->start = isset($this->availability->start) ?
                $this->availability->start->setTimezone(Event::TIMEZONE) :
                Event::convertFromUtcToLocal($args['start'], Event::TIMEZONE, false, 'Y-m-d\TH:i:s.uP');

            $this->end = isset($this->availability->end) ?
                $this->availability->end->setTimezone(Event::TIMEZONE) :
                Event::convertFromUtcToLocal($args['end'], Event::TIMEZONE, false, 'Y-m-d\TH:i:s.uP');

        } else {
            $this->start = date("Y-m-d\TH:i:s", strtotime($this->availability->start ?? $args['start']));
            // all day events do not need to have the end date set, so check it
            $this->end = isset($this->availability) && $this->availability->end ?
                date("Y-m-d\TH:i:s", strtotime($this->availability->end ?? $args['end']))
                :
                null;


        }

        $this->isModalOpen = true;
    }


    /**
     * Create new or update existing event
     *
     * @return Redirector
     */
    public function createOrUpdateAvailability(): Redirector
    {

        $this->validate();

        // get selected worker entity (we have all the workers in the collection)
        $workerEntity = null;
        foreach ($this->workers as $worker) {
            if ($worker->id === $this->selectedWorkerId) {
                $workerEntity = $worker;
            }
        }

        DB::transaction(
            function () use ($workerEntity) {

                // if we have an id, update existing availability
                if ($this->updateId !== '') {
                    $updateAvailability = null;
                    foreach ($this->availabilities as $availability) {
                        if ($availability->id === $this->updateId) {
                            $updateAvailability = $availability;
                        }
                    }

                    if ($this->checkIfAvailabilityExists()) {
                        $this->banner(__('Worker availability does not exists!'), 'danger');

                        return redirect()->route('workers');
                    }

                    $data = $this->getAvailabilityProps();

                    $updateAvailability->update($data);

                    if ($this->selectedWorkerId !== $updateAvailability->worker->id) {
                        // null parent relation
                        $updateAvailability->worker()->dissociate();

                        // associate with the selected user
                        $workerEntity = Worker::where('id', $this->selectedWorkerId)->first();
                        $updateAvailability->worker()->associate($workerEntity);
                    }

                    $updateAvailability->save();

                } else {

                    $data = $this->getAvailabilityProps();
                    $data['id'] = Str::uuid();

                    // create
                    $newAvailability = WorkerAvailability::create($data);
                    $newAvailability->worker()->associate($workerEntity);
                    $newAvailability->save();
                }

            },
            2
        );


        $this->updateId !== '' ?
            $this->banner(__('Successfully updated the worker availability ":name"!',
                ['name' => htmlspecialchars($workerEntity->name)]))
            :
            $this->banner(__('Successfully created the worker availability ":name"!',
                ['name' => htmlspecialchars($workerEntity->name)]));

        // Need to clear previous event data
        $this->initializeProperties();

        return redirect()->route('workers');

    }


    /**
     * @return Redirector|null
     */
    public function deleteAvailability(): ?Redirector
    {

        // if we have an id, delete existing event
        if ($this->updateId !== '') {

            $availability = WorkerAvailability::where('id', $this->updateId)->with('worker')->first();
            $title = htmlspecialchars($availability->worker->name);

            // delete role, rollback transaction if fails
            DB::transaction(
                function () use ($availability) {
                    $availability->delete();
                },
                2
            );

            $this->initializeProperties();
            $this->banner(__('Successfully deleted the worker availability ":name"!', ['name' => $title]));
        }

        return redirect()->route('workers');
    }


    /**
     * @return void
     */
    public function openDeleteAvailabilityModal(): void
    {
        $this->isDeleteModalOpen = true;
    }


    /**
     * @return void
     */
    public function closeAvailabilityModal(): void
    {
        $this->initializeProperties();
    }


    /**
     * @return bool
     */
    private function checkIfAvailabilityExists(): bool
    {
        return $this->availability === null;
    }


    /**
     * @return array
     */
    private function getAvailabilityProps(): array
    {
        $start = WorkerAvailability::convertFromLocalToUtc($this->start, WorkerAvailability::TIMEZONE);

        // inconsistent formats workaround
        if ($start === false) {
            $start = WorkerAvailability::convertFromLocalToUtc($this->start, WorkerAvailability::TIMEZONE,
                false, 'Y-m-d\\TH:i:s');

            if ($start === false) {
                $start = WorkerAvailability::convertFromLocalToUtc($this->start, WorkerAvailability::TIMEZONE,
                    false, 'Y-m-d\\TH:i');
            }
        }

        // inconsistent formats workaround
        $end = WorkerAvailability::convertFromLocalToUtc($this->end, WorkerAvailability::TIMEZONE);
        if ($end === false) {
            $end = WorkerAvailability::convertFromLocalToUtc($this->end, WorkerAvailability::TIMEZONE,
                false, 'Y-m-d\\TH:i:s');

            if ($end === false) {
                $end = WorkerAvailability::convertFromLocalToUtc($this->end, WorkerAvailability::TIMEZONE,
                    false, 'Y-m-d\\TH:i');

            }
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
    }
}
