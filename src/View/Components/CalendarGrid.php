<?php

/**
 * Calendar Grid Component
 *
 * The design-system Calendar's markup (#64), rendered on the server.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\CalendarModel;
use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;

/**
 * Calendar Grid Component Class
 *
 * `<x-artisanpack-calendar>`: the `.apui-calendar` markup with no state of
 * its own. The days, titles, labels and event order come from
 * CalendarModel, the port of behavior's `calendarModel`, so it matches React
 * and Vue; Alpine (`apuiCalendar`) only adds the arrow keys. Its controls
 * dispatch `view-change`, `date-change`, `event-click` and `create` as
 * browser events. `<livewire:artisanpack-calendar>` (the Calendar Livewire
 * component) renders this and answers them.
 *
 * @since 3.0.0
 */
class CalendarGrid extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The id prefix for HasStablePartIds.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'calendar';

    /**
     * Props left out of the generated id, so it stays put while navigating.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['view', 'date', 'today', 'events', 'labels', 'classes'];

    /**
     * Create a new component instance.
     *
     * @since 3.0.0
     *
     * @param  string  $view  month, week, day or agenda.
     * @param  string|null  $date  The date the view shows (YYYY-MM-DD). Defaults to today.
     * @param  string|null  $today  The date marked as today. Defaults to the current date in the time zone.
     * @param  array<int, mixed>  $events  The events.
     * @param  int|null  $weekStartsOn  The first day of the week, 0 (Sunday) to 6. Follows the locale unless set.
     * @param  string|null  $locale  BCP 47 locale. Defaults to the app locale.
     * @param  string|null  $timeZone  IANA zone for today and zoned times. Defaults to UTC.
     * @param  int  $maxEvents  Events a month cell shows before "+N more".
     * @param  bool  $creatable  Shows the New event button.
     * @param  bool  $details  Events open a details Dialog (aria-haspopup).
     * @param  array<string, string>  $labels  Built-in text overrides.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $view = 'month',
        public ?string $date = null,
        public ?string $today = null,
        public array $events = [],
        public ?int $weekStartsOn = null,
        public ?string $locale = null,
        public ?string $timeZone = null,
        public int $maxEvents = 2,
        public bool $creatable = false,
        public bool $details = true,
        public array $labels = [],
        public array $classes = [],
    ) {}

    /**
     * The view model: CalendarModel's days, events and labels.
     *
     * @since 3.0.0
     *
     * @return array<string, mixed>
     */
    public function model(): array
    {
        return CalendarModel::build([
            'view'         => $this->view,
            'date'         => $this->date,
            'today'        => $this->today,
            'events'       => $this->events,
            'weekStartsOn' => $this->weekStartsOn,
            'locale'       => $this->locale,
            'timeZone'     => $this->timeZone,
            'maxEvents'    => $this->maxEvents,
            'labels'       => CalendarModel::labels($this->labels),
        ]);
    }

    /**
     * A safe href for an event, or null for a button.
     *
     * @since 3.0.0
     */
    public function eventHref(?string $href): ?string
    {
        return SafeUrl::href($href);
    }

    /**
     * A value as a JavaScript literal for an Alpine expression.
     *
     * @since 3.0.0
     */
    public function js(mixed $value): string
    {
        return Js::from($value)->toHtml();
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.calendar');
    }
}
