<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * CodeEditor (#53): the design-system markup contract, the native control,
 * tabs, languages, diagnostics and the status bar. The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php.
 */

/**
 * The parts a render carries, in order, once each.
 *
 * @return list<string>
 */
function codeEditorParts(string $html): array
{
    $parts = [];
    foreach (designSystemDom($html)->query('//*[@data-scope="code-editor"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    return array_values(array_unique($parts));
}

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render(
        '<x-artisanpack-code-editor id="code" label="Source" required hint="Two spaces." error="Fix it." :rows="3" :tabs="[\'a.ts\', \'b.ts\']" :languages="[[\'value\' => \'ts\', \'label\' => \'TypeScript\']]" language="ts" :diagnostics="[[\'line\' => 2, \'message\' => \'Bad type.\']]" />',
    );

    expect(codeEditorParts($html))->toBe(['label', 'required', 'box', 'header', 'tabs', 'tab', 'languageSelect', 'body', 'gutter', 'line', 'control', 'diagnostics', 'diagnostic', 'status', 'instructions', 'hint', 'error']);

    $dom     = designSystemDom($html);
    $control = $dom->query('//textarea')->item(0);
    $body    = $dom->query('//*[@data-part="body"]')->item(0);

    expect($control->getAttribute('id'))->toBe('code')
        ->and($control->getAttribute('aria-describedby'))->toBe('code-hint code-error code-instructions')
        ->and($control->getAttribute('aria-invalid'))->toBe('true')
        ->and($control->hasAttribute('required'))->toBeTrue()
        ->and($control->getAttribute('spellcheck'))->toBe('false')
        ->and($body->getAttribute('dir'))->toBe('ltr')
        ->and($body->getAttribute('role'))->toBe('tabpanel')
        ->and($body->getAttribute('aria-labelledby'))->toBe('code-tab-0')
        ->and($dom->query('//*[@data-part="line"]')->length)->toBe(3)
        ->and($dom->query('//*[@data-part="line"][@data-severity="error"]')->item(0)->textContent)->toBe('2')
        ->and($dom->query('//*[@data-part="diagnostic"]')->item(0)->textContent)->toContain('Line 2')
        ->and($dom->query('//*[@data-part="status"]')->item(0)->textContent)->toContain('1 error')
        ->and($dom->query('//*[@data-part="status"]')->item(0)->textContent)->toContain('TypeScript')
        ->and($dom->query('//*[@data-part="status"]')->item(0)->textContent)->toContain('Spaces: 2');
});

test('wire:model and other attributes land on the textarea, listeners on the root', function (): void {
    $html = Blade::render('<x-artisanpack-code-editor wire:model.blur="css" name="css" class="mt-3" x-on:tab-change="go($event)" />');
    $dom  = designSystemDom($html);
    $root = $dom->query('//div[contains(@class, "apui-code-editor")]')->item(0);

    expect($dom->query('//textarea')->item(0)->getAttribute('wire:model.blur'))->toBe('css')
        ->and($dom->query('//textarea')->item(0)->getAttribute('name'))->toBe('css')
        ->and($root->getAttribute('class'))->toBe('apui-code-editor mt-3')
        ->and($root->getAttribute('x-on:tab-change'))->toBe('go($event)')
        ->and($root->getAttribute('x-init'))->toContain("\$dispatch('tab-change'")
        ->and($dom->query('//textarea')->item(0)->hasAttribute('x-on:tab-change'))->toBeFalse();
});

test('tabs select the first by default, and tab picks another', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-code-editor aria-label="Code" :tabs="[\'a\', [\'value\' => \'b\', \'label\' => \'B file\']]" tab="b" />'));
    $tabs = $dom->query('//*[@data-part="tab"]');

    expect($tabs->item(0)->getAttribute('aria-selected'))->toBe('false')
        ->and($tabs->item(0)->getAttribute('tabindex'))->toBe('-1')
        ->and($tabs->item(1)->getAttribute('aria-selected'))->toBe('true')
        ->and($tabs->item(1)->textContent)->toBe('B file')
        ->and($dom->query('//*[@data-part="tabs"]')->item(0)->getAttribute('aria-label'))->toBe('Open files');
});

test('without tabs or languages there is no header, and the gutter grows with the value', function (): void {
    $html = Blade::render('<x-artisanpack-code-editor aria-label="Code" :rows="2" :value="$value" />', ['value' => "a\nb\nc\nd"]);

    expect(codeEditorParts($html))->not->toContain('header')
        ->and(substr_count($html, 'data-part="line"'))->toBe(4)
        ->and($html)->toContain("a\nb\nc\nd</textarea>");
});

test('readonly and disabled reach the textarea and the root', function (): void {
    $html = Blade::render('<x-artisanpack-code-editor readonly disabled aria-label="Code" />');

    expect($html)->toContain('data-readonly')
        ->and($html)->toContain('data-disabled')
        ->and(designSystemDom($html)->query('//textarea[@readonly and @disabled]')->length)->toBe(1);
});

test('the built-in strings are translatable and overridable', function (): void {
    $html = Blade::render('<x-artisanpack-code-editor aria-label="Code" :tabs="[\'a\']" tabs-label="Files" position-label="L :line C :column" />');

    expect($html)->toContain('aria-label="Files"')
        ->and($html)->toContain('L 1 C 1')
        ->and($html)->toContain('press Escape, then Tab');
});
