<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * Signature (#53): the design-system markup contract, the modes, the
 * Alpine wiring, attribute routing and the error lookup. The manifest
 * parity check is in tests/Feature/DesignSystemParityTest.php.
 */

/**
 * The parts a render carries, in order.
 *
 * @return list<string>
 */
function signatureParts(string $html): array
{
    $parts = [];
    foreach (designSystemDom($html)->query('//*[@data-scope="signature"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    return $parts;
}

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render('<x-artisanpack-signature id="sig" label="Signature" required hint="Olivia Rhye" error="Sign to accept." />');

    expect(signatureParts($html))->toBe([
        'label', 'required', 'modes', 'mode', 'mode', 'mode', 'pad', 'line', 'mark', 'ink', 'typed', 'upload', 'tools', 'undo', 'clear', 'placeholder', 'hint', 'error', 'status',
    ]);

    $dom  = designSystemDom($html);
    $root = $dom->query('//div[contains(@class, "apui-signature")]')->item(0);

    expect($root->getAttribute('id'))->toBe('sig')
        ->and($root->getAttribute('role'))->toBe('group')
        ->and($root->getAttribute('aria-labelledby'))->toBe('sig-label')
        ->and($root->getAttribute('aria-describedby'))->toBe('sig-hint sig-error')
        ->and($root->getAttribute('data-mode'))->toBe('draw')
        ->and($root->getAttribute('data-pen-color'))->toBe('default')
        ->and($root->hasAttribute('data-invalid'))->toBeTrue()
        ->and($root->getAttribute('x-data'))->toStartWith('apuiSignature({ value: ')
        ->and($dom->query('//canvas')->item(0)->getAttribute('role'))->toBe('img')
        ->and($dom->query('//*[@data-part="typed"]')->item(0)->getAttribute('aria-required'))->toBe('true')
        ->and($dom->query('//*[@data-part="status"]')->item(0)->getAttribute('aria-live'))->toBe('polite');
});

test('every mode button is a toggle and only the current mode shows its parts', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-signature id="s" mode="type" />'));

    $pressed = [];
    foreach ($dom->query('//*[@data-part="mode"]') as $button) {
        $pressed[$button->getAttribute('data-value')] = $button->getAttribute('aria-pressed');
    }

    expect($pressed)->toBe(['draw' => 'false', 'type' => 'true', 'upload' => 'false'])
        ->and($dom->query('//canvas')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//*[@data-part="typed"]')->item(0)->hasAttribute('hidden'))->toBeFalse()
        ->and($dom->query('//*[@data-part="tools"]')->item(0)->hasAttribute('hidden'))->toBeTrue()
        ->and($dom->query('//*[@data-part="upload"]')->item(0)->hasAttribute('hidden'))->toBeTrue();
});

test('type mode is always offered and an unknown mode falls back', function (): void {
    $html = Blade::render('<x-artisanpack-signature :modes="[\'draw\']" mode="upload" />');

    expect(signatureParts($html))->not->toContain('upload')
        ->and(substr_count($html, 'data-part="mode"'))->toBe(2)
        ->and($html)->toContain('data-value="type"')
        ->and($html)->toContain('data-mode="draw"');
});

test('wire:model entangles the value and name lands on the hidden input', function (): void {
    $html = Blade::render('<x-artisanpack-signature wire:model.live="signature" name="signature" class="mt-4" data-test="x" />');
    $dom  = designSystemDom($html);
    $root = $dom->query('//div[contains(@class, "apui-signature")]')->item(0);

    expect($root->getAttribute('x-data'))->toContain("\$wire.entangle('signature').live")
        ->and($root->getAttribute('class'))->toBe('apui-signature mt-4')
        ->and($root->getAttribute('data-test'))->toBe('x')
        ->and($root->hasAttribute('name'))->toBeFalse()
        ->and($html)->not->toContain('wire:model')
        ->and($dom->query('//input[@type="hidden"]')->item(0)->getAttribute('name'))->toBe('signature');
});

test('the built-in strings are translatable and overridable', function (): void {
    $html = Blade::render('<x-artisanpack-signature clear-label="Erase" placeholder="Sign here" />');

    expect($html)->toContain('<span>Erase</span>')
        ->and($html)->toContain('>Sign here</span>')
        ->and($html)->toContain('aria-label="Undo stroke"')
        ->and($html)->toContain('aria-label="Signature method"');
});

test('disabled disables every control; the clear button waits for a value', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-signature disabled />'));

    expect($dom->query('//div[contains(@class, "apui-signature")]')->item(0)->hasAttribute('data-disabled'))->toBeTrue()
        ->and($dom->query('//button[not(@disabled)]')->length)->toBe(0)
        ->and($dom->query('//input[@type="text" and @disabled]')->length)->toBe(1);

    $filled = designSystemDom(Blade::render('<x-artisanpack-signature value="data:image/png;base64,AA" />'));
    expect($filled->query('//*[@data-part="clear"]')->item(0)->hasAttribute('disabled'))->toBeFalse();
});

test('the error comes from $errors for the wire:model key', function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag(['signature' => 'Please sign.'])));

    $html = Blade::render('<x-artisanpack-signature wire:model="signature" label="Signature" />');

    expect($html)->toContain('Please sign.</p>')
        ->and($html)->toContain('data-invalid');
});

test('pen colors land on the root', function (string $color): void {
    expect(Blade::render('<x-artisanpack-signature pen-color="'.$color.'" />'))->toContain('data-pen-color="'.$color.'"');
})->with(['default', 'primary', 'error', 'info']);
