<section>
    <div>
        <form wire:submit.prevent="getResults">
            <div class="row-padding">
                <div class="col s6">
                    <label for="startDate">{{ __('Start date') }}<span class="text-red">*</span></label>
                    <input type="date" wire:model.defer="startDate"
                           class="{{ $errors->has('startDate') ? 'border border-red' : '' }}"/>
                    <div class="{{ $errors->has('startDate') ? 'error-message' : '' }}">
                        {{ $errors->has('startDate') ? $errors->first('startDate') : '' }}
                    </div>
                </div>
                <div class="col s6">
                    <label for="startDate">{{ __('End date') }}<span class="text-red">*</span></label>
                    <input type="date" wire:model.defer="endDate"
                           class="{{ $errors->has('endDate') ? 'border border-red' : '' }}"/>
                    <div class="{{ $errors->has('endDate') ? 'error-message' : '' }}">
                        {{ $errors->has('endDate') ? $errors->first('endDate') : '' }}
                    </div>
                </div>
            </div>

            <div>
                <label for="clientId">{{ __('Client name') }}<span class="text-red">*</span></label>
                <select
                    wire:model.defer="clientId"
                    class="{{ $errors->has('clientId') ? 'border border-red' : '' }}"
                    aria-label="{{ __("Select a client") }}"
                    name="clientId"
                >
                    @foreach ($clientsData as $key => $value)
                        <option {{ $clientId === $value ? "selected": "" }} value="{{ $value }}">
                            {{ $key }}
                        </option>
                    @endforeach
                </select>
                <div class="{{ $errors->has('clientId') ? 'error-message' : '' }}">
                    {{ $errors->has('clientId') ? $errors->first('clientId') : '' }}
                </div>
            </div>

            <button type="submit" class="primary">{{ __('Generate') }}</button>
        </form>
    </div>

    @if ($cleaningJobs->total() > 0)
        <table>
            <thead>
            <tr class="fs-14">
                <th>#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Hours') }}</th>
                <th>{{ __('Start') }}</th>
                <th>{{ __('End') }}</th>
                <th>{{ __('Recurrence') }}</th>
            </tr>
            </thead>
            <tbody>
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
						$recurrence = $rrule->interval . ' ' . ($rrule->freq === 'weekly' ? __('weekly') : __('monthly'));

						// variables needed for date calculations between start and end dates of the user-defined-interval
						$intervalToAdd = '+' . $rrule->interval . ' ' . substr($rrule->freq, 0, -1); // example: +1 week
					    $utc = new DateTimeZone( 'UTC' );
					    $tz        = new DateTimeZone( 'Europe/Budapest' );
						$startDate = new DateTime( $this->startDate, $tz );
						$endDate = new DateTime( $this->endDate, $tz);
						$iteratedDate = new DateTime( $rrule->dtstart, $utc);
                        $iteratedDate = $iteratedDate->setTimezone($tz);

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
                            {{ $jobEndDate }}
                        @else
                            @foreach($recurringEndDates as $dateItem)
                                {{ str_replace('T', ' ', substr($dateItem, 0, -9)) }}<br>
                            @endforeach
                        @endif
                    </td>

                    <td>{{ $recurrence  }}</td>

                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $cleaningJobs->links('components.pagination-livewire') }}
    @else
        <p>{{ __('No results for the query.') }}</p>

    @endif

</section>
