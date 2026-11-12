<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\ThemePreview;
use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Livewire\Livewire;

/*
 * ThemePreview (#65, Livewire only, D-003): a radio group of cards whose
 * miniatures are drawn by the real tokens.
 */

function themePreviewDom(string $attributes = '', ?array $options = null): DOMXPath
{
    return designSystemDom(Blade::render(
        '<x-artisanpack-theme-preview :options="$options" '.$attributes.' />',
        ['options' => $options ?? [
            ['value' => 'light', 'label' => 'Light'],
            ['value' => 'dark', 'label' => 'Dark'],
            ['value' => 'high-contrast', 'label' => 'High contrast'],
            ['value' => 'system', 'label' => 'System'],
        ]],
    ));
}

test('it is a named radio group of theme cards', function (): void {
    $dom   = themePreviewDom('value="dark"');
    $root  = $dom->query('//div[contains(@class, "apui-theme-preview")]')->item(0);
    $cards = $dom->query('./label[contains(@class, "apui-theme-card")]', $root);

    expect($root->getAttribute('role'))->toBe('radiogroup')
        ->and($root->getAttribute('aria-label'))->toBe('Theme')
        ->and($cards)->toHaveCount(4)
        ->and($dom->query('//input[@type="radio"][@checked]')->item(0)->getAttribute('value'))->toBe('dark')
        ->and($dom->query('//label[@data-state="checked"]')->item(0)->getAttribute('data-value'))->toBe('dark')
        ->and(trim($dom->query('.//*[@data-part="text"]', $cards->item(2))->item(0)->textContent))->toBe('High contrast');
});

test('each miniature is decorative and drawn with its theme', function (): void {
    $dom   = themePreviewDom();
    $minis = $dom->query('//*[@data-part="miniature"]');

    expect($minis)->toHaveCount(5)
        ->and(array_map(static fn (DOMElement $mini): string => $mini->getAttribute('data-theme'), iterator_to_array($minis)))->toBe(['light', 'dark', 'high-contrast', 'light', 'dark'])
        ->and($dom->query('//*[@data-part="miniature"][not(@aria-hidden="true")]'))->toHaveCount(0)
        ->and($dom->query('//label[@data-value="system"]//*[@data-part="miniature"]/@data-half'))->toHaveCount(2)
        ->and($dom->query('//label[@data-value="light"]//*[@data-part="sidebar"]/*[@data-part="line"]'))->toHaveCount(3)
        ->and($dom->query('//label[@data-value="light"]//*[@data-part="tiles"]/*[@data-part="tile"]'))->toHaveCount(2);
});

test('preset and theme set the miniature, and disabled options are disabled', function (): void {
    $dom = themePreviewDom('label="Style preset"', [
        ['value' => 'strata', 'label' => 'Strata'],
        ['value' => 'ledger', 'label' => 'Ledger', 'preset' => 'ledger', 'theme' => 'dark'],
        ['value' => 'harbor', 'label' => 'Harbor', 'preset' => 'harbor', 'disabled' => true],
        ['label' => 'No value'],
    ]);

    expect($dom->query('//label[@data-part="card"]'))->toHaveCount(3)
        ->and($dom->query('//*[@role="radiogroup"]')->item(0)->getAttribute('aria-label'))->toBe('Style preset')
        ->and($dom->query('//label[@data-value="strata"]//*[@data-part="miniature"]')->item(0)->hasAttribute('data-preset'))->toBeFalse()
        ->and($dom->query('//label[@data-value="strata"]//*[@data-part="miniature"]')->item(0)->getAttribute('data-theme'))->toBe('light')
        ->and($dom->query('//label[@data-value="ledger"]//*[@data-part="miniature"]')->item(0)->getAttribute('data-preset'))->toBe('ledger')
        ->and($dom->query('//label[@data-value="ledger"]//*[@data-part="miniature"]')->item(0)->getAttribute('data-theme'))->toBe('dark')
        ->and($dom->query('//input[@value="harbor"][@disabled]'))->toHaveCount(1)
        ->and($dom->query('//label[@data-value="harbor"][@data-disabled]'))->toHaveCount(1);
});

test('wire:model lands on every radio, which share its name', function (): void {
    $dom    = themePreviewDom('wire:model.live="theme" class="mt-4"');
    $radios = $dom->query('//input[@type="radio"]');

    expect($radios)->toHaveCount(4)
        ->and(array_unique(array_map(static fn (DOMElement $radio): string => $radio->getAttribute('name'), iterator_to_array($radios))))->toBe(['theme'])
        ->and($dom->query('//input[@*[name()="wire:model.live"]="theme"]'))->toHaveCount(4)
        ->and($dom->query('//div[@role="radiogroup"]')->item(0)->getAttribute('class'))->toContain('mt-4')
        ->and($dom->query('//div[@role="radiogroup"][@*[name()="wire:model.live"]]'))->toHaveCount(0);
});

test('without wire:model or name the radios share a generated name', function (): void {
    $names = array_map(
        static fn (DOMElement $radio): string => $radio->getAttribute('name'),
        iterator_to_array(themePreviewDom()->query('//input[@type="radio"]')),
    );

    expect(array_unique($names))->toHaveCount(1)
        ->and($names[0])->toStartWith('apui-theme-preview-');
});

test('the bound property is checked in a Livewire component', function (): void {
    $component = Livewire::test(new class extends Component {
        public string $theme = 'high-contrast';

        public function render(): string
        {
            return <<<'BLADE'
                <div><x-artisanpack-theme-preview wire:model.live="theme" :options="[ [ 'value' => 'light', 'label' => 'Light' ], [ 'value' => 'high-contrast', 'label' => 'High contrast' ] ]" /></div>
                BLADE;
        }
    });

    expect(designSystemDom($component->html())->query('//input[@checked]')->item(0)->getAttribute('value'))->toBe('high-contrast');

    $component->set('theme', 'light');

    expect(designSystemDom($component->html())->query('//input[@checked]')->item(0)->getAttribute('value'))->toBe('light');
});

test('cards() normalizes the options', function (): void {
    $cards = (new ThemePreview(options: [['value' => 'system', 'label' => 'Auto'], ['value' => 7], 'nope']))->cards();

    expect($cards)->toBe([
        ['value' => 'system', 'label' => 'Auto', 'theme' => 'system', 'preset' => null, 'disabled' => false],
        ['value' => '7', 'label' => '7', 'theme' => 'light', 'preset' => null, 'disabled' => false],
    ]);
});

test('it matches its manifest', function (): void {
    expect('ThemePreview')->toMatchManifest();
});
