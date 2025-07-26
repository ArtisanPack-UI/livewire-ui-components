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
    public ?string $colorScheme = 'primary';
    public ?string $customColor = null;
    public ?string $view = 'month'; // month, week
    public ?array $events = [];
    public ?bool $sundayStart = false;
    public ?string $locale = 'en-EN';

    // Internal State
    public Carbon $gridStartsAt;

    /**
     * Mount the component and initialize the state.
     */
    public function mount(
        ?string $id = null,
        ?string $colorScheme = 'primary',
        ?string $customColor = null,
        ?string $view = 'month',
        ?array $events = [],
        ?bool $sundayStart = false,
        ?string $locale = 'en-EN'
    ) {
        $this->id = $id ?? 'calendar-' . uniqid();
        $this->colorScheme = $colorScheme;
        $this->customColor = $customColor;
        $this->view = $view;
        $this->events = $events;
        $this->sundayStart = $sundayStart;
        $this->locale = $locale;
        
        Carbon::setLocale($this->locale);
        $this->gridStartsAt = Carbon::today()->startOfMonth();
    }

    /**
     * Go to the next month.
     */
    public function goToNextMonth(): void
    {
        $this->gridStartsAt->addMonthNoOverflow();
    }

    /**
     * Go to the previous month.
     */
    public function goToPreviousMonth(): void
    {
        $this->gridStartsAt->subMonthNoOverflow();
    }

    /**
     * Go to the current month.
     */
    public function goToCurrentMonth(): void
    {
        $this->gridStartsAt = Carbon::today()->startOfMonth();
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
                    // Here you can attach events to the day
                    $day->events = collect($this->events)->filter(function($event) use ($day) {
                        return Carbon::parse($event['date'])->isSameDay($day);
                    });
                    return $day;
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