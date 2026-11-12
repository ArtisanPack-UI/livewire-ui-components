<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Kbd;
use Illuminate\Support\Facades\Blade;

/*
 * Kbd (#65): the design-system markup contract, and keyList(), the PHP port
 * of the behavior package's kbdKeys. Its expectations match
 * test/clipboard-kbd-code.test.ts in the design system, so Blade shows the
 * keys React and Vue show. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function kbdDom(string $blade): DOMXPath
{
    return designSystemDom(Blade::render($blade));
}

function kbdTexts(DOMXPath $dom): array
{
    $texts = [];
    foreach ($dom->query('//kbd[@data-part="key"]') as $key) {
        $texts[] = $key->textContent;
    }

    return $texts;
}

test('keyList matches the TypeScript helper', function (): void {
    $keys = static fn (array $list, bool $apple = false, array $labels = []): array => (new Kbd(keys: $list, labels: $labels))->keyList($apple);

    expect($keys(['mod', 'k']))->toBe([['text' => 'Ctrl', 'label' => null], ['text' => 'K', 'label' => null]])
        ->and($keys(['mod', 'k'], true))->toBe([['text' => '⌘', 'label' => 'Command'], ['text' => 'K', 'label' => null]])
        ->and(array_column($keys(['ctrl', 'alt', 'shift', 'p']), 'text'))->toBe(['Ctrl', 'Alt', 'Shift', 'P'])
        ->and($keys(['ctrl', 'option', 'shift'], true))->toBe([
            ['text' => '⌃', 'label' => 'Control'],
            ['text' => '⌥', 'label' => 'Option'],
            ['text' => '⇧', 'label' => 'Shift'],
        ])
        ->and($keys(['enter', 'up', '⌘', 'Esc', 'tab']))->toBe([
            ['text' => '↵', 'label' => 'Enter'],
            ['text' => '↑', 'label' => 'Up arrow'],
            ['text' => '⌘', 'label' => 'Command'],
            ['text' => 'Esc', 'label' => null],
            ['text' => 'Tab', 'label' => null],
        ])
        ->and($keys(['enter'], false, ['enter' => 'Entrée']))->toBe([['text' => '↵', 'label' => 'Entrée']])
        ->and(array_column($keys(['?', 'F5', ' ', '', null, 'é']), 'text'))->toBe(['?', 'F5', 'É']);
});

test('renders a shortcut as a kbd of kbd elements', function (): void {
    $dom   = kbdDom('<x-artisanpack-kbd :keys="[\'ctrl\', \'shift\', \'p\']" platform="other" />');
    $combo = $dom->query('//body/kbd')->item(0);

    expect($combo->getAttribute('class'))->toBe('apui-kbd-combo')
        ->and($combo->getAttribute('data-scope'))->toBe('kbd')
        ->and($combo->getAttribute('data-part'))->toBe('combo')
        ->and($combo->hasAttribute('x-data'))->toBeFalse()
        ->and(kbdTexts($dom))->toBe(['Ctrl', 'Shift', 'P']);

    foreach ($dom->query('//kbd[@data-part="key"]') as $key) {
        expect($key->getAttribute('class'))->toBe('apui-kbd')
            ->and($key->getAttribute('data-size'))->toBe('md');
    }
});

test('renders Apple keys with hidden names for platform mac', function (): void {
    $dom  = kbdDom('<x-artisanpack-kbd :keys="[\'mod\', \'k\']" platform="mac" size="sm" />');
    $keys = $dom->query('//kbd[@data-part="key"]');

    expect($dom->query('.//*[@aria-hidden="true"]', $keys->item(0))->item(0)->textContent)->toBe('⌘')
        ->and($dom->query('.//*[@class="apui-sr-only"]', $keys->item(0))->item(0)->textContent)->toBe('Command')
        ->and($keys->item(1)->textContent)->toBe('K')
        ->and($keys->item(1)->getAttribute('data-size'))->toBe('sm');
});

test('lets Alpine swap in Apple keys when the platform is auto', function (): void {
    $html = Blade::render('<x-artisanpack-kbd :keys="[\'mod\', \'k\']" />');
    $dom  = designSystemDom($html);

    expect($dom->query('//kbd[@data-part="combo"]')->item(0)->getAttribute('x-data'))->toContain('apuiKbd({ keys:')
        ->and($html)->toContain('x-text="keys[0].text"')
        ->and(kbdTexts($dom))->toBe(['Ctrl', 'K'])
        ->and(Blade::render('<x-artisanpack-kbd :keys="[\'enter\']" />'))->not->toContain('x-data');
});

test('renders the slot as one key without keys, as in 2.x', function (): void {
    $dom = kbdDom('<x-artisanpack-kbd size="sm" class="extra">Esc</x-artisanpack-kbd>');
    $key = $dom->query('//body/kbd')->item(0);

    expect($key->getAttribute('class'))->toBe('apui-kbd extra')
        ->and($key->getAttribute('data-part'))->toBe('key')
        ->and($key->getAttribute('data-size'))->toBe('sm')
        ->and(trim($key->textContent))->toBe('Esc');
});

test('translates the symbol names and takes part classes', function (): void {
    $dom = kbdDom('<x-artisanpack-kbd :keys="[\'enter\']" :labels="[\'enter\' => \'Entrée\']" :classes="[\'root\' => \'r\', \'combo\' => \'c\', \'key\' => \'k\']" />');

    expect($dom->query('//*[@class="apui-sr-only"]')->item(0)->textContent)->toBe('Entrée')
        ->and($dom->query('//kbd[@data-part="combo"]')->item(0)->getAttribute('class'))->toBe('apui-kbd-combo r c')
        ->and($dom->query('//kbd[@data-part="key"]')->item(0)->getAttribute('class'))->toBe('apui-kbd k');
});
