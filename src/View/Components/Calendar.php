<?php
namespace ArtisanPack\LivewireUiComponents\View\Components;

use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Carbon\CarbonPeriod;

class Calendar extends Component
{
    // Component Properties
    public ?string $id = null;
    public ?int $months = 1;
    public ?string $locale = 'en-EN';
    public ?bool $weekendHighlight = false;
    public ?bool $sundayStart = false;
    public ?string $colorScheme = 'primary';
    public ?string $customColor = null;
    public ?string $view = 'month';
    public ?array $config = [];
    public ?array $events = [];
    public string $uuid;
    public string $customColorScript;

    // Internal State
    public Carbon $gridStartsAt;
    public string $headerText = '';

    /**
     * Mount the component and initialize the state.
     */
    public function mount(
        ?string $id = null,
        ?int $months = 1,
        ?string $locale = 'en-EN',
        ?bool $weekendHighlight = false,
        ?bool $sundayStart = false,
        ?string $colorScheme = 'primary',
        ?string $customColor = null,
        ?string $view = 'month',
        ?array $config = [],
        ?array $events = []
    ) {
        $this->id = $id ?? 'calendar-' . uniqid();
        $this->uuid = uniqid('calendar-');
        $this->months = $months;
        $this->locale = $locale;
        $this->weekendHighlight = $weekendHighlight;
        $this->sundayStart = $sundayStart;
        
        // Validate color scheme
        $validColorSchemes = ['primary', 'secondary', 'accent', 'custom'];
        $this->colorScheme = in_array($colorScheme, $validColorSchemes) ? $colorScheme : 'primary';
        
        // Validate custom color
        if ($this->colorScheme === 'custom' && !empty($customColor)) {
            $this->customColor = $customColor;
        }
        
        // Validate view
        $validViews = ['day', 'week', 'month', 'year'];
        $this->view = in_array($view, $validViews) ? $view : 'month';
        
        $this->config = $config;
        $this->events = $events;
        
        Carbon::setLocale($this->locale);
        $this->gridStartsAt = Carbon::today()->startOfMonth();
        $this->customColorScript = $this->generateCustomColorScript();
        
        // Initialize header text based on current view
        $this->updateHeaderText();
    }

    /**
     * Generate the javascript for custom colors
     */
    private function generateCustomColorScript(): string
    {
        if ($this->colorScheme !== 'custom' || empty($this->customColor)) {
            return 'applyCustomColors() {},';
        }

        $bgColor = $this->customColor;
        $textColor = $this->a11yGetContrastColor($bgColor);
        $bgColorLight = $this->hexToRgba($bgColor, 0.1); // 10% opacity for hover/selected states

        return <<<JS
        applyCustomColors() {
            const style = document.createElement('style');
            style.textContent = `
                [wire\\:key="calendar-{$this->uuid}"] .bg-custom {
                    background-color: {$bgColor};
                    color: {$textColor};
                }
                [wire\\:key="calendar-{$this->uuid}"] .text-custom {
                    color: {$bgColor};
                }
                [wire\\:key="calendar-{$this->uuid}"] .hover\\:bg-custom:hover {
                    background-color: {$bgColorLight};
                }
                [wire\\:key="calendar-{$this->uuid}"] .border-custom {
                    border-color: {$bgColor};
                }
                [wire\\:key="calendar-{$this->uuid}"] .bg-custom-light {
                    background-color: {$bgColorLight};
                }
            `;
            document.head.appendChild(style);
        },
        JS;
    }
    
    /**
     * Convert hex color to rgba with opacity
     */
    private function hexToRgba(string $hexColor, float $opacity = 1.0): string
    {
        $hexColor = ltrim($hexColor, '#');
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));
        
        return "rgba({$r}, {$g}, {$b}, {$opacity})";
    }

    /**
     * Go to the next period based on current view.
     */
    public function goToNextPeriod(): void
    {
        switch ($this->view) {
            case 'day':
                $this->gridStartsAt->addDay();
                break;
            case 'week':
                $this->gridStartsAt->addWeek();
                break;
            case 'year':
                $this->gridStartsAt->addYear();
                break;
            case 'month':
            default:
                $this->gridStartsAt->addMonthNoOverflow();
                break;
        }
        
        // Update header text after changing the date
        $this->updateHeaderText();
    }

    /**
     * Go to the previous period based on current view.
     */
    public function goToPreviousPeriod(): void
    {
        switch ($this->view) {
            case 'day':
                $this->gridStartsAt->subDay();
                break;
            case 'week':
                $this->gridStartsAt->subWeek();
                break;
            case 'year':
                $this->gridStartsAt->subYear();
                break;
            case 'month':
            default:
                $this->gridStartsAt->subMonthNoOverflow();
                break;
        }
        
        // Update header text after changing the date
        $this->updateHeaderText();
    }

    /**
     * Go to today/current period based on current view.
     */
    public function goToToday(): void
    {
        $today = Carbon::today();
        
        switch ($this->view) {
            case 'day':
                $this->gridStartsAt = $today;
                break;
            case 'week':
                $this->gridStartsAt = $today->startOfWeek($this->sundayStart ? Carbon::SUNDAY : Carbon::MONDAY);
                break;
            case 'year':
                $this->gridStartsAt = $today->startOfYear();
                break;
            case 'month':
            default:
                $this->gridStartsAt = $today->startOfMonth();
                break;
        }
        
        // Update header text after changing the date
        $this->updateHeaderText();
    }
    
    /**
     * Go to the next month (legacy method for backward compatibility).
     */
    public function goToNextMonth(): void
    {
        $this->goToNextPeriod();
    }

    /**
     * Go to the previous month (legacy method for backward compatibility).
     */
    public function goToPreviousMonth(): void
    {
        $this->goToPreviousPeriod();
    }

    /**
     * Go to the current month (legacy method for backward compatibility).
     */
    public function goToCurrentMonth(): void
    {
        $this->goToToday();
    }
    
    /**
     * Switch to a different view (day, week, month, year).
     */
    public function updatedView(): void
    {
        // Reset grid start date based on new view
        $this->goToToday();
        
        // Update header text
        $this->updateHeaderText();
    }
    
    /**
     * Update the header text based on current view and date.
     */
    public function updateHeaderText(): void
    {
        switch ($this->view) {
            case 'day':
                $this->headerText = $this->gridStartsAt->format('l, F j, Y');
                break;
            case 'week':
                $weekStart = $this->gridStartsAt->clone()->startOfWeek($this->sundayStart ? Carbon::SUNDAY : Carbon::MONDAY);
                $weekEnd = $weekStart->clone()->addDays(6);
                
                if ($weekStart->month === $weekEnd->month) {
                    // Same month
                    $this->headerText = $weekStart->format('F j') . ' - ' . $weekEnd->format('j, Y');
                } elseif ($weekStart->year === $weekEnd->year) {
                    // Different months, same year
                    $this->headerText = $weekStart->format('F j') . ' - ' . $weekEnd->format('F j, Y');
                } else {
                    // Different years
                    $this->headerText = $weekStart->format('F j, Y') . ' - ' . $weekEnd->format('F j, Y');
                }
                break;
            case 'year':
                $this->headerText = $this->gridStartsAt->format('Y');
                break;
            case 'month':
            default:
                $this->headerText = $this->gridStartsAt->format('F Y');
                break;
        }
    }
    
    /**
     * Computed property to get the weekday names.
     */
    public function getWeekdaysProperty(): Collection
    {
        $weekdays = collect();
        $startDay = $this->sundayStart ? Carbon::SUNDAY : Carbon::MONDAY;
        $day = Carbon::now()->startOfWeek($startDay);

        for ($i = 0; $i < 7; $i++) {
            $weekdays->push($day->clone());
            $day->addDay();
        }

        return $weekdays;
    }

    /**
     * Computed property to get the weeks for the calendar grid.
     */
    public function getWeeksProperty(): Collection
    {
        $startDayOfWeek = $this->sundayStart ? Carbon::SUNDAY : Carbon::MONDAY;
        $endDayOfWeek = $this->sundayStart ? Carbon::SATURDAY : Carbon::SUNDAY;

        $start = $this->gridStartsAt->clone()->startOfMonth()->startOfWeek($startDayOfWeek);
        $end = $this->gridStartsAt->clone()->endOfMonth()->endOfWeek($endDayOfWeek);

        return collect(CarbonPeriod::create($start, '1 day', $end)->toArray())
            ->chunk(7)
            ->map(function (Collection $week) {
                return $week->map(function (Carbon $day) {
                    $dayEvents = collect($this->events)->filter(function($event) use ($day) {
                        // Handle single date events
                        if (isset($event['date'])) {
                            return Carbon::parse($event['date'])->isSameDay($day);
                        }

                        // Handle date range events
                        if (isset($event['range']) && is_array($event['range']) && count($event['range']) === 2) {
                            $rangeStart = Carbon::parse($event['range'][0]);
                            $rangeEnd = Carbon::parse($event['range'][1]);
                            return $day->between($rangeStart, $rangeEnd);
                        }
                        
                        return false;
                    })->map(function($event) {
                        // Process event data
                        
                        // Support for title property (renamed from description)
                        if (!isset($event['title']) && isset($event['description'])) {
                            $event['title'] = $event['description'];
                        }
                        
                        // Ensure label exists
                        if (!isset($event['label'])) {
                            $event['label'] = $event['title'] ?? 'Event';
                        }
                        
                        // Ensure id exists
                        if (!isset($event['id'])) {
                            $event['id'] = uniqid('event-');
                        }
                        
                        // Set multiday flag and format dates for display
                        if (isset($event['range']) && is_array($event['range']) && count($event['range']) === 2) {
                            $rangeStart = Carbon::parse($event['range'][0]);
                            $rangeEnd = Carbon::parse($event['range'][1]);
                            
                            // Set is_multiday flag if event spans multiple days
                            $event['is_multiday'] = !$rangeStart->isSameDay($rangeEnd);
                            
                            // Format dates for display
                            $event['start_date'] = $rangeStart->format('j M');
                            $event['end_date'] = $rangeEnd->format('j M');
                        }
                        
                        // Format times for single-day events
                        if (isset($event['time']) && is_array($event['time']) && count($event['time']) === 2) {
                            $event['start_time'] = $event['time'][0];
                            $event['end_time'] = $event['time'][1];
                        }
                        
                        return $event;
                    });
                    
                    return (object)[
                        'date' => $day,
                        'events' => $dayEvents,
                    ];
                });
            });
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
		return view('livewire-ui-components::components.calendar');
	}

    /**
     * Determines appropriate text color (black or white) based on background color
     */
    public function a11yGetContrastColor(string $hexColor = null): string
    {
        if (!$hexColor) {
            return '#ffffff';
        }
        $hexColor = ltrim($hexColor, '#');
        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }
}