<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Code;
use Illuminate\Support\Facades\Blade;

/*
 * Code (#65): the design-system markup contract, and lines(), the PHP port
 * of the behavior package's codeLines. Its expectations match
 * test/clipboard-kbd-code.test.ts in the design system, so Blade renders the
 * lines React and Vue render. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

const CODE_SNIPPET = "const a = 1;\n\n  return a;\n";

function codeDom(string $props = '', ?string $code = CODE_SNIPPET): DOMXPath
{
    return designSystemDom(Blade::render('<x-artisanpack-code :code="$code" '.$props.' />', ['code' => $code]));
}

test('lines matches the TypeScript helper', function (): void {
    expect(Code::lines("a\r\nb\rc\nd\n"))->toBe(['a', 'b', 'c', 'd'])
        ->and(Code::lines("a\n\n"))->toBe(['a', ''])
        ->and(Code::lines("\tindented  "))->toBe(["\tindented  "])
        ->and(Code::lines("\n"))->toBe([''])
        ->and(Code::lines(''))->toBe([])
        ->and(Code::lines(null))->toBe([]);
});

test('renders the header, a labeled LTR region and one span per line', function (): void {
    $dom  = codeDom('file-name="a.ts" language="TypeScript" numbered :highlight="[3]"');
    $root = $dom->query('//body/div')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-code')
        ->and($root->hasAttribute('data-numbered'))->toBeTrue()
        ->and($root->getAttribute('x-data'))->toContain('apuiClipboard({ timeout: 2000')
        ->and($dom->query('//*[@data-part="header"]/*[@data-part="file"]')->item(0)->textContent)->toBe('a.ts');

    $badge = $dom->query('//*[@data-part="language"]')->item(0);
    expect($badge->getAttribute('class'))->toBe('apui-badge')
        ->and($badge->getAttribute('data-variant'))->toBe('outline')
        ->and($badge->textContent)->toBe('TypeScript');

    $copy = $dom->query('//button[@data-part="copy"]')->item(0);
    expect($copy->getAttribute('class'))->toBe('apui-button')
        ->and($copy->getAttribute('data-size'))->toBe('xs')
        ->and($copy->getAttribute('x-on:click'))->toBe('copyCode()')
        ->and(trim($dom->query('.//*[@data-part="label"]', $copy)->item(0)->textContent))->toBe('Copy')
        ->and($dom->query('//*[@data-part="status"][@role="status"]'))->toHaveCount(1);

    $pre = $dom->query('//pre[@data-part="pre"]')->item(0);
    expect($pre->getAttribute('role'))->toBe('region')
        ->and($pre->getAttribute('aria-label'))->toBe('a.ts')
        ->and($pre->getAttribute('tabindex'))->toBe('0');

    $texts = $numbers = $highlights = [];
    foreach ($dom->query('//pre/code/span[@data-part="line"]') as $line) {
        $texts[]      = $line->textContent;
        $numbers[]    = $line->getAttribute('data-n');
        $highlights[] = $line->hasAttribute('data-highlight');
    }
    expect($texts)->toBe(['const a = 1;', '', '  return a;'])
        ->and($numbers)->toBe(['1', '2', '3'])
        ->and($highlights)->toBe([false, false, true]);
});

test('names the region by label, file name, language, then Code', function (): void {
    $name = static fn (string $props): string => codeDom($props, 'x')->query('//pre')->item(0)->getAttribute('aria-label');

    expect($name('label="Example" file-name="a.ts"'))->toBe('Example')
        ->and($name('language="Shell"'))->toBe('Shell')
        ->and($name(''))->toBe('Code');
});

test('leaves the header and the copy behavior out when there is nothing to show', function (): void {
    $dom = codeDom(':copyable="false"', 'x');

    expect($dom->query('//*[@data-part="header"]'))->toHaveCount(0)
        ->and($dom->query('//body/div')->item(0)->hasAttribute('x-data'))->toBeFalse();
});

test('renders inline code as a code element, escaping the text', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-code inline code=\'<b>data-theme="dark"</b>\' />'));
    $root = $dom->query('//body/code')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-code')
        ->and($root->hasAttribute('data-inline'))->toBeTrue()
        ->and($root->textContent)->toBe('<b>data-theme="dark"</b>')
        ->and($dom->query('//b'))->toHaveCount(0);
});

test('takes the code from the slot and drops the 2.x editor props', function (): void {
    $html = Blade::render("<x-artisanpack-code hint=\"Old\" height=\"300px\" light-theme=\"github\">\nif (a < b) {}\n</x-artisanpack-code>");
    $dom  = designSystemDom($html);

    expect($dom->query('//span[@data-part="line"]')->item(0)->textContent)->toBe('if (a < b) {}')
        ->and($html)->not->toContain('hint=')
        ->and($html)->not->toContain('light-theme=');
});

test('translates the copy strings', function (): void {
    $root = codeDom('copy-label="Copiar" copied-label="Copiado" copied-message="Copiado al portapapeles"')->query('//body/div')->item(0);

    expect($root->getAttribute('x-data'))->toContain('Copiado')
        ->and($root->getAttribute('x-data'))->toContain('Copiado al portapapeles');
});
