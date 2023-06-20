<?php

namespace App\Http\Livewire\Statistics;

use App\Models\Client;
use App\Models\Event;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Widget extends Component {
    use WithPagination;

    /**
     * The data to pass to the Google Chart library to render
     *
     */
    public ?Collection $chartData;

    protected $cleaningJobs;

    /**
     * The client you want to generate the statistics for
     *
     * @var int
     */
    public int $clientId;

    public Collection $clients;

    public array $clientsData;

    /**
     * All ("all") or single Client ("client") statistics will be generated
     *
     * @var string
     */
    public string $dataOption;

    public string $startDate;
    public string $endDate;

    /* Chart option properties */
    public string $chartTitle;
    public string $chartId;
    public string $chartAreaWidth;
    public string $chartColor;
    public string $chartXAxisTitle;
    public string $chartVAxisTitle;

    public $totalWorks;


    protected array $rules = [
        'clientId'  => [ 'required', 'int', 'max:255' ],
        //'dataOption' => [ 'required', 'string', 'in:all,client' ],
        'startDate' => [ 'nullable', 'date' ],
        'endDate'   => [ 'nullable', 'date' ],
    ];


    protected $listeners = [
        'queryData'     => 'getStatisticsForChart',
        'generateChart' => 'generateChart'
    ];


    /**
     * @throws Exception
     */
    public function mount() {
        $this->chartData    = null;
        $this->cleaningJobs = null;

        $this->clientId   = 0;
        $this->dataOption = 'all';
        $this->totalWorks = null;

        $firstDayOfTheMonth = new DateTime( 'first day of this month', new DateTimeZone( 'Europe/Budapest' ) );
        $lastDayOfTheMonth  = new DateTime( 'last day of this month', new DateTimeZone( 'Europe/Budapest' ) );

        $this->startDate = $firstDayOfTheMonth->format( 'Y-m-d' );
        $this->endDate   = $lastDayOfTheMonth->format( 'Y-m-d' );

        $this->chartTitle      = __( 'Hours of cleaning works by clients' );
        $this->chartId         = 'chart_div';
        $this->chartAreaWidth  = '65%';
        $this->chartColor      = '#13B623';
        $this->chartXAxisTitle = __( 'Hours of work' );
        $this->chartVAxisTitle = __( 'Client name' );

        $this->clients = Client::all();

        $this->clientsData[ __( 'All' ) ] = 0;
        foreach ( $this->clients as $client ) {
            $this->clientsData[ $client->name ] = $client->id;
        }
    }


    public function render() {
        $this->queryDataForChart();
        $this->getJobList();

        return view( 'livewire.statistics.widget' )->with( [
            'cleaningJobs' => $this->cleaningJobs
        ] );
    }


    /**
     * @throws Exception
     */
    public function getJobList() {
        // validate user input
        $this->validate();

        $tz        = new DateTimeZone( 'Europe/Budapest' );
        $startDate = new DateTime( $this->startDate, $tz );
        $endDate   = new DateTime( $this->endDate, $tz );
        $interval  = $startDate->diff( $endDate );
        $weeks     = (int) floor( $interval->days / 7 );


        $result = DB::table( 'events' )
                    ->selectRaw(
                        "clients.name,
                        events.status,
                        events.is_recurring,
                        CASE
                            WHEN (events.is_recurring = 0) THEN
                                TIME_FORMAT(ABS(TIMEDIFF(events.start, events.end)),'%H:%i')
                            WHEN (events.is_recurring = 1) THEN
                                TIME_FORMAT(events.duration,'%H:%i')
                        END AS durationCalc,

                        CASE
                            WHEN (events.is_recurring = 0) THEN
                                TIME_TO_SEC(TIMEDIFF(events.end, events.start)) / 3600
                            WHEN (events.is_recurring = 1) THEN
                                TIME_TO_SEC(events.duration) / 3600 * FLOOR( $weeks / JSON_EXTRACT(`rrule` , '$.interval') )
                        END AS hours,
                        events.start,
                        events.end,
                        events.rrule"
                    )
                    ->join( 'clients', 'events.client_id', '=', 'clients.id' );

        if ( $this->clientId === 0 ) {
            $result = $result
                ->whereRaw( "events.start > ? AND events.start < ?", [ $this->startDate, $this->endDate ] )
                ->orWhereRaw( "DATE(JSON_EXTRACT(events.rrule , '$.dtstart')) > ?", [ $this->startDate ] );
        } else {
            $result = $result
                ->whereRaw( "events.start > ? AND events.start < ? AND events.client_id = ?",
                    [ $this->startDate, $this->endDate, $this->clientId ] )
                ->orWhereRaw( "DATE(JSON_EXTRACT(events.rrule , '$.dtstart')) > ? AND events.client_id = ? ",
                    [ $this->startDate, $this->clientId ] );
        }

        $result = $result
            ->orderByDesc( 'events.end' )
            ->groupBy( 'clients.name',
                'events.status',
                'events.is_recurring',
                'durationCalc',
                'hours',
                'events.rrule',
                'events.start',
                'events.end'
            )
            ->paginate( Event::RECORDS_PER_PAGE );

        $this->cleaningJobs = $result;
    }


    /**
     * @return void
     */
    public function getResults(): void {

        $this->queryDataForChart();
        $this->getJobList();
        $this->resetPage( 'page' );

    }


    /**
     * @throws Exception
     */
    public function queryDataForChart() {
        // validate user input
        $this->validate();


        $tz        = new DateTimeZone( 'Europe/Budapest' );
        $startDate = new DateTime( $this->startDate, $tz );
        $endDate   = new DateTime( $this->endDate, $tz );
        $interval  = $startDate->diff( $endDate );
        $weeks     = (int) floor( $interval->days / 7 );

        $statistics = DB::table( 'events' )
                        ->selectRaw(
                            "clients.name,
                            SUM(CASE
                                WHEN (events.is_recurring = 0) THEN
                                     TIME_TO_SEC( TIMEDIFF( events.end, events.start ) ) / 3600
                                WHEN (events.is_recurring = 1) THEN
                                    TIME_TO_SEC(events.duration) / 3600 * FLOOR( $weeks / JSON_EXTRACT(`rrule` , '$.interval') )
                            END) AS hours"
                        )
                        ->join( 'clients', 'events.client_id', '=', 'clients.id' );

        if ( $this->clientId === 0 ) {
            $statistics = $statistics
                ->whereRaw( "events.start > ? AND events.start < ?", [ $this->startDate, $this->endDate ] )
                ->orWhereRaw( "DATE(JSON_EXTRACT(events.rrule , '$.dtstart')) > ?", [ $this->startDate ] );
        } else {
            $statistics = $statistics
                ->whereRaw( "events.start > ? AND events.start < ? AND events.client_id = ?",
                    [ $this->startDate, $this->endDate, $this->clientId ] )
                ->orWhereRaw( "DATE(JSON_EXTRACT(events.rrule , '$.dtstart')) > ? AND events.client_id = ? ",
                    [ $this->startDate, $this->clientId ] );
        }
        $statistics = $statistics
            ->groupBy( 'clients.name', 'events.is_recurring' )
            ->get();

        $this->chartData = $statistics;
    }
}
