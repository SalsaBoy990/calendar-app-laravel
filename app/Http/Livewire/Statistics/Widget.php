<?php

namespace App\Http\Livewire\Statistics;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Widget extends Component
{
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

    /**
     * The month you want to create the statistics
     *
     * @var int
     */
    public int $month;

    public string $startDate;
    public string $endDate;

    /* Chart option properties */
    public string $chartTitle;
    public string $chartId;
    public string $chartAreaWidth;
    public string $chartColor;
    public string $chartXAxisTitle;
    public string $chartVAxisTitle;


    protected array $rules = [
        //'clientId' => [ 'nullable', 'int', 'max:255' ],
        //'dataOption' => [ 'required', 'string', 'in:all,client' ],
        'startDate' => [ 'nullable', 'date' ],
        'endDate' => [ 'nullable', 'date' ],
    ];


    protected $listeners = [
        'queryData' => 'getStatisticsForChart',
        'generateChart' => 'generateChart'
    ];


    /**
     * @throws Exception
     */
    public function mount() {
        $this->chartData = null;
        $this->cleaningJobs = null;

        $this->clientId = 0;
        $this->dataOption = 'all';
        $this->month = 6;

        $firstDayOfTheMonth = new \DateTime('first day of this month', new \DateTimeZone('Europe/Budapest'));
        $lastDayOfTheMonth = new \DateTime('last day of this month', new \DateTimeZone('Europe/Budapest'));

        $this->startDate = $firstDayOfTheMonth->format('Y-m-d');
        $this->endDate = $lastDayOfTheMonth->format('Y-m-d');

        $this->chartTitle = 'Hours of cleaning works by clients';
        $this->chartId = 'chart_div';
        $this->chartAreaWidth = '65%';
        $this->chartColor = '#13B623';
        $this->chartXAxisTitle = 'Hours of work';
        $this->chartVAxisTitle = 'Client name';
    }


    public function render()
    {
        $this->queryDataForChart();
        $this->getJobList();

        return view('livewire.statistics.widget')->with([
            'cleaningJobs' => $this->cleaningJobs
        ]);
    }



    public function getJobList() {
        $result = DB::table( 'events' )
                    ->selectRaw(
                        "clients.name,
                        events.status,
                        events.is_recurring,
                        CASE
                            WHEN (events.is_recurring = 0) THEN TIME_FORMAT(ABS(TIMEDIFF(events.start, events.end)),'%H:%i')
                            ELSE TIME_FORMAT(events.duration,'%H:%i')
                        END AS durationCalc,

                        CASE
                            WHEN (events.is_recurring = 0) THEN TIME_TO_SEC(TIMEDIFF(events.end, events.start)) / 3600
                            ELSE TIME_TO_SEC(events.duration) / 3600
                        END AS hours,
                        events.start,
                        events.end"
                    )
                    ->join( 'clients', 'events.client_id', '=', 'clients.id' )
                    ->whereBetween('events.start', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'], 'and' )
                    ->orderByDesc( 'events.end' )
                    ->groupBy( 'clients.name',
                        'events.status',
                        'events.is_recurring',
                        'durationCalc',
                        'hours',
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
        $this->resetPage('page');

    }


    public function queryDataForChart() {
        // validate user input
        $this->validate();

        $statistics = DB::table( 'events' )
                        ->selectRaw(
                            "clients.name,
                CASE
                    WHEN (events.is_recurring = 0) THEN
                        SUM( TIME_TO_SEC( TIMEDIFF( events.end, events.start ) ) / 3600 )
                    ELSE
                        SUM( TIME_TO_SEC( events.duration ) / 3600 )
                END AS hours"
                        )->join( 'clients', 'events.client_id', '=', 'clients.id' )
                        ->whereBetween('events.start', [$this->startDate . ' 00:00:00', $this->endDate . ' 23:59:59'], 'and' )
                        ->groupBy( 'clients.name', 'events.is_recurring' )
                        ->get();

        $this->chartData = $statistics;
    }
}
