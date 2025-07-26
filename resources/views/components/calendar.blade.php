<div wire:key="calendar-{{ rand() }}" class="mx-auto px-4 lg:container">
	<!-- Header with month/year display, navigation, and view selector -->
	<div
		x-data="{
            currentMonth: new Date().toLocaleString('{{ $locale }}', { month: 'long' }),
            currentYear: new Date().getFullYear(),
            currentView: 'month',
            currentDate: new Date(),
            days: [],
            weekdays: [],
            events: {{ json_encode($events) }},
            
            init() {
                // Initialize calendar data
                this.initCalendar();
                {!! $customColorScript !!}
                
                // Handle view switching
                this.$watch('currentView', (value) => {
                    this.switchView(value);
                });
                
                // Initialize event interactions
                this.initEventInteractions();
                
                // Update display based on view
                this.updateViewDisplay(this.currentView);
            },
            
            // Initialize calendar data
            initCalendar() {
                // Set up weekdays based on locale and start day
                this.setupWeekdays();
                
                // Generate days for the current month/view
                this.generateDays();
            },
            
            // Set up weekday names based on locale and start day
            setupWeekdays() {
                const weekdays = [];
                const date = new Date(2023, 0, 1); // Sunday
                
                // Adjust to start with Sunday or Monday based on sundayStart property
                const startDay = {{ $sundayStart ? 0 : 1 }};
                
                for (let i = 0; i < 7; i++) {
                    const day = new Date(date);
                    day.setDate(date.getDate() + ((i + startDay) % 7));
                    
                    weekdays.push({
                        full: day.toLocaleString('{{ $locale }}', { weekday: 'long' }),
                        short: day.toLocaleString('{{ $locale }}', { weekday: 'short' }),
                        min: day.toLocaleString('{{ $locale }}', { weekday: 'narrow' })
                    });
                }
                
                this.weekdays = weekdays;
            },
            
            // Generate days for the current month/view
            generateDays() {
                const days = [];
                const year = this.currentDate.getFullYear();
                const month = this.currentDate.getMonth();
                
                // Create a date for the first day of the month
                const firstDay = new Date(year, month, 1);
                
                // Get the day of the week for the first day (0 = Sunday, 1 = Monday, etc.)
                let firstDayOfWeek = firstDay.getDay();
                
                // Adjust for Monday start if needed
                if (!{{ $sundayStart ? 'true' : 'false' }} && firstDayOfWeek === 0) {
                    firstDayOfWeek = 7;
                }
                
                // Calculate the date for the first cell in the calendar grid
                // This might be in the previous month
                const start = new Date(firstDay);
                start.setDate(1 - (firstDayOfWeek - ({{ $sundayStart ? 0 : 1 }})));
                
                // Get the number of days in the current month
                const lastDay = new Date(year, month + 1, 0);
                const daysInMonth = lastDay.getDate();
                
                // Calculate the total number of cells needed (up to 6 weeks)
                const totalCells = Math.ceil((daysInMonth + firstDayOfWeek - ({{ $sundayStart ? 0 : 1 }})) / 7) * 7;
                
                // Generate the days
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                for (let i = 0; i < totalCells; i++) {
                    const date = new Date(start);
                    date.setDate(start.getDate() + i);
                    
                    const isCurrentMonth = date.getMonth() === month;
                    const isToday = date.getTime() === today.getTime();
                    const isWeekend = date.getDay() === 0 || date.getDay() === 6;
                    
                    // Find events for this day
                    const dayEvents = this.getEventsForDate(date);
                    
                    days.push({
                        date: date,
                        day: date.getDate(),
                        month: date.getMonth(),
                        year: date.getFullYear(),
                        isCurrentMonth: isCurrentMonth,
                        isToday: isToday,
                        isWeekend: isWeekend,
                        events: dayEvents
                    });
                }
                
                this.days = days;
            },
            
            // Get events for a specific date
            getEventsForDate(date) {
                if (!this.events || !this.events.length) return [];
                
                const dateStr = date.toISOString().split('T')[0]; // YYYY-MM-DD
                
                return this.events.filter(event => {
                    // Single day event
                    if (event.date && event.date === dateStr) return true;
                    
                    // Range event
                    if (event.range && Array.isArray(event.range) && event.range.length === 2) {
                        const startDate = new Date(event.range[0]);
                        const endDate = new Date(event.range[1]);
                        return date >= startDate && date <= endDate;
                    }
                    
                    return false;
                });
            },
            
            // Switch between day, week, month, and year views
            switchView(view) {
                this.currentView = view;
                this.generateDays();
                this.updateViewDisplay(view);
            },
            
            // Update display based on current view
            updateViewDisplay(view) {
                // Adjust header display based on view
                if (view === 'day') {
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    this.headerText = this.currentDate.toLocaleDateString('{{ $locale }}', options);
                } else if (view === 'week') {
                    // Calculate start and end of week
                    const weekStart = new Date(this.currentDate);
                    const weekEnd = new Date(this.currentDate);
                    const dayOfWeek = this.currentDate.getDay();
                    const diff = this.currentDate.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : {{ $sundayStart ? 0 : 1 }});
                    
                    weekStart.setDate(diff);
                    weekEnd.setDate(diff + 6);
                    
                    const startStr = weekStart.toLocaleDateString('{{ $locale }}', { month: 'short', day: 'numeric' });
                    const endStr = weekEnd.toLocaleDateString('{{ $locale }}', { month: 'short', day: 'numeric', year: 'numeric' });
                    
                    this.headerText = `${startStr} - ${endStr}`;
                } else if (view === 'year') {
                    this.headerText = this.currentYear.toString();
                } else {
                    // Month view (default)
                    this.headerText = `${this.currentMonth} ${this.currentYear}`;
                }
            },
            
            // Navigate to previous period based on current view
            navigatePrevious() {
                if (this.currentView === 'day') {
                    this.currentDate.setDate(this.currentDate.getDate() - 1);
                } else if (this.currentView === 'week') {
                    this.currentDate.setDate(this.currentDate.getDate() - 7);
                } else if (this.currentView === 'year') {
                    this.currentDate.setFullYear(this.currentDate.getFullYear() - 1);
                } else {
                    // Month view (default)
                    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
                }
                
                this.currentMonth = this.currentDate.toLocaleString('{{ $locale }}', { month: 'long' });
                this.currentYear = this.currentDate.getFullYear();
                
                this.generateDays();
                this.updateViewDisplay(this.currentView);
            },
            
            // Navigate to next period based on current view
            navigateNext() {
                if (this.currentView === 'day') {
                    this.currentDate.setDate(this.currentDate.getDate() + 1);
                } else if (this.currentView === 'week') {
                    this.currentDate.setDate(this.currentDate.getDate() + 7);
                } else if (this.currentView === 'year') {
                    this.currentDate.setFullYear(this.currentDate.getFullYear() + 1);
                } else {
                    // Month view (default)
                    this.currentDate.setMonth(this.currentDate.getMonth() + 1);
                }
                
                this.currentMonth = this.currentDate.toLocaleString('{{ $locale }}', { month: 'long' });
                this.currentYear = this.currentDate.getFullYear();
                
                this.generateDays();
                this.updateViewDisplay(this.currentView);
            },
            
            // Navigate to today
            navigateToday() {
                this.currentDate = new Date();
                this.currentMonth = this.currentDate.toLocaleString('{{ $locale }}', { month: 'long' });
                this.currentYear = this.currentDate.getFullYear();
                
                this.generateDays();
                this.updateViewDisplay(this.currentView);
            },
            
            // Initialize event interactions
            initEventInteractions() {
                // Event click handling is now done with Alpine.js @click handlers
            },
            
            // Handle day click
            handleDayClick(day) {
                console.log('Day clicked:', day.date);
                // Dispatch custom event for day click
                const event = new CustomEvent('calendar-day-click', {
                    detail: { date: day.date }
                });
                document.dispatchEvent(event);
            },
            
            // Handle event click
            handleEventClick(event) {
                console.log('Event clicked:', event.id);
                // Dispatch custom event for event click
                const customEvent = new CustomEvent('calendar-event-click', {
                    detail: { eventId: event.id }
                });
                document.dispatchEvent(customEvent);
            },
            
            // Get color classes for an event
            getEventColorClasses(event) {
                const colorScheme = event.colorScheme || 'primary';
                
                if (colorScheme === 'custom' && event.customColor) {
                    return {
                        'custom-color': true,
                        'text-white': true
                    };
                }
                
                return {
                    'bg-primary text-white': colorScheme === 'primary',
                    'bg-secondary text-white': colorScheme === 'secondary',
                    'bg-accent text-white': colorScheme === 'accent'
                };
            },
            
            // Header text computed property
            headerText: ''
        }"
		class="mb-[30px] flex flex-col space-y-3 rounded-lg border border-stroke dark:border-dark-3 bg-gray-2 dark:bg-dark-2 py-3 px-4"
	>
		<!-- Navigation and title row -->
		<div class="flex items-center justify-between">
			<!-- Navigation buttons -->
			<div class="flex items-center space-x-2">
				<button
					@click="navigatePrevious()"
					class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-3 transition-colors duration-200 ease-in-out"
					aria-label="Previous"
				>
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M10.6663 2.66668L4.66634 8.00001L10.6663 13.3333" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>

				<button
					@click="navigateToday()"
					class="px-3 py-1 text-sm rounded-lg hover:bg-gray-100 dark:hover:bg-dark-3 transition-colors duration-200 ease-in-out"
				>
					Today
				</button>

				<button
					@click="navigateNext()"
					class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-3 transition-colors duration-200 ease-in-out"
					aria-label="Next"
				>
					<svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M5.33366 2.66668L11.3337 8.00001L5.33366 13.3333" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
			</div>

			<!-- Current period display -->
			<p
				x-text="headerText || `${currentMonth} ${currentYear}`"
				class="text-base font-semibold text-dark dark:text-white sm:text-xl"
			></p>

			<!-- View selector dropdown -->
			<div class="relative z-20 inline-flex rounded-[5px] bg-white dark:bg-dark">
				<select
					x-model="currentView"
					class="relative z-20 h-11 appearance-none rounded-[5px] text-dark dark:text-white border border-stroke dark:border-dark-3 bg-transparent pr-10 pl-5 outline-hidden transition-all duration-300 ease-in-out"
				>
					<option value="day" class="dark:bg-dark-2">Day</option>
					<option value="week" class="dark:bg-dark-2">Week</option>
					<option value="month" class="dark:bg-dark-2">Month</option>
					<option value="year" class="dark:bg-dark-2">Year</option>
				</select>
				<!-- Dropdown arrow icon -->
				<span class="absolute right-4 top-1/2 -translate-y-1/2 text-dark dark:text-white">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.00039 11.4L3.20039 6.60001L4.60039 5.20001L8.00039 8.60001L11.4004 5.20001L12.8004 6.60001L8.00039 11.4Z" fill="currentColor"/>
                    </svg>
                </span>
			</div>
		</div>
	</div>

	<!-- Calendar grid -->
	<div class="w-full max-w-full bg-white dark:bg-dark-2 overflow-x-auto rounded-lg shadow-sm">
		<table class="w-full">
			<thead>
			<tr class="rounded-t-lg" :class="{
                    'bg-primary text-white': '{{ $colorScheme }}' === 'primary',
                    'bg-secondary text-white': '{{ $colorScheme }}' === 'secondary',
                    'bg-accent text-white': '{{ $colorScheme }}' === 'accent',
                    'custom-color-header': '{{ $colorScheme }}' === 'custom'
                }">
				<template x-for="(day, index) in weekdays" :key="index">
					<th class="h-[60px] p-2 text-xs lg:w-28 xl:text-base 2xl:w-40" :class="{ 'rounded-tl-lg': index === 0, 'rounded-tr-lg': index === 6 }">
						<span class="hidden lg:block" x-text="day.full"></span>
						<span class="hidden md:block lg:hidden" x-text="day.short"></span>
						<span class="block md:hidden" x-text="day.min"></span>
					</th>
				</template>
			</tr>
			</thead>
			<tbody>
			<template x-for="week in Math.ceil(days.length / 7)" :key="week">
				<tr class="h-20 text-center">
					<template x-for="i in 7" :key="i">
						<td
							class="relative h-28 w-10 cursor-pointer border border-stroke dark:border-dark-3 p-1 transition duration-500 ease hover:bg-gray-2 dark:hover:bg-dark-3 md:h-[125px] lg:w-28 2xl:w-40"
							:class="{
                                    'bg-gray-100 dark:bg-dark-3': days[(week-1)*7 + i - 1]?.isToday,
                                    'bg-gray-50 dark:bg-dark-3/50': days[(week-1)*7 + i - 1]?.isWeekend && {{ $weekendHighlight ? 'true' : 'false' }},
                                    'text-gray-400': !days[(week-1)*7 + i - 1]?.isCurrentMonth
                                }"
							@click="handleDayClick(days[(week-1)*7 + i - 1])"
						>
							<!-- Day number -->
							<div class="text-sm font-medium p-1" x-text="days[(week-1)*7 + i - 1]?.day"></div>

							<!-- Events for this day -->
							<div class="mt-1 space-y-1 overflow-y-auto max-h-20">
								<template x-for="(event, eventIndex) in days[(week-1)*7 + i - 1]?.events" :key="eventIndex">
									<div
										class="rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]"
										:class="getEventColorClasses(event)"
										:style="event.colorScheme === 'custom' && event.customColor ? `background-color: ${event.customColor}; color: ${a11yGetContrastColor(event.customColor)};` : ''"
										@click.stop="handleEventClick(event)"
									>
										<div class="font-medium" x-text="event.label"></div>
										<div x-show="event.start_time" class="text-xs opacity-80" x-text="event.start_time"></div>
									</div>
								</template>
							</div>
						</td>
					</template>
				</tr>
			</template>
			</tbody>
		</table>
	</div>
</div>
