<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Tab;
use Illuminate\Support\Facades\Blade;

/*
 * Tab (#56): a child of Tabs, rendering its panel and registering its trigger.
 */

test('takes its value from value, then the deprecated name, then its label', function (): void {
    expect((new Tab(value: 'a', name: 'b', label: 'C'))->tabValue())->toBe('a')
        ->and((new Tab(name: 'b', label: 'C'))->tabValue())->toBe('b')
        ->and((new Tab(label: 'C'))->tabValue())->toBe('C');
});

test('keeps the deprecated name working', function (): void {
    $html = Blade::render('<x-artisanpack-tabs id="t" selected="two"><x-artisanpack-tab name="one" label="One">1</x-artisanpack-tab><x-artisanpack-tab name="two" label="Two">2</x-artisanpack-tab></x-artisanpack-tabs>');
    $dom  = designSystemDom($html);

    expect($dom->query('//*[@id="t-trigger-two"]')->item(0)->getAttribute('aria-selected'))->toBe('true')
        ->and($dom->query('//*[@id="t-content-one"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//*[contains(@class, "apui-tabs")]')->item(0)->hasAttribute('selected'))->toBeFalse();
});

test('reads the deprecated vertical-left and vertical-right as vertical', function (): void {
    $html = Blade::render('<x-artisanpack-tabs id="t" orientation="vertical-right"><x-artisanpack-tab value="a" label="A">A</x-artisanpack-tab></x-artisanpack-tabs>');

    expect(designSystemDom($html)->query('//*[@data-orientation="vertical" and contains(@class, "apui-tabs")]')->length)->toBe(1);
});

test('renders nothing for a hidden tab, and skips its trigger', function (): void {
    $html = Blade::render('<x-artisanpack-tabs id="t"><x-artisanpack-tab value="a" label="A">A</x-artisanpack-tab><x-artisanpack-tab value="b" label="B" hidden>B</x-artisanpack-tab></x-artisanpack-tabs>');

    expect(designSystemDom($html)->query('//*[@data-part="trigger"]')->length)->toBe(1)
        ->and($html)->not->toContain('t-content-b');
});

test('puts its attributes and the deprecated contentClasses on the panel', function (): void {
    $html  = Blade::render('<x-artisanpack-tabs id="t"><x-artisanpack-tab value="a" label="A" class="p-4" content-classes="py-2" data-test="x">A</x-artisanpack-tab></x-artisanpack-tabs>');
    $panel = designSystemDom($html)->query('//*[@data-part="content"]')->item(0);

    expect($panel->getAttribute('class'))->toContain('p-4')
        ->and($panel->getAttribute('class'))->toContain('py-2')
        ->and($panel->getAttribute('data-test'))->toBe('x');
});

test('renders a plain panel outside Tabs', function (): void {
    $html = Blade::render('<x-artisanpack-tab value="a" label="A">Alone</x-artisanpack-tab>');

    expect($html)->toContain('role="tabpanel"')
        ->and($html)->not->toContain('x-apui:content');
});
