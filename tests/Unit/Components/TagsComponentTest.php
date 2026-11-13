<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Combobox;
use ArtisanPack\LivewireUiComponents\View\Components\Tags;
use Illuminate\Support\Facades\Blade;

/*
 * tags (#145) is a deprecated alias of combobox with multiple and
 * allow-create: a tag input picks several values and creates new ones.
 */

function legacyTagsHtml(string $attributes = '', array $data = []): string
{
    return Blade::render(
        '<x-artisanpack-tags id="labels" label="Labels" :portal="false" :options="$options" '.$attributes.' />',
        ['options' => [['id' => 'bug', 'name' => 'Bug'], ['id' => 'docs', 'name' => 'Docs']], ...$data],
    );
}

test('extends combobox', function (): void {
    expect(is_subclass_of(Tags::class, Combobox::class))->toBeTrue();
});

test('renders the combobox markup with multiple and allow-create', function (): void {
    $dom  = designSystemDom(legacyTagsHtml('search="Rooftop" :value="[\'bug\', \'Garden\']"'));
    $root = $dom->query('//div[contains(@class, "apui-combobox")]')->item(0);

    expect($root)->not->toBeNull()
        ->and($root->getAttribute('id'))->toBe('labels')
        ->and($root->hasAttribute('data-multiple'))->toBeTrue()
        ->and(array_map(fn ($chip) => $chip->getAttribute('data-value'), iterator_to_array($dom->query('//*[@data-part="chip"][@data-value]'))))->toBe(['bug', 'Garden'])
        ->and(trim($dom->query('//*[@data-part="chip"][@data-value="bug"]')->item(0)->textContent))->toBe('Bug')
        ->and($dom->query('//*[@data-create]')->item(0)->getAttribute('data-value'))->toBe('Rooftop');
});

test('allow-custom-tags false turns allow-create off', function (): void {
    expect(designSystemDom(legacyTagsHtml(':allow-custom-tags="false"'))->query('//*[@data-create]'))->toHaveCount(0);
});

test('custom-tags-text becomes the create label', function (): void {
    $html = legacyTagsHtml('custom-tags-text="Add {value}" search="Rooftop"');

    expect(trim(designSystemDom($html)->query('//*[@data-create]/*[@data-part="item-text"]')->item(0)->textContent))->toBe('Add Rooftop');
});

test('single turns multiple off', function (): void {
    expect(designSystemDom(legacyTagsHtml('single'))->query('//div[@data-part="root"][@data-multiple]'))->toHaveCount(0);
});

test('works without options', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-tags id="free" label="Tags" :portal="false" :value="[\'one\']" />'));

    expect($dom->query('//*[@data-part="chip"][@data-value="one"]'))->toHaveCount(1);
});

test('maps option keys, icon, no-result-text, clearable, hint-class and error-class', function (): void {
    $html = Blade::render(
        '<x-artisanpack-tags id="labels" :portal="false" :options="$options" option-value="slug" option-label="title" option-sub-label="note" icon="tag" no-result-text="No tags." clearable hint="Add some." hint-class="hint-x" error="Required." error-class="error-x" />',
        ['options' => [['slug' => 'ux', 'title' => 'UX', 'note' => 'Design work']]],
    );
    $dom = designSystemDom($html);

    expect($dom->query('//*[@data-part="item"][@data-value="ux"]'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="item-description"]')->item(0)->textContent))->toBe('Design work')
        ->and($dom->query('//div[@data-part="box"]/*[@data-part="icon"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="clear-trigger"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="hint"][@class="hint-x"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="error"][@class="error-x"]'))->toHaveCount(1)
        ->and($html)->toContain('No tags.');
});

test('searchable is ignored, without the 2.x exception for missing options', function (): void {
    $html = Blade::render('<x-artisanpack-tags id="free" :portal="false" searchable />');

    expect(designSystemDom($html)->query('//div[@data-part="root"]')->item(0)->hasAttribute('searchable'))->toBeFalse()
        ->and($html)->not->toContain('x-on:search-change');
});

test('does not leak the removed 2.x props as attributes', function (): void {
    $html = legacyTagsHtml('icon-right="o-x" inline prefix="$" suffix="kg" searchable debounce="100ms" :min-chars="2" search-function="find" option-avatar="photo" height="max-h-10" omit-error first-error-only hint-class="h" error-class="e" :allow-custom-tags="true" custom-tags-text="Add"');
    $root = designSystemDom($html)->query('//div[@data-part="root"]')->item(0);

    foreach (['icon-right', 'inline', 'prefix', 'suffix', 'searchable', 'debounce', 'min-chars', 'search-function', 'option-avatar', 'height', 'omit-error', 'first-error-only', 'hint-class', 'error-class', 'allow-custom-tags', 'custom-tags-text'] as $attribute) {
        expect($root->hasAttribute($attribute))->toBeFalse("{$attribute} leaked onto the root");
    }

    expect($html)->not->toContain('max-h-10');
});
