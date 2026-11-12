<?php

/**
 * Calendar date math for the design-system Calendar.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use IntlCalendar;
use IntlDateFormatter;
use IntlDatePatternGenerator;
use Throwable;

/**
 * The month, week, day and agenda views of a list of events (#64): a port of
 * `calendarModel` in `@artisanpack-ui/behavior` (`src/data/calendar.ts`), so
 * the server-rendered Livewire grid shows the same days, titles, labels and
 * event order as React and Vue. Keep the two in step.
 *
 * Dates are ISO strings (`2026-11-02`). Names come from ICU through the same
 * skeletons the browser's Intl options resolve to (`yMMMM`, `EEE`,
 * `yMMMMEEEEd`, `MMMd`, `yMMMd`). Times show as 24-hour `HH:mm`. Events sort
 * all-day and continued first, then by start time, then as given.
 *
 * @since 3.0.0
 */
final class CalendarModel
{
    /**
     * The views.
     *
     * @since 3.0.0
     */
    public const VIEWS = ['month', 'week', 'day', 'agenda'];

    /**
     * The soft tints an event can take.
     *
     * @since 3.0.0
     */
    public const COLORS = ['primary', 'secondary', 'accent', 'success', 'warning', 'error', 'info', 'neutral'];

    /**
     * Events a month cell shows before "+N more".
     *
     * @since 3.0.0
     */
    public const MAX_EVENTS = 2;

    /**
     * The built-in strings, untranslated.
     *
     * @since 3.0.0
     */
    public const LABELS = [
        'today'    => 'Today',
        'previous' => 'Previous',
        'next'     => 'Next',
        'views'    => 'Calendar view',
        'month'    => 'Month',
        'week'     => 'Week',
        'day'      => 'Day',
        'agenda'   => 'Agenda',
        'allDay'   => 'All day',
        'more'     => '+{count} more',
        'showMore' => 'Show {count} more events on {date}',
        'newEvent' => 'New event',
        'empty'    => 'No events',
        'legend'   => 'Categories',
    ];

    private const ISO = '/^(\d{4})-(\d{2})-(\d{2})(?:[T ](\d{2}):(\d{2})(?::(\d{2})(?:\.\d+)?)?)?(Z|[+-]\d{2}:?\d{2})?$/';

    /**
     * The built-in strings, translated, with the given ones on top.
     *
     * @since 3.0.0
     *
     * @param  array<string, string>  $labels  Overrides.
     *
     * @return array<string, string>
     */
    public static function labels(array $labels = []): array
    {
        $translated = [];
        foreach (self::LABELS as $key => $text) {
            $translated[$key] = (string) __($text);
        }

        return array_merge($translated, array_filter($labels, static fn (mixed $value): bool => is_string($value)));
    }

    /**
     * Builds a view of the events: the title, the previous and next dates,
     * the column headers, the rows of days with their events, the agenda and
     * the legend. Mirrors `calendarModel()`.
     *
     * @since 3.0.0
     *
     * @param  array{view?: string|null, date?: string|null, today?: string|null, events?: array<int, mixed>|null, weekStartsOn?: int|null, locale?: string|null, timeZone?: string|null, maxEvents?: int|null, labels?: array<string, string>}  $options  The options, as the props.
     *
     * @return array{view: string, date: string, today: string, title: string, previousDate: string, nextDate: string, weekStartsOn: int, weekdays: list<array<string, mixed>>, rows: list<list<array<string, mixed>>>, agenda: list<array<string, mixed>>, legend: list<array{category: string, color: string}>, labels: array<string, string>}
     */
    public static function build(array $options = []): array
    {
        $labels    = array_merge(self::LABELS, array_filter($options['labels'] ?? [], static fn (mixed $value): bool => is_string($value)));
        $locale    = self::locale($options['locale'] ?? null);
        $timeZone  = self::timeZone($options['timeZone'] ?? null);
        $view      = self::view($options['view'] ?? null);
        $today     = self::parseDate($options['today'] ?? null) ?? new DateTimeImmutable('today', new DateTimeZone($timeZone));
        $today     = self::day($today);
        $date      = self::parseDate($options['date'] ?? null) ?? $today;
        $weekStart = self::weekStart($options['weekStartsOn'] ?? null, $locale);
        $max       = isset($options['maxEvents']) && is_int($options['maxEvents']) && $options['maxEvents'] >= 0 ? $options['maxEvents'] : self::MAX_EVENTS;
        $events    = self::normalizeEvents($options['events'] ?? [], $timeZone);

        $full         = fn (DateTimeImmutable $day): string => self::format($day, $locale, 'yMMMMEEEEd', 'l, F j, Y');
        $weekdayShort = fn (DateTimeImmutable $day): string => self::format($day, $locale, 'EEE', 'D');
        $weekdayLong  = fn (DateTimeImmutable $day): string => self::format($day, $locale, 'EEEE', 'l');

        $when = function (array $item) use ($full, $labels): string {
            $single = $item['start'] == $item['end'];
            if ($item['allDay']) {
                return $single
                    ? $full($item['start']).', '.$labels['allDay']
                    : $full($item['start']).' – '.$full($item['end']).', '.$labels['allDay'];
            }
            if ($single) {
                return $full($item['start']).', '.$item['startTime'].(null !== $item['endTime'] ? ' – '.$item['endTime'] : '');
            }

            return $full($item['start']).', '.$item['startTime'].' – '.$full($item['end']).(null !== $item['endTime'] ? ', '.$item['endTime'] : '');
        };

        $place = function (array $item, DateTimeImmutable $day) use ($when, $labels): array {
            $continued = $item['start'] < $day;
            $single    = $item['start'] == $item['end'];
            $range     = '';
            if ($item['allDay']) {
                $range = $labels['allDay'];
            } elseif (! $continued) {
                $range = $item['startTime'].(null !== $item['endTime'] && $single ? ' – '.$item['endTime'] : '');
            }
            $whenText = $when($item);

            return [
                'key'         => $item['eventKey'].'-'.$day->format('Y-m-d'),
                'eventKey'    => $item['eventKey'],
                'index'       => $item['index'],
                'title'       => $item['title'],
                'color'       => $item['color'],
                'category'    => $item['category'],
                'description' => $item['description'],
                'href'        => $item['href'],
                'allDay'      => $item['allDay'],
                'start'       => $item['start']->format('Y-m-d'),
                'end'         => $item['end']->format('Y-m-d'),
                'startTime'   => $item['startTime'],
                'endTime'     => $item['endTime'],
                'time'        => $item['allDay'] || $continued ? '' : (string) $item['startTime'],
                'range'       => $range,
                'when'        => $whenText,
                'label'       => implode(', ', array_filter([$item['title'], $item['category'], $whenText], static fn (?string $part): bool => null !== $part && '' !== $part)),
                'context'     => implode(', ', array_filter([$item['category'], $whenText], static fn (?string $part): bool => null !== $part && '' !== $part)),
                'continued'   => $continued,
                'event'       => $item['event'],
            ];
        };

        $eventsOn = function (DateTimeImmutable $day) use ($events, $place): array {
            $on   = array_values(array_filter($events, static fn (array $item): bool => $item['start'] <= $day && $item['end'] >= $day));
            $rank = static fn (array $item): string => $item['allDay'] || $item['start'] < $day ? '' : (string) $item['startTime'];
            usort($on, static function (array $a, array $b) use ($rank): int {
                $ra = $rank($a);
                $rb = $rank($b);
                if ($ra !== $rb) {
                    return $ra < $rb ? -1 : 1;
                }

                return $a['index'] <=> $b['index'];
            });

            return array_map(static fn (array $item): array => $place($item, $day), $on);
        };

        $first = $date->setDate((int) $date->format('Y'), (int) $date->format('n'), 1);
        $last  = $first->modify('last day of this month');

        $makeDay = function (DateTimeImmutable $day, ?int $limit) use ($eventsOn, $full, $weekdayShort, $view, $date, $today, $labels): array {
            $all   = $eventsOn($day);
            $shown = null !== $limit && count($all) > $limit ? array_slice($all, 0, $limit) : $all;
            $more  = count($all) - count($shown);
            $label = $full($day);

            return [
                'date'      => $day->format('Y-m-d'),
                'day'       => (int) $day->format('j'),
                'label'     => $label,
                'weekday'   => $weekdayShort($day),
                'outside'   => 'month' === $view && $day->format('n') !== $date->format('n'),
                'today'     => $day == $today,
                'focus'     => $day == $date,
                'events'    => $shown,
                'more'      => $more,
                'moreText'  => $more > 0 ? self::fill($labels['more'], ['count' => $more]) : '',
                'moreLabel' => $more > 0 ? self::fill($labels['showMore'], ['count' => $more, 'date' => $label]) : '',
            ];
        };

        $header = static fn (DateTimeImmutable $day, bool $dated) => [
            'short' => $weekdayShort($day),
            'long'  => $weekdayLong($day),
            'date'  => $dated ? $day->format('Y-m-d') : null,
            'day'   => $dated ? (int) $day->format('j') : null,
            'today' => $dated && $day == $today,
        ];

        $rows     = [];
        $weekdays = [];
        $agenda   = [];

        if ('week' === $view) {
            $start = self::startOfWeek($date, $weekStart);
            $end   = $start->modify('+6 days');
            $title = $start->format('Y') === $end->format('Y')
                ? self::format($start, $locale, 'MMMd', 'M j').' – '.self::format($end, $locale, 'yMMMd', 'M j, Y')
                : self::format($start, $locale, 'yMMMd', 'M j, Y').' – '.self::format($end, $locale, 'yMMMd', 'M j, Y');
            $previous = $date->modify('-7 days');
            $next     = $date->modify('+7 days');
            $row      = [];
            for ($i = 0; $i < 7; $i++) {
                $day        = $start->modify('+'.$i.' days');
                $row[]      = $makeDay($day, null);
                $weekdays[] = $header($day, true);
            }
            $rows[] = $row;
        } elseif ('day' === $view) {
            $title      = $full($date);
            $previous   = $date->modify('-1 day');
            $next       = $date->modify('+1 day');
            $rows[]     = [$makeDay($date, null)];
            $weekdays[] = $header($date, true);
        } else {
            $title    = self::format($first, $locale, 'yMMMM', 'F Y');
            $previous = self::addMonths($date, -1);
            $next     = self::addMonths($date, 1);
            if ('month' === $view) {
                $start = self::startOfWeek($first, $weekStart);
                $end   = self::startOfWeek($last, $weekStart)->modify('+6 days');
                for ($i = 0; $i < 7; $i++) {
                    $weekdays[] = $header($start->modify('+'.$i.' days'), false);
                }
                $row = [];
                for ($day = $start; $day <= $end; $day = $day->modify('+1 day')) {
                    $row[] = $makeDay($day, $max);
                    if (7 === count($row)) {
                        $rows[] = $row;
                        $row    = [];
                    }
                }
            } else {
                for ($day = $first; $day <= $last; $day = $day->modify('+1 day')) {
                    $dayEvents = $eventsOn($day);
                    if ([] === $dayEvents) {
                        continue;
                    }
                    $agenda[] = [
                        'date'   => $day->format('Y-m-d'),
                        'label'  => $full($day),
                        'today'  => $day == $today,
                        'events' => $dayEvents,
                    ];
                }
            }
        }

        $legend = [];
        $seen   = [];
        foreach ($events as $item) {
            if (null !== $item['category'] && ! isset($seen[$item['category']])) {
                $seen[$item['category']] = true;
                $legend[]                = ['category' => $item['category'], 'color' => $item['color']];
            }
        }

        return [
            'view'         => $view,
            'date'         => $date->format('Y-m-d'),
            'today'        => $today->format('Y-m-d'),
            'title'        => $title,
            'previousDate' => $previous->format('Y-m-d'),
            'nextDate'     => $next->format('Y-m-d'),
            'weekStartsOn' => $weekStart,
            'weekdays'     => $weekdays,
            'rows'         => $rows,
            'agenda'       => $agenda,
            'legend'       => $legend,
            'labels'       => $labels,
        ];
    }

    /**
     * The view, defaulting to month (2.x `year` included).
     *
     * @since 3.0.0
     */
    public static function view(mixed $view): string
    {
        return in_array($view, self::VIEWS, true) ? $view : 'month';
    }

    /**
     * The color, defaulting to primary.
     *
     * @since 3.0.0
     */
    public static function color(mixed $color): string
    {
        return in_array($color, self::COLORS, true) ? $color : 'primary';
    }

    /**
     * A valid IANA zone, else UTC.
     *
     * @since 3.0.0
     */
    public static function timeZone(?string $timeZone): string
    {
        if (null === $timeZone || '' === $timeZone) {
            return 'UTC';
        }

        try {
            new DateTimeZone($timeZone);

            return $timeZone;
        } catch (Throwable) {
            return 'UTC';
        }
    }

    /**
     * The locale as BCP 47: the given one, else the app locale (`en_US` → `en-US`).
     *
     * @since 3.0.0
     */
    public static function locale(?string $locale): string
    {
        $locale = null !== $locale && '' !== $locale ? $locale : (string) app()->getLocale();

        return str_replace('_', '-', '' !== $locale ? $locale : 'en-US');
    }

    /**
     * The first day of the week: `weekStartsOn` when 0–6, else the locale's.
     *
     * @since 3.0.0
     */
    public static function weekStart(mixed $weekStartsOn, string $locale): int
    {
        if (is_int($weekStartsOn) && $weekStartsOn >= 0 && $weekStartsOn <= 6) {
            return $weekStartsOn;
        }

        if (! class_exists(IntlCalendar::class)) {
            return 0;
        }

        $calendar = IntlCalendar::createInstance(null, str_replace('-', '_', $locale));

        return $calendar instanceof IntlCalendar ? ($calendar->getFirstDayOfWeek() + 6) % 7 : 0;
    }

    /**
     * Parses `YYYY-MM-DD` (a time or zone is ignored).
     *
     * @since 3.0.0
     */
    public static function parseDate(mixed $value): ?DateTimeImmutable
    {
        if (! is_string($value) || 1 !== preg_match(self::ISO, trim($value), $m)) {
            return null;
        }

        return self::makeDate((int) $m[1], (int) $m[2], (int) $m[3]);
    }

    /**
     * Parses an ISO date or date-time, or a DateTime; a `Z`, an offset or a
     * DateTime's zone is converted to the time zone.
     *
     * @since 3.0.0
     *
     * @return array{date: DateTimeImmutable, time: string|null}|null
     */
    public static function parseMoment(mixed $value, string $timeZone): ?array
    {
        if ($value instanceof DateTimeInterface) {
            $local = DateTimeImmutable::createFromInterface($value)->setTimezone(new DateTimeZone($timeZone));

            return ['date' => self::day($local), 'time' => $local->format('H:i')];
        }

        if (! is_string($value) || 1 !== preg_match(self::ISO, trim($value), $m)) {
            return null;
        }

        $date = self::makeDate((int) $m[1], (int) $m[2], (int) $m[3]);
        if (null === $date) {
            return null;
        }

        if (! isset($m[4]) || '' === $m[4]) {
            return ['date' => $date, 'time' => null];
        }

        $hour   = (int) $m[4];
        $minute = (int) $m[5];
        if ($hour > 23 || $minute > 59) {
            return null;
        }

        if (! isset($m[7]) || '' === $m[7]) {
            return ['date' => $date, 'time' => sprintf('%02d:%02d', $hour, $minute)];
        }

        $zone    = 'Z' === $m[7] ? '+00:00' : $m[7];
        $instant = new DateTimeImmutable(sprintf('%04d-%02d-%02dT%02d:%02d:%02d%s', $m[1], $m[2], $m[3], $hour, $minute, (int) ($m[6] ?? 0), $zone));
        $local   = $instant->setTimezone(new DateTimeZone($timeZone));

        return ['date' => self::day($local), 'time' => $local->format('H:i')];
    }

    /**
     * Fills `{name}` placeholders.
     *
     * @since 3.0.0
     *
     * @param  array<string, int|string>  $values  The values.
     */
    public static function fill(string $template, array $values): string
    {
        return (string) preg_replace_callback(
            '/\{(\w+)\}/',
            static fn (array $match): string => array_key_exists($match[1], $values) ? (string) $values[$match[1]] : $match[0],
            $template,
        );
    }

    /**
     * The events that parse, with their dates in the time zone. The 2.x
     * shape (date, range, time, label, colorScheme) maps onto the new one.
     *
     * @since 3.0.0
     *
     * @param  array<int|string, mixed>  $events  The events.
     *
     * @return list<array<string, mixed>>
     */
    public static function normalizeEvents(array $events, string $timeZone): array
    {
        $out   = [];
        $index = -1;
        foreach ($events as $event) {
            $index++;
            if (is_object($event) && method_exists($event, 'toArray')) {
                $event = $event->toArray();
            }
            if (! is_array($event)) {
                continue;
            }

            [$startValue, $endValue] = self::legacyRange($event);
            $start                   = self::parseMoment($startValue, $timeZone);
            if (null === $start) {
                continue;
            }

            $parsedEnd = null === $endValue ? null : self::parseMoment($endValue, $timeZone);
            $allDay    = true === ($event['allDay'] ?? $event['all_day'] ?? false) || null === $start['time'];
            $end       = $parsedEnd ?? ['date' => $start['date'], 'time' => null];
            $endDate   = $end['date'];
            if (! $allDay && '00:00' === $end['time'] && $endDate > $start['date']) {
                $endDate = $endDate->modify('-1 day');
            }
            if ($endDate < $start['date']) {
                $endDate = $start['date'];
            }

            $id    = $event['id'] ?? null;
            $title = $event['title'] ?? $event['label'] ?? '';
            $out[] = [
                'eventKey'    => is_string($id) || is_int($id) ? (string) $id : (string) $index,
                'index'       => $index,
                'title'       => is_scalar($title) ? (string) $title : '',
                'color'       => self::color($event['color'] ?? $event['colorScheme'] ?? null),
                'category'    => self::text($event['category'] ?? null),
                'description' => self::text($event['description'] ?? null),
                'href'        => self::text($event['href'] ?? null),
                'allDay'      => $allDay,
                'start'       => $start['date'],
                'end'         => $endDate,
                'startTime'   => $allDay ? null : $start['time'],
                'endTime'     => $allDay || null === $parsedEnd ? null : $parsedEnd['time'],
                'event'       => $event,
            ];
        }

        return $out;
    }

    /**
     * An event's start and end, from `start`/`end` or the 2.x `date`,
     * `endDate`, `range` and `time`.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $event  The event.
     *
     * @return array{0: mixed, 1: mixed}
     */
    private static function legacyRange(array $event): array
    {
        $start = $event['start'] ?? $event['date'] ?? null;
        $end   = $event['end'] ?? $event['endDate'] ?? null;
        if (null === $start && isset($event['range']) && is_array($event['range']) && 2 === count($event['range'])) {
            [$start, $end] = array_values($event['range']);
        }

        $time = $event['time'] ?? null;
        if (is_string($start) && 1 === preg_match('/^\d{4}-\d{2}-\d{2}$/', $start) && is_array($time) && [] !== $time) {
            $times = array_values($time);
            $day   = is_string($end) && 1 === preg_match('/^\d{4}-\d{2}-\d{2}$/', $end) ? $end : $start;
            $start = $start.'T'.$times[0];
            $end   = isset($times[1]) ? $day.'T'.$times[1] : null;
        }

        return [$start, $end];
    }

    /**
     * A non-empty string, else null.
     *
     * @since 3.0.0
     */
    private static function text(mixed $value): ?string
    {
        return is_string($value) && '' !== trim($value) ? $value : null;
    }

    /**
     * A valid date at midnight UTC, else null.
     *
     * @since 3.0.0
     */
    private static function makeDate(int $year, int $month, int $day): ?DateTimeImmutable
    {
        if (! checkdate($month, $day, $year)) {
            return null;
        }

        return new DateTimeImmutable(sprintf('%04d-%02d-%02d', $year, $month, $day), new DateTimeZone('UTC'));
    }

    /**
     * The calendar day of a date-time, at midnight UTC.
     *
     * @since 3.0.0
     */
    private static function day(DateTimeImmutable $date): DateTimeImmutable
    {
        return new DateTimeImmutable($date->format('Y-m-d'), new DateTimeZone('UTC'));
    }

    /**
     * Moves by months, keeping the day of the month where it exists (Jan 31 → Feb 28).
     *
     * @since 3.0.0
     */
    private static function addMonths(DateTimeImmutable $date, int $months): DateTimeImmutable
    {
        $total = (int) $date->format('Y') * 12 + (int) $date->format('n') - 1 + $months;
        $year  = intdiv($total, 12);
        $month = $total % 12 + 1;
        $days  = (int) (new DateTimeImmutable(sprintf('%04d-%02d-01', $year, $month), new DateTimeZone('UTC')))->format('t');
        $day   = min((int) $date->format('j'), $days);

        return new DateTimeImmutable(sprintf('%04d-%02d-%02d', $year, $month, $day), new DateTimeZone('UTC'));
    }

    /**
     * The start of the week the date is in.
     *
     * @since 3.0.0
     */
    private static function startOfWeek(DateTimeImmutable $date, int $weekStart): DateTimeImmutable
    {
        $back = ((int) $date->format('w') - $weekStart + 7) % 7;

        return $date->modify('-'.$back.' days');
    }

    /**
     * A date formatted in the locale through ICU, from a skeleton, else in
     * English with `date()`.
     *
     * @since 3.0.0
     */
    private static function format(DateTimeImmutable $date, string $locale, string $skeleton, string $fallback): string
    {
        if (! class_exists(IntlDateFormatter::class) || ! class_exists(IntlDatePatternGenerator::class)) {
            return $date->format($fallback);
        }

        try {
            $icuLocale = str_replace('-', '_', $locale);
            $pattern   = (new IntlDatePatternGenerator($icuLocale))->getBestPattern($skeleton);
            $formatter = new IntlDateFormatter($icuLocale, IntlDateFormatter::NONE, IntlDateFormatter::NONE, 'UTC', null, false === $pattern ? null : $pattern);
            $text      = $formatter->format(new DateTimeImmutable($date->format('Y-m-d').' 12:00:00', new DateTimeZone('UTC')));
        } catch (Throwable) {
            return $date->format($fallback);
        }

        return is_string($text) ? $text : $date->format($fallback);
    }
}
