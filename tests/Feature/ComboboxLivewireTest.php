<?php

declare(strict_types=1);

use Livewire\Component;
use Livewire\Livewire;

/*
 * Combobox (#54) inside a Livewire component: wire:model entangles the value
 * (single and multiple), the server renders the bound values and the
 * search-model text, and a re-render keeps every id (D-015).
 */

class ComboboxLivewireTestComponent extends Component
{
    public ?string $ownerId = 'grace';

    public array $tags = ['Garden', 'Rooftop'];

    public string $ownerSearch = 'gra';

    public function people(): array
    {
        $people = [['value' => 'ada', 'label' => 'Ada Lovelace'], ['value' => 'grace', 'label' => 'Grace Hopper']];

        return array_values(array_filter($people, fn (array $person): bool => str_contains(strtolower($person['label']), strtolower($this->ownerSearch))));
    }

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-combobox wire:model.live="ownerId" search-model="ownerSearch" remote :label="__( 'Owner' )" :options="$this->people()" :portal="false" />
                <x-artisanpack-combobox wire:model="tags" name="tags[]" multiple allow-create :label="__( 'Tags' )" :options="[ 'Garden', 'Pool' ]" :portal="false" />
            </div>
            BLADE;
    }
}

test('entangles the value and renders the bound values', function (): void {
    $html = Livewire::test(ComboboxLivewireTestComponent::class)->html();
    $dom  = designSystemDom($html);

    expect($html)->toContain('$wire.entangle(&#039;ownerId&#039;).live')
        ->toContain('$wire.entangle(&#039;tags&#039;)')
        ->and($dom->query('//input[@data-part="control"]')->item(0)->getAttribute('value'))->toBe('gra')
        ->and($dom->query('//*[@data-part="chip"][@data-value]')->length)->toBe(2)
        // name submits the bound values next to wire:model, which still entangles.
        ->and(array_map(fn ($input) => $input->getAttribute('value'), iterator_to_array($dom->query('//input[@type="hidden"][@name="tags[]"][@value]'))))->toBe(['Garden', 'Rooftop'])
        // A created tag the options don't list still renders, labelled by its value.
        ->and($dom->query('//*[@data-part="item"][@data-value="Rooftop"]')->length)->toBe(1)
        ->and($dom->query('//*[@data-part="item"][@data-value="grace"]')->item(0)->getAttribute('aria-selected'))->toBe('true');
});

test('keeps ids across a re-render with new options', function (): void {
    $component = Livewire::test(ComboboxLivewireTestComponent::class);
    preg_match_all('/\sid="(apui-combobox[^"]*)"/', $component->html(), $before);

    $component->set('ownerSearch', 'ada');
    preg_match_all('/\sid="(apui-combobox[^"]*)"/', $component->html(), $after);

    expect($before[1])->not->toBeEmpty()
        ->and($after[1])->toBe($before[1])
        ->and($component->html())->toContain('data-value="ada"');
});
