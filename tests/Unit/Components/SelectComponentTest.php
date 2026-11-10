<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\View\Components\Select;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * Select (#46): the design-system markup contract, options, the placeholder,
 * attribute routing and the error lookup. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

/**
 * The options' value, label, selected and disabled state.
 *
 * @param  string  $html  The rendered select.
 *
 * @return list<array{value: string, label: string, selected: bool, disabled: bool}> The options.
 */
function selectOptions(string $html): array
{
    $options = [];
    foreach (designSystemDom($html)->query('//select/option') as $option) {
        $options[] = [
            'value'    => $option->getAttribute('value'),
            'label'    => $option->textContent,
            'selected' => $option->hasAttribute('selected'),
            'disabled' => $option->hasAttribute('disabled'),
        ];
    }

    return $options;
}

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render(
        '<x-artisanpack-select id="country" label="Country" :options="$options" required hint="Where the account is registered." error="Choose a country." leading-icon="user" />',
        ['options' => [['value' => 'us', 'label' => 'United States'], ['value' => 'ca', 'label' => 'Canada']]],
    );
    $dom = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="select"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    $control = $dom->query('//select')->item(0);

    expect($parts)->toBe(['label', 'required', 'box', 'icon', 'control', 'indicator', 'hint', 'error'])
        ->and($html)->toContain('<span data-scope="select" data-part="indicator" aria-hidden="true">'.IconRegistry::svg('chevron-down').'</span>')
        ->and($html)->toContain('<p data-scope="select" data-part="error" id="country-error">'.IconRegistry::svg('circle-alert').'Choose a country.</p>')
        ->and($control->getAttribute('id'))->toBe('country')
        ->and($control->hasAttribute('required'))->toBeTrue()
        ->and($control->getAttribute('aria-invalid'))->toBe('true')
        ->and($control->getAttribute('aria-describedby'))->toBe('country-hint country-error')
        ->and($dom->query('//div[contains(@class, "apui-select")]')->item(0)->getAttribute('data-size'))->toBe('md');
});

test('the placeholder comes first, translated by default', function (): void {
    expect(selectOptions(Blade::render('<x-artisanpack-select aria-label="Plan" :options="[\'One\']" />')))->toBe([
        ['value' => '', 'label' => 'Select…', 'selected' => true, 'disabled' => false],
        ['value' => 'One', 'label' => 'One', 'selected' => false, 'disabled' => false],
    ]);
});

test('the placeholder is disabled when required, custom when given and dropped when empty', function (): void {
    expect(selectOptions(Blade::render('<x-artisanpack-select aria-label="Plan" required />'))[0]['disabled'])->toBeTrue()
        ->and(selectOptions(Blade::render('<x-artisanpack-select aria-label="Plan" placeholder="Pick one" />'))[0]['label'])->toBe('Pick one')
        ->and(selectOptions(Blade::render('<x-artisanpack-select aria-label="Plan" placeholder="" :options="[\'One\']" />')))->toHaveCount(1);
});

test('options accept strings, arrays, objects and collections', function (): void {
    $options = collect([
        'Plain',
        ['value' => 2, 'label' => 'Two'],
        (object) ['value' => 'three', 'label' => 'Three', 'disabled' => true],
    ]);

    expect(selectOptions(Blade::render('<x-artisanpack-select aria-label="N" placeholder="" :options="$options" />', ['options' => $options])))->toBe([
        ['value' => 'Plain', 'label' => 'Plain', 'selected' => false, 'disabled' => false],
        ['value' => '2', 'label' => 'Two', 'selected' => false, 'disabled' => false],
        ['value' => 'three', 'label' => 'Three', 'selected' => false, 'disabled' => true],
    ]);
});

test('the value selects its option, compared as a string', function (): void {
    $options = selectOptions(Blade::render(
        '<x-artisanpack-select aria-label="N" :options="$options" :value="2" />',
        ['options' => [['value' => 1, 'label' => 'One'], ['value' => 2, 'label' => 'Two']]],
    ));

    expect(array_column($options, 'selected'))->toBe([false, false, true]);
});

test('an empty value selects the placeholder, even when required', function (): void {
    expect(selectOptions(Blade::render('<x-artisanpack-select aria-label="N" value="" :options="[\'One\']" />'))[0]['selected'])->toBeTrue()
        ->and(selectOptions(Blade::render('<x-artisanpack-select aria-label="N" required :options="[\'One\']" />'))[0])->toBe(['value' => '', 'label' => 'Select…', 'selected' => true, 'disabled' => true])
        ->and(selectOptions(Blade::render('<x-artisanpack-select aria-label="N" value="One" :options="[\'One\']" />'))[0]['selected'])->toBeFalse();
});

test('option children render after the options', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-select aria-label="Plan" placeholder="" :options="['starter']">
            <option value="team">Team</option>
            <option value="enterprise" disabled>Enterprise</option>
        </x-artisanpack-select>
        BLADE);

    expect(array_column(selectOptions($html), 'value'))->toBe(['starter', 'team', 'enterprise']);
});

test('class lands on the root and every other attribute on the select', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-select class="w-64" wire:model.live="country" name="country" label="Country" />'));
    $root    = $dom->query('//div[contains(@class, "apui-select")]')->item(0);
    $control = $dom->query('//select')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-select w-64')
        ->and($root->hasAttribute('wire:model.live'))->toBeFalse()
        ->and($control->getAttribute('wire:model.live'))->toBe('country')
        ->and($control->getAttribute('name'))->toBe('country')
        ->and($control->getAttribute('id'))->toBe('apui-select-country-'.substr(md5('country'), 0, 8))
        ->and($dom->query('//label')->item(0)->getAttribute('for'))->toBe($control->getAttribute('id'));
});

test('the error comes from $errors for the wire:model name or errorField', function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag([
        'country'         => 'Choose a country.',
        'address.country' => 'Choose a shipping country.',
    ])));

    expect(Blade::render('<x-artisanpack-select wire:model="country" label="Country" />'))->toContain('Choose a country.')
        ->and(Blade::render('<x-artisanpack-select wire:model="country" error-field="address.country" label="Country" />'))->toContain('Choose a shipping country.');
});

test('disabled sets the select and the root', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-select disabled label="Region" />'));

    expect($dom->query('//select')->item(0)->hasAttribute('disabled'))->toBeTrue()
        ->and($dom->query('//div[contains(@class, "apui-select")]')->item(0)->hasAttribute('data-disabled'))->toBeTrue();
});

test('the icon slot overrides the leading icon', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-select label="Plan" leading-icon="user">
            <x-slot:icon><svg data-custom="start"></svg></x-slot:icon>
        </x-artisanpack-select>
        BLADE);

    expect($html)->toContain('<span data-scope="select" data-part="icon" aria-hidden="true"><svg data-custom="start"></svg></span>');
});

test('option labels are escaped', function (): void {
    expect(Blade::render('<x-artisanpack-select aria-label="N" :options="[\'<b>x</b>\']" />'))->not->toContain('<b>x</b>');
});

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(Select::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe(['size', 'options', 'label', 'hint', 'error', 'invalid', 'disabled', 'required', 'placeholder', 'value', 'leadingIcon', 'errorField', 'classes']);
});
