<?php

namespace App\Http\Livewire\Home;

use App\Models\Event;
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
    public bool $isModalOpen;

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


    // Is all-day event?
    public $allDay;

    // Event list as collection
    public Collection $events;

    protected array $rules = [
        'updateId'    => [ 'nullable', 'uuid', 'max:255' ],
        'title'       => [ 'required', 'string', 'max:255' ],
        'start'       => [ 'required', 'string', 'max:255' ],
        'end'         => [ 'nullable', 'string' ],
        'address'     => [ 'required', 'string', 'max:255' ],
        'description' => [ 'required', 'string', 'max:255' ],
        'status'      => [ 'required', 'in:pending,opened,completed,closed' ],
    ];

    public function mount() {
        $this->initializeProperties();
    }

    public function initializeProperties() {
        // Alpine
        $this->modalId = 'event-modal';

        // Entity properties
        $this->title       = '';
        $this->start       = '';
        $this->end         = null;
        $this->address     = '';
        $this->description = '';
        $this->status      = '';

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
    }


    /**
     * @return Application|Factory|View
     */
    public function render(): View|Factory|Application {
        $this->events = Event::all();

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
            $this->event    = Event::where('id', $this->updateId)->first();

            $this->title    = $this->event->title;
            $this->address     = $this->event->address;
            $this->description = $this->event->description;
            $this->status      = $this->event->status;
        }

        $this->allDay = $args['allDay'];
        if ( $this->allDay === false ) {
            // datetime-local
            $this->start = date( "Y-m-d\TH:i", strtotime( $this->event->start ?? $args['start'] ) );
            $this->end   = date( "Y-m-d\TH:i", strtotime( $this->event->end ?? $args['end'] ) );

        } else {
            $this->start = date( "Y-m-d\TH:i", strtotime( $this->event->start ?? $args['start'] ) );
            // all day events do not need to have the end date set, so check it
            $this->end = isset( $this->event ) && $this->event->end ?
                date( "Y-m-d\TH:i", strtotime( $this->event->end ?? $args['end'] ) )
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
                        'title' => htmlspecialchars( $this->title ),
                        'start' => htmlspecialchars( $this->start ),
                        'end'   => htmlspecialchars( $this->end ),
                        'address' => htmlspecialchars( $this->address ),
                        'description' => htmlspecialchars( $this->description ),
                        'status'   => htmlspecialchars( $this->status ),
                    ] );
                } else {
                    $newEvent = Event::create( [
                        'id'    => Str::uuid(),
                        'title' => htmlspecialchars( $this->title ),
                        'start' => htmlspecialchars( $this->start ),
                        'end'   => htmlspecialchars( $this->end ),
                        'address' => htmlspecialchars( $this->address ),
                        'description' => htmlspecialchars( $this->description ),
                        'status'   => htmlspecialchars( $this->status ),
                    ] );

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

        return redirect()->route( 'home' );

    }
}
