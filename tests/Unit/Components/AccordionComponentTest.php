<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Accordion;
use Illuminate\Support\Facades\Blade;

/*
 * Accordion (#58): the design-system accordion and its accordion-item
 * children, on the accordion machine. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function accordionComposition(string $attributes = 'id="faq" :value="[\'live\']"'): string
{
    return Blade::render(<<<BLADE
        <x-artisanpack-accordion {$attributes}>
            <x-artisanpack-accordion-item value="live" title="How long does a listing stay live?">Until you archive it.</x-artisanpack-accordion-item>
            <x-artisanpack-accordion-item value="owners"><x-slot:title>Can <em>co-owners</em> edit?</x-slot:title> Yes.</x-artisanpack-accordion-item>
            <x-artisanpack-accordion-item value="files" title="Which files?" disabled>Images and PDFs.</x-artisanpack-accordion-item>
        </x-artisanpack-accordion>
        BLADE);
}

test('renders the root, items, titles, triggers, chevrons and panels with stable ids', function (): void {
    $dom  = designSystemDom(accordionComposition());
    $root = $dom->query('//div[contains(@class, "apui-accordion")]')->item(0);

    expect($root->getAttribute('id'))->toBe('faq')
        ->and($root->getAttribute('data-variant'))->toBe('default')
        ->and($root->hasAttribute('data-collapsible'))->toBeTrue()
        ->and($root->hasAttribute('data-multiple'))->toBeFalse()
        ->and($root->getAttribute('x-data'))->toStartWith('apuiAccordion(');

    $parts  = array_map(static fn (DOMElement $el): string => $el->getAttribute('data-part'), iterator_to_array($dom->query('//*[@data-scope="accordion"]')));
    $counts = array_count_values($parts);
    ksort($counts);
    expect($counts)->toBe(['chevron' => 3, 'content' => 3, 'item' => 3, 'root' => 1, 'title' => 3, 'trigger' => 3]);

    $trigger = $dom->query('//button[@id="faq-trigger-live"]')->item(0);
    expect($trigger->getAttribute('aria-expanded'))->toBe('true')
        ->and($trigger->getAttribute('aria-controls'))->toBe('faq-content-live')
        ->and($trigger->getAttribute('data-ownedby'))->toBe('faq')
        ->and($trigger->parentNode->nodeName)->toBe('h3');

    $panel = $dom->query('//*[@id="faq-content-live"]')->item(0);
    expect($panel->getAttribute('role'))->toBe('region')
        ->and($panel->getAttribute('aria-labelledby'))->toBe('faq-trigger-live')
        ->and($panel->hasAttribute('hidden'))->toBeFalse()
        ->and($dom->query('//*[@id="faq-content-owners"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//*[@id="faq-item-owners"]')->item(0)->getAttribute('data-state'))->toBe('closed');
});

test('renders a title slot, disables an item and hides the chevron', function (): void {
    $dom = designSystemDom(accordionComposition());

    expect($dom->query('//button[@id="faq-trigger-owners"]/em')->item(0)->textContent)->toBe('co-owners')
        ->and($dom->query('//button[@id="faq-trigger-files"]')->item(0)->hasAttribute('disabled'))->toBeTrue()
        ->and($dom->query('//*[@id="faq-item-files"]')->item(0)->hasAttribute('data-disabled'))->toBeTrue()
        ->and($dom->query('//*[@data-part="chevron"]')->item(0)->getAttribute('aria-hidden'))->toBe('true');
});

test('renders the same items from data, before composed children', function (): void {
    $html = Blade::render('<x-artisanpack-accordion id="faq" :items="$items"><x-artisanpack-accordion-item value="c" title="C">Cc</x-artisanpack-accordion-item></x-artisanpack-accordion>', ['items' => [
        ['value' => 'a', 'title' => 'A', 'content' => 'Ac'],
        ['value' => 'b', 'title' => 'B', 'content' => 'Bc', 'disabled' => true],
    ]]);
    $ids = array_map(static fn (DOMElement $el): string => $el->getAttribute('id'), iterator_to_array(designSystemDom($html)->query('//*[@data-part="item"]')));

    expect($ids)->toBe(['faq-item-a', 'faq-item-b', 'faq-item-c']);
});

test('takes variant, multiple, collapsible, heading level and disabled', function (): void {
    $dom  = designSystemDom(accordionComposition('id="faq" variant="separated" multiple :collapsible="false" :heading-level="2" disabled'));
    $root = $dom->query('//div[contains(@class, "apui-accordion")]')->item(0);

    expect($root->getAttribute('data-variant'))->toBe('separated')
        ->and($root->hasAttribute('data-multiple'))->toBeTrue()
        ->and($root->hasAttribute('data-collapsible'))->toBeFalse()
        ->and($root->getAttribute('x-data'))->toContain('multiple: true')->toContain('collapsible: false')->toContain('disabled: true')
        ->and($dom->query('//h2[@data-part="title"]')->length)->toBe(3)
        ->and($dom->query('//button[@disabled]')->length)->toBe(3);
});

test('falls back to the default variant and clamps the heading level', function (): void {
    $dom = designSystemDom(accordionComposition('id="faq" variant="join" :heading-level="9"'));

    expect($dom->query('//div[contains(@class, "apui-accordion")]')->item(0)->getAttribute('data-variant'))->toBe('default')
        ->and($dom->query('//h6[@data-part="title"]')->length)->toBe(3);

    $accordion = new Accordion(headingLevel: 1);
    expect($accordion->headingLevel)->toBe(2);
    $accordion->finishRendering();
});

test('entangles wire:model, live with wire:model.live', function (): void {
    $root = designSystemDom(accordionComposition('id="faq" wire:model.live="open"'))->query('//div[contains(@class, "apui-accordion")]')->item(0);

    expect($root->getAttribute('x-data'))->toContain('$wire.entangle(\'open\').live')
        ->and($root->hasAttribute('wire:model.live'))->toBeFalse();
});

test('opens a single item from a string value', function (): void {
    $dom = designSystemDom(accordionComposition('id="faq" value="owners"'));

    expect($dom->query('//button[@aria-expanded="true"]')->item(0)->getAttribute('id'))->toBe('faq-trigger-owners');
});

test('applies classes per part and class to the root', function (): void {
    $dom = designSystemDom(accordionComposition('id="faq" class="mb-4" :classes="[\'item\' => \'my-item\', \'trigger\' => \'my-trigger\', \'content\' => \'my-panel\']"'));

    expect($dom->query('//div[contains(@class, "apui-accordion") and contains(@class, "mb-4")]')->length)->toBe(1)
        ->and($dom->query('//*[contains(@class, "my-item")]')->length)->toBe(3)
        ->and($dom->query('//button[contains(@class, "my-trigger")]')->length)->toBe(3)
        ->and($dom->query('//*[contains(@class, "my-panel")]')->length)->toBe(3);
});
