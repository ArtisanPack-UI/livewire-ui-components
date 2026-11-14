<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Popover (#58): the design-system markup contract (D-019) on the popover
 * machine. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function popoverPanel(DOMXPath $dom): DOMElement
{
    return $dom->query('//div[contains(@class, "apui-popover")]')->item(0);
}

test('the fixture renders every part in order with stable ids', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-popover id="share" trigger="Share" title="Share report" actions="Copy link" open :portal="false">Anyone can view it.</x-artisanpack-popover>',
    ));

    $parts = [];
    foreach ($dom->query('//*[@data-scope="popover"]') as $element) {
        $parts[$element->getAttribute('data-part')] = $element->getAttribute('id');
    }

    expect($parts)->toBe([
        'trigger'    => 'share-trigger',
        'positioner' => 'share-positioner',
        'content'    => 'share-content',
        'arrow'      => 'share-arrow',
        'title'      => 'share-title',
        'body'       => 'share-body',
        'actions'    => 'share-actions',
    ]);

    $panel = popoverPanel($dom);
    expect($panel->getAttribute('role'))->toBe('dialog')
        ->and($panel->hasAttribute('aria-modal'))->toBeFalse()
        ->and($panel->getAttribute('aria-labelledby'))->toBe('share-title')
        ->and($panel->getAttribute('data-placement'))->toBe('bottom')
        ->and($panel->getAttribute('data-state'))->toBe('open')
        ->and($panel->hasAttribute('hidden'))->toBeFalse()
        ->and($dom->query('//*[@data-part="arrow"]')->item(0)->getAttribute('aria-hidden'))->toBe('true')
        ->and($dom->query('//h3[@data-part="title"]')->item(0)->textContent)->toBe('Share report');
});

test('text in trigger renders an outline Button that controls the panel', function (): void {
    $trigger = designSystemDom(Blade::render('<x-artisanpack-popover id="p" trigger="Share" title="Share" />'))->query('//button[@data-part="trigger"]')->item(0);

    expect($trigger->getAttribute('class'))->toContain('apui-button')
        ->and($trigger->getAttribute('data-variant'))->toBe('outline')
        ->and($trigger->getAttribute('aria-haspopup'))->toBe('dialog')
        ->and($trigger->getAttribute('aria-expanded'))->toBe('false')
        ->and($trigger->getAttribute('aria-controls'))->toBe('p-content')
        ->and($trigger->getAttribute('x-apui:trigger'))->toBe('');
});

test('an element in the trigger slot becomes the trigger and keeps its own id', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-popover id="p" title="About visits"><x-slot:trigger><x-artisanpack-button id="about" variant="ghost" icon-only leading-icon="info" aria-label="About visits" /></x-slot:trigger> Text</x-artisanpack-popover>',
    ));
    $triggers = $dom->query('//*[@data-part="trigger"]');

    expect($triggers)->toHaveCount(1)
        ->and($triggers->item(0)->getAttribute('id'))->toBe('about')
        ->and($triggers->item(0)->getAttribute('data-variant'))->toBe('ghost')
        ->and($triggers->item(0)->getAttribute('aria-label'))->toBe('About visits')
        ->and($triggers->item(0)->getAttribute('aria-controls'))->toBe('p-content')
        ->and($triggers->item(0)->hasAttribute('x-apui:trigger'))->toBeTrue();
});

test('placement renders data-placement; the 2.x position maps left and right', function (string $attributes, string $expected): void {
    $panel = popoverPanel(designSystemDom(Blade::render('<x-artisanpack-popover title="T" '.$attributes.' />')));

    expect($panel->getAttribute('data-placement'))->toBe($expected);
})->with([
    'default'        => ['', 'bottom'],
    'end'            => ['placement="end"', 'end'],
    'position left'  => ['position="left"', 'start'],
    'position right' => ['position="right"', 'end'],
    'position top'   => ['position="top"', 'top'],
    'unknown'        => ['placement="left"', 'bottom'],
]);

test('the placement reaches the machine, and :arrow="false" drops the arrow', function (): void {
    $html = Blade::render('<x-artisanpack-popover title="T" placement="top" :arrow="false" />');

    expect(designSystemDom($html)->query('//div[@x-data]')->item(0)->getAttribute('x-data'))->toContain("placement: 'top'")
        ->and(designSystemDom($html)->query('//*[@data-part="arrow"]'))->toHaveCount(0);
});

test('a closed popover renders its panel hidden', function (): void {
    $panel = popoverPanel(designSystemDom(Blade::render('<x-artisanpack-popover title="T" :portal="false" />')));

    expect($panel->hasAttribute('hidden'))->toBeTrue()
        ->and($panel->getAttribute('data-state'))->toBe('closed');
});

test('the 2.x content slot is the body, and dropped attributes stay off the host', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-popover position="top" offset="12" glass="true"><x-slot:trigger>Info</x-slot:trigger><x-slot:content>More</x-slot:content></x-artisanpack-popover>'));
    $host = $dom->query('//div[@x-data]')->item(0);

    expect($dom->query('//*[@data-part="body"]')->item(0)->textContent)->toBe('More')
        ->and($host->hasAttribute('position'))->toBeFalse()
        ->and($host->hasAttribute('offset'))->toBeFalse()
        ->and($host->hasAttribute('glass'))->toBeFalse();
});

test('aria-label names a panel without a title', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-popover trigger="Info" aria-label="Visits">Text</x-artisanpack-popover>'));

    expect(popoverPanel($dom)->getAttribute('aria-label'))->toBe('Visits')
        ->and($dom->query('//div[@x-data]')->item(0)->hasAttribute('aria-label'))->toBeFalse();
});

test('wire:model entangles the open state', function (): void {
    expect(Blade::render('<x-artisanpack-popover wire:model="sharing" title="Share" />'))
        ->toContain('$wire.entangle(&#039;sharing&#039;)')
        ->not->toContain('class="dropdown');
});

test('no DaisyUI classes remain', function (): void {
    $html = Blade::render('<x-artisanpack-popover trigger="Share" title="Share">Body</x-artisanpack-popover>');

    expect($html)->not->toMatch('/class="[^"]*\b(dropdown|dropdown-content|bg-base-100|rounded-box)\b/');
});
