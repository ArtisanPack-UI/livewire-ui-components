<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Livewire\Livewire;

/*
 * Tabs (#56) inside Livewire: wire:model sets the selected tab on the
 * server render and entangles it on the client.
 */

class TabsTestSections extends Component
{
    public string $section = 'billing';

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-tabs wire:model="section">
                    <x-artisanpack-tab value="profile" label="Profile">Profile panel</x-artisanpack-tab>
                    <x-artisanpack-tab value="billing" label="Billing">Billing panel</x-artisanpack-tab>
                </x-artisanpack-tabs>
            </div>
            BLADE;
    }
}

test('renders the bound tab selected and entangles it', function (): void {
    $html = Livewire::test(TabsTestSections::class)->html();
    $dom  = designSystemDom($html);

    $billing = $dom->query('//*[@data-part="trigger" and @data-value="billing"]')->item(0);
    expect($billing->getAttribute('aria-selected'))->toBe('true');
    expect($dom->query('//*[contains(@class, "apui-tabs")]')->item(0)->getAttribute('x-data'))->toContain('$wire.entangle(\'section\')');
});

test('keeps part ids across re-renders', function (): void {
    $component = Livewire::test(TabsTestSections::class);
    $ids       = static fn (string $html): array => array_map(
        static fn (DOMElement $el): string => $el->getAttribute('id'),
        iterator_to_array(designSystemDom($html)->query('//*[@data-scope="tabs"][@id]')),
    );
    $before = $ids($component->html());

    expect($ids($component->set('section', 'profile')->html()))->toBe($before);
});

test('nests tabs inside a tab panel', function (): void {
    $html = Blade::render('<x-artisanpack-tabs id="outer"><x-artisanpack-tab value="a" label="A"><x-artisanpack-tabs id="inner"><x-artisanpack-tab value="x" label="X">X</x-artisanpack-tab></x-artisanpack-tabs></x-artisanpack-tab><x-artisanpack-tab value="b" label="B">B</x-artisanpack-tab></x-artisanpack-tabs>');
    $dom  = designSystemDom($html);

    expect($dom->query('//*[@id="outer-list"]/*[@data-part="trigger"]')->length)->toBe(2)
        ->and($dom->query('//*[@id="inner-list"]/*[@data-part="trigger"]')->length)->toBe(1);
});
