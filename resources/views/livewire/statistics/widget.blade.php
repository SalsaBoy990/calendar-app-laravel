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
            for (var i = 0; i < this.chartData.length; i++) {
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
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Hours</th>
            <th>Start</th>
            <th>End</th>
            <th>Status</th>
            <th>Is recurring?</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($cleaningJobs))
            @foreach($cleaningJobs as $job)
                <tr>
                    <td><b>{{ $job->name }}</b></td>
                    <td>{{ $job->durationCalc }}</td>
                    <td>{{ $job->start }}</td>
                    <td>{{ $job->end }}</td>
                    <td>{{ $job->status }}</td>
                    <td>{{ $job->is_recurring }}</td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

    @if (isset($cleaningJobs))
        {{ $cleaningJobs->links('components.pagination-livewire') }}
    @endif

</section>
