<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\View\Components\Checkbox;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * Checkbox (#47): the design-system markup contract, data-state, the native
 * indeterminate property, ids and the error lookup. The manifest parity check
 * is in tests/Feature/DesignSystemParityTest.php.
 */

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render('<x-artisanpack-checkbox id="offers" label="Email me about new offers" description="At most once a day." error="Required to continue." />');
    $dom  = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="checkbox"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    $root    = $dom->query('//div[contains(@class, "apui-checkbox")]')->item(0);
    $control = $dom->query('//input')->item(0);

    expect($parts)->toBe(['box', 'control', 'indicator', 'label', 'description', 'error'])
        ->and($root->getAttribute('data-state'))->toBe('unchecked')
        ->and($root->hasAttribute('data-invalid'))->toBeTrue()
        ->and($control->getAttribute('type'))->toBe('checkbox')
        ->and($control->getAttribute('id'))->toBe('offers')
        ->and($control->getAttribute('aria-invalid'))->toBe('true')
        ->and($control->getAttribute('aria-describedby'))->toBe('offers-description offers-error')
        ->and($html)->toContain('<label data-scope="checkbox" data-part="label" for="offers">Email me about new offers</label>')
        ->and($html)->toContain('<span data-scope="checkbox" data-part="indicator" aria-hidden="true">'.IconRegistry::svg('check', ['data-icon' => 'check']).IconRegistry::svg('minus', ['data-icon' => 'minus']).'</span>')
        ->and($html)->toContain('<p data-scope="checkbox" data-part="error" id="offers-error">'.IconRegistry::svg('circle-alert').'Required to continue.</p>');
});

test('checked renders data-state and the native attribute', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-checkbox checked label="On" />'));

    expect($dom->query('//div[contains(@class, "apui-checkbox")]')->item(0)->getAttribute('data-state'))->toBe('checked')
        ->and($dom->query('//input')->item(0)->hasAttribute('checked'))->toBeTrue();
});

test('indeterminate wins over checked', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-checkbox checked indeterminate label="Select all" />'));

    expect($dom->query('//div[contains(@class, "apui-checkbox")]')->item(0)->getAttribute('data-state'))->toBe('indeterminate');
});

test('the indicator holds the check then the minus, in every state', function (string $props): void {
    $glyphs = [];
    foreach (designSystemDom(Blade::render('<x-artisanpack-checkbox '.$props.' label="All" />'))->query('//*[@data-part="indicator"]/svg') as $svg) {
        $glyphs[] = $svg->getAttribute('data-icon');
    }

    expect($glyphs)->toBe(['check', 'minus']);
})->with(['unchecked' => '', 'checked' => 'checked', 'indeterminate' => 'indeterminate']);

test('Alpine keeps the root data-state in step on change', function (): void {
    $control = designSystemDom(Blade::render('<x-artisanpack-checkbox label="All" />'))->query('//input')->item(0);

    expect($control->getAttribute('x-on:change'))
        ->toBe("\$el.closest('.apui-checkbox').dataset.state = \$el.indeterminate ? 'indeterminate' : (\$el.checked ? 'checked' : 'unchecked')");
});

test('Alpine sets the native indeterminate property from data-state and follows morphs', function (): void {
    $control = designSystemDom(Blade::render('<x-artisanpack-checkbox indeterminate label="All" />'))->query('//input')->item(0);
    $init    = $control->getAttribute('x-init');

    expect($control->hasAttribute('x-data'))->toBeTrue()
        ->and($control->hasAttribute('x-effect'))->toBeFalse()
        ->and($init)->toContain("\$el.indeterminate = 'indeterminate' === root.dataset.state")
        ->and($init)->toContain('sync();')
        ->and($init)->toContain("new MutationObserver(sync).observe(root, { attributes: true, attributeFilter: ['data-state'] })");
});

test('a server change to indeterminate only changes data-state, which the observer reads', function (): void {
    $on  = designSystemDom(Blade::render('<x-artisanpack-checkbox indeterminate label="All" />'));
    $off = designSystemDom(Blade::render('<x-artisanpack-checkbox label="All" />'));

    // The input's script is identical either way, so a morph only patches data-state.
    expect($on->query('//input')->item(0)->getAttribute('x-init'))->toBe($off->query('//input')->item(0)->getAttribute('x-init'))
        ->and($on->query('//div[contains(@class, "apui-checkbox")]')->item(0)->getAttribute('data-state'))->toBe('indeterminate')
        ->and($off->query('//div[contains(@class, "apui-checkbox")]')->item(0)->getAttribute('data-state'))->toBe('unchecked');
});

test('required sets the native attribute without a required mark', function (): void {
    $html = Blade::render('<x-artisanpack-checkbox required label="I agree" />');

    expect(designSystemDom($html)->query('//input')->item(0)->hasAttribute('required'))->toBeTrue()
        ->and($html)->not->toContain('data-part="required"');
});

test('disabled sets the control and the root', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-checkbox disabled label="Managed" />'));

    expect($dom->query('//input')->item(0)->hasAttribute('disabled'))->toBeTrue()
        ->and($dom->query('//div[contains(@class, "apui-checkbox")]')->item(0)->hasAttribute('data-disabled'))->toBeTrue();
});

test('class lands on the root and every other attribute on the control', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-checkbox class="mt-2" wire:model="terms" name="terms" value="1" label="Terms" />'));
    $root    = $dom->query('//div[contains(@class, "apui-checkbox")]')->item(0);
    $control = $dom->query('//input')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-checkbox mt-2')
        ->and($root->hasAttribute('wire:model'))->toBeFalse()
        ->and($control->getAttribute('wire:model'))->toBe('terms')
        ->and($control->getAttribute('name'))->toBe('terms')
        ->and($control->getAttribute('value'))->toBe('1');
});

test('the id derives from wire:model, plus the value for array bindings', function (): void {
    $single = designSystemDom(Blade::render('<x-artisanpack-checkbox wire:model="newsletter" label="News" />'));
    $admin  = designSystemDom(Blade::render('<x-artisanpack-checkbox wire:model="roles" value="admin" label="Admin" />'));
    $editor = designSystemDom(Blade::render('<x-artisanpack-checkbox wire:model="roles" value="editor" label="Editor" />'));

    expect($single->query('//input')->item(0)->getAttribute('id'))->toBe('apui-checkbox-newsletter-'.substr(md5('newsletter'), 0, 8))
        ->and($single->query('//label')->item(0)->getAttribute('for'))->toBe($single->query('//input')->item(0)->getAttribute('id'))
        ->and($admin->query('//input')->item(0)->getAttribute('id'))->toBe('apui-checkbox-roles-admin-'.substr(md5("roles\0admin"), 0, 8))
        ->and($editor->query('//input')->item(0)->getAttribute('id'))->toBe('apui-checkbox-roles-editor-'.substr(md5("roles\0editor"), 0, 8));
});

test('values that slug alike still get different ids', function (): void {
    $plus  = designSystemDom(Blade::render('<x-artisanpack-checkbox wire:model="grades" value="A+" label="A plus" />'))->query('//input')->item(0)->getAttribute('id');
    $plain = designSystemDom(Blade::render('<x-artisanpack-checkbox wire:model="grades" value="A" label="A" />'))->query('//input')->item(0)->getAttribute('id');

    expect($plus)->not->toBe($plain);
});

test('without id or wire:model the id is a stable hash', function (): void {
    $first  = designSystemDom(Blade::render('<x-artisanpack-checkbox label="A" />'))->query('//input')->item(0)->getAttribute('id');
    $second = designSystemDom(Blade::render('<x-artisanpack-checkbox label="A" />'))->query('//input')->item(0)->getAttribute('id');

    expect($first)->toMatch('/^apui-checkbox-[0-9a-f]{32}$/')->and($second)->toBe($first);
});

test('the error comes from $errors for the wire:model name', function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag(['terms' => 'Accept the terms.'])));

    $dom = designSystemDom(Blade::render('<x-artisanpack-checkbox wire:model="terms" label="Terms" />'));

    expect($dom->query('//p[@data-part="error"]')->item(0)->textContent)->toBe('Accept the terms.')
        ->and($dom->query('//input')->item(0)->getAttribute('aria-describedby'))->toBe('apui-checkbox-terms-'.substr(md5('terms'), 0, 8).'-error');
});

test('label and description slots override the props', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-checkbox id="t" label="Plain">
            <x-slot:label>I agree to the <a href="/terms">terms</a></x-slot:label>
            <x-slot:description><em>Required</em></x-slot:description>
        </x-artisanpack-checkbox>
        BLADE);

    expect($html)->toContain('<label data-scope="checkbox" data-part="label" for="t">I agree to the <a href="/terms">terms</a></label>')
        ->toContain('<p data-scope="checkbox" data-part="description" id="t-description"><em>Required</em></p>');
});

test('the constructor declares only the manifest props', function (): void {
    $parameters = array_map(
        static fn (ReflectionParameter $parameter): string => $parameter->getName(),
        (new ReflectionClass(Checkbox::class))->getConstructor()->getParameters(),
    );

    expect($parameters)->toBe(['checked', 'indeterminate', 'label', 'description', 'error', 'invalid', 'disabled', 'required', 'errorField', 'classes']);
});
