<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Combobox (#54), with Tags as its multiple + allowCreate mode: the
 * design-system markup contract (D-018, D-019). The manifest parity check is
 * in tests/Feature/DesignSystemParityTest.php.
 */

function comboboxOptions(): array
{
    return [
        ['value' => 'lakeview', 'label' => 'Lakeview Lofts', 'description' => 'United States, Chicago'],
        ['value' => 'lakeside', 'label' => 'Lakeside Commons', 'color' => 'success'],
        ['value' => 'ci', 'label' => "Côte d'Ivoire"],
    ];
}

function comboboxHtml(string $attributes = '', array $data = []): string
{
    return Blade::render(
        '<x-artisanpack-combobox id="cb" label="Property" :portal="false" :options="$options" '.$attributes.' />',
        ['options' => comboboxOptions(), ...$data],
    );
}

/**
 * The combobox's parts in document order, as `part#id` when they have one.
 *
 * @return list<string>
 */
function comboboxParts(string $html): array
{
    $parts = [];
    foreach (designSystemDom($html)->query('//*[@data-scope="combobox"]') as $element) {
        $parts[] = $element->getAttribute('data-part').($element->hasAttribute('id') ? '#'.$element->getAttribute('id') : '');
    }

    return $parts;
}

test('renders the field anatomy with stable ids and the listbox', function (): void {
    expect(comboboxParts(comboboxHtml('hint="Type a name." leading-icon="search" required clearable')))->toBe([
        'root#cb',
        'label#cb-label',
        'required',
        'box#cb-box',
        'icon',
        'control#cb-control',
        'clear-trigger#cb-clear-trigger',
        'trigger#cb-trigger',
        'hint#cb-hint',
        'positioner#cb-positioner',
        'content#cb-content',
        'item#combobox:cb:option:lakeview',
        'item-text',
        'item-description',
        'item#combobox:cb:option:lakeside',
        'item-text',
        'item#combobox:cb:option:ci',
        'item-text',
        'empty#cb-empty',
        'status#cb-status',
    ]);
});

test('wires ARIA between the label, input, hint and listbox', function (): void {
    $dom   = designSystemDom(comboboxHtml('hint="Type a name." error="Choose one."'));
    $input = $dom->query('//input[@data-part="control"]')->item(0);

    expect($input->getAttribute('role'))->toBe('combobox')
        ->and($input->getAttribute('aria-controls'))->toBe('cb-content')
        ->and($input->getAttribute('aria-describedby'))->toBe('cb-hint cb-error')
        ->and($input->getAttribute('aria-invalid'))->toBe('true')
        ->and($dom->query('//label')->item(0)->getAttribute('for'))->toBe('cb-control')
        ->and($dom->query('//*[@data-part="content"]')->item(0)->getAttribute('role'))->toBe('listbox')
        ->and($dom->query('//*[@data-part="content"]')->item(0)->getAttribute('aria-labelledby'))->toBe('cb-label');
});

test('mounts apuiCombobox on the root with the value and translated labels', function (): void {
    $html = comboboxHtml('value="lakeside" x-on:create="added = true"');

    expect($html)->toContain('x-data="apuiCombobox(Object.assign(')
        ->toContain('x-on:create="added = true"')
        ->toContain('Show options')
        ->toContain('Remove {label}')
        ->and(designSystemDom($html)->query('//input')->item(0)->getAttribute('value'))->toBe('Lakeside Commons');
});

test('marks the search text in labels, ignoring case and accents, and hides non-matches', function (): void {
    $dom = designSystemDom(comboboxHtml('search="cote"'));

    expect($dom->query('//mark[@data-part="mark"]')->item(0)->textContent)->toBe('Côte')
        ->and($dom->query('//*[@data-value="lakeview"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//*[@data-value="ci"]')->item(0)->hasAttribute('hidden'))->toBeFalse()
        ->and($dom->query('//input')->item(0)->getAttribute('value'))->toBe('cote');
});

test('remote shows every option the server sends', function (): void {
    $dom = designSystemDom(comboboxHtml('search="zz" remote'));

    expect($dom->query('//*[@data-part="item"][@hidden]')->length)->toBe(0)
        ->and($dom->query('//*[@data-part="spinner"]')->item(0)->hasAttribute('hidden'))->toBeTrue();
});

test('tags: renders chips with remove buttons, item indicators and the create option', function (): void {
    $html = comboboxHtml('multiple allow-create search="Rooftop" :value="[\'lakeside\', \'Garden\']"');
    $dom  = designSystemDom($html);

    expect(designSystemDom($html)->query('//*[@data-part="root"]')->item(0)->hasAttribute('data-multiple'))->toBeTrue()
        ->and(array_map(fn ($chip) => $chip->getAttribute('data-value'), iterator_to_array($dom->query('//*[@data-part="chip"][@data-value]'))))->toBe(['lakeside', 'Garden'])
        ->and($dom->query('//*[@data-value="lakeside"][@data-part="chip"]')->item(0)->getAttribute('data-color'))->toBe('success')
        ->and($dom->query('//*[@data-part="chip-remove"]')->item(0)->getAttribute('aria-label'))->toBe('Remove Lakeside Commons')
        ->and($dom->query('//*[@data-part="item-indicator"]')->length)->toBe(4)
        ->and($dom->query('//*[@data-create]')->item(0)->getAttribute('data-value'))->toBe('Rooftop')
        ->and(trim($dom->query('//*[@data-create]/*[@data-part="item-text"]')->item(0)->textContent))->toBe('Create “Rooftop”')
        // A chosen value that isn't an option is listed, labelled by itself.
        ->and($dom->query('//*[@data-value="Garden"][@data-part="item"]')->length)->toBe(1);
});

test('does not offer to create text that names an option', function (): void {
    $dom = designSystemDom(comboboxHtml('multiple allow-create search="LAKESIDE commons"'));

    expect($dom->query('//*[@data-create]')->item(0)->hasAttribute('hidden'))->toBeTrue();
});

test('disables the other options once max is reached', function (): void {
    $dom = designSystemDom(comboboxHtml('multiple :max="1" :value="[\'ci\']"'));

    expect($dom->query('//*[@data-value="lakeview"]')->item(0)->getAttribute('aria-disabled'))->toBe('true')
        ->and($dom->query('//*[@data-value="ci"][@data-part="item"]')->item(0)->hasAttribute('aria-disabled'))->toBeFalse();
});

test('shows the empty text with the search, the loading text and the empty slot', function (): void {
    expect(comboboxHtml('search="zzz"'))->toContain('No matches for “zzz”.');
    expect(comboboxHtml('search="zzz" loading'))->toContain('Loading…')->toContain('aria-busy="true"');

    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-combobox id="cb" label="Owner" :portal="false" :options="[]">
            <x-slot:empty>Add a <a href="/people/create">person</a>.</x-slot:empty>
        </x-artisanpack-combobox>
        BLADE);
    expect($html)->toContain('<a href="/people/create">person</a>');
});

test('sets search-model after the debounce, with the spinner on', function (): void {
    $html = comboboxHtml('search-model="ownerSearch" :debounce="400" remote');

    expect($html)->toContain('x-on:search-change.debounce.400ms=')
        ->toContain('$wire.$set(&#039;ownerSearch&#039;, $event.detail.search)');
});

test('renders the item scoped slot', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-combobox id="cb" label="Property" :portal="false" :options="$options">
            @scope('item', $option)
                <strong>{{ $option['label'] }}</strong>
            @endscope
        </x-artisanpack-combobox>
        BLADE, ['options' => comboboxOptions()]);

    expect($html)->toContain('<strong>Lakeview Lofts</strong>');
});

test('teleports the listbox by default', function (): void {
    $html = Blade::render('<x-artisanpack-combobox id="cb" label="Property" :options="[]" />');

    expect($html)->toContain('<template x-teleport="body">');
});

test('submits the value with a native form through hidden inputs', function (): void {
    $single = designSystemDom(comboboxHtml('name="property" value="lakeside"'));
    $inputs = $single->query('//input[@type="hidden"][@value]');

    expect($inputs->length)->toBe(1)
        ->and($inputs->item(0)->getAttribute('name'))->toBe('property')
        ->and($inputs->item(0)->getAttribute('value'))->toBe('lakeside')
        // The text input holds a label, so it is never submitted.
        ->and($single->query('//input[@data-part="control"]')->item(0)->hasAttribute('name'))->toBeFalse();

    $multiple = designSystemDom(comboboxHtml('name="tags[]" multiple :value="[\'lakeside\', \'Garden\']"'));
    $values   = array_map(
        fn ($input): array => [$input->getAttribute('name'), $input->getAttribute('value')],
        iterator_to_array($multiple->query('//input[@type="hidden"][@value]')),
    );

    expect($values)->toBe([['tags[]', 'lakeside'], ['tags[]', 'Garden']]);
});

test('renders no hidden input while the value is empty, or without a name', function (): void {
    expect(designSystemDom(comboboxHtml('name="property"'))->query('//input[@type="hidden"][@value]')->length)->toBe(0)
        ->and(comboboxHtml('value="lakeside"'))->not->toContain('type="hidden"');
});

test('hands integer option values to the client as integers', function (): void {
    $html = Blade::render(
        '<x-artisanpack-combobox id="floor" label="Floor" :portal="false" :options="$options" :value="3" />',
        ['options' => [['value' => 1, 'label' => 'First floor'], ['value' => 3, 'label' => 'Third floor']]],
    );
    $dom = designSystemDom($html);

    expect($dom->query('//*[@data-value="3"][@data-part="item"]')->item(0)->getAttribute('x-apui:item'))->toContain('\\u0022value\\u0022:3,')
        ->and($dom->query('//*[@data-value="3"][@data-part="item"]')->item(0)->getAttribute('aria-selected'))->toBe('true')
        ->and($dom->query('//input[@data-part="control"]')->item(0)->getAttribute('value'))->toBe('Third floor');
});
