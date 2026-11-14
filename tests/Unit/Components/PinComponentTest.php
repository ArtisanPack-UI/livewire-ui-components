<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * Pin (#53): one native input over the cells, separators, masking, the
 * `complete` event, ids and the error lookup. The manifest parity check is
 * in tests/Feature/DesignSystemParityTest.php.
 */

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render('<x-artisanpack-pin id="otp" label="Verification code" required :group-size="3" hint="We sent it to your phone." error="That code has expired." />');
    $dom  = designSystemDom($html);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="pin"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    $control = $dom->query('//input')->item(0);

    expect($parts)->toBe(['label', 'required', 'cells', 'control', 'cell', 'cell', 'cell', 'separator', 'cell', 'cell', 'cell', 'hint', 'error'])
        ->and($control->getAttribute('autocomplete'))->toBe('one-time-code')
        ->and($control->getAttribute('inputmode'))->toBe('numeric')
        ->and($control->getAttribute('pattern'))->toBe('[0-9]*')
        ->and($control->getAttribute('maxlength'))->toBe('6')
        ->and($control->getAttribute('type'))->toBe('text')
        ->and($control->getAttribute('aria-describedby'))->toBe('otp-hint otp-error')
        ->and($control->getAttribute('aria-invalid'))->toBe('true')
        ->and($html)->toContain('<p data-scope="pin" data-part="error" id="otp-error">'.IconRegistry::svg('circle-alert').'That code has expired.</p>');
});

test('the cells are aria-hidden mirrors of the value, with the next cell active', function (): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-pin label="Code" :length="4" value="12" />'));
    $cells = $dom->query('//*[@data-part="cell"]');

    $text = [];
    foreach ($cells as $cell) {
        $text[] = $cell->textContent;
        expect($cell->getAttribute('aria-hidden'))->toBe('true');
    }

    expect($text)->toBe(['1', '2', '', ''])
        ->and($cells->item(0)->hasAttribute('data-filled'))->toBeTrue()
        ->and($cells->item(2)->hasAttribute('data-filled'))->toBeFalse()
        ->and($cells->item(2)->hasAttribute('data-active'))->toBeTrue()
        ->and($cells->item(0)->hasAttribute('data-active'))->toBeFalse();
});

test('a full code keeps the last cell active', function (): void {
    $cells = designSystemDom(Blade::render('<x-artisanpack-pin label="Code" :length="3" value="123" />'))->query('//*[@data-part="cell"]');

    expect($cells->item(2)->hasAttribute('data-active'))->toBeTrue();
});

test('mask shows dots and makes the control a password input', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-pin label="PIN" :length="4" value="12" mask />'));

    expect($dom->query('//input')->item(0)->getAttribute('type'))->toBe('password')
        ->and($dom->query('//*[@data-part="cell"]')->item(0)->textContent)->toBe('•');
});

test('alphanumeric drops the numeric keyboard and pattern', function (): void {
    $control = designSystemDom(Blade::render('<x-artisanpack-pin label="Code" type="alphanumeric" />'))->query('//input')->item(0);

    expect($control->getAttribute('inputmode'))->toBe('text')
        ->and($control->hasAttribute('pattern'))->toBeFalse();
});

test('no separator without groupSize, and none after the last cell', function (): void {
    $none  = Blade::render('<x-artisanpack-pin label="Code" :length="4" />');
    $pairs = designSystemDom(Blade::render('<x-artisanpack-pin label="Code" :length="4" :group-size="2" />'))->query('//*[@data-part="separator"]');

    expect($none)->not->toContain('data-part="separator"')
        ->and($pairs->length)->toBe(1);
});

test('the input dispatches complete and filters what people type and paste', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-pin label="Code" />'));
    $control = $dom->query('//input')->item(0);
    $root    = $dom->query('//div')->item(0);

    expect($control->getAttribute('x-on:input'))->toBe("if (write(\$el, \$el.value)) \$dispatch('complete', value)")
        ->and($control->hasAttribute('x-on:paste.prevent'))->toBeTrue()
        ->and($root->getAttribute('x-data'))->toContain('/[^0-9]/g');
});

test('class lands on the root and every other attribute on the control', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-pin class="mt-2" wire:model="code" wire:complete="verify" label="Code" />'));
    $control = $dom->query('//input')->item(0);

    expect($dom->query('//div')->item(0)->getAttribute('class'))->toBe('apui-pin mt-2')
        ->and($control->getAttribute('wire:model'))->toBe('code')
        ->and($control->getAttribute('wire:complete'))->toBe('verify')
        ->and($control->getAttribute('id'))->toBe('apui-pin-code-'.substr(md5('code'), 0, 8));
});

test('size, disabled and the error from $errors render', function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag(['code' => 'The code is wrong.'])));

    $dom  = designSystemDom(Blade::render('<x-artisanpack-pin wire:model="code" label="Code" size="sm" disabled />'));
    $root = $dom->query('//div')->item(0);

    expect($root->getAttribute('data-size'))->toBe('sm')
        ->and($root->hasAttribute('data-disabled'))->toBeTrue()
        ->and($dom->query('//input')->item(0)->hasAttribute('disabled'))->toBeTrue()
        ->and($dom->query('//p[@data-part="error"]')->item(0)->textContent)->toBe('The code is wrong.');
});

test('Alpine rereads the control after a form reset and unhooks on destroy', function (): void {
    $xData = designSystemDom(Blade::render('<x-artisanpack-pin label="A" />'))->query('//div')->item(0)->getAttribute('x-data');

    expect($xData)->toContain("this.form = input.form; this.onReset = () => setTimeout(read); this.form?.addEventListener('reset', this.onReset);")
        ->and($xData)->toContain("this.form?.removeEventListener('reset', this.onReset)");
});
