<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Calendar;
use ArtisanPack\LivewireUiComponents\View\Components\EventModalContent;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

/*
 * Calendar (#64): <x-artisanpack-calendar> renders the design-system markup
 * from CalendarModel (tested in tests/Unit/Support/CalendarModelTest.php),
 * and <livewire:artisanpack-calendar> keeps the view and date and opens
 * events in a Dialog with its internal event-modal-content child. The
 * manifest parity check is in tests/Feature/DesignSystemParityTest.php.
 */

function calendarEvents(): array
{
    return [
        ['id' => 1, 'start' => '2026-11-02T09:00', 'end' => '2026-11-02T10:00', 'title' => 'Team meeting', 'color' => 'error', 'category' => 'Marketing', 'description' => 'Weekly sync.'],
        ['id' => 6, 'start' => '2026-11-12T11:00', 'title' => 'Viewing · Lakeview', 'color' => 'primary'],
        ['id' => 7, 'start' => '2026-11-12T16:00', 'title' => 'Hubstaff broadcast', 'color' => 'info'],
        ['id' => 8, 'start' => '2026-11-12T18:00', 'title' => 'Board dinner', 'color' => 'success', 'href' => 'https://example.com/dinner'],
    ];
}

function calendarDom(string $props = '', array $data = []): DOMXPath
{
    $data += ['events' => calendarEvents()];

    return designSystemDom(Blade::render('<x-artisanpack-calendar id="cal" :events="$events" date="2026-11-07" today="2026-11-07" :week-starts-on="1" locale="en-US" '.$props.' />', $data));
}

test('renders the month grid with a tab stop, today and labeled days', function (): void {
    $dom  = calendarDom();
    $root = $dom->query('//*[contains(@class, "apui-calendar")]')->item(0);
    $grid = $dom->query('//*[@data-part="grid"]')->item(0);

    expect($root->getAttribute('data-view'))->toBe('month')
        ->and($root->getAttribute('x-data'))->toBe('apuiCalendar()')
        ->and($grid->getAttribute('role'))->toBe('grid')
        ->and($grid->getAttribute('aria-labelledby'))->toBe('cal-title')
        ->and($dom->query('//*[@id="cal-title"]')->item(0)->textContent)->toBe('November 2026')
        ->and($dom->query('//*[@role="columnheader"]')->length)->toBe(7)
        ->and($dom->query('//*[@role="gridcell"]')->length)->toBe(42)
        ->and($dom->query('//*[@role="gridcell"][@tabindex="0"]')->item(0)->getAttribute('data-date'))->toBe('2026-11-07');

    $today = $dom->query('//*[@aria-current="date"]')->item(0);
    expect(trim($dom->query('./span[@class="apui-sr-only"]', $today)->item(0)->textContent))->toBe('Saturday, November 7, 2026')
        ->and($today->hasAttribute('aria-label'))->toBeFalse()
        ->and($today->hasAttribute('data-today'))->toBeTrue()
        ->and($dom->query('//*[@data-date="2026-10-26"][@data-outside]')->length)->toBe(1);
});

test('renders events as named buttons and links, and folds the rest into +N more', function (): void {
    $dom   = calendarDom();
    $event = $dom->query('//*[@data-date="2026-11-02"]//*[@data-part="event"]')->item(0);

    expect($event->tagName)->toBe('button')
        ->and($event->getAttribute('data-color'))->toBe('error')
        ->and($event->getAttribute('aria-haspopup'))->toBe('dialog')
        ->and($event->hasAttribute('aria-label'))->toBeFalse()
        ->and($event->textContent)->toBe('09:00Team meeting, Marketing, Monday, November 2, 2026, 09:00 – 10:00')
        ->and($event->getAttribute('x-on:click'))->toContain("\$dispatch('event-click'")
        ->and($dom->query('.//*[@data-part="event-time"]', $event)->item(0)->textContent)->toBe('09:00');

    $more = $dom->query('//*[@data-date="2026-11-12"]//*[@data-part="more"]')->item(0);
    expect($dom->query('//*[@data-date="2026-11-12"]//*[@data-part="event"]')->length)->toBe(2)
        ->and($more->textContent)->toBe('+1 more')
        ->and($more->getAttribute('aria-label'))->toBe('Show 1 more events on Thursday, November 12, 2026');

    $link = calendarDom(':max-events="3"')->query('//*[@data-date="2026-11-12"]//a[@data-part="event"]')->item(0);
    expect($link->getAttribute('href'))->toBe('https://example.com/dinner')
        ->and($link->hasAttribute('aria-haspopup'))->toBeFalse();
});

test('renders the toolbar from design-system Buttons and Tabs', function (): void {
    $dom = calendarDom('creatable');

    expect($dom->query('//*[@data-part="today-trigger"][contains(@class, "apui-button")]')->item(0)->getAttribute('x-on:click'))->toContain("'2026-11-07'")
        ->and($dom->query('//*[@data-part="previous-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Previous')
        ->and($dom->query('//*[@data-part="next-trigger"]')->item(0)->getAttribute('x-on:click'))->toContain("'2026-12-07'")
        ->and($dom->query('//*[@data-part="create-trigger"]')->item(0)->textContent)->toContain('New event');

    $tabs = $dom->query('//*[@role="tab"]');
    expect($tabs->length)->toBe(4)
        ->and($tabs->item(0)->getAttribute('aria-selected'))->toBe('true')
        ->and($tabs->item(0)->getAttribute('aria-controls'))->toBe('cal-body')
        ->and($tabs->item(1)->getAttribute('tabindex'))->toBe('-1')
        ->and($dom->query('//*[@role="tablist"]')->item(0)->getAttribute('aria-label'))->toBe('Calendar view')
        ->and($dom->query('//*[@id="cal-body"]')->item(0)->getAttribute('aria-labelledby'))->toBe('cal-tab-month');
});

test('renders the week, day and agenda views', function (): void {
    $week = calendarDom('view="week" date="2026-11-12"');
    expect($week->query('//*[@data-part="title"]')->item(0)->textContent)->toBe('Nov 9 – Nov 15, 2026')
        ->and($week->query('//*[@role="gridcell"]')->length)->toBe(7)
        ->and(trim($week->query('//*[@role="columnheader"]')->item(3)->textContent))->toBe('Thu 12');

    $day = calendarDom('view="day" date="2026-11-12"');
    expect($day->query('//*[@role="gridcell"]')->length)->toBe(1)
        ->and($day->query('//*[@data-part="event"]')->length)->toBe(3);

    $agenda = calendarDom('view="agenda"');
    expect($agenda->query('//ul[@data-part="grid"]/li[@data-part="row"]')->length)->toBe(2)
        ->and($agenda->query('//h3[@data-part="weekday"]')->item(0)->textContent)->toBe('Monday, November 2, 2026')
        ->and($agenda->query('//*[@data-part="event-time"]')->item(0)->textContent)->toBe('09:00 – 10:00');

    $empty = calendarDom('view="agenda"', ['events' => []]);
    expect($empty->query('//*[contains(@class, "apui-empty-state")]')->item(0)->textContent)->toContain('No events');
});

test('renders the legend and takes labels and classes', function (): void {
    $dom = calendarDom(':labels="[\'today\' => \'Hoy\', \'legend\' => \'Event categories\']" :classes="[\'cell\' => \'my-cell\']"');

    expect(trim($dom->query('//*[@data-part="today-trigger"]')->item(0)->textContent))->toBe('Hoy')
        ->and($dom->query('//*[@data-part="legend"]')->item(0)->getAttribute('aria-label'))->toBe('Event categories')
        ->and($dom->query('//*[@data-part="legend-item"][@data-color="error"]')->item(0)->textContent)->toBe('Marketing')
        ->and($dom->query('//*[@data-part="cell"][contains(@class, "my-cell")]')->length)->toBe(42);
});

test('the Livewire component keeps the view and date', function (): void {
    Livewire::test(Calendar::class, ['events' => calendarEvents(), 'date' => '2026-11-07', 'today' => '2026-11-07', 'weekStartsOn' => 1, 'locale' => 'en-US'])
        ->assertSee('November 2026')
        ->call('setDate', '2026-12-07')
        ->assertSet('date', '2026-12-07')
        ->assertSee('December 2026')
        ->call('setView', 'week')
        ->assertSet('view', 'week')
        ->assertSee('Dec 7 – Dec 13, 2026')
        ->call('setView', 'year')
        ->assertSet('view', 'month')
        ->call('goToToday')
        ->assertSet('date', null)
        ->assertSee('November 2026');
});

test('the Livewire component takes an explicit null view and events', function (): void {
    Livewire::test(Calendar::class, ['view' => null, 'events' => null, 'date' => '2026-11-07', 'locale' => 'en-US'])
        ->assertSet('view', 'month')
        ->assertSet('events', [])
        ->assertSee('November 2026');
});

test('the Livewire component opens an event in the details Dialog', function (): void {
    Livewire::test(Calendar::class, ['events' => calendarEvents(), 'date' => '2026-11-07', 'locale' => 'en-US'])
        ->call('openEvent', '1')
        ->assertSet('eventModal', true)
        ->assertSet('selectedEvent.title', 'Team meeting')
        ->assertSet('selectedEvent.when', 'Monday, November 2, 2026, 09:00 – 10:00')
        ->assertDispatched('loadEventModal')
        ->assertSeeHtml('apui-dialog');

    Livewire::test(EventModalContent::class, ['event' => ['title' => 'Team meeting', 'category' => 'Marketing', 'color' => 'error', 'description' => 'Weekly sync.']])
        ->assertSee('Marketing')
        ->assertSee('Weekly sync.')
        ->assertSeeHtml('data-color="error"');
});

test('the Livewire component keeps the 2.x props working', function (): void {
    $calendar = Livewire::test(Calendar::class, [
        'events'      => [['id' => 'a', 'date' => '2026-11-03', 'time' => ['09:00', '10:00'], 'label' => 'Standup']],
        'sundayStart' => true,
        'view'        => 'year',
        'date'        => '2026-11-07',
    ]);

    $calendar->assertSet('weekStartsOn', 0)
        ->assertSet('view', 'month')
        ->assertSee('Standup')
        ->call('goToNextPeriod')
        ->assertSet('date', '2026-12-07')
        ->call('loadEventModal', 'a')
        ->assertSet('selectedEvent.when', 'Tuesday, November 3, 2026, 09:00 – 10:00');
});

test('the Livewire component answers the grid events', function (): void {
    $html = Livewire::test(Calendar::class, ['events' => calendarEvents()])->html();

    expect($html)->toContain('x-on:view-change="$wire.setView( $event.detail )"')
        ->and($html)->toContain('$wire.setDate( $event.detail )')
        ->and($html)->toContain('x-on:event-click=');
});

test('previous and next step from the server date, so each quick click moves a period', function (): void {
    $calendar = Livewire::test(Calendar::class, ['events' => calendarEvents(), 'date' => '2026-11-07', 'locale' => 'en-US']);
    $dom      = designSystemDom($calendar->html());
    $root     = $dom->query('//*[@*[name()="x-on:date-change"]]')->item(0);
    $handler  = $root->getAttribute('x-on:date-change');

    expect($handler)->toContain("closest( '[data-part=next-trigger]' ) ? \$wire.goToNextPeriod()")
        ->and($handler)->toContain("closest( '[data-part=previous-trigger]' ) ? \$wire.goToPreviousPeriod()")
        ->and($dom->query('//button[@data-part="next-trigger"][@*[name()="x-on:click"]]')->length)->toBe(1)
        ->and($dom->query('//button[@data-part="previous-trigger"][@*[name()="x-on:click"]]')->length)->toBe(1);

    $calendar->call('goToNextPeriod')
        ->call('goToNextPeriod')
        ->assertSet('date', '2027-01-07')
        ->call('goToPreviousPeriod')
        ->call('goToPreviousPeriod')
        ->call('goToPreviousPeriod')
        ->assertSet('date', '2026-10-07');
});

test('+N more moves focus to the day it opens', function (): void {
    $more = calendarDom()->query('//*[@data-part="more"]')->item(0);

    expect($more->getAttribute('x-on:click'))->toStartWith("moreFocus('2026-11-12');");
});

test('the details child remounts when the same event changes', function (): void {
    $events   = calendarEvents();
    $calendar = Livewire::test(Calendar::class, ['events' => $events, 'date' => '2026-11-07', 'locale' => 'en-US'])
        ->call('openEvent', '1');
    $first = $calendar->get('selectedKey');

    $calendar->call('openEvent', '1')->assertSet('selectedKey', $first);

    $events[0]['description'] = 'Moved to Tuesday.';
    $calendar->set('events', $events)->call('openEvent', '1');

    expect($calendar->get('selectedKey'))->toStartWith('1-')
        ->and($calendar->get('selectedKey'))->not->toBe($first);
});

test('the details child only takes loadEventModal from its own calendar', function (): void {
    Livewire::test(Calendar::class, ['events' => calendarEvents(), 'date' => '2026-11-07', 'locale' => 'en-US', 'id' => 'cal-a'])
        ->call('openEvent', '1')
        ->assertDispatched('loadEventModal', calendarId: 'cal-a')
        ->assertSeeHtml('cal-a-details')
        ->assertSee('Weekly sync.');

    $child = Livewire::test(EventModalContent::class, ['event' => ['title' => 'Mine'], 'calendarId' => 'cal-a']);

    $child->dispatch('loadEventModal', selectedEvent: ['title' => 'Theirs'], calendarId: 'cal-b')
        ->assertSet('event.title', 'Mine')
        ->dispatch('loadEventModal', selectedEvent: ['title' => 'Updated'], calendarId: 'cal-a')
        ->assertSet('event.title', 'Updated')
        ->dispatch('loadEventModal', selectedEvent: ['title' => 'Legacy'])
        ->assertSet('event.title', 'Legacy');

    Livewire::test(EventModalContent::class, ['event' => ['title' => 'Unscoped']])
        ->dispatch('loadEventModal', selectedEvent: ['title' => 'Any'], calendarId: 'cal-b')
        ->assertSet('event.title', 'Any');
});
