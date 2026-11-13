<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Choices;
use ArtisanPack\LivewireUiComponents\View\Components\Combobox;
use Illuminate\Support\Facades\Blade;

/*
 * choices (#145) is a deprecated alias of combobox: multiple unless `single`
 * is set, with `searchable` as remote search.
 */

function legacyChoicesHtml(string $attributes = '', array $data = []): string
{
    return Blade::render(
        '<x-artisanpack-choices id="owners" label="Owners" :portal="false" :options="$options" '.$attributes.' />',
        ['options' => [['id' => 1, 'name' => 'Olivia Rhye'], ['id' => 2, 'name' => 'Phoenix Baker']], ...$data],
    );
}

test('extends combobox', function (): void {
    expect(is_subclass_of(Choices::class, Combobox::class))->toBeTrue();
});

test('renders the combobox markup, multiple by default', function (): void {
    $dom  = designSystemDom(legacyChoicesHtml());
    $root = $dom->query('//div[contains(@class, "apui-combobox")]')->item(0);

    expect($root)->not->toBeNull()
        ->and($root->getAttribute('id'))->toBe('owners')
        ->and($root->hasAttribute('data-multiple'))->toBeTrue()
        ->and($dom->query('//input[@role="combobox"]'))->toHaveCount(1)
        ->and(trim($dom->query('//label')->item(0)->textContent))->toBe('Owners');
});

test('single turns multiple off', function (): void {
    $root = designSystemDom(legacyChoicesHtml('single'))->query('//div[@data-part="root"]')->item(0);

    expect($root->hasAttribute('data-multiple'))->toBeFalse();
});

test('maps option-value, option-label and option-sub-label onto the options', function (): void {
    $dom = designSystemDom(Blade::render(
        '<x-artisanpack-choices id="owners" :portal="false" :options="$options" option-value="key" option-label="title" option-sub-label="role" :value="[\'b\']" />',
        ['options' => [['key' => 'a', 'title' => 'Olivia Rhye', 'role' => 'Admin'], ['key' => 'b', 'title' => 'Phoenix Baker']]],
    ));

    expect($dom->query('//*[@data-part="item"][@data-value="a"]'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="item"][@data-value="a"]//*[@data-part="item-description"]')->item(0)->textContent))->toBe('Admin')
        ->and(trim($dom->query('//*[@data-part="chip"][@data-value="b"]')->item(0)->textContent))->toBe('Phoenix Baker');
});

test('maps the default id and name keys', function (): void {
    $dom = designSystemDom(legacyChoicesHtml());

    expect($dom->query('//*[@data-part="item"][@data-value="1"]//*[@data-part="item-text"]')->item(0)->textContent)->toContain('Olivia Rhye');
});

test('maps icon, no-result-text, clearable, hint-class and error-class', function (): void {
    $dom = designSystemDom(legacyChoicesHtml('icon="search" no-result-text="Nobody here." clearable hint="Pick owners." hint-class="hint-x" error="Required." error-class="error-x"'));

    expect($dom->query('//div[@data-part="box"]/*[@data-part="icon"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="clear-trigger"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="hint"][@class="hint-x"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="error"][@class="error-x"]'))->toHaveCount(1)
        ->and(legacyChoicesHtml('no-result-text="Nobody here."'))->toContain('Nobody here.');
});

test('searchable becomes remote and calls search-function after debounce and min-chars', function (): void {
    $html = legacyChoicesHtml('searchable search-function="findOwners" debounce="400ms" :min-chars="2"');

    expect($html)->toContain('x-on:search-change.debounce.400ms=')
        ->toContain('$event.detail.search.length &lt; 2 ? null :')
        ->toContain('$wire.call(&#039;findOwners&#039;, $event.detail.search)')
        ->and(designSystemDom($html)->query('//*[@data-part="spinner"]'))->toHaveCount(1);
});

test('passes the arguments written in search-function', function (): void {
    expect(legacyChoicesHtml('searchable search-function="findOwners(5)"'))
        ->toContain('$wire.call(&#039;findOwners&#039;, $event.detail.search, 5)');
});

test('does not search without searchable', function (): void {
    expect(legacyChoicesHtml())->not->toContain('x-on:search-change');
});

test('reads 2.x debounce strings as milliseconds', function (string|int|null $debounce, int $milliseconds): void {
    expect(Choices::legacyDebounce($debounce))->toBe($milliseconds);
})->with([
    'ms'      => ['300ms', 300],
    'seconds' => ['1s', 1000],
    'integer' => [150, 150],
    'bare'    => ['75', 75],
    'garbage' => ['soon', 250],
    'null'    => [null, 250],
]);

test('passes disabled and required to the field', function (): void {
    $dom = designSystemDom(legacyChoicesHtml('disabled required'));

    expect($dom->query('//input[@data-part="control"][@disabled][@required]'))->toHaveCount(1)
        ->and($dom->query('//div[@data-part="root"][@data-disabled]'))->toHaveCount(1);
});

test('does not leak the removed 2.x props as attributes', function (): void {
    $html = legacyChoicesHtml('icon-right="o-x" inline prefix="$" suffix="kg" compact compact-text="picked" allow-all allow-all-text="All" remove-all-text="None" option-avatar="photo" values-as-string height="max-h-10" omit-error first-error-only lazy-load lazy-load-method="more" lazy-load-modifier="once" :has-more-options="false" hint-class="h" error-class="e"');
    $root = designSystemDom($html)->query('//div[@data-part="root"]')->item(0);

    foreach (['icon-right', 'inline', 'prefix', 'suffix', 'compact', 'compact-text', 'allow-all', 'allow-all-text', 'remove-all-text', 'option-avatar', 'values-as-string', 'height', 'omit-error', 'first-error-only', 'lazy-load', 'lazy-load-method', 'lazy-load-modifier', 'has-more-options', 'hint-class', 'error-class'] as $attribute) {
        expect($root->hasAttribute($attribute))->toBeFalse("{$attribute} leaked onto the root");
    }

    expect($html)->not->toContain('max-h-10')->not->toContain('$wire.call');
});
