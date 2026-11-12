<?php

declare(strict_types=1);
/**
 * Calendar Component
 *
 * The design-system Calendar (#64) as a full Livewire component.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\CalendarModel;
use Illuminate\View\View;
use Livewire\Component;

/**
 * `<livewire:artisanpack-calendar>`: keeps the view and date, renders
 * `<x-artisanpack-calendar>` (CalendarGrid) with them, and answers its
 * `view-change`, `date-change` and `event-click` events. A clicked event
 * opens in the design-system Dialog, whose body is the internal
 * event-modal-content child (or the Livewire component `event-view` names).
 * `create` and `event-click` bubble past it, so a parent can listen too.
 *
 * The 2.x props still work: `sunday-start` sets weekStartsOn (0, or 1 when
 * false), `view="year"` falls back to month, and events keep their 2.x
 * shape (CalendarModel maps it). `months`, `color-scheme`, `custom-color`,
 * `weekend-highlight` and `config` are accepted and ignored. It no longer
 * loads lazily by default; pass `lazy` to defer it.
 *
 * @since 1.0.0
 * @since 3.0.0 Rebuilt on the design-system Calendar.
 */
class Calendar extends Component
{
    /**
     * The calendar's id, for its parts.
     *
     * @since 3.0.0
     */
    public string $calendarId = '';

    /**
     * month, week, day or agenda. Nullable only so an explicit null reaches
     * mount(), which sets it to month; never null after that.
     *
     * @since 1.0.0
     */
    public ?string $view = 'month';

    /**
     * The date the view shows (YYYY-MM-DD); null for today.
     *
     * @since 3.0.0
     */
    public ?string $date = null;

    /**
     * The date marked as today; null for the current date in the time zone.
     *
     * @since 3.0.0
     */
    public ?string $today = null;

    /**
     * The events. Nullable only so an explicit null reaches mount(), which
     * sets it to []; never null after that.
     *
     * @since 1.0.0
     *
     * @var array<int, mixed>|null
     */
    public ?array $events = [];

    /**
     * The first day of the week, 0 (Sunday) to 6; null follows the locale.
     *
     * @since 3.0.0
     */
    public ?int $weekStartsOn = null;

    /**
     * BCP 47 locale; null for the app locale.
     *
     * @since 1.0.0
     */
    public ?string $locale = null;

    /**
     * IANA time zone; null for UTC.
     *
     * @since 3.0.0
     */
    public ?string $timeZone = null;

    /**
     * Events a month cell shows before "+N more".
     *
     * @since 3.0.0
     */
    public int $maxEvents = CalendarModel::MAX_EVENTS;

    /**
     * Shows the New event button.
     *
     * @since 3.0.0
     */
    public bool $creatable = false;

    /**
     * Opens clicked events in the details Dialog.
     *
     * @since 3.0.0
     */
    public bool $details = true;

    /**
     * Built-in text overrides.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $labels = [];

    /**
     * Extra classes per part.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public array $classes = [];

    /**
     * The Livewire component the details Dialog renders.
     *
     * @since 1.0.0
     */
    public string $eventView = 'event-modal-content';

    /**
     * Whether the details Dialog is open.
     *
     * @since 1.0.0
     */
    public bool $eventModal = false;

    /**
     * The event in the details Dialog, as passed in.
     *
     * @since 1.0.0
     *
     * @var array<string, mixed>|null
     */
    public ?array $selectedEvent = null;

    /**
     * The selected event's key, which keys the details child.
     *
     * @since 3.0.0
     */
    public string $selectedKey = '';

    /**
     * Mount the component.
     *
     * @since 1.0.0
     * @since 3.0.0 Takes the design-system Calendar's props.
     *
     * @param  string|null  $view  month, week, day or agenda (2.x year, or null: month).
     * @param  array<int, mixed>|null  $events  The events (null: none).
     * @param  string|null  $date  The date the view shows.
     * @param  string|null  $today  The date marked as today.
     * @param  int|null  $weekStartsOn  The first day of the week.
     * @param  string|null  $locale  BCP 47 locale.
     * @param  string|null  $timeZone  IANA time zone.
     * @param  int  $maxEvents  Events a month cell shows.
     * @param  bool  $creatable  Shows the New event button.
     * @param  bool  $details  Opens clicked events in a Dialog.
     * @param  array<string, string>  $labels  Built-in text overrides.
     * @param  array<string, string>  $classes  Extra classes per part.
     * @param  string  $eventView  The Livewire component for the details.
     * @param  string|null  $id  The calendar's id.
     * @param  bool|null  $sundayStart  2.x: weekStartsOn 0, or 1 when false.
     * @param  mixed  ...$ignored  2.x props that no longer apply.
     */
    public function mount(
        ?string $view = 'month',
        ?array $events = [],
        ?string $date = null,
        ?string $today = null,
        ?int $weekStartsOn = null,
        ?string $locale = null,
        ?string $timeZone = null,
        int $maxEvents = CalendarModel::MAX_EVENTS,
        bool $creatable = false,
        bool $details = true,
        array $labels = [],
        array $classes = [],
        string $eventView = 'event-modal-content',
        ?string $id = null,
        ?bool $sundayStart = null,
        mixed $months = null,
        mixed $colorScheme = null,
        mixed $customColor = null,
        mixed $weekendHighlight = null,
        mixed $config = null,
    ): void {
        $this->calendarId   = null !== $id && '' !== $id ? $id : 'apui-calendar-'.substr(md5($this->getId()), 0, 8);
        $this->view         = CalendarModel::view($view ?? 'month');
        $this->events       = $events ?? [];
        $this->date         = null !== CalendarModel::parseDate($date) ? $date : null;
        $this->today        = null !== CalendarModel::parseDate($today) ? $today : null;
        $this->weekStartsOn = $weekStartsOn ?? (null === $sundayStart ? null : ($sundayStart ? 0 : 1));
        $this->locale       = $locale;
        $this->timeZone     = $timeZone;
        $this->maxEvents    = max(0, $maxEvents);
        $this->creatable    = $creatable;
        $this->details      = $details;
        $this->labels       = $labels;
        $this->classes      = $classes;
        $this->eventView    = $eventView;
    }

    /**
     * Shows a view (the view tabs and "+N more").
     *
     * @since 3.0.0
     */
    public function setView(string $view): void
    {
        $this->view = CalendarModel::view($view);
    }

    /**
     * Shows a date (Today, previous, next and "+N more").
     *
     * @since 3.0.0
     */
    public function setDate(string $date): void
    {
        $parsed = CalendarModel::parseDate($date);
        if (null !== $parsed) {
            $this->date = $parsed->format('Y-m-d');
        }
    }

    /**
     * Opens an event in the details Dialog, by its id or index.
     *
     * @since 3.0.0
     */
    public function openEvent(string $key): void
    {
        if (! $this->details) {
            return;
        }

        foreach (CalendarModel::normalizeEvents($this->events ?? [], CalendarModel::timeZone($this->timeZone)) as $item) {
            if ($item['eventKey'] === $key) {
                $this->selectedEvent = $this->detailsFor($item);
                $this->selectedKey   = $key;
                $this->eventModal    = true;
                // 2.x event views listen for loadEventModal.
                $this->dispatch('loadEventModal', selectedEvent: $this->selectedEvent);

                return;
            }
        }
    }

    /**
     * 2.x: opens an event by its id.
     *
     * @since 1.0.0
     */
    public function loadEventModal(string $eventId): void
    {
        $this->openEvent($eventId);
    }

    /**
     * 2.x: the next period.
     *
     * @since 1.0.0
     */
    public function goToNextPeriod(): void
    {
        $this->setDate($this->model()['nextDate']);
    }

    /**
     * 2.x: the previous period.
     *
     * @since 1.0.0
     */
    public function goToPreviousPeriod(): void
    {
        $this->setDate($this->model()['previousDate']);
    }

    /**
     * 2.x: today.
     *
     * @since 1.0.0
     */
    public function goToToday(): void
    {
        $this->date = null;
    }

    /**
     * The view model the grid renders.
     *
     * @since 3.0.0
     *
     * @return array<string, mixed>
     */
    public function model(): array
    {
        return CalendarModel::build([
            'view'         => $this->view ?? 'month',
            'date'         => $this->date,
            'today'        => $this->today,
            'events'       => $this->events ?? [],
            'weekStartsOn' => $this->weekStartsOn,
            'locale'       => $this->locale,
            'timeZone'     => $this->timeZone,
            'maxEvents'    => $this->maxEvents,
            'labels'       => CalendarModel::labels($this->labels),
        ]);
    }

    /**
     * The Livewire component the details Dialog renders: the internal
     * EventModalContent for the default `event-modal-content`, whatever the
     * package's prefix, else the one `event-view` names.
     *
     * @since 3.0.0
     */
    public function detailsComponent(): string
    {
        return 'event-modal-content' === $this->eventView ? EventModalContent::class : $this->eventView;
    }

    /**
     * Render the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.calendar-livewire');
    }

    /**
     * The event as passed in, with the text the details show: its title,
     * when it is and its category, tint and description.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $item  A normalized event.
     *
     * @return array<string, mixed>
     */
    private function detailsFor(array $item): array
    {
        $model = CalendarModel::build([
            'view'     => 'day',
            'date'     => $item['start']->format('Y-m-d'),
            'today'    => $this->today ?? $item['start']->format('Y-m-d'),
            'events'   => [$item['event']],
            'locale'   => $this->locale,
            'timeZone' => $this->timeZone,
            'labels'   => CalendarModel::labels($this->labels),
        ]);
        $placed = $model['rows'][0][0]['events'][0] ?? [];
        $event  = is_array($item['event']) ? $item['event'] : [];

        return array_merge($event, [
            'title'       => $item['title'],
            'when'        => $placed['when'] ?? '',
            'category'    => $item['category'],
            'color'       => $item['color'],
            'description' => $item['description'],
        ]);
    }
}
