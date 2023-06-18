@push('scripts')
    <script src="https://www.gstatic.com/charts/loader.js"></script>
@endpush

<section x-data="{
        /* Binding PHP and JS properties */
        chartId: $wire.entangle('chartId'),
        chartData: $wire.entangle('chartData'),
        chartTitle: $wire.entangle('chartTitle'),
        chartAreaWidth: $wire.entangle('chartAreaWidth'),
        chartColor: $wire.entangle('chartColor'),
        chartXAxisTitle: $wire.entangle('chartXAxisTitle'),
        chartVAxisTitle: $wire.entangle('chartVAxisTitle'),
        sumOfHours: 0,

        /* Basic chart options */
        getOptions() {
            return {
                title: this.chartTitle,
                chartArea: {width: this.chartAreaWidth},
                colors: [this.chartColor],
                legend: {position: 'none'},
                hAxis: {
                    title: this.chartXAxisTitle,
                    minValue: 0
                },
                vAxis: {
                    title: this.chartVAxisTitle
                }
            }
        },

        /* Creates a 2D array from the PHP array of objects */
        prepareChartData() {
            var dataArray = [];
            this.sumOfHours = 0;
            for (var i = 0; i < this.chartData.length; i++) {
                this.sumOfHours += parseFloat(this.chartData[i].hours);
                dataArray.push([
                    this.chartData[i].name, parseFloat(this.chartData[i].hours)
                ]);
            }
            return dataArray;
        },

        /* Draws the Google Chart */
        drawChart() {
            console.log(this.chartData);

            var dataArray = this.prepareChartData();
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Client');
            data.addColumn('number', '');
            data.addRows(dataArray);


            var chart = new google.visualization.BarChart(document.getElementById(this.chartId));
            chart.draw(data, this.getOptions());
        }
    }"
         x-init="
        // init google chart config with packages to be used
        google.charts.load('current', {packages: ['corechart', 'bar']});
        // Set a callback to run when the Google Visualization API is loaded. laod chart for the first time
        google.charts.setOnLoadCallback(function() { drawChart(); });

        // watches the changes for chartData, and re-renders the chart with the updated data
        $watch('chartData', function() { drawChart(); })
    "
>
    <div>
        <form wire:submit.prevent="getResults">
            <div class="row-padding">
                <div class="col s6">
                    <label for="startDate">Start date</label>
                    <input type="date" wire:model.defer="startDate"/>
                </div>
                <div class="col s6">
                    <label for="startDate">End date</label>
                    <input type="date" wire:model.defer="endDate"/>
                </div>
            </div>

            <button type="submit">Generate</button>
        </form>
    </div>

    <div id="chart_div"></div>

    <h4 class="fs-18">Total number works: <span
            class="badge gray-60 text-white round">{{ $cleaningJobs->total() }}</span></h4>
    <h4 class="fs-18">Total working hours: <span class="badge orange-dark text-white round" x-text="sumOfHours"></span>
    </h4>

    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Hours</th>
            <th>Start</th>
            <th>End</th>
            <th>Recurrence</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($cleaningJobs))
            @php
                $index = 1;
				$currentPage = $cleaningJobs->currentPage()
            @endphp

            @foreach($cleaningJobs as $job)
                @php
                    $rrule = $job->is_recurring ? json_decode($job->rrule) : null;

					// recurrence '-' or '1 / week'
					$recurrence = '-';
					$recurringStartDates = [];
					$recurringEndDates = [];

					$jobStartDate = $job->start ? substr($job->start, 0, -3) : '';
					$jobEndDate = $job->end ? substr($job->end, 0, -3): '';

					// For recurring jobs
					if ($rrule) {
						$recurrence = $rrule->freq . ' ' . $rrule->interval; // example: '1 / week'

						// variables needed for date calculations between start and end dates of the user-defined-interval
						$intervalToAdd = '+' . $rrule->interval . ' ' . substr($rrule->freq, 0, -1); // example: +1 week
					    $tz        = new DateTimeZone( 'Europe/Budapest' );
						$startDate = new DateTime( $this->startDate, $tz );
						$endDate = new DateTime( $this->endDate, $tz);
						$iteratedDate = new DateTime( $rrule->dtstart, $tz);
						$firstRun = 0;

						while ($iteratedDate <= $endDate) {
							if ($iteratedDate <= $startDate) {
								continue;
							}

							// the first datetime
							if ($firstRun === 0) {
							    $recurringStartDates[] = $iteratedDate->format(DateTimeInterface::ATOM);

								// need a new object for the end datetime (not a reference to $iteratedDate)
								$tempDate = new DateTime($iteratedDate->format(DateTimeInterface::ATOM), $tz);

								// extract duration in minutes
							    $time = explode(':', $job->durationCalc);
                                $minutes = ($time[0] * 60.0 + $time[1] * 1.0);
							    $duration = '+' . $minutes . ' minute';

								// add the duration for the end datetime
							    $tempDate->modify($duration);
							    $recurringEndDates[] = $tempDate->format(DateTimeInterface::ATOM);

								$firstRun++;
								continue;
							}

							// increase datetime by the recurrence interval (for example: '+1 week')
							$iteratedDate->modify($intervalToAdd);

							// if the increased datetime is outside the end date of the interval
							if ($iteratedDate > $endDate) {
								continue;
							}

							$recurringStartDates[] = $iteratedDate->format(DateTimeInterface::ATOM);

							// end datetime
							$tempDate = new DateTime($iteratedDate->format(DateTimeInterface::ATOM), $tz);

							// extract duration in minutes
							$time = explode(':', $job->durationCalc);
                            $minutes = ($time[0] * 60.0 + $time[1] * 1.0);
							$duration = '+' . $minutes . ' minute';

							// add the duration for the end datetime
							$tempDate->modify($duration);
							$recurringEndDates[] = $tempDate->format(DateTimeInterface::ATOM);
						}
					}
                @endphp

                <tr>
                    <td>
                        @if($currentPage > 1)
                            {{ ($currentPage - 1) * $cleaningJobs->perPage() + $index++ }}
                        @else
                            {{ $index++ }}
                        @endif
                    </td>

                    <td><b>{{ $job->name }}</b></td>
                    <td>{{ $job->durationCalc }}</td>

                    <td>
                        @if ($jobStartDate !== '')
                            {{ $jobStartDate }}
                        @else
                            @foreach($recurringStartDates as $dateItem)
                                {{ str_replace('T', ' ', substr($dateItem, 0, -9)) }}<br>
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if ($jobEndDate !== '')
                            {{ $jobStartDate }}
                        @else
                            @foreach($recurringEndDates as $dateItem)
                                {{ str_replace('T', ' ', substr($dateItem, 0, -9)) }}<br>
                            @endforeach
                        @endif
                    </td>

                    <td>{{ $recurrence  }}</td>

                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

    @if (isset($cleaningJobs))
        {{ $cleaningJobs->links('components.pagination-livewire') }}
    @endif

</section>
