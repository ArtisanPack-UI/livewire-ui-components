@php
    use Carbon\Carbon;
    // The `a11yGetContrastColor` method is not static, so we need an instance to call it.
    // This is a temporary solution until the method can be made static.
    $calendarInstance = new \ArtisanPack\LivewireUiComponents\View\Components\Calendar();
@endphp

<div
    x-data="{
        {{ $customColorScript }}
        
        // View switching functionality
        switchView(view) {
            // This will trigger the Livewire updatedView method
            @this.set('view', view);
        },
        
        // Event interaction
        handleEventClick(eventId) {
            // Dispatch custom event for parent components to handle
            const event = new CustomEvent('calendar-event-click', {
                detail: { eventId: eventId }
            });
            this.$el.dispatchEvent(event);
        }
    }"
    x-init="
        applyCustomColors();
        
        // Add event listeners for event clicks
        $el.addEventListener('click', (e) => {
            const eventEl = e.target.closest('[data-event-id]');
            if (eventEl) {
                const eventId = eventEl.dataset.eventId;
                if (eventId) {
                    handleEventClick(eventId);
                }
            }
        });
    "
    wire:key="calendar-{{ $uuid }}"
    class="w-full"
>
    <div class="bg-white dark:bg-base-200 border border-stroke dark:border-base-300 rounded-lg p-4">
        {{-- Header --}}
        <div class="mb-[30px] flex items-center justify-between rounded-lg border border-stroke dark:border-base-300 bg-gray-2 dark:bg-base-200 py-3 pr-4 pl-[30px]">
            <div class="flex items-center space-x-2">
                <button wire:click="goToPreviousPeriod" type="button" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-base-300 transition-colors duration-200 ease-in-out">
                    <x-artisanpack-icon name="o-chevron-left" class="w-4 h-4" />
                </button>
                <button wire:click="goToNextPeriod" type="button" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-base-300 transition-colors duration-200 ease-in-out">
                    <x-artisanpack-icon name="o-chevron-right" class="w-4 h-4" />
                </button>
                <button wire:click="goToToday" type="button" class="px-3 py-1 rounded-md hover:bg-gray-100 dark:hover:bg-base-300 transition-colors duration-200 ease-in-out">
                    Today
                </button>
            </div>

            <p class="text-base font-semibold text-dark dark:text-white sm:text-xl">
                {{ $headerText }}
            </p>

            <div class="relative z-20 inline-flex rounded-[5px] bg-white dark:bg-base-100">
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

        {{-- Weekdays --}}
        <div class="w-full max-w-full bg-white dark:bg-neutral overflow-x-auto rounded-lg shadow-sm">
            <table class="w-full">
                <thead>
                    <tr class="rounded-t-lg {{ $colorScheme === 'custom' ? 'bg-custom' : 'bg-' . $colorScheme . ' text-white' }}">
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
                                    $isToday = $day->date->isToday();
                                    $isCurrentMonth = $day->date->isSameMonth($gridStartsAt);
                                    $isWeekend = $day->date->isWeekend() && $weekendHighlight;

                                    $cellClasses = 'ease hover:bg-gray-2 dark:hover:bg-base-200 relative h-28 w-10 cursor-pointer border border-stroke dark:border-base-300 pt-0.5 pb-2 px-3 transition duration-500 md:h-[125px] lg:w-28 2xl:w-40';
                                    if (!$isCurrentMonth) {
                                        $cellClasses .= ' bg-gray-1 dark:bg-base-300/50';
                                    } elseif ($isWeekend) {
                                        $cellClasses .= ' bg-gray-1/50 dark:bg-base-300/30';
                                    }
                                @endphp
                                <td class="{{ $cellClasses }}">
                                    {{-- Day Number --}}
                                    <div class="text-left text-sm font-medium {{ !$isCurrentMonth ? 'text-dark/40 dark:text-white/40' : 'text-dark dark:text-white' }}">
                                        @if($isToday)
                                            @php
                                                $todayClasses = 'inline-flex items-center justify-center w-7 h-7 rounded-full ';
                                                if ($colorScheme === 'custom') {
                                                    $todayClasses .= 'bg-custom';
                                                } else {
                                                    $todayClasses .= 'bg-' . $colorScheme . ' text-white';
                                                }
                                            @endphp
                                            <span class="{{ $todayClasses }}">
                                                {{ $day->date->day }}
                                            </span>
                                        @else
                                            <span class="inline-block mb-2">{{ $day->date->day }}</span>
                                        @endif
                                    </div>

                                    {{-- Events --}}
                                    <div class="mt-2 space-y-1 overflow-y-auto max-h-[80px]">
                                        @foreach ($day->events as $event)
                                            @php
                                                $eventColorScheme = $event['colorScheme'] ?? 'primary';
                                                // Use base-300 for event background
                                                $eventClasses = 'group relative z-10 rounded-sm border-l-[3px] bg-base-300 dark:bg-base-300 px-[24px] py-[6px] text-left mb-1 transition-transform duration-200 ease-in-out hover:scale-[1.02]';
                                                $eventStyles = '';
                                                $borderColor = '';

                                                if ($eventColorScheme === 'custom' && !empty($event['customColor'])) {
                                                    $borderColor = $event['customColor'];
                                                    $eventStyles = "border-color: {$borderColor};";
                                                } else {
                                                    $eventClasses .= ' border-' . $eventColorScheme;
                                                }
                                                
                                                // Add tooltip with event details
                                                $tooltip = $event['title'] ?? $event['label'];
                                                if (!empty($event['start_time'])) {
                                                    $tooltip = $event['start_time'] . ' - ' . $tooltip;
                                                }

                                                // Determine if this is a multiday event
                                                $isMultiDay = isset($event['is_multiday']) && $event['is_multiday'];
                                                
                                                // For multiday events, we no longer use w-[190%] to make them span across days
                                                // Instead, they will be the width of a single day
                                            @endphp
                                            <div
                                                class="{{ $eventClasses }}"
                                                @if($eventStyles) style="{{ $eventStyles }}" @endif
                                                title="{{ $tooltip }}"
                                                data-event-id="{{ $event['id'] }}"
                                            >
                                                <span class="event-name text-sm font-medium text-dark dark:text-white block">
                                                    {{ $event['label'] }}
                                                </span>
                                                <span class="time text-sm text-body-color dark:text-dark-6">
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
</div>