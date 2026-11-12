<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\CalendarModel;

/*
 * The PHP port of behavior's calendarModel (#64). These are the same
 * expectations as packages/behavior/test/calendar-data.test.ts in the design
 * system, so the server-rendered grid matches React and Vue.
 */

function calendarFixtureEvents(): array
{
    return [
        ['id' => 1, 'start' => '2026-11-02T09:00', 'end' => '2026-11-02T10:00', 'title' => 'Team meeting', 'color' => 'error', 'category' => 'Marketing'],
        ['id' => 2, 'start' => '2026-11-03T10:00', 'end' => '2026-11-03T12:00', 'title' => 'Marketing course', 'color' => 'info', 'category' => 'Learning'],
        ['id' => 3, 'start' => '2026-11-03T13:00', 'title' => 'Lunch · Edgehill Grill', 'color' => 'success', 'category' => 'Personal'],
        ['id' => 4, 'start' => '2026-11-05', 'title' => 'Anna’s birthday', 'color' => 'accent', 'category' => 'People'],
        ['id' => 5, 'start' => '2026-11-09T11:00', 'title' => 'Upgrade Slack plan', 'color' => 'secondary', 'category' => 'Admin'],
        ['id' => 6, 'start' => '2026-11-12T11:00', 'title' => 'Viewing · Lakeview', 'color' => 'primary', 'category' => 'Viewings'],
        ['id' => 7, 'start' => '2026-11-12T16:00', 'title' => 'Hubstaff broadcast', 'color' => 'info', 'category' => 'Learning'],
        ['id' => 8, 'start' => '2026-11-12T18:00', 'title' => 'Board dinner', 'color' => 'success', 'category' => 'Personal'],
        ['id' => 9, 'start' => '2026-11-13T09:30', 'title' => 'Meet a new intern', 'color' => 'accent', 'category' => 'People'],
        ['id' => 10, 'start' => '2026-11-17', 'title' => 'Facebook campaign', 'color' => 'error', 'category' => 'Marketing'],
        ['id' => 11, 'start' => '2026-11-19T10:00', 'end' => '2026-11-19T11:30', 'title' => 'Design review', 'color' => 'primary', 'category' => 'Viewings'],
        ['id' => 12, 'start' => '2026-11-24', 'title' => 'Open house · Grove', 'color' => 'success', 'category' => 'Personal'],
        ['id' => 13, 'start' => '2026-11-26', 'title' => 'Thanksgiving', 'color' => 'neutral', 'category' => 'Holidays'],
    ];
}

function calendarBase(array $overrides = []): array
{
    return array_merge(['date' => '2026-11-07', 'today' => '2026-11-07', 'weekStartsOn' => 1, 'locale' => 'en-US', 'events' => calendarFixtureEvents()], $overrides);
}

function calendarDay(array $model, string $date): array
{
    return collect($model['rows'])->flatten(1)->firstWhere('date', $date);
}

describe('month', function (): void {
    it('lays out the spec month: Oct 26 to Dec 6, Monday first', function (): void {
        $model = CalendarModel::build(calendarBase());

        expect($model['title'])->toBe('November 2026')
            ->and(array_column($model['weekdays'], 'short'))->toBe(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])
            ->and($model['rows'])->toHaveCount(6)
            ->and($model['rows'][0][0]['date'])->toBe('2026-10-26')
            ->and($model['rows'][0][0]['outside'])->toBeTrue()
            ->and($model['rows'][5][6]['date'])->toBe('2026-12-06')
            ->and($model['previousDate'])->toBe('2026-10-07')
            ->and($model['nextDate'])->toBe('2026-12-07');
    });

    it('marks today and the tab stop', function (): void {
        $days = collect(CalendarModel::build(calendarBase())['rows'])->flatten(1);

        expect($days->where('today', true)->pluck('date')->all())->toBe(['2026-11-07'])
            ->and($days->where('focus', true)->pluck('date')->all())->toBe(['2026-11-07'])
            ->and($days->firstWhere('date', '2026-11-07')['label'])->toBe('Saturday, November 7, 2026');
    });

    it('orders events by time and folds the rest into +N more', function (): void {
        $day = calendarDay(CalendarModel::build(calendarBase()), '2026-11-12');

        expect(array_column($day['events'], 'title'))->toBe(['Viewing · Lakeview', 'Hubstaff broadcast'])
            ->and(array_column($day['events'], 'time'))->toBe(['11:00', '16:00'])
            ->and($day['more'])->toBe(1)
            ->and($day['moreText'])->toBe('+1 more')
            ->and($day['moreLabel'])->toBe('Show 1 more events on Thursday, November 12, 2026');
    });

    it('labels every event with its full time and title', function (): void {
        $model = CalendarModel::build(calendarBase());
        $first = calendarDay($model, '2026-11-02')['events'][0];
        $all   = calendarDay($model, '2026-11-24')['events'][0];

        expect($first['label'])->toBe('Team meeting, Marketing, Monday, November 2, 2026, 09:00 – 10:00')
            ->and($first['range'])->toBe('09:00 – 10:00')
            ->and($first['context'])->toBe('Marketing, Monday, November 2, 2026, 09:00 – 10:00')
            ->and($all['time'])->toBe('')
            ->and($all['range'])->toBe('All day')
            ->and($all['label'])->toBe('Open house · Grove, Personal, Tuesday, November 24, 2026, All day');
    });

    it('keeps the categories in the order they appear', function (): void {
        $legend = array_map(static fn (array $c): string => $c['category'].':'.$c['color'], CalendarModel::build(calendarBase())['legend']);

        expect($legend)->toBe(['Marketing:error', 'Learning:info', 'Personal:success', 'People:accent', 'Admin:secondary', 'Viewings:primary', 'Holidays:neutral']);
    });

    it('follows the locale when weekStartsOn is unset', function (): void {
        expect(CalendarModel::weekStart(null, 'en-US'))->toBe(0)
            ->and(CalendarModel::weekStart(null, 'en-GB'))->toBe(1)
            ->and(CalendarModel::weekStart(9, 'en-GB'))->toBe(1);

        $sunday = CalendarModel::build(calendarBase(['weekStartsOn' => null]));

        expect($sunday['rows'][0][0]['date'])->toBe('2026-11-01')
            ->and($sunday['rows'])->toHaveCount(5);
    });

    it('constrains the day when moving a month', function (): void {
        $model = CalendarModel::build(calendarBase(['date' => '2027-01-31']));

        expect($model['nextDate'])->toBe('2027-02-28')
            ->and($model['previousDate'])->toBe('2026-12-31');
    });
});

describe('week, day and agenda', function (): void {
    it('shows one row of seven with dated headers', function (): void {
        $model = CalendarModel::build(calendarBase(['view' => 'week', 'date' => '2026-11-12']));

        expect($model['title'])->toBe('Nov 9 – Nov 15, 2026')
            ->and($model['rows'])->toHaveCount(1)
            ->and(array_column($model['rows'][0], 'day'))->toBe([9, 10, 11, 12, 13, 14, 15])
            ->and($model['weekdays'][3])->toMatchArray(['short' => 'Thu', 'day' => 12, 'date' => '2026-11-12'])
            ->and(array_column($model['rows'][0][3]['events'], 'title'))->toBe(['Viewing · Lakeview', 'Hubstaff broadcast', 'Board dinner'])
            ->and($model['previousDate'])->toBe('2026-11-05')
            ->and(CalendarModel::build(calendarBase(['view' => 'week', 'date' => '2026-12-30']))['title'])->toBe('Dec 28, 2026 – Jan 3, 2027');
    });

    it('shows one day', function (): void {
        $model = CalendarModel::build(calendarBase(['view' => 'day', 'date' => '2026-11-03']));

        expect($model['title'])->toBe('Tuesday, November 3, 2026')
            ->and(array_column($model['rows'][0][0]['events'], 'range'))->toBe(['10:00 – 12:00', '13:00'])
            ->and($model['nextDate'])->toBe('2026-11-04');
    });

    it('lists the month by day in the agenda', function (): void {
        $model = CalendarModel::build(calendarBase(['view' => 'agenda']));

        expect($model['title'])->toBe('November 2026')
            ->and($model['rows'])->toBe([])
            ->and(array_column($model['agenda'], 'date'))->toBe(['2026-11-02', '2026-11-03', '2026-11-05', '2026-11-09', '2026-11-12', '2026-11-13', '2026-11-17', '2026-11-19', '2026-11-24', '2026-11-26'])
            ->and($model['agenda'][0]['label'])->toBe('Monday, November 2, 2026')
            ->and(CalendarModel::build(calendarBase(['view' => 'agenda', 'date' => '2027-02-01']))['agenda'])->toBe([]);
    });
});

describe('events', function (): void {
    it('spreads multi-day events, all-day and continued ones first', function (): void {
        $model = CalendarModel::build(calendarBase([
            'view'   => 'week',
            'date'   => '2026-11-02',
            'events' => [
                ['start' => '2026-11-03T08:00', 'title' => 'Early'],
                ['id'    => 'trip', 'start' => '2026-11-02T15:00', 'end' => '2026-11-04T09:00', 'title' => 'Trip'],
                ['start' => '2026-11-03', 'end' => '2026-11-04', 'title' => 'Offsite'],
                ['start' => '2026-11-04T09:00', 'end' => '2026-11-05T00:00', 'title' => 'Late'],
            ],
        ]));
        $tuesday = $model['rows'][0][1];

        expect(array_map(static fn (array $e): string => $e['title'].'|'.$e['time'].'|'.$e['range'], $tuesday['events']))->toBe(['Trip||', 'Offsite||All day', 'Early|08:00|08:00'])
            ->and($tuesday['events'][0]['key'])->toBe('trip-2026-11-03')
            ->and($tuesday['events'][0]['when'])->toBe('Monday, November 2, 2026, 15:00 – Wednesday, November 4, 2026, 09:00')
            ->and(array_column($model['rows'][0][2]['events'], 'title'))->toBe(['Trip', 'Offsite', 'Late'])
            ->and($model['rows'][0][3]['events'])->toBe([]);
    });

    it('converts zoned times, defaults colors and skips what does not parse', function (): void {
        expect(CalendarModel::parseMoment('2026-11-02T23:30:00Z', 'Europe/Madrid')['time'])->toBe('00:30')
            ->and(CalendarModel::parseMoment('2026-11-02T09:00-05:00', 'UTC')['time'])->toBe('14:00')
            ->and(CalendarModel::parseMoment('2026-02-30', 'UTC'))->toBeNull();

        $model = CalendarModel::build(calendarBase([
            'view'     => 'day',
            'date'     => '2026-11-03',
            'timeZone' => 'Europe/Madrid',
            'events'   => [
                ['start' => '2026-11-02T23:30:00Z', 'title' => 'Zoned', 'color' => 'teal'],
                ['start' => 'soon', 'title' => 'Bad'],
                ['date'  => '2026-11-03', 'title' => '2.x'],
            ],
        ]));

        expect(array_map(static fn (array $e): string => $e['title'].'|'.$e['color'].'|'.$e['time'], $model['rows'][0][0]['events']))->toBe(['2.x|primary|', 'Zoned|primary|00:30']);
    });

    it('maps the 2.x range, time, label and colorScheme', function (): void {
        $model = CalendarModel::build(calendarBase([
            'view'   => 'day',
            'date'   => '2026-11-03',
            'events' => [
                ['date' => '2026-11-03', 'time' => ['09:00', '10:30'], 'label' => 'Standup', 'colorScheme' => 'secondary'],
                ['range' => ['2026-11-02', '2026-11-04'], 'title' => 'Conference'],
            ],
        ]));
        $events = $model['rows'][0][0]['events'];

        expect(array_column($events, 'title'))->toBe(['Conference', 'Standup'])
            ->and($events[1]['range'])->toBe('09:00 – 10:30')
            ->and($events[1]['color'])->toBe('secondary')
            ->and($events[0]['allDay'])->toBeTrue();
    });

    it('takes DateTime starts, converted to the time zone', function (): void {
        $moment = CalendarModel::parseMoment(new DateTimeImmutable('2026-11-02 23:30', new DateTimeZone('UTC')), 'Europe/Madrid');

        expect($moment['date']->format('Y-m-d'))->toBe('2026-11-03')
            ->and($moment['time'])->toBe('00:30');
    });

    it('fills label templates and defaults to month and today', function (): void {
        expect(CalendarModel::fill('+{count} more', ['count' => 3]))->toBe('+3 more')
            ->and(CalendarModel::fill('{missing}', []))->toBe('{missing}');

        $model = CalendarModel::build(['today' => '2026-11-07', 'view' => 'year', 'labels' => ['allDay' => 'Todo el día']]);

        expect($model['view'])->toBe('month')
            ->and($model['date'])->toBe('2026-11-07')
            ->and($model['labels']['allDay'])->toBe('Todo el día')
            ->and($model['labels']['today'])->toBe('Today');
    });
});
