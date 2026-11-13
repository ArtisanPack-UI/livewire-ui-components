<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\ChoicesOffline;
use ArtisanPack\LivewireUiComponents\View\Components\Combobox;
use Illuminate\Support\Facades\Blade;

/*
 * choices-offline (#145) is a deprecated alias of combobox: multiple unless
 * `single` is set, filtering on the client.
 */

function legacyChoicesOfflineHtml(string $attributes = '', array $data = []): string
{
    return Blade::render(
        '<x-artisanpack-choices-offline id="cities" label="Cities" :portal="false" :options="$options" '.$attributes.' />',
        ['options' => [['id' => 'chi', 'name' => 'Chicago'], ['id' => 'nyc', 'name' => 'New York']], ...$data],
    );
}

test('extends combobox', function (): void {
    expect(is_subclass_of(ChoicesOffline::class, Combobox::class))->toBeTrue();
});

test('renders the combobox markup, multiple by default', function (): void {
    $dom  = designSystemDom(legacyChoicesOfflineHtml());
    $root = $dom->query('//div[contains(@class, "apui-combobox")]')->item(0);

    expect($root)->not->toBeNull()
        ->and($root->getAttribute('id'))->toBe('cities')
        ->and($root->hasAttribute('data-multiple'))->toBeTrue()
        ->and($dom->query('//*[@data-part="item"][@data-value="chi"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="item"][@data-value="nyc"]//*[@data-part="item-text"]')->item(0)->textContent)->toContain('New York');
});

test('single turns multiple off', function (): void {
    $root = designSystemDom(legacyChoicesOfflineHtml('single'))->query('//div[@data-part="root"]')->item(0);

    expect($root->hasAttribute('data-multiple'))->toBeFalse();
});

test('searchable is ignored: the options filter on the client, not the server', function (): void {
    $html = legacyChoicesOfflineHtml('searchable search="new"');
    $dom  = designSystemDom($html);

    expect($dom->query('//*[@data-part="item"][@data-value="chi"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//*[@data-part="item"][@data-value="nyc"]')->item(0)->hasAttribute('hidden'))->toBeFalse()
        ->and($dom->query('//*[@data-part="spinner"]'))->toHaveCount(0)
        ->and($html)->not->toContain('x-on:search-change');
});

test('maps option-value, option-label and option-sub-label onto the options', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-choices-offline id="cities" :portal="false" :options="$options" option-value="code" option-label="city" option-sub-label="country" />',
        ['options' => [['code' => 'par', 'city' => 'Paris', 'country' => 'France']]],
    ));

    expect($dom->query('//*[@data-part="item"][@data-value="par"]'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="item-description"]')->item(0)->textContent))->toBe('France');
});

test('maps icon, no-result-text, clearable, hint-class, error-class and debounce', function (): void {
    $html = legacyChoicesOfflineHtml('icon="search" no-result-text="No city." clearable hint="Pick some." hint-class="hint-x" error="Required." error-class="error-x" search="zzz" search-model="citySearch" debounce="1s"');
    $dom  = designSystemDom($html);

    expect($dom->query('//div[@data-part="box"]/*[@data-part="icon"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="clear-trigger"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="hint"][@class="hint-x"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="error"][@class="error-x"]'))->toHaveCount(1)
        ->and($html)->toContain('No city.')
        ->toContain('x-on:search-change.debounce.1000ms=');
});

test('passes disabled, required and placeholder to the field', function (): void {
    $input = designSystemDom(legacyChoicesOfflineHtml('disabled required placeholder="Find a city"'))->query('//input[@data-part="control"]')->item(0);

    expect($input->hasAttribute('disabled'))->toBeTrue()
        ->and($input->hasAttribute('required'))->toBeTrue()
        ->and($input->getAttribute('placeholder'))->toBe('Find a city');
});

test('does not leak the removed 2.x props as attributes', function (): void {
    $html = legacyChoicesOfflineHtml('icon-right="o-x" inline prefix="$" suffix="kg" searchable compact compact-text="picked" allow-all allow-all-text="All" remove-all-text="None" :min-chars="3" option-avatar="photo" values-as-string height="max-h-10" omit-error first-error-only hint-class="h" error-class="e" debounce="100ms"');
    $root = designSystemDom($html)->query('//div[@data-part="root"]')->item(0);

    foreach (['icon-right', 'inline', 'prefix', 'suffix', 'searchable', 'compact', 'compact-text', 'allow-all', 'allow-all-text', 'remove-all-text', 'min-chars', 'option-avatar', 'values-as-string', 'height', 'omit-error', 'first-error-only', 'hint-class', 'error-class', 'debounce'] as $attribute) {
        expect($root->hasAttribute($attribute))->toBeFalse("{$attribute} leaked onto the root");
    }

    expect($html)->not->toContain('max-h-10');
});

test('readonly is consumed rather than leaking onto the root', function (): void {
    $html = Blade::render('<x-artisanpack-choices-offline readonly :portal="false" :options="[ [ \'id\' => 1, \'name\' => \'One\' ] ]" />');

    expect(designSystemDom($html)->query('//div[contains(@class, "apui-combobox")]')->item(0)->hasAttribute('readonly'))->toBeFalse();
});
