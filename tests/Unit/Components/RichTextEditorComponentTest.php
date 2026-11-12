<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\RichTextHtml;
use ArtisanPack\LivewireUiComponents\View\Components\RichTextEditor;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * RichTextEditor (#53): the design-system markup contract, the toolbar,
 * sanitizing, the Alpine wiring and the deprecated `editor` alias. The
 * manifest parity check is in tests/Feature/DesignSystemParityTest.php.
 */

/**
 * The parts a render carries, in order, once each.
 *
 * @return list<string>
 */
function richTextParts(string $html): array
{
    $parts = [];
    foreach (designSystemDom($html)->query('//*[@data-scope="rich-text-editor"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    return array_values(array_unique($parts));
}

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render('<x-artisanpack-rich-text-editor id="rte" label="Description" required hint="Shown on the listing." error="Too short." counter placeholder="Write…" />');

    expect(richTextParts($html))->toBe(['label', 'required', 'box', 'toolbar', 'button', 'divider', 'content', 'placeholder', 'footer', 'count', 'hint', 'error']);

    $dom     = designSystemDom($html);
    $content = $dom->query('//*[@data-part="content"]')->item(0);
    $toolbar = $dom->query('//*[@data-part="toolbar"]')->item(0);

    expect($content->getAttribute('role'))->toBe('textbox')
        ->and($content->getAttribute('aria-multiline'))->toBe('true')
        ->and($content->getAttribute('aria-labelledby'))->toBe('rte-label')
        ->and($content->getAttribute('aria-describedby'))->toBe('rte-hint rte-error')
        ->and($content->getAttribute('aria-invalid'))->toBe('true')
        ->and($content->getAttribute('aria-required'))->toBe('true')
        ->and($content->getAttribute('contenteditable'))->toBe('true')
        ->and($toolbar->getAttribute('role'))->toBe('toolbar')
        ->and($toolbar->getAttribute('aria-label'))->toBe('Formatting')
        ->and($dom->query('//*[@data-part="count"]')->item(0)->textContent)->toBe('0 words · 0 characters');
});

test('the default toolbar has every command with one tab stop and toggles with aria-pressed', function (): void {
    $dom     = designSystemDom(Blade::render('<x-artisanpack-rich-text-editor aria-label="Body" />'));
    $buttons = $dom->query('//*[@data-part="button"]');

    $commands = [];
    foreach ($buttons as $button) {
        $commands[] = $button->getAttribute('data-command');
    }

    expect($commands)->toBe(['bold', 'italic', 'underline', 'strike', 'heading2', 'heading3', 'bulletList', 'orderedList', 'quote', 'code', 'link', 'image', 'undo', 'redo'])
        ->and($dom->query('//*[@data-part="button"][@tabindex="0"]')->length)->toBe(1)
        ->and($dom->query('//*[@data-command="bold"]')->item(0)->getAttribute('aria-pressed'))->toBe('false')
        ->and($dom->query('//*[@data-command="undo"]')->item(0)->hasAttribute('aria-pressed'))->toBeFalse()
        ->and($dom->query('//*[@data-command="bold"]')->item(0)->getAttribute('aria-label'))->toBe('Bold')
        ->and($dom->query('//*[@data-part="divider"]')->length)->toBe(4);
});

test('presets, lists and markdown output shape the toolbar', function (): void {
    $compact  = Blade::render('<x-artisanpack-rich-text-editor toolbar="compact" />');
    $list     = Blade::render('<x-artisanpack-rich-text-editor :toolbar="[\'|\', \'bold\', \'|\', \'|\', \'nope\', \'underline\']" />');
    $markdown = Blade::render('<x-artisanpack-rich-text-editor output="markdown" />');

    expect(substr_count($compact, 'data-part="button"'))->toBe(4)
        ->and(substr_count($list, 'data-part="button"'))->toBe(2)
        ->and(substr_count($list, 'data-part="divider"'))->toBe(1)
        ->and($markdown)->toContain('data-output="markdown"')
        ->and($markdown)->not->toContain('data-command="underline"');
});

test('the value is sanitized before it renders', function (): void {
    $html = Blade::render('<x-artisanpack-rich-text-editor :value="$value" />', ['value' => '<p onclick="x()">Hi <b>there</b><script>alert(1)</script></p><a href="javascript:alert(1)">bad</a>']);

    expect($html)->toContain('<p>Hi <strong>there</strong></p>bad</div>')
        ->and($html)->not->toContain('<script>alert')
        ->and(designSystemDom($html)->query('//*[@data-part="content"]//*[@onclick]')->length)->toBe(0);
});

test('a markdown value renders as HTML', function (): void {
    $html = Blade::render('<x-artisanpack-rich-text-editor output="markdown" :value="$value" />', ['value' => "## Title\n\n- **one**"]);

    expect($html)->toContain('<h2>Title</h2>')
        ->and($html)->toContain('<li><strong>one</strong></li>');
});

test('wire:model entangles the value and the engine options reach Alpine', function (): void {
    $html = Blade::render('<x-artisanpack-rich-text-editor wire:model="body" name="body" class="mt-2" engine="native" :max-length="50" disk="s3" />');
    $dom  = designSystemDom($html);
    $root = $dom->query('//div[contains(@class, "apui-rich-text-editor")]')->item(0);

    expect($root->getAttribute('x-data'))->toContain("\$wire.entangle('body')")
        ->and($root->getAttribute('x-data'))->toContain("engine: 'native'")
        ->and($root->getAttribute('x-data'))->toContain('maxLength: 50')
        ->and($root->getAttribute('x-data'))->toContain('s3')
        ->and($root->getAttribute('class'))->toBe('apui-rich-text-editor mt-2')
        ->and($dom->query('//input[@type="hidden"]')->item(0)->getAttribute('name'))->toBe('body')
        ->and($dom->query('//*[@data-part="box"]')->item(0)->hasAttribute('wire:ignore'))->toBeTrue()
        ->and($html)->not->toContain('wire:model');
});

test('disabled and readonly stop editing', function (): void {
    $disabled = designSystemDom(Blade::render('<x-artisanpack-rich-text-editor disabled />'));
    $readonly = designSystemDom(Blade::render('<x-artisanpack-rich-text-editor readonly />'));

    expect($disabled->query('//*[@data-part="content"]')->item(0)->getAttribute('contenteditable'))->toBe('false')
        ->and($disabled->query('//*[@data-part="button"][not(@disabled)]')->length)->toBe(0)
        ->and($readonly->query('//*[@data-part="content"]')->item(0)->getAttribute('aria-readonly'))->toBe('true')
        ->and($readonly->query('//div[@data-readonly]')->length)->toBe(1);
});

test('the error comes from $errors for the wire:model key', function (): void {
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag(['body' => 'Write something.'])));

    expect(Blade::render('<x-artisanpack-rich-text-editor wire:model="body" label="Body" />'))->toContain('Write something.</p>');
});

test('the deprecated editor tag renders the rich text editor', function (): void {
    expect(Blade::render('<x-artisanpack-editor label="Body" />'))->toContain('class="apui-rich-text-editor"');
});

test('the sanitizer keeps the editor formats and safe URLs only', function (): void {
    expect(RichTextHtml::sanitize('<h1>T</h1><ul><li><i>a</i></li></ul><img src="x.png" onerror="x()"><img src="data:image/png;base64,AA"><iframe src="/"></iframe><a href=" JaVaScRiPt:alert(1)">x</a><a href="https://a.test" target="_blank">y</a>'))
        ->toBe('<h2>T</h2><ul><li><em>a</em></li></ul><img src="x.png" alt="">x<a href="https://a.test">y</a>');
});

test('the editor alias takes the 2.x nullable config, disk and folder', function (): void {
    $html = Blade::render('<x-artisanpack-editor label="Body" :config="null" :disk="null" :folder="null" />');
    $data = designSystemDom($html)->query('//div[contains(@class, "apui-rich-text-editor")]')->item(0)->getAttribute('x-data');

    expect($data)->toContain('config: {}')
        ->and($data)->toContain('uploadFields: JSON.parse(\'{\u0022folder\u0022:\u0022editor\u0022}\')');
});

test('uploads leave the disk to the route unless one is set', function (): void {
    expect((new RichTextEditor)->uploadFields())->toBe(['folder' => 'editor'])
        ->and((new RichTextEditor(disk: 's3', folder: 'posts'))->uploadFields())->toBe(['folder' => 'posts', 'disk' => 's3'])
        ->and((new RichTextEditor(disk: '', folder: null))->uploadFields())->toBe(['folder' => 'editor']);
});
