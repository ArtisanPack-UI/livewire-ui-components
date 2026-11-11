<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * MenuSub (#56): the disclosure pattern, with Alpine owning its state.
 */

test('renders closed by default, with a toggle Alpine owns', function (): void {
    $html = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-sub title="Reports" icon="calendar"><x-artisanpack-menu-item href="/r">All</x-artisanpack-menu-item></x-artisanpack-menu-sub></x-artisanpack-menu>');
    $dom  = designSystemDom($html);

    $toggle = $dom->query('//button[@data-part="item"]')->item(0);
    expect($toggle->getAttribute('aria-expanded'))->toBe('false')
        ->and($toggle->getAttribute('x-on:click'))->toBe('open = ! open')
        ->and($toggle->hasAttribute('wire:ignore.self'))->toBeTrue()
        ->and($toggle->textContent)->toContain('Reports');
    $sub = $dom->query('//*[@data-part="sub"]')->item(0);
    expect($sub->hasAttribute('hidden'))->toBeTrue()
        ->and($sub->getAttribute('x-bind:hidden'))->toBe('! open');
    expect($dom->query('//*[@data-part="chevron" and @data-mirror]')->length)->toBe(1);
});

test('renders open with open, or when a nested item is current', function (): void {
    $open   = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-sub label="A" open><x-artisanpack-menu-item href="/a">A</x-artisanpack-menu-item></x-artisanpack-menu-sub></x-artisanpack-menu>');
    $nested = Blade::render('<x-artisanpack-menu id="n"><x-artisanpack-menu-sub label="A"><x-artisanpack-menu-sub label="B"><x-artisanpack-menu-item href="/b" current>B</x-artisanpack-menu-item></x-artisanpack-menu-sub></x-artisanpack-menu-sub></x-artisanpack-menu>');

    expect(designSystemDom($open)->query('//button[@aria-expanded="true"]')->length)->toBe(1)
        ->and(designSystemDom($nested)->query('//button[@aria-expanded="true"]')->length)->toBe(2);
});

test('numbers sub-menu ids in render order', function (): void {
    $html = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-sub label="A"><x-artisanpack-menu-item href="/a">A</x-artisanpack-menu-item></x-artisanpack-menu-sub><x-artisanpack-menu-sub label="B"><x-artisanpack-menu-item href="/b">B</x-artisanpack-menu-item></x-artisanpack-menu-sub></x-artisanpack-menu>');
    $dom  = designSystemDom($html);

    expect($dom->query('//*[@id="m-sub-1"]')->length)->toBe(1)
        ->and($dom->query('//*[@id="m-sub-2"]')->length)->toBe(1);
});

test('renders a badge and a disabled toggle, from composition and data', function (): void {
    $composed = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-sub label="Reports" badge="2" disabled><x-artisanpack-menu-item href="/r">All</x-artisanpack-menu-item></x-artisanpack-menu-sub></x-artisanpack-menu>');
    $data     = Blade::render('<x-artisanpack-menu id="m" :items="$items" />', ['items' => [['label' => 'Reports', 'badge' => 2, 'disabled' => true, 'items' => [['label' => 'All', 'href' => '/r']]]]]);

    foreach ([$composed, $data] as $html) {
        $toggle = designSystemDom($html)->query('//button[@data-part="item"]')->item(0);
        expect($toggle->hasAttribute('disabled'))->toBeTrue()
            ->and(trim(designSystemDom($html)->query('//button//*[@data-part="badge"]')->item(0)->textContent))->toBe('2');
    }
});
