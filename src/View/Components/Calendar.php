<?php

declare(strict_types=1);
/**
 * Calendar Component
 *
 * A Livewire component for displaying a calendar with events.
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPackUI\Accessibility\A11y;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;

/**
 * Renders an interactive calendar component.
 *
 * Uses Livewire's #[Lazy] attribute for deferred loading in Livewire 3+.
 * The component will show a placeholder until it becomes visible in the viewport.
 *
 * @since 1.0.0
 * @since 2.0.0 Added #[Lazy] attribute for deferred loading.
 */
#[Lazy]
class Calendar extends Component
{
    public ?string $id = null;

    public ?int $months = 1;

    public ?string $locale = 'en-US';

    public ?bool $weekendHighlight = false;

    public ?bool $sundayStart = false;

    public ?string $colorScheme = 'primary';

    public ?string $customColor = null;

    public ?string $view = 'month';

    public ?array $config = [];

    public ?array $events = [];

    public string $uuid;

    public string $customColorScript;

    public Carbon $gridStartsAt;

    public string $headerText = '';

    public bool $eventModal = false;

    public ?array $selectedEvent = null;

    /**
     * The Livewire component to use for the event modal content.
     *
     * @since 1.0.0
     */
    public string $eventView;

    /**
     * Mount the component and initialize the state.
     *
     * @since 1.0.0
     *
     * @param  string|null  $id  Optional. The ID for the calendar component. Default null.
     * @param  int|null  $months  Optional. The number of months to display. Default 1.
     * @param  string|null  $locale  Optional. The locale for date formatting. Default 'en-US'.
     * @param  bool|null  $weekendHighlight  Optional. Whether to highlight weekends. Default false.
     * @param  bool|null  $sundayStart  Optional. Whether the week starts on Sunday. Default false.
     * @param  string|null  $colorScheme  Optional. The color scheme. Accepts 'primary', 'secondary', 'accent', 'custom'. Default 'primary'.
     * @param  string|null  $customColor  Optional. A custom hex color if 'custom' scheme is used. Default null.
     * @param  string|null  $view  Optional. The default view ('day', 'week', 'month', 'year'). Default 'month'.
     * @param  array|null  $config  Optional. Configuration options. Default empty array.
     * @param  array|null  $events  Optional. An array of event objects. Default empty array.
     * @param  string  $eventView  Optional. The Livewire component to use for the modal. Defaults to the package's built-in component.
     */
    public function mount(
        ?string $id = null,
        ?int $months = 1,
        ?string $locale = 'en-US',
        ?bool $weekendHighlight = false,
        ?bool $sundayStart = false,
        ?string $colorScheme = 'primary',
        ?string $customColor = null,
        ?string $view = 'month',
        ?array $config = [],
        ?array $events = [],
        string $eventView = 'event-modal-content',
    ): void {
        $this->id               = $id ?? 'calendar-'.uniqid();
        $this->uuid             = uniqid('calendar-');
        $this->months           = $months;
        $this->locale           = $locale;
        $this->weekendHighlight = $weekendHighlight;
        $this->sundayStart      = $sundayStart;
        $this->eventView        = $eventView;

        $validColorSchemes = ['primary', 'secondary', 'accent', 'custom'];
        $this->colorScheme = in_array($colorScheme, $validColorSchemes, true) ? $colorScheme : 'primary';

        if ('custom' === $this->colorScheme && ! empty($customColor)) {
            $this->customColor = $customColor;
        }

        $validViews = ['day', 'week', 'month', 'year'];
        $this->view = in_array($view, $validViews, true) ? $view : 'month';

        $this->config = $config;
        $this->events = $events;

        Carbon::setLocale($this->locale);
        $this->gridStartsAt      = Carbon::today()->startOfMonth();
        $this->customColorScript = $this->generateCustomColorScript();

        $this->updateHeaderText();
    }

    /**
     * Loads event data, opens the modal, and dispatches an event for the child modal component.
     *
     * @since 1.0.0
     *
     * @param  string  $eventId  The ID of the event to load.
     */
    public function loadEventModal(string $eventId): void
    {
        $eventData = collect($this->events)->firstWhere('id', $eventId);

        if ($eventData) {
            $this->selectedEvent = $eventData;
            $this->eventModal    = true;
            $this->dispatch('loadEventModal', selectedEvent: $eventData);
        }
    }

    /**
     * Go to the next period based on the current view.
     *
     * @since 1.0.0
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

        $this->updateHeaderText();
    }

    /**
     * Go to the previous period based on the current view.
     *
     * @since 1.0.0
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

        $this->updateHeaderText();
    }

    /**
     * Go to today's date based on the current view.
     *
     * @since 1.0.0
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

        $this->updateHeaderText();
    }

    /**
     * Fired when the view property is updated.
     *
     * @since 1.0.0
     */
    public function updatedView(): void
    {
        $this->goToToday();
        $this->updateHeaderText();
    }

    /**
     * Update the header text based on current view and date.
     *
     * @since 1.0.0
     */
    public function updateHeaderText(): void
    {
        switch ($this->view) {
            case 'day':
                $this->headerText = $this->gridStartsAt->format('l, F j, Y');
                break;
            case 'week':
                $weekStart = $this->gridStartsAt->clone()->startOfWeek($this->sundayStart ? Carbon::SUNDAY : Carbon::MONDAY);
                $weekEnd   = $weekStart->clone()->addDays(6);

                if ($weekStart->month === $weekEnd->month) {
                    $this->headerText = $weekStart->format('F j').' - '.$weekEnd->format('j, Y');
                } elseif ($weekStart->year === $weekEnd->year) {
                    $this->headerText = $weekStart->format('F j').' - '.$weekEnd->format('F j, Y');
                } else {
                    $this->headerText = $weekStart->format('F j, Y').' - '.$weekEnd->format('F j, Y');
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
     *
     * Uses Livewire's #[Computed] attribute for optimized computed property handling.
     * This is compatible with both Livewire 3 and Livewire 4.
     *
     * @since 1.0.0
     * @since 2.0.0 Updated to use #[Computed] attribute.
     */
    #[Computed]
    public function weekdays(): Collection
    {
        $weekdays = collect();
        $startDay = $this->sundayStart ? Carbon::SUNDAY : Carbon::MONDAY;
        $day      = Carbon::now()->startOfWeek($startDay);

        for ($i = 0; $i < 7; $i++) {
            $weekdays->push($day->clone());
            $day->addDay();
        }

        return $weekdays;
    }

    /**
     * Computed property to get the weeks for the calendar grid.
     *
     * Uses Livewire's #[Computed] attribute for optimized computed property handling.
     * This property depends on dynamic state (gridStartsAt, events) that changes
     * during navigation, so it's recalculated per-request rather than persisted.
     *
     * This is compatible with both Livewire 3 and Livewire 4.
     *
     * @since 1.0.0
     * @since 2.0.0 Updated to use #[Computed] attribute.
     */
    #[Computed]
    public function weeks(): Collection
    {
        $startDayOfWeek = $this->sundayStart ? Carbon::SUNDAY : Carbon::MONDAY;
        $endDayOfWeek   = $this->sundayStart ? Carbon::SATURDAY : Carbon::SUNDAY;

        $start = $this->gridStartsAt->clone()->startOfMonth()->startOfWeek($startDayOfWeek);
        $end   = $this->gridStartsAt->clone()->endOfMonth()->endOfWeek($endDayOfWeek);

        return collect(CarbonPeriod::create($start, '1 day', $end)->toArray())
            ->chunk(7)
            ->map(
                function (Collection $week) {
                    return $week->map(
                        function (Carbon $day) {
                            $dayEvents = collect($this->events)->filter(
                                function ($event) use ($day) {
                                    if (isset($event['date'])) {
                                        return Carbon::parse($event['date'])->isSameDay($day);
                                    }

                                    if (isset($event['range']) && is_array($event['range']) && 2 === count($event['range'])) {
                                        $rangeStart = Carbon::parse($event['range'][0]);
                                        $rangeEnd   = Carbon::parse($event['range'][1]);

                                        return $day->between($rangeStart, $rangeEnd);
                                    }

                                    return false;
                                },
                            )->map(
                                function ($event) {
                                    if (! isset($event['title']) && isset($event['description'])) {
                                        $event['title'] = $event['description'];
                                    }
                                    if (! isset($event['label'])) {
                                        $event['label'] = $event['title'] ?? 'Event';
                                    }
                                    if (! isset($event['id'])) {
                                        $event['id'] = uniqid('event-');
                                    }

                                    if (isset($event['range']) && is_array($event['range']) && 2 === count($event['range'])) {
                                        $rangeStart = Carbon::parse($event['range'][0]);
                                        $rangeEnd   = Carbon::parse($event['range'][1]);

                                        $event['is_multiday'] = ! $rangeStart->isSameDay($rangeEnd);
                                        $event['start_date']  = $rangeStart->format('j M');
                                        $event['end_date']    = $rangeEnd->format('j M');
                                    }

                                    if (isset($event['time']) && is_array($event['time']) && 2 === count($event['time'])) {
                                        $event['start_time'] = $event['time'][0];
                                        $event['end_time']   = $event['time'][1];
                                    }

                                    return $event;
                                },
                            );

                            return (object) [
                                'date'   => $day,
                                'events' => $dayEvents,
                            ];
                        },
                    );
                },
            );
    }

    /**
     * Render the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.calendar');
    }

    /**
     * Return the placeholder view while the component is lazy loading.
     *
     * This method is used by Livewire's #[Lazy] attribute (available in Livewire 3+)
     * to display a loading state before the component hydrates.
     *
     * @since 2.0.0
     *
     * @param  array<string, mixed>  $params  The component parameters.
     *
     * @return View The placeholder view.
     */
    public function placeholder(array $params = []): View
    {
        return view('livewire-ui-components::placeholders.calendar', $params);
    }

    /**
     * Generate the JavaScript for custom colors.
     *
     * @since 1.0.0
     */
    private function generateCustomColorScript(): string
    {
        if ('custom' !== $this->colorScheme || empty($this->customColor)) {
            return '';
        }

        $a11y         = new A11y;
        $bgColor      = $this->customColor;
        $textColor    = $a11y->a11yGetContrastColor($bgColor);
        $bgColorLight = self::hexToRgba($bgColor, 0.1);

        return "
			const style = document.createElement('style');
			style.textContent = `
				[wire\\:key=\"calendar-{$this->uuid}\"] .bg-custom { background-color: {$bgColor}; color: {$textColor}; }
				[wire\\:key=\"calendar-{$this->uuid}\"] .text-custom { color: {$bgColor}; }
				[wire\\:key=\"calendar-{$this->uuid}\"] .hover\\\\:bg-custom:hover { background-color: {$bgColorLight}; }
				[wire\\:key=\"calendar-{$this->uuid}\"] .border-custom { border-color: {$bgColor}; }
				[wire\\:key=\"calendar-{$this->uuid}\"] .bg-custom-light { background-color: {$bgColorLight}; }
			`;
			document.head.appendChild(style);
		";
    }

    /**
     * Convert hex color to rgba with opacity.
     *
     * @since 1.0.0
     *
     * @param  string  $hexColor  The hex color code.
     * @param  float  $opacity  Optional. The opacity value. Default 1.0.
     *
     * @return string The color in rgba format.
     */
    private static function hexToRgba(string $hexColor, float $opacity = 1.0): string
    {
        $hexColor = ltrim($hexColor, '#');
        $r        = hexdec(substr($hexColor, 0, 2));
        $g        = hexdec(substr($hexColor, 2, 2));
        $b        = hexdec(substr($hexColor, 4, 2));

        return "rgba({$r}, {$g}, {$b}, {$opacity})";
    }
}
