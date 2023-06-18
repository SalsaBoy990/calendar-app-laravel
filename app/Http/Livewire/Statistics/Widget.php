<?php

namespace App\Http\Livewire\Statistics;

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
        //'clientId' => [ 'nullable', 'int', 'max:255' ],
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

        $this->chartTitle      = 'Hours of cleaning works by clients';
        $this->chartId         = 'chart_div';
        $this->chartAreaWidth  = '65%';
        $this->chartColor      = '#13B623';
        $this->chartXAxisTitle = 'Hours of work';
        $this->chartVAxisTitle = 'Client name';
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
        $tz        = new DateTimeZone( 'Europe/Budapest' );
        $startDate = new DateTime( $this->startDate, $tz );
        $endDate   = new DateTime( $this->endDate, $tz );
        $interval  = $startDate->diff( $endDate );
        $weeks = (int) floor($interval->days/7);


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
                    ->join( 'clients', 'events.client_id', '=', 'clients.id' )

                    ->whereRaw("events.start > ? AND events.start < ?", [ $this->startDate, $this->endDate ] )
                    ->orWhereRaw( "DATE(JSON_EXTRACT(events.rrule , '$.dtstart')) > ?", [ $this->startDate ] )

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
                    ->paginate( 4 );

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


    public function queryDataForChart() {
        // validate user input
        $this->validate();


        $tz        = new DateTimeZone( 'Europe/Budapest' );
        $startDate = new DateTime( $this->startDate, $tz );
        $endDate   = new DateTime( $this->endDate, $tz );
        $interval  = $startDate->diff( $endDate );
        $weeks = (int) floor($interval->days/7);

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
                        ->join( 'clients', 'events.client_id', '=', 'clients.id' )
                        ->whereRaw("events.start > ? AND events.start < ?", [ $this->startDate, $this->endDate ] )
                        ->orWhereRaw( "DATE(JSON_EXTRACT(events.rrule , '$.dtstart')) > ?", [ $this->startDate ] )
                        ->groupBy( 'clients.name', 'events.is_recurring' )
                        ->get();

        $this->chartData = $statistics;
    }
}
