<div
    x-data="calendar(@js($events))"
    wire:key="calendar-{{ $uuid }}"
    class="w-full"
>
    <div class="bg-white dark:bg-base-200 border border-stroke dark:border-base-300 rounded-lg p-4">
        {{-- Header --}}
        <div class="mb-[30px] flex items-center justify-between rounded-lg border border-stroke dark:border-base-300 bg-gray-2 dark:bg-base-200 py-3 pr-4 pl-[30px]">
            <div class="flex items-center space-x-2">
                <button wire:click="goToPreviousPeriod" type="button" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-base-300 transition-colors duration-200 ease-in-out text-dark dark:text-white">
                    <x-artisanpack-icon name="o-chevron-left" class="w-4 h-4" />
                </button>
                <button wire:click="goToNextPeriod" type="button" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-base-300 transition-colors duration-200 ease-in-out text-dark dark:text-white">
                    <x-artisanpack-icon name="o-chevron-right" class="w-4 h-4" />
                </button>
                <button wire:click="goToToday" type="button" class="px-3 py-1 rounded-md hover:bg-gray-100 dark:hover:bg-base-300 transition-colors duration-200 ease-in-out text-dark dark:text-white">
                    Today
                </button>
            </div>
            <p class="text-base font-semibold text-dark dark:text-white sm:text-xl">
                {{ $headerText }}
            </p>
            <div class="relative z-20 inline-flex rounded-[5px] bg-white dark:bg-base-100 text-dark dark:text-white">
                <x-artisanpack-select
                    :options="[
						['name' => 'Day', 'id' => 'day'],
						['name' => 'Week', 'id' => 'week'],
						['name' => 'Month', 'id' => 'month'],
						['name' => 'Year', 'id' => 'year']
					]"
                    wire:model.live="view"
                    class="w-40"
                />
            </div>
        </div>

        {{-- Calendar Grid --}}
        <div class="w-full max-w-full bg-white dark:bg-neutral overflow-x-auto rounded-lg shadow-sm">
            <table class="w-full">
                <thead>
                <tr class="rounded-t-lg {{ 'custom' === $colorScheme ? 'bg-custom' : 'bg-' . $colorScheme . ' text-white' }}">
                    @foreach ($this->weekdays as $weekday)
                        <th class="h-[60px] w-10 p-2 text-xs lg:w-28 xl:text-base 2xl:w-40 {{ $loop->first ? 'rounded-tl-lg' : '' }} {{ $loop->last ? 'rounded-tr-lg' : '' }}">
                            <span class="hidden lg:block">{{ $weekday->format('l') }}</span>
                            <span class="hidden md:block lg:hidden">{{ $weekday->format('D') }}</span>
                            <span class="block md:hidden">{{ substr($weekday->format('D'), 0, 1) }}</span>
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach ($this->weeks as $week)
                    <tr class="h-20 text-center align-top">
                        @foreach ($week as $day)
                            @php
                                $isToday          = $day->date->isToday();
								$isCurrentMonth   = $day->date->isSameMonth($gridStartsAt);
								$isWeekend        = $day->date->isWeekend() && $weekendHighlight;

								$cellClasses = 'ease hover:bg-gray-2 dark:hover:bg-base-200 relative h-28 w-10 cursor-pointer border border-stroke dark:border-base-300 pt-0.5 pb-2 px-3 transition duration-500 md:h-[125px] lg:w-28 2xl:w-40';
								if ( ! $isCurrentMonth ) {
									$cellClasses .= ' bg-gray-1 dark:bg-base-300/50';
								} elseif ( $isWeekend ) {
									$cellClasses .= ' bg-gray-1/50 dark:bg-base-300/30';
								}
                            @endphp
                            <td class="{{ $cellClasses }}">
                                <div class="text-left text-sm font-medium {{ ! $isCurrentMonth ? 'text-dark/40 dark:text-white/40' : 'text-dark dark:text-white' }}">
                                    @if($isToday)
                                        @php
                                            $todayClasses = 'inline-flex items-center justify-center w-7 h-7 rounded-full ';
											if ( 'custom' === $colorScheme ) {
												$todayClasses .= 'bg-custom';
											} else {
												$todayClasses .= 'bg-' . $colorScheme . ' text-white';
											}
                                        @endphp
                                        <span class="{{ $todayClasses }}">{{ $day->date->day }}</span>
                                    @else
                                        <span class="inline-block mb-2">{{ $day->date->day }}</span>
                                    @endif
                                </div>
                                <div class="mt-2 space-y-1 overflow-y-auto max-h-[80px]">
                                    @foreach ($day->events as $event)
                                        @php
                                            $eventColorScheme = $event['colorScheme'] ?? 'primary';
											$eventClasses     = 'group relative z-10 rounded-sm border-l-[3px] bg-base-300 dark:bg-base-300 px-[24px] py-[6px] text-left mb-1 transition-transform duration-200 ease-in-out hover:scale-[1.02]';
											$eventStyles      = '';

											if ( 'custom' === $eventColorScheme && ! empty( $event['customColor'] ) ) {
												$eventStyles = "border-color: {$event['customColor']};";
											} else {
												$eventClasses .= match ( $eventColorScheme ) {
													'secondary' => ' border-secondary',
													'accent'    => ' border-accent',
													default     => ' border-primary',
												};
											}

											$tooltip    = $event['title'] ?? $event['label'];
											if ( ! empty( $event['start_time'] ) ) {
												$tooltip = $event['start_time'] . ' - ' . $tooltip;
											}
											$isMultiDay = isset( $event['is_multiday'] ) && $event['is_multiday'];
                                        @endphp
                                        <div class="{{ $eventClasses }}" @if(!empty($eventStyles)) style="{{ $eventStyles }}" @endif title="{{ $tooltip }}" data-event-id="{{ $event['id'] }}">
                                            <span class="event-name text-sm font-medium text-dark dark:text-white block">{{ $event['label'] }}</span>
                                            <span class="time text-sm text-base-content">
													@if($isMultiDay && isset($event['start_date']) && isset($event['end_date']))
                                                    {{ $event['start_date'] }} - {{ $event['end_date'] }}
                                                @elseif(!empty($event['start_time']))
                                                    {{ $event['start_time'] }}@if(!empty($event['end_time'])) - {{ $event['end_time'] }}@endif
                                                @endif
												</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Event Details Modal --}}
    <x-artisanpack-modal wire:model="eventModal" title="Event Details">
        <livewire:dynamic-component :component="$eventView" />
    </x-artisanpack-modal>
</div>

<script>
    function calendar(events) {
        return {
            allEvents: events,
            init() {
                this.applyCustomColors();

                this.$el.addEventListener('click', (e) => {
                    const eventEl = e.target.closest('[data-event-id]');
                    if (eventEl && eventEl.dataset.eventId) {
                        this.$wire.loadEventModal(eventEl.dataset.eventId);
                    }
                });
            },
            applyCustomColors() {
                {!! $customColorScript !!}
            }
        }
    }
</script>