<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Collapse (#58): the design-system disclosure on the collapsible machine,
 * as a section or a read-more. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

test('renders a closed section: trigger, then the hidden region', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-collapse id="adv" label="Advanced settings">Custom URL</x-artisanpack-collapse>'));
    $root = $dom->query('//div[contains(@class, "apui-collapse")]')->item(0);

    expect($root->getAttribute('data-variant'))->toBe('section')
        ->and($root->getAttribute('data-state'))->toBe('closed')
        ->and($root->getAttribute('x-data'))->toStartWith('apuiCollapse(');

    $parts = array_map(static fn (DOMElement $el): string => $el->getAttribute('data-part'), iterator_to_array($dom->query('//*[@data-scope="collapse"]')));
    expect($parts)->toBe(['root', 'trigger', 'icon', 'content']);

    $trigger = $dom->query('//button[@id="adv-trigger"]')->item(0);
    expect($trigger->getAttribute('aria-expanded'))->toBe('false')
        ->and($trigger->getAttribute('aria-controls'))->toBe('adv-content')
        ->and(trim($trigger->textContent))->toBe('Advanced settings')
        ->and($dom->query('//*[@id="adv-content"]')->item(0)->hasAttribute('hidden'))->toBeTrue();
});

test('renders open', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-collapse id="adv" label="More" open>Body</x-artisanpack-collapse>'));

    expect($dom->query('//button')->item(0)->getAttribute('aria-expanded'))->toBe('true')
        ->and($dom->query('//*[@id="adv-content"]')->item(0)->hasAttribute('hidden'))->toBeFalse();
});

test('renders a read-more: clamped region with the fade, then the trigger', function (): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-collapse id="desc" variant="read-more">Long text</x-artisanpack-collapse>'));
    $parts = array_map(static fn (DOMElement $el): string => $el->getAttribute('data-part'), iterator_to_array($dom->query('//*[@data-scope="collapse"]')));

    expect($parts)->toBe(['root', 'content', 'fade', 'trigger', 'icon']);
    $content = $dom->query('//*[@id="desc-content"]')->item(0);
    expect($content->hasAttribute('hidden'))->toBeFalse()
        ->and($content->getAttribute('style'))->toContain('max-height: var(--apui-collapse-clamp-height, 64px)')
        ->and($content->getAttribute('style'))->not->toContain('min-height')
        ->and($dom->query('//*[@data-part="fade"]')->item(0)->getAttribute('aria-hidden'))->toBe('true')
        ->and(trim($dom->query('//button')->item(0)->textContent))->toBe('Read more')
        ->and($dom->query('//button//*[@x-text]')->item(0)->getAttribute('x-text'))->toContain('Show less');
});

test('disables the trigger', function (): void {
    $trigger = designSystemDom(Blade::render('<x-artisanpack-collapse label="A" disabled>B</x-artisanpack-collapse>'))->query('//button')->item(0);

    expect($trigger->hasAttribute('disabled'))->toBeTrue()
        ->and($trigger->hasAttribute('data-disabled'))->toBeTrue();
});

test('takes a trigger slot, and the deprecated heading and content slots', function (): void {
    $slot = designSystemDom(Blade::render('<x-artisanpack-collapse><x-slot:trigger>Show <strong>more</strong></x-slot:trigger> Body</x-artisanpack-collapse>'));
    expect($slot->query('//button/strong')->item(0)->textContent)->toBe('more');

    $legacy = designSystemDom(Blade::render('<x-artisanpack-collapse><x-slot:heading>Old title</x-slot:heading><x-slot:content>Old body</x-slot:content></x-artisanpack-collapse>'));
    expect(trim($legacy->query('//button')->item(0)->textContent))->toBe('Old title')
        ->and(trim($legacy->query('//*[@data-part="content"]')->item(0)->textContent))->toBe('Old body');
});

test('entangles wire:model', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-collapse id="adv" label="A" wire:model="advanced">B</x-artisanpack-collapse>'))->query('//div[contains(@class, "apui-collapse")]')->item(0);

    expect($root->getAttribute('x-data'))->toContain('$wire.entangle(\'advanced\')')
        ->and($root->hasAttribute('wire:model'))->toBeFalse();
});

test('applies classes per part', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-collapse label="A" :classes="[\'trigger\' => \'my-trigger\', \'content\' => \'my-content\']">B</x-artisanpack-collapse>'));

    expect($dom->query('//button[contains(@class, "my-trigger")]')->length)->toBe(1)
        ->and($dom->query('//*[contains(@class, "my-content")]')->length)->toBe(1);
});
