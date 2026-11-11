<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\View\Components\Textarea;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * Textarea (#53): the design-system markup contract, the counter and its
 * live region, ids and the error lookup. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render('<x-artisanpack-textarea id="desc" label="Description" required :max-length="500" hint="Shown on the listing page." error="Write at least 40 characters." />');
    $dom  = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="textarea"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    $control = $dom->query('//textarea')->item(0);

    expect($parts)->toBe(['label', 'required', 'box', 'control', 'count', 'hint', 'error'])
        ->and($control->getAttribute('id'))->toBe('desc')
        ->and($control->getAttribute('rows'))->toBe('4')
        ->and($control->getAttribute('maxlength'))->toBe('500')
        ->and($control->hasAttribute('required'))->toBeTrue()
        ->and($control->getAttribute('aria-invalid'))->toBe('true')
        ->and($control->getAttribute('aria-describedby'))->toBe('desc-hint desc-error')
        ->and($html)->toContain('<span data-scope="textarea" data-part="count" aria-hidden="true" x-text="length + \' / \' + max">0 / 500</span>')
        ->and($html)->toContain('<p data-scope="textarea" data-part="error" id="desc-error">'.IconRegistry::svg('circle-alert').'Write at least 40 characters.</p>');
});

test('a bare textarea renders only the box and control, without Alpine', function (): void {
    $html = Blade::render('<x-artisanpack-textarea aria-label="Notes" />');
    $dom  = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="textarea"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect($parts)->toBe(['box', 'control'])
        ->and($html)->not->toContain('x-data')
        ->and($html)->not->toContain('aria-live');
});

test('the value is the textarea content, escaped', function (): void {
    $control = designSystemDom(Blade::render('<x-artisanpack-textarea label="A" value="<b>Hi</b>" />'))->query('//textarea')->item(0);

    expect($control->textContent)->toBe('<b>Hi</b>');
});

test('the live region stays empty until the limit is near', function (): void {
    $far  = designSystemDom(Blade::render('<x-artisanpack-textarea label="A" :max-length="100" value="short" />'));
    $near = designSystemDom(Blade::render('<x-artisanpack-textarea label="A" :max-length="100" value="'.str_repeat('a', 95).'" />'));

    expect($far->query('//*[@aria-live="polite"]')->item(0)->textContent)->toBe('')
        ->and($near->query('//*[@aria-live="polite"]')->item(0)->textContent)->toBe('Characters left: 5')
        ->and($near->query('//*[@data-part="count"]')->item(0)->textContent)->toBe('95 / 100');
});

test('the threshold is 10% of the limit and at least 10', function (): void {
    expect((new Textarea(maxLength: 500))->announceWithin())->toBe(50)
        ->and((new Textarea(maxLength: 40))->announceWithin())->toBe(10)
        ->and((new Textarea)->announceWithin())->toBe(0);
});

test('remainingLabel replaces the announcement and Alpine gets it', function (): void {
    $html = Blade::render('<x-artisanpack-textarea label="A" :max-length="10" value="abcdefgh" remaining-label="Quedan {count}" />');

    expect(designSystemDom($html)->query('//*[@aria-live="polite"]')->item(0)->textContent)->toBe('Quedan 2')
        ->and($html)->toContain('Quedan {count}');
});

test('autoResize, readonly and disabled flag the root', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-textarea label="A" auto-resize readonly disabled />'))->query('//div')->item(0);

    expect($root->hasAttribute('data-auto-resize'))->toBeTrue()
        ->and($root->hasAttribute('data-readonly'))->toBeTrue()
        ->and($root->hasAttribute('data-disabled'))->toBeTrue();
});

test('class lands on the root and every other attribute on the control', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-textarea class="mt-2" wire:model.blur="notes" name="notes" label="Notes" />'));
    $root    = $dom->query('//div[contains(@class, "apui-textarea")]')->item(0);
    $control = $dom->query('//textarea')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-textarea mt-2')
        ->and($control->getAttribute('wire:model.blur'))->toBe('notes')
        ->and($control->getAttribute('name'))->toBe('notes')
        ->and($control->getAttribute('id'))->toBe('apui-textarea-notes-'.substr(md5('notes'), 0, 8));
});

test('the error comes from $errors for the wire:model name', function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag(['bio' => 'The bio is too short.'])));

    $dom = designSystemDom(Blade::render('<x-artisanpack-textarea wire:model="bio" label="Bio" />'));

    expect($dom->query('//p[@data-part="error"]')->item(0)->textContent)->toBe('The bio is too short.');
});

test('Alpine rereads the control after a form reset and unhooks on destroy', function (): void {
    $xData = designSystemDom(Blade::render('<x-artisanpack-textarea label="A" :max-length="20" />'))->query('//div')->item(0)->getAttribute('x-data');

    expect($xData)->toContain("this.form = input.form; this.onReset = () => setTimeout(read); this.form?.addEventListener('reset', this.onReset);")
        ->and($xData)->toContain("this.form?.removeEventListener('reset', this.onReset)");
});
