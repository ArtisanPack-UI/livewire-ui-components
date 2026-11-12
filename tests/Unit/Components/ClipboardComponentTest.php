<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Clipboard (#65): the design-system markup contract. The copy itself runs
 * on the behavior package's apuiClipboard, tested in the design system. The
 * manifest parity check is in tests/Feature/DesignSystemParityTest.php.
 */

const CLIPBOARD_URL = 'https://horizonhomes.com/l/grove-greenfield';

function clipboardDom(string $props = ''): DOMXPath
{
    return designSystemDom(Blade::render('<x-artisanpack-clipboard :value="$value" '.$props.' />', ['value' => CLIPBOARD_URL]));
}

test('renders an outline copy button and an empty live region', function (): void {
    $dom    = clipboardDom();
    $root   = $dom->query('//body/span')->item(0);
    $button = $dom->query('//button[@data-part="button"]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-clipboard')
        ->and($root->getAttribute('data-variant'))->toBe('button')
        ->and($root->getAttribute('data-size'))->toBe('sm')
        ->and($root->getAttribute('data-state'))->toBe('idle')
        ->and($root->getAttribute('x-data'))->toContain('apuiClipboard({ timeout: 2000')
        ->and($button->getAttribute('class'))->toBe('apui-button')
        ->and($button->getAttribute('data-variant'))->toBe('outline')
        ->and($button->getAttribute('data-color'))->toBe('neutral')
        ->and($button->getAttribute('value'))->toBe(CLIPBOARD_URL)
        ->and($button->getAttribute('x-on:click'))->toContain("\$dispatch( 'copy', \$el.value )")
        ->and($button->getAttribute('x-on:click'))->toContain("\$dispatch( 'error' )")
        ->and($dom->query('.//*[@data-part="icon"][@aria-hidden="true"]/svg', $button))->toHaveCount(1)
        ->and(trim($dom->query('.//*[@data-part="label"]', $button)->item(0)->textContent))->toBe('Copy')
        ->and(trim($dom->query('//*[@data-part="tooltip"][@role="status"]')->item(0)->textContent))->toBe('');
});

test('names the icon button through its label', function (): void {
    $dom    = clipboardDom('variant="icon" label="Copy reference"');
    $button = $dom->query('//button')->item(0);

    expect($button->getAttribute('data-variant'))->toBe('ghost')
        ->and($button->hasAttribute('data-icon-only'))->toBeTrue()
        ->and($button->getAttribute('aria-label'))->toBe('Copy reference')
        ->and($button->getAttribute('x-bind:aria-label'))->toBe('label')
        ->and($dom->query('.//*[@data-part="label"]', $button))->toHaveCount(0);
});

test('shows the value in a field joined to the button', function (): void {
    $dom  = clipboardDom('variant="field" size="md"');
    $root = $dom->query('//body/div')->item(0);

    expect($root->getAttribute('data-variant'))->toBe('field')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($dom->query('//*[@data-part="field"]/*[@data-part="value"]')->item(0)->textContent)->toBe(CLIPBOARD_URL)
        ->and($dom->query('//button')->item(0)->getAttribute('data-size'))->toBe('md');
});

test('falls back to the default variant and size', function (): void {
    $root = clipboardDom('variant="nope" size="xl"')->query('//body/span')->item(0);

    expect($root->getAttribute('data-variant'))->toBe('button')
        ->and($root->getAttribute('data-size'))->toBe('sm');
});

test('translates the strings and takes part classes', function (): void {
    $dom  = clipboardDom('label="Copiar" copied-label="Copiado" error-message="No se pudo copiar." :timeout="500" :classes="[\'root\' => \'r\', \'button\' => \'b\', \'tooltip\' => \'t\']"');
    $root = $dom->query('//body/span')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-clipboard r')
        ->and($root->getAttribute('x-data'))->toContain('timeout: 500')
        ->and($root->getAttribute('x-data'))->toContain('Copiado')
        ->and($root->getAttribute('x-data'))->toContain('No se pudo copiar.')
        ->and($dom->query('//button')->item(0)->getAttribute('class'))->toBe('apui-button b')
        ->and($dom->query('//*[@data-part="tooltip"]')->item(0)->getAttribute('class'))->toBe('t');
});
