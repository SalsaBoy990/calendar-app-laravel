<?php

namespace App\Http\Livewire\Home;

use App\Models\Event;
use App\Models\Role;
use App\Models\User;
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
    // uuid for new event
    public string $newId;

    // uui for existing event
    public string $updateId;
    public ?Event $event;

    // basic event properties
    public string $title;
    public string $start;
    public ?string $end;
    public string $address;
    public string $description;
    public string $status;

    public array $statusArray;
    public Collection $workers;
    public array $workerIds;


    // Is all-day event?
    public $allDay;

    // Event list as collection
    public Collection $events;

    protected array $rules = [
        'updateId'    => [ 'nullable', 'uuid', 'max:255' ],
        'workerIds'   => [ 'array' ],
        'title'       => [ 'required', 'string', 'max:255' ],
        'start'       => [ 'required', 'string', 'max:255' ],
        'end'         => [ 'nullable', 'string' ],
        'address'     => [ 'required', 'string', 'max:255' ],
        'description' => [ 'required', 'string', 'max:255' ],
        'status'      => [ 'required', 'in:pending,opened,completed,closed' ],
    ];

    protected $listeners = [
        'deleteEventListener'  => 'deleteEvent',
        'openDeleteEventModal' => 'openDeleteEventModal',
        'closeEventModal'      => 'closeEventModal',
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


    }

    public function updatedIsModalOpen() {
        $this->initializeProperties();
    }

    public function initializeProperties() {
        // Alpine
        $this->modalId           = 'event-modal';
        $this->deleteModalId     = 'delete-event-modal';
        $this->isDeleteModalOpen = false;
        $this->isModalOpen       = false;

        // Entity properties
        $this->title       = '';
        $this->start       = '';
        $this->end         = null;
        $this->address     = '';
        $this->description = '';
        $this->status      = 'opened';

        //
        $this->allDay   = false;
        $this->newId    = '';
        $this->updateId = '';
        $this->event    = null;

        $this->statusArray = [
            'pending'   => 'Pending',
            'opened'    => 'Opened',
            'completed' => 'Completed',
            'closed'    => 'Opened'
        ];

        $this->workerIds = [];
    }


    /**
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application {
        $this->events = Event::with( 'users' )->get();

        return view( 'livewire.home.calendar' );
    }


    public function eventChange( $event ): void {
        $changedEvent        = Event::where( 'id', $event['id'] )->first();
        $changedEvent->start = $event['start'];

        if ( Arr::exists( $event, 'end' ) ) {
            $changedEvent->end = $event['end'];
        }

        $changedEvent->save();
    }


    /**
     * Opens modal, fills up livewire class properties for the form modal
     *
     * @param  array  $args
     *
     * @return void
     */
    public function eventModal( array $args ): void {

        // existing event update flattening
        if ( array_key_exists( 'event', $args ) ) {
            $args           = $args['event'];
            $this->updateId = $args['id'];
            $this->event    = Event::where( 'id', $this->updateId )->first();

            $this->workerIds = $this->event->users()->get()->pluck( [ 'id' ] )->toArray();

            $this->title       = $this->event->title;
            $this->address     = $this->event->address;
            $this->description = $this->event->description;
            $this->status      = $this->event->status;
        }

        $this->allDay = $args['allDay'];
        if ( $this->allDay === false ) {
            // datetime-local
            // Y-m-d\TH:i:s
            $this->start = date( "Y-m-d\TH:i:s", strtotime( $this->event->start ?? $args['start'] ) );
            $this->end   = date( "Y-m-d\TH:i:s", strtotime( $this->event->end ?? $args['end'] ) );

        } else {
            $this->start = date( "Y-m-d\TH:i:s", strtotime( $this->event->start ?? $args['start'] ) );
            // all day events do not need to have the end date set, so check it
            $this->end = isset( $this->event ) && $this->event->end ?
                date( "Y-m-d\TH:i:s", strtotime( $this->event->end ?? $args['end'] ) )
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
    public function createOrUpdateEvent(): Redirector {
        $this->validate();

        DB::transaction(
            function () {

                // if we have an id, update existing event
                if ( $this->updateId !== '' ) {
                    $updateEvent = Event::where( 'id', $this->updateId )->first();


                    $updateEvent->update( [
                        'title'       => $this->title,
                        'start'       => $this->start,
                        'end'         => $this->end,
                        'address'     => $this->address,
                        'description' => $this->description,
                        'status'      => $this->status,
                    ] );

                    $updateEvent->users()->sync( $this->workerIds );
                    $updateEvent->save();
                } else {
                    $newEvent = Event::create( [
                        'id'          => Str::uuid(),
                        'title'       => $this->title,
                        'start'       => $this->start,
                        'end'         => $this->end,
                        'address'     => $this->address,
                        'description' => $this->description,
                        'status'      => $this->status,
                    ] );

                    $newEvent->users()->sync( $this->workerIds );
                    $newEvent->save();
                }

            },
            2
        );


        $this->updateId !== '' ?
            $this->banner( 'Successfully updated the event "' . htmlspecialchars( $this->title ) . '"!' )
            :
            $this->banner( 'Successfully created the event "' . htmlspecialchars( $this->title ) . '"!' );

        // Need to clear previous event data
        $this->initializeProperties();

        return redirect()->route( 'calendar' );

    }


    public function deleteEvent(): ?Redirector {

        // if we have an id, delete existing event
        if ( $this->updateId !== '' ) {

            $event = Event::where( 'id', $this->updateId )->first();
            $title = $event->title;

            // delete role, rollback transaction if fails
            DB::transaction(
                function () use ( $event ) {
                    $event->delete();
                },
                2
            );

            $this->initializeProperties();

            $this->banner( 'Successfully deleted the event "' . htmlspecialchars( $title ) . '"!' );
        }

        return redirect()->route( 'calendar' );
    }

    public function openDeleteEventModal() {
        $this->isDeleteModalOpen = true;
    }

    public function closeEventModal() {
        $this->initializeProperties();
    }

}
