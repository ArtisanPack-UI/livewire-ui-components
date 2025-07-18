<?php
/**
 * Calendar
 *
 * This file contains the Calendar class for the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents\View
 * @subpackage Components
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */


namespace ArtisanPack\LivewireUiComponents\View\Components;

use Carbon\CarbonPeriod;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;
/**
 * Calendar Class
 *
 * Provides functionality for the Calendar component.
 *
 * @since 1.0.0
 */

class Calendar extends Component
{
    public string $uuid;

    public function __construct(
        public ?string $id = null,
        public ?int $months = 1,
        public ?string $locale = 'en-EN',
        public ?bool $weekendHighlight = false,
        public ?bool $sundayStart = false,
        public ?string $colorScheme = 'primary', // New: primary, secondary, accent, or custom
        public ?string $customColor = null,      // New: hex color code for custom scheme
        public ?string $view = 'month',          // New: day, week, month, year
        public ?array $config = [],
        public ?array $events = [],
    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function setup(): string
    {
        $config = json_encode(array_merge([
            'type' => $this->months == 1 ? 'default' : 'multiple',
            'months' => $this->months,
            'jumpMonths' => $this->months,
            'view' => $this->view,                // New: Add view option
            'popups' => $this->popups(),
            'settings' => [
                'lang' => $this->locale,
                'visibility' => [
                    'daysOutside' => false,
                    'weekend' => $this->weekendHighlight,
                ],
                'selection' => [
                    'day' => false,
                ],
                'iso8601' => ! $this->sundayStart,
                'theme' => [                      // New: Add theme settings
                    'colorScheme' => $this->colorScheme,
                    'customColor' => $this->customColor,
                ],
            ],
            'CSSClasses' => 'y',
            'actions' => 'x',
        ], $this->config));

        $config = $this->addCss($config);

        return $config;
    }

    /**
     * Determines appropriate text color (black or white) based on background color
     * 
     * @param string $hexColor Hex color code (e.g., #FF5733)
     * @return string Text color (#ffffff or #000000)
     */
    public function getContrastColor(string $hexColor = null): string
    {
        if (!$hexColor) {
            return '#ffffff'; // Default to white text
        }
        
        // Remove # if present
        $hexColor = ltrim($hexColor, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));
        
        // Calculate luminance - standard formula
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        // Return black for bright colors, white for dark colors
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }

    // Add Tailwind CSS classes for the calendar
    public function addCss(string $config): string
    {
        $cssClasses = [
            'grid' => 'vanilla-calendar-grid flex flex-wrap justify-around',
            'calendar' => 'vanilla-calendar w-full',
            'header' => 'flex justify-between items-center p-4 border-b border-stroke dark:border-dark-3',
            'headerTitle' => 'text-lg font-semibold text-dark dark:text-white',
            'headerButton' => 'p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-3 transition-colors duration-200 ease-in-out',
            'weekDays' => 'grid grid-cols-7 text-center py-2 border-b border-stroke dark:border-dark-3',
            'weekDay' => match($this->colorScheme) {
                'secondary' => 'py-2 text-sm font-medium bg-secondary text-white',
                'accent' => 'py-2 text-sm font-medium bg-accent text-white',
                'custom' => 'py-2 text-sm font-medium custom-color-header',
                default => 'py-2 text-sm font-medium bg-primary text-white',
            },
            // Responsive day names for different screen sizes
            'weekDayName' => [
                'full' => 'hidden lg:block', // Full name (e.g., "Monday") - visible on large screens
                'short' => 'hidden md:block lg:hidden', // Short name (e.g., "Mon") - visible on medium screens
                'min' => 'block md:hidden', // Minimal name (e.g., "M") - visible on small screens
            ],
            'month' => 'grid grid-cols-7 gap-px',
            'monthDay' => 'p-1 h-24 sm:h-28 md:h-32 border border-stroke dark:border-dark-3 transition-all duration-200 ease-in-out hover:bg-gray-50 dark:hover:bg-dark-3',
            'monthDayNumber' => 'text-sm font-medium p-1',
            'monthDayToday' => 'bg-gray-100 dark:bg-dark-3',
            'monthDaySelected' => match($this->colorScheme) {
                'secondary' => 'bg-secondary/10 border-secondary',
                'accent' => 'bg-accent/10 border-accent',
                'custom' => 'custom-color-selected',
                default => 'bg-primary/10 border-primary',
            },
            'monthDayWeekend' => 'bg-gray-50 dark:bg-dark-3/50',
            'event' => [
                'primary' => 'bg-primary text-white rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
                'secondary' => 'bg-secondary text-white rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
                'accent' => 'bg-accent text-white rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
                'custom' => 'custom-color rounded-md p-1 text-xs mb-1 truncate shadow-sm transition-transform duration-200 ease-in-out hover:scale-[1.02]',
            ],
            'popup' => 'bg-white dark:bg-dark-2 p-3 rounded-lg shadow-lg border border-stroke dark:border-dark-3 max-w-xs z-50',
            'popupEvent' => 'mb-2 last:mb-0',
            'popupEventTitle' => 'font-semibold text-dark dark:text-white',
            'popupEventDescription' => 'text-sm text-gray-600 dark:text-gray-300',
            'popupEventTime' => 'text-xs text-gray-500 dark:text-gray-400',
        ];
        
        return str_replace('"y"', json_encode($cssClasses), $config);
    }

    public function popups()
    {
        $buffer = [];

        return collect($this->events)->flatMap(function ($event) use (&$buffer) {
            if ($range = $event['range'] ?? []) {
                $dates = [];

                $period = CarbonPeriod::create($range[0], $range[1]);

                foreach ($period as $date) {
                    $dates[] = Carbon::parse($date)->format('Y-m-d');
                }
            }

            if (isset($event['date'])) {
                $dates = [Carbon::parse($event['date'])->format('Y-m-d')];
            }

            // Get event color scheme
            $colorScheme = $event['colorScheme'] ?? $this->colorScheme;
            $customColor = $event['customColor'] ?? $this->customColor;
            
            // Determine CSS class based on color scheme
            $cssClass = match($colorScheme) {
                'secondary' => 'bg-secondary text-white',
                'accent' => 'bg-accent text-white',
                'custom' => '',  // Will use inline style with custom color
                default => 'bg-primary text-white', // Default to primary
            };

            return collect($dates)->flatMap(function ($date) use ($event, &$buffer, $colorScheme, $customColor, $cssClass) {
                // Use title instead of description if available
                $title = $event['title'] ?? $event['description'] ?? null;
                $startTime = isset($event['start_time']) ? '<div class="text-sm">' . $event['start_time'] . '</div>' : '';
                
                // Create HTML for event popup
                $html = '<div class="event-popup">';
                $html .= '<div><strong>' . $event['label'] . '</strong></div>';
                $html .= $startTime;
                $html .= '<div>' . $title . '</div>';
                $html .= '<hr class="my-3 last:hidden" />';
                $html .= '</div>';

                $buffer[$date] = ($buffer[$date] ?? '') . $html;

                // Add custom color inline style if needed
                $modifier = $event['css'] ?? $cssClass;
                if ($colorScheme === 'custom' && $customColor) {
                    $textColor = $this->getContrastColor($customColor);
                    $modifier .= ' custom-color';
                    // The inline style will be applied via JavaScript
                }

                return [
                    $date => [
                        'modifier' => $modifier,
                        'html' => $buffer[$date],
                        'data' => [
                            'color-scheme' => $colorScheme,
                            'custom-color' => $customColor,
                        ],
                    ],
                ];
            });
        });
    }

    /**
     * Generate JavaScript for handling custom colors
     *
     * @return string JavaScript code
     */
    public function customColorScript(): string
    {
        // Get the custom color and contrast color
        $customColor = $this->customColor ?? '#6366f1'; // Default to indigo if not set
        $textColor = $this->getContrastColor($customColor);
        
        return <<<JS
    function applyCustomColors() {
        // Apply custom color to regular event elements
        document.querySelectorAll('.custom-color').forEach(el => {
            const customColor = el.closest('[data-custom-color]')?.dataset.customColor || '{$customColor}';
            if (customColor) {
                const textColor = getContrastColor(customColor);
                el.style.backgroundColor = customColor;
                el.style.color = textColor;
            }
        });
        
        // Apply custom color to header elements
        document.querySelectorAll('.custom-color-header').forEach(el => {
            el.style.backgroundColor = '{$customColor}';
            el.style.color = '{$textColor}';
        });
        
        // Apply custom color to selected day elements
        document.querySelectorAll('.custom-color-selected').forEach(el => {
            el.style.backgroundColor = `\${'{$customColor}'}20`; // 20% opacity
            el.style.borderColor = '{$customColor}';
        });
        
        // Add hover effect for custom color elements
        document.querySelectorAll('.custom-color').forEach(el => {
            el.addEventListener('mouseenter', () => {
                el.style.transform = 'scale(1.02)';
            });
            el.addEventListener('mouseleave', () => {
                el.style.transform = 'scale(1)';
            });
        });
    }
    
    function getContrastColor(hexColor) {
        hexColor = hexColor.replace('#', '');
        const r = parseInt(hexColor.substr(0, 2), 16);
        const g = parseInt(hexColor.substr(2, 2), 16);
        const b = parseInt(hexColor.substr(4, 2), 16);
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        return luminance > 0.5 ? '#000000' : '#ffffff';
    }
JS;
    }

    public function render(): View|Closure|string
    {
        return <<<'BLADE'
            <div wire:key="calendar-{{ rand() }}" class="mx-auto px-4 lg:container">
                <!-- Header with month/year display, navigation, and view selector -->
                <div 
                    x-data="{
                        currentMonth: new Date().toLocaleString('{{ $locale }}', { month: 'long' }),
                        currentYear: new Date().getFullYear(),
                        currentView: '{{ $view }}',
                        currentDate: new Date(),
                        calendar: null,
                        
                        init() {
                            // Initialize calendar
                            this.calendar = new VanillaCalendar($refs.calendar, {{ $setup() }}); 
                            this.calendar.init();
                            {{ $customColorScript() }}
                            applyCustomColors();
                            
                            // Update month/year display when calendar changes
                            document.addEventListener('vanilla-calendar-date-selected', (e) => {
                                this.currentMonth = e.detail.date.toLocaleString('{{ $locale }}', { month: 'long' });
                                this.currentYear = e.detail.date.getFullYear();
                                this.currentDate = e.detail.date;
                            });
                            
                            // Handle view switching
                            this.$watch('currentView', (value) => {
                                this.switchView(value);
                            });
                            
                            // Initialize event interactions
                            this.initEventInteractions();
                        },
                        
                        // Switch between day, week, month, and year views
                        switchView(view) {
                            if (!this.calendar) return;
                            
                            // Update calendar view
                            this.calendar.settings.selection.day = view === 'day';
                            this.calendar.settings.selection.week = view === 'week';
                            this.calendar.settings.selection.month = view === 'month';
                            this.calendar.settings.selection.year = view === 'year';
                            
                            // Apply the view change
                            this.calendar.setType(view);
                            
                            // Refresh the calendar to apply changes
                            this.calendar.update();
                            
                            // Update display based on view
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
                            if (!this.calendar) return;
                            
                            if (this.currentView === 'day') {
                                this.calendar.prev('day');
                            } else if (this.currentView === 'week') {
                                this.calendar.prev('week');
                            } else if (this.currentView === 'year') {
                                this.calendar.prev('year');
                            } else {
                                // Month view (default)
                                this.calendar.prev();
                            }
                            
                            // Update the display after navigation
                            this.updateViewDisplay(this.currentView);
                        },
                        
                        // Navigate to next period based on current view
                        navigateNext() {
                            if (!this.calendar) return;
                            
                            if (this.currentView === 'day') {
                                this.calendar.next('day');
                            } else if (this.currentView === 'week') {
                                this.calendar.next('week');
                            } else if (this.currentView === 'year') {
                                this.calendar.next('year');
                            } else {
                                // Month view (default)
                                this.calendar.next();
                            }
                            
                            // Update the display after navigation
                            this.updateViewDisplay(this.currentView);
                        },
                        
                        // Navigate to today
                        navigateToday() {
                            if (!this.calendar) return;
                            
                            this.calendar.today();
                            this.currentDate = new Date();
                            this.currentMonth = this.currentDate.toLocaleString('{{ $locale }}', { month: 'long' });
                            this.currentYear = this.currentDate.getFullYear();
                            
                            // Update the display after navigation
                            this.updateViewDisplay(this.currentView);
                        },
                        
                        // Initialize event interactions
                        initEventInteractions() {
                            // Add event listeners after calendar is initialized
                            document.addEventListener('vanilla-calendar-day-click', (e) => {
                                // Handle day click event
                                console.log('Day clicked:', e.detail.date);
                            });
                            
                            // Event hover interactions are handled by CSS in the addCss method
                            
                            // Add click event handling for events
                            document.addEventListener('click', (e) => {
                                const eventEl = e.target.closest('.vanilla-calendar-day__event');
                                if (eventEl) {
                                    const eventId = eventEl.dataset.eventId;
                                    if (eventId) {
                                        console.log('Event clicked:', eventId);
                                        // Dispatch custom event for event click
                                        const event = new CustomEvent('calendar-event-click', {
                                            detail: { eventId: eventId }
                                        });
                                        document.dispatchEvent(event);
                                    }
                                }
                            });
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
                    <div 
                        x-ref="calendar"
                        class="w-full"
                        x-init="$nextTick(() => { updateViewDisplay(currentView); })"
                    ></div>
                </div>
            </div>
            BLADE;
    }
}
