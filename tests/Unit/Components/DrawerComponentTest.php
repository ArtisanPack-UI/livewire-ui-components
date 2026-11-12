<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Drawer (#58): the design-system markup contract (D-019) on the dialog
 * machine, scoped drawer. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function drawerPanel(DOMXPath $dom): DOMElement
{
    return $dom->query('//aside[contains(@class, "apui-drawer")]')->item(0);
}

test('the fixture renders every part in order with stable ids', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-drawer id="filters" side="bottom" trigger="Filters" title="Filters" description="218 properties match" footer="Show results" :portal="false">Fields</x-artisanpack-drawer>',
    ));

    $parts = [];
    foreach ($dom->query('//*[@data-scope="drawer"]') as $element) {
        $parts[$element->getAttribute('data-part')] = $element->getAttribute('id');
    }

    expect($parts)->toBe([
        'trigger'       => 'filters-trigger',
        'backdrop'      => 'filters-backdrop',
        'positioner'    => 'filters-positioner',
        'content'       => 'filters-content',
        'handle'        => 'filters-handle',
        'header'        => 'filters-header',
        'title'         => 'filters-title',
        'description'   => 'filters-description',
        'close-trigger' => 'filters-close-trigger',
        'body'          => 'filters-body',
        'footer'        => 'filters-footer',
    ]);

    $panel = drawerPanel($dom);
    expect($panel->getAttribute('role'))->toBe('dialog')
        ->and($panel->getAttribute('aria-modal'))->toBe('true')
        ->and($panel->getAttribute('aria-labelledby'))->toBe('filters-title')
        ->and($panel->getAttribute('aria-describedby'))->toBe('filters-description')
        ->and($panel->getAttribute('tabindex'))->toBe('-1')
        ->and($panel->getAttribute('data-side'))->toBe('bottom')
        ->and($panel->getAttribute('data-size'))->toBe('md')
        ->and($panel->getAttribute('data-surface'))->toBe('overlay')
        ->and($panel->hasAttribute('x-apui:content'))->toBeTrue()
        ->and($dom->query('//*[@data-part="handle"]')->item(0)->getAttribute('aria-hidden'))->toBe('true')
        ->and($dom->query('//*[@data-part="positioner"]')->item(0)->getAttribute('data-side'))->toBe('bottom')
        ->and($dom->query('//*[@data-part="body"]')->item(0)->textContent)->toBe('Fields');
});

test('the side defaults to end, and only the bottom sheet has a handle', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-drawer title="Filters" :portal="false" />'));

    expect(drawerPanel($dom)->getAttribute('data-side'))->toBe('end')
        ->and($dom->query('//*[@data-part="handle"]'))->toHaveCount(0);
});

test('side and size render on the panel; an unknown side falls back to end', function (string $side, string $expected): void {
    $panel = drawerPanel(designSystemDom(Blade::render('<x-artisanpack-drawer title="Filters" side="'.$side.'" size="lg" surface="glass" />')));

    expect($panel->getAttribute('data-side'))->toBe($expected)
        ->and($panel->getAttribute('data-size'))->toBe('lg')
        ->and($panel->getAttribute('data-surface'))->toBe('glass');
})->with([
    'start'   => ['start', 'start'],
    'end'     => ['end', 'end'],
    'bottom'  => ['bottom', 'bottom'],
    'unknown' => ['left', 'end'],
]);

test('a closed drawer still renders its panel, hidden; an open one renders open', function (): void {
    $closed = designSystemDom(Blade::render('<x-artisanpack-drawer title="Filters" :portal="false" />'));
    $open   = designSystemDom(Blade::render('<x-artisanpack-drawer title="Filters" trigger="Filters" open :portal="false" />'));

    expect(drawerPanel($closed)->hasAttribute('hidden'))->toBeTrue()
        ->and(drawerPanel($closed)->getAttribute('data-state'))->toBe('closed')
        ->and($closed->query('//*[@data-part="backdrop"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and(drawerPanel($open)->hasAttribute('hidden'))->toBeFalse()
        ->and(drawerPanel($open)->getAttribute('data-state'))->toBe('open')
        ->and($open->query('//*[@data-part="trigger"]')->item(0)->getAttribute('aria-expanded'))->toBe('true');
});

test('the trigger is an outline neutral Button that controls the panel', function (): void {
    $trigger = designSystemDom(Blade::render('<x-artisanpack-drawer id="d" trigger="Filters" title="Filters" :portal="false" />'))->query('//button[@data-part="trigger"]')->item(0);

    expect($trigger->getAttribute('class'))->toContain('apui-button')
        ->and($trigger->getAttribute('data-variant'))->toBe('outline')
        ->and($trigger->getAttribute('data-scope'))->toBe('drawer')
        ->and($trigger->getAttribute('aria-haspopup'))->toBe('dialog')
        ->and($trigger->getAttribute('aria-controls'))->toBe('d-content')
        ->and($trigger->getAttribute('x-apui:trigger'))->toBe('');
});

test('the close button has a translated name; closeLabel and hideClose change it', function (): void {
    app('translator')->addLines(['*.Close' => 'Fermer'], 'fr');
    app()->setLocale('fr');
    $close = designSystemDom(Blade::render('<x-artisanpack-drawer title="Filters" />'))->query('//button[@data-part="close-trigger"]')->item(0);
    app()->setLocale('en');

    expect($close->getAttribute('aria-label'))->toBe('Fermer')
        ->and($close->getAttribute('data-variant'))->toBe('ghost')
        ->and($close->hasAttribute('data-icon-only'))->toBeTrue()
        ->and(designSystemDom(Blade::render('<x-artisanpack-drawer title="Filters" close-label="Done" />'))->query('//button[@data-part="close-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Done')
        ->and(designSystemDom(Blade::render('<x-artisanpack-drawer title="Filters" hide-close />'))->query('//*[@data-part="close-trigger"]'))->toHaveCount(0);
});

test('a non-modal drawer drops aria-modal and tells the machine', function (): void {
    $html = Blade::render('<x-artisanpack-drawer title="Filters" :modal="false" />');

    expect(drawerPanel(designSystemDom($html))->hasAttribute('aria-modal'))->toBeFalse()
        ->and($html)->toContain('apuiDrawer(')
        ->and($html)->toContain('modal: false');
});

test('portal teleports the overlay to the body; off renders it in place', function (): void {
    expect(Blade::render('<x-artisanpack-drawer title="Filters" />'))->toContain('<template x-teleport="body">')
        ->and(Blade::render('<x-artisanpack-drawer title="Filters" :portal="false" />'))->not->toContain('x-teleport');
});

test('wire:model entangles the open state and leaves the host attributes alone', function (): void {
    $html = Blade::render('<x-artisanpack-drawer wire:model="showFilters" title="Filters" x-on:open-change="log($event.detail)" />');
    $host = designSystemDom($html)->query('//div[@x-data]')->item(0);

    expect($host->getAttribute('x-data'))->toContain("open: \$wire.entangle('showFilters')")
        ->and($host->getAttribute('x-on:open-change'))->toBe('log($event.detail)')
        ->and($host->hasAttribute('wire:model'))->toBeFalse()
        ->and($host->getAttribute('id'))->toStartWith('apui-drawer-showfilters-');
});

test('the 2.x right, subtitle and actions still work, and dropped attributes stay off the host', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-drawer right title="Filters" subtitle="Narrow it down" separator with-close-button close-on-escape :portal="false"><x-slot:actions>Apply</x-slot:actions> Fields</x-artisanpack-drawer>',
    ));
    $host = $dom->query('//div[@x-data]')->item(0);

    expect(drawerPanel($dom)->getAttribute('data-side'))->toBe('end')
        ->and($dom->query('//p[@data-part="description"]')->item(0)->textContent)->toBe('Narrow it down')
        ->and($dom->query('//*[@data-part="footer"]')->item(0)->textContent)->toBe('Apply')
        ->and($host->hasAttribute('right'))->toBeFalse()
        ->and($host->hasAttribute('subtitle'))->toBeFalse()
        ->and($host->hasAttribute('separator'))->toBeFalse()
        ->and($host->hasAttribute('close-on-escape'))->toBeFalse();
});

test('class lands on the panel after classes.root, and part classes on their parts', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-drawer title="Filters" class="w-full" :classes="[\'root\' => \'shadow-none\', \'body\' => \'gap-2\']">Body</x-artisanpack-drawer>'));

    expect(drawerPanel($dom)->getAttribute('class'))->toBe('apui-drawer shadow-none w-full')
        ->and($dom->query('//*[@data-part="body"]')->item(0)->getAttribute('class'))->toBe('gap-2');
});

test('no DaisyUI drawer classes remain', function (): void {
    $html = Blade::render('<x-artisanpack-drawer title="Filters" trigger="Open">Body</x-artisanpack-drawer>');

    expect($html)->not->toMatch('/class="(?:[^"]*\s)?(drawer|drawer-side|drawer-toggle|drawer-overlay|drawer-end)(?:\s[^"]*)?"/');
});
