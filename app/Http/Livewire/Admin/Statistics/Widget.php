<?php

namespace App\Http\Livewire\Admin\Statistics;

use App\Models\Client;
use App\Models\Event;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Widget extends Component
{
    use WithPagination;

    /**
     * Paginated Collection of jobs (events)
     * @var
     */
    protected $cleaningJobs;

    /**
     * The client you want to generate the statistics for
     *
     * @var int
     */
    public int $clientId;

    public Collection $clients;

    // client name and id for select field
    public array $clientsData;

    // for filtering by date interval
    public string $startDate;
    public string $endDate;


    protected array $rules = [
        'clientId' => ['required', 'int', 'max:255'],
        'startDate' => ['nullable', 'date'],
        'endDate' => ['nullable', 'date'],
    ];


    /**
     * @throws Exception
     */
    public function mount()
    {
        $this->cleaningJobs = null;
        $this->clientId = 0;

        $firstDayOfTheMonth = new DateTime('first day of this month', new DateTimeZone('Europe/Budapest'));
        $lastDayOfTheMonth = new DateTime('last day of this month', new DateTimeZone('Europe/Budapest'));

        $this->startDate = $firstDayOfTheMonth->format('Y-m-d');
        $this->endDate = $lastDayOfTheMonth->format('Y-m-d');

        $this->clients = Client::all();
        $this->clientsData[__('All')] = 0;

        foreach ($this->clients as $client) {
            $this->clientsData[$client->name] = $client->id;
        }
    }


    /**
     * @throws Exception
     */
    public function render()
    {
        $this->getJobList();

        return view('admin.livewire.statistics.widget')->with([
            'cleaningJobs' => $this->cleaningJobs
        ]);
    }


    /**
     * @throws Exception
     */
    public function getJobList()
    {
        // validate user input
        $this->validate();

        $tz = new DateTimeZone('Europe/Budapest');
        $startDate = new DateTime($this->startDate, $tz);
        $endDate = new DateTime($this->endDate, $tz);
        $interval = $startDate->diff($endDate);
        $weeks = (int) floor($interval->days / 7);


        $result = DB::table('events')
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
            ->join('clients', 'events.client_id', '=', 'clients.id');

        $result = $this->addWhereConditionsToQueries($result);
        $result = $result
            ->orderByDesc('clients.name')
            ->groupBy('clients.name',
                'events.status',
                'events.is_recurring',
                'durationCalc',
                'hours',
                'events.rrule',
                'events.start',
                'events.end'
            )
            ->paginate(Event::RECORDS_PER_PAGE);

        $this->cleaningJobs = $result;
    }


    /**
     * @return void
     * @throws Exception
     */
    public function getResults(): void
    {
        $this->getJobList();
        $this->resetPage();
    }


    /**
     * @param $query
     *
     * @return mixed
     */
    private function addWhereConditionsToQueries($query): mixed
    {

        if ($this->clientId === 0) {
            $query = $query
                ->whereRaw("events.start > ? AND events.start < ?", [$this->startDate, $this->endDate])
                ->orWhereRaw("DATE(JSON_UNQUOTE(JSON_EXTRACT(events.rrule , '$.dtstart'))) > ?", [$this->startDate]);
        } else {
            $query = $query
                ->whereRaw("events.start > ? AND events.start < ? AND events.client_id = ?",
                    [$this->startDate, $this->endDate, $this->clientId])
                ->orWhereRaw("DATE(JSON_UNQUOTE(JSON_EXTRACT(events.rrule , '$.dtstart'))) > ? AND events.client_id = ? ",
                    [$this->startDate, $this->clientId]);
        }

        return $query;
    }
}
