<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\Markdown\MarkdownHtml;
use ArtisanPack\LivewireUiComponents\Support\Markdown\MarkdownRenderer;

/*
 * MarkdownRenderer (#65): league/commonmark with the GFM extension, raw HTML
 * escaped and unsafe URLs dropped. The design system's shared vectors
 * (packages/behavior/test/fixtures/markdown-vectors.json) hold it to the
 * HTML the behavior package's renderMarkdown writes for React and Vue.
 */

/**
 * The shared vectors, from the linked design-system checkout.
 *
 * @return list<array{name: string, source: string, html: string, unsanitized: string}>
 */
function markdownVectors(): array
{
    foreach ([dirname(__DIR__, 3).'/vendor/artisanpack-ui/design-system', dirname(__DIR__, 3).'/../design-system'] as $root) {
        $file = $root.'/packages/behavior/test/fixtures/markdown-vectors.json';
        if (is_file($file)) {
            return json_decode((string) file_get_contents($file), true)['vectors'];
        }
    }

    throw new RuntimeException('The design-system markdown vectors are missing.');
}

test('it writes the shared vectors', function (): void {
    foreach (markdownVectors() as $vector) {
        expect(MarkdownRenderer::render($vector['source']))->toBe($vector['html'], $vector['name'])
            ->and(MarkdownRenderer::render($vector['source'], false))->toBe($vector['unsanitized'], $vector['name'].' (sanitize off)');
    }
});

test('it renders nothing for empty source', function (): void {
    expect(MarkdownRenderer::render(null))->toBe('')
        ->and(MarkdownRenderer::render(''))->toBe('');
});

test('task items are labelled, disabled checkboxes', function (): void {
    expect(MarkdownRenderer::render("- [x] Done\n- [ ] Open"))->toBe(
        "<ul>\n<li data-scope=\"markdown\" data-part=\"task\"><label><input checked=\"\" disabled=\"\" type=\"checkbox\"> Done</label></li>\n"
        ."<li data-scope=\"markdown\" data-part=\"task\"><label><input disabled=\"\" type=\"checkbox\"> Open</label></li>\n</ul>\n",
    );
});

test('tables keep header cells and write alignment as data-align', function (): void {
    $html = MarkdownRenderer::render("| A | B | C |\n|:--|:-:|--:|\n| 1 | 2 | 3 |");

    expect($html)->toContain('<th data-align="start">A</th>')
        ->and($html)->toContain('<th data-align="center">B</th>')
        ->and($html)->toContain('<td data-align="end">3</td>');
});

test('it never renders script-capable markup', function (bool $sanitize): void {
    $html = MarkdownRenderer::render(implode("\n\n", [
        '<script>alert(1)</script>',
        '<img src=x onerror=alert(1)>',
        '<a href="javascript:alert(1)">a</a> <a href="&#106;avascript:alert(1)">b</a>',
        '<iframe src="https://x.test"></iframe> <svg onload=alert(1)>',
        '[c](javascript:alert(1)) ![d](javascript:alert(1)) [e](vbscript:x) [f](data:text/html,x)',
        '<div style="background:url(javascript:x)" class="x">s</div>',
    ]), $sanitize);

    $dom = designSystemDom($html);
    expect($dom->query('//script | //iframe | //svg | //style | //object | //embed')->length)->toBe(0);
    foreach ($dom->query('//body//*') as $element) {
        foreach ($element->attributes as $attribute) {
            expect($attribute->name)->not->toMatch('/^(on|style$|class$)/i');
            if (in_array($attribute->name, ['href', 'src'], true)) {
                expect($attribute->value)->not->toMatch('/^\s*(javascript|vbscript|data:text)/i');
            }
        }
    }
})->with([true, false]);

test('the raw HTML filter rebuilds allowed tags and escapes the rest', function (): void {
    expect(MarkdownHtml::filter('<KBD class="k" title="Key">K</KBD>'))->toBe('<kbd title="Key">K</kbd>')
        ->and(MarkdownHtml::filter('<a href="/x" target="_blank" onclick="y">x</a>'))->toBe('<a href="/x">x</a>')
        ->and(MarkdownHtml::filter('<details open><summary>S</summary></details>'))->toBe('<details open=""><summary>S</summary></details>')
        ->and(MarkdownHtml::filter('<x-card>a</x-card><!-- c --><b>b</b>'))->toBe('&lt;x-card&gt;a&lt;/x-card&gt;<b>b</b>')
        ->and(MarkdownHtml::filter('<p>1 < 2</p>'))->toBe('<p>1 &lt; 2</p>')
        ->and(MarkdownHtml::filter('<a href="&#x6A;avascript:x">a</a>'))->toBe('<a>a</a>')
        ->and(MarkdownHtml::filter('<a href="?a=1&amp;b=2">a</a>'))->toBe('<a href="?a=1&amp;b=2">a</a>')
        ->and(MarkdownHtml::filter('<a href="java&Tab;script:x">a</a>'))->toBe('<a href="java&amp;Tab;script:x">a</a>');
});
