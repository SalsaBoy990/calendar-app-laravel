<?php

namespace App\Http\Livewire\Worker;

use App\Models\Role;
use App\Models\User;
use App\Models\WorkerAvailability;
use App\Support\InteractsWithBanner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Redirector;

class Calendar extends Component {
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
    public string $description;
    public string $backgroundColor;

    public Collection $workers;
    public Collection $availabilities;
    public ?int $selectedWorkerId;


    // Is all-day event?
    public bool $allDay;


    protected array $rules = [
        'updateId'         => [ 'nullable', 'uuid', 'max:255' ],
        'selectedWorkerId' => [ 'required', 'int', 'min:1' ],
        'start'            => [ 'required', 'string', 'max:255' ],
        'end'              => [ 'nullable', 'string' ],
        'description'      => [ 'nullable', 'string', 'max:255' ],
        'backgroundColor'  => [ 'nullable', 'string', 'max:20' ],
    ];

    protected $listeners = [
        'deleteAvailabilityListener'  => 'deleteAvailability',
        'openDeleteAvailabilityModal' => 'openDeleteAvailabilityModal',
        'closeAvailabilityModal'      => 'closeAvailabilityModal',
    ];

    public function mount() {
        $this->initializeProperties();

        $workerRole = Role::where( 'slug', 'worker' )->get()->first();

        // query users that have worker role
        $this->workers = User::whereHas(
            'role',
            function ( $q ) use ( $workerRole ) {
                $q->where( 'id', $workerRole->id );
            } )->get();

        $this->selectedWorkerId = null;

    }

    public function updatedIsModalOpen() {
        $this->initializeProperties();
    }

    public function initializeProperties() {
        // Alpine
        $this->modalId           = 'worker-modal';
        $this->deleteModalId     = 'delete-worker-modal';
        $this->isDeleteModalOpen = false;
        $this->isModalOpen       = false;

        // Entity properties
        $this->start           = '';
        $this->end             = '';
        $this->description     = '';
        $this->backgroundColor = '';

        //
        $this->allDay           = false;
        $this->newId            = '';
        $this->updateId         = '';
        $this->availability     = null;
        $this->selectedWorkerId = null;

    }


    /**
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application {
        $this->availabilities = WorkerAvailability::with( 'user' )->get();

        return view( 'livewire.worker.calendar' );
    }


    public function availabilityChange( $availability ): void {
        $changedAvailability        = WorkerAvailability::where( 'id', $availability['id'] )->first();
        $changedAvailability->start = $availability['start'];

        if ( Arr::exists( $availability, 'end' ) ) {
            $changedAvailability->end = $availability['end'];
        }

        $changedAvailability->save();
    }


    /**
     * Opens modal, fills up livewire class properties for the form modal
     *
     * @param  array  $args
     *
     */
    public function availabilityModal( array $args ) {

        // existing event update flattening
        if ( array_key_exists( 'event', $args ) ) {
            $args           = $args['event'];
            $this->updateId = $args['id'];

            foreach ( $this->availabilities as $availability ) {
                if ( $availability->id === $this->updateId ) {
                    $this->availability = $availability;
                }
            }

            if ( $this->checkIfAvailabilityExists() ) {
                $this->banner( __( 'Worker availability does not exists!' ), 'danger' );

                return redirect()->route( 'workers' );
            }

            $this->description      = $this->availability->description ?? '';
            $this->backgroundColor  = $this->availability->backgroundColor ?? '';
            $this->selectedWorkerId = $this->availability->user->id ?? null;
        }

        $this->allDay = $args['allDay'];
        if ( $this->allDay === false ) {
            // datetime-local
            // Y-m-d\TH:i:s
            $this->start = date( "Y-m-d\TH:i:s", strtotime( $this->availability->start ?? $args['start'] ) );
            $this->end   = date( "Y-m-d\TH:i:s", strtotime( $this->availability->end ?? $args['end'] ) );

        } else {
            $this->start = date( "Y-m-d\TH:i:s", strtotime( $this->availability->start ?? $args['start'] ) );
            // all day events do not need to have the end date set, so check it
            $this->end = isset( $this->availability ) && $this->availability->end ?
                date( "Y-m-d\TH:i:s", strtotime( $this->availability->end ?? $args['end'] ) )
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
    public function createOrUpdateAvailability(): Redirector {

        $this->validate();

        // $user = User::where( 'id', $this->selectedWorkerId )->first();
        // get selected user entity (we have all the workers in the collection)
        $user = null;
        foreach ( $this->workers as $worker ) {
            if ( $worker->id === $this->selectedWorkerId ) {
                $user = $worker;
            }
        }

        DB::transaction(
            function () use ( $user ) {

                // if we have an id, update existing availability
                if ( $this->updateId !== '' ) {
                    $updateAvailability = null;
                    foreach ( $this->availabilities as $availability ) {
                        if ( $availability->id === $this->updateId ) {
                            $updateAvailability = $availability;
                        }
                    }

                    if ( $this->checkIfAvailabilityExists() ) {
                        $this->banner( __( 'Worker availability does not exists!' ), 'danger' );

                        return redirect()->route( 'workers' );
                    }

                    $updateAvailability->update( [
                        'start'           => $this->start,
                        'end'             => $this->end,
                        'description'     => $this->description,
                        'backgroundColor' => $this->backgroundColor,
                    ] );

                    if ( $this->selectedWorkerId !== $updateAvailability->user->id ) {
                        // null parent relation
                        $updateAvailability->user()->dissociate();

                        // associate with the selected user
                        $user = User::where( 'id', $this->selectedWorkerId )->first();
                        $updateAvailability->user()->associate( $user );
                    }

                    $updateAvailability->save();

                } else {
                    // create
                    $newAvailability = WorkerAvailability::create( [
                        'id'              => Str::uuid(),
                        'start'           => $this->start,
                        'end'             => $this->end,
                        'description'     => $this->description,
                        'backgroundColor' => $this->backgroundColor,
                    ] );

                    $newAvailability->user()->associate( $user );
                    $newAvailability->save();
                }

            },
            2
        );


        $this->updateId !== '' ?
            $this->banner( 'Successfully updated the worker availability "' . htmlspecialchars( $user->name ) . '"!' )
            :
            $this->banner( 'Successfully created the worker availability "' . htmlspecialchars( $user->name ) . '"!' );

        // Need to clear previous event data
        $this->initializeProperties();

        return redirect()->route( 'workers' );

    }


    /**
     * @return Redirector|null
     */
    public function deleteAvailability(): ?Redirector {

        // if we have an id, delete existing event
        if ( $this->updateId !== '' ) {

            $availability = WorkerAvailability::where( 'id', $this->updateId )->with( 'user' )->first();
            $title        = htmlspecialchars( $availability->user->name );

            // delete role, rollback transaction if fails
            DB::transaction(
                function () use ( $availability ) {
                    $availability->delete();
                },
                2
            );

            $this->initializeProperties();
            $this->banner( 'Successfully deleted the worker availability "' . $title . '"!' );
        }

        return redirect()->route( 'workers' );
    }


    /**
     * @return void
     */
    public function openDeleteAvailabilityModal(): void {
        $this->isDeleteModalOpen = true;
    }


    /**
     * @return void
     */
    public function closeAvailabilityModal(): void {
        $this->initializeProperties();
    }


    /**
     * @return bool
     */
    private function checkIfAvailabilityExists(): bool {
        return $this->availability === null;
    }
}
