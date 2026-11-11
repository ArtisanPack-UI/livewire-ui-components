<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * Toggle (#53): the design-system markup contract, role=switch, data-state,
 * ids and the error lookup. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render('<x-artisanpack-toggle id="dark" label="Dark mode" description="Follows your system setting when off." error="Ask your admin." />');
    $dom  = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="toggle"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    $root    = $dom->query('//div[contains(@class, "apui-toggle")]')->item(0);
    $control = $dom->query('//input')->item(0);

    expect($parts)->toBe(['track', 'control', 'thumb', 'label', 'description', 'error'])
        ->and($root->getAttribute('data-state'))->toBe('unchecked')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-color'))->toBe('primary')
        ->and($root->hasAttribute('data-invalid'))->toBeTrue()
        ->and($control->getAttribute('type'))->toBe('checkbox')
        ->and($control->getAttribute('role'))->toBe('switch')
        ->and($control->getAttribute('aria-describedby'))->toBe('dark-description dark-error')
        ->and($html)->toContain('<label data-scope="toggle" data-part="label" for="dark">Dark mode</label>')
        ->and($html)->toContain('<p data-scope="toggle" data-part="error" id="dark-error">'.IconRegistry::svg('circle-alert').'Ask your admin.</p>');
});

test('checked renders data-state and the native attribute', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-toggle checked label="On" />'));

    expect($dom->query('//div[contains(@class, "apui-toggle")]')->item(0)->getAttribute('data-state'))->toBe('checked')
        ->and($dom->query('//input')->item(0)->hasAttribute('checked'))->toBeTrue();
});

test('size and color render as data attributes', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-toggle size="lg" color="success" label="A" />'))->query('//div')->item(0);

    expect($root->getAttribute('data-size'))->toBe('lg')
        ->and($root->getAttribute('data-color'))->toBe('success');
});

test('Alpine keeps the root data-state in step on change', function (): void {
    $control = designSystemDom(Blade::render('<x-artisanpack-toggle label="A" />'))->query('//input')->item(0);

    expect($control->getAttribute('x-on:change'))
        ->toBe("\$el.closest('.apui-toggle').dataset.state = \$el.checked ? 'checked' : 'unchecked'");
});

test('disabled and required set the control and the root', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-toggle disabled required label="Managed" />'));

    expect($dom->query('//input')->item(0)->hasAttribute('disabled'))->toBeTrue()
        ->and($dom->query('//input')->item(0)->hasAttribute('required'))->toBeTrue()
        ->and($dom->query('//div[contains(@class, "apui-toggle")]')->item(0)->hasAttribute('data-disabled'))->toBeTrue();
});

test('class lands on the root and every other attribute on the control', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-toggle class="mt-2" wire:model.live="darkMode" name="dark" label="Dark mode" />'));
    $root    = $dom->query('//div[contains(@class, "apui-toggle")]')->item(0);
    $control = $dom->query('//input')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-toggle mt-2')
        ->and($root->hasAttribute('wire:model.live'))->toBeFalse()
        ->and($control->getAttribute('wire:model.live'))->toBe('darkMode')
        ->and($control->getAttribute('name'))->toBe('dark')
        ->and($control->getAttribute('id'))->toBe('apui-toggle-darkmode-'.substr(md5('darkMode'), 0, 8));
});

test('the error comes from $errors for the wire:model name', function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag(['notify' => 'Turn this on to continue.'])));

    $dom = designSystemDom(Blade::render('<x-artisanpack-toggle wire:model="notify" label="Notify" />'));

    expect($dom->query('//p[@data-part="error"]')->item(0)->textContent)->toBe('Turn this on to continue.')
        ->and($dom->query('//input')->item(0)->getAttribute('aria-invalid'))->toBe('true');
});

test('classes add to their parts', function (): void {
    $html = Blade::render('<x-artisanpack-toggle :classes="[ \'track\' => \'t\', \'thumb\' => \'k\', \'label\' => \'l\' ]" label="A" />');

    expect($html)->toContain('data-part="track" class="t"')
        ->and($html)->toContain('data-part="thumb" aria-hidden="true" class="k"')
        ->and($html)->toContain('data-part="label" for=');
});
