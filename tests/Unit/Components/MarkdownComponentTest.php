<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\Markdown\MarkdownRenderer;
use Illuminate\Support\Facades\Blade;
use Livewire\Component;
use Livewire\Livewire;

/*
 * Markdown (#65): the design-system markup contract. The HTML itself is
 * MarkdownRenderer's (tests/Unit/Support/MarkdownRendererTest.php); the
 * manifest parity check is in tests/Feature/DesignSystemParityTest.php.
 */

const MARKDOWN_SOURCE = "## Viewing checklist\n\nBring **photo ID** and proof of income.\n\n- [x] Confirm time with agent\n- [ ] Measure the living room\n\n> Viewings run 30 minutes.\n\n| Unit | Rent |\n|---|---:|\n| 2B | \$1,400 |\n";

function markdownHtml(string $props = '', array $data = []): string
{
    return Blade::render('<x-artisanpack-markdown :source="$source" '.$props.' />', ['source' => MARKDOWN_SOURCE, ...$data]);
}

class MarkdownLivewireTestComponent extends Component
{
    public string $notes = "# Notes\n\n- [ ] Call";

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <x-artisanpack-markdown wire:model.live.debounce.500ms="notes" mode="split" />
            </div>
            BLADE;
    }
}

test('preview mode renders the HTML in the prose root, with no script', function (): void {
    $html = markdownHtml('class="extra"');
    $dom  = designSystemDom($html);
    $root = $dom->query('//body/div')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-markdown apui-prose extra')
        ->and($root->getAttribute('data-mode'))->toBe('preview')
        ->and($root->hasAttribute('x-data'))->toBeFalse()
        ->and($html)->toContain(MarkdownRenderer::render(MARKDOWN_SOURCE))
        ->and($dom->query('//h2')->item(0)->textContent)->toBe('Viewing checklist')
        ->and($dom->query('//th[@data-align="end"]')->item(0)->textContent)->toBe('Rent');
});

test('task items are labelled, disabled checkboxes', function (): void {
    $dom   = designSystemDom(markdownHtml());
    $boxes = $dom->query('//li[@data-scope="markdown"][@data-part="task"]/label/input[@type="checkbox"]');

    expect($boxes->length)->toBe(2)
        ->and($boxes->item(0)->hasAttribute('disabled'))->toBeTrue()
        ->and($boxes->item(0)->hasAttribute('checked'))->toBeTrue()
        ->and($boxes->item(1)->hasAttribute('checked'))->toBeFalse()
        ->and(trim($boxes->item(0)->parentNode->textContent))->toBe('Confirm time with agent');
});

test('raw HTML is escaped, or filtered with sanitize off', function (): void {
    $source = '<b onclick="x()">b</b> <script>alert(1)</script> [x](javascript:alert(1))';

    $safe = designSystemDom(markdownHtml('', ['source' => $source]));
    expect($safe->query('//b | //script | //a')->length)->toBe(0)
        ->and($safe->query('//body/div')->item(0)->textContent)->toContain('<b onclick="x()">b</b>');

    $raw = designSystemDom(markdownHtml(':sanitize="false"', ['source' => $source]));
    expect($raw->query('//b')->item(0)->attributes->length)->toBe(0)
        ->and($raw->query('//script | //a')->length)->toBe(0);
});

test('split mode renders the tabs, the editor and the preview', function (): void {
    $dom  = designSystemDom(markdownHtml('mode="split"'));
    $root = $dom->query('//body/div')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-markdown')
        ->and($root->getAttribute('data-mode'))->toBe('split')
        ->and($root->getAttribute('x-data'))->toBe('apuiMarkdown({ view: "split", source: '.json_encode(MARKDOWN_SOURCE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT).', sanitize: true })')
        ->and($dom->query('//*[@role="tablist"]')->item(0)->getAttribute('aria-label'))->toBe('Markdown mode')
        ->and(array_map(fn ($tab) => trim($tab->textContent), iterator_to_array($dom->query('//*[@role="tab"]'))))->toBe(['Write', 'Preview', 'Split'])
        ->and($dom->query('//*[@role="tab"][@aria-selected="true"]')->item(0)->getAttribute('data-value'))->toBe('split');

    $split = $dom->query('//*[@data-scope="markdown"][@data-part="split"]')->item(0);
    expect($split->parentNode->hasAttribute('hidden'))->toBeFalse();

    $textarea = $dom->query('.//*[@data-part="editor"]//textarea', $split)->item(0);
    expect($textarea->getAttribute('aria-label'))->toBe('Markdown source')
        ->and($textarea->textContent)->toBe(MARKDOWN_SOURCE)
        ->and($textarea->getAttribute('x-bind:disabled'))->toBe("view !== 'split'")
        ->and($textarea->getAttribute('x-on:input'))->toContain("\$dispatch('source-change'");

    $preview = $dom->query('.//*[@data-part="preview"]', $split)->item(0);
    expect($preview->getAttribute('class'))->toBe('apui-prose')
        ->and($preview->getAttribute('role'))->toBe('region')
        ->and($preview->getAttribute('aria-label'))->toBe('Rendered preview')
        ->and($dom->query('.//h2', $preview)->item(0)->textContent)->toBe('Viewing checklist');

    // The Write panel holds its own Textarea, hidden with its panel.
    $ids = array_map(fn ($node) => $node->getAttribute('id'), iterator_to_array($dom->query('//textarea')));
    expect($ids)->toHaveCount(2)
        ->and($ids[0])->toEndWith('-write-source')
        ->and($ids[1])->toEndWith('-split-source');
});

test('without wire:model, both Textareas share one Alpine source, so a plain form keeps edits across tabs', function (): void {
    $source = "Say \"hi\" & <b>it's</b>\n";
    $dom    = designSystemDom(Blade::render('<form><x-artisanpack-markdown :source="$source" name="notes" mode="write" :sanitize="false" /></form>', ['source' => $source]));
    $root   = $dom->query('//*[contains(concat(" ", @class, " "), " apui-markdown ")]')->item(0);

    // x-data is apuiMarkdown with the Markdown as source, with no raw quote or tag to break the attribute.
    $data = $root->getAttribute('x-data');
    expect($data)->toStartWith('apuiMarkdown({ view: "write", source: "')
        ->and($data)->toEndWith(', sanitize: false })')
        ->and($data)->not->toContain('<b>')
        ->and(json_decode(substr($data, strlen('apuiMarkdown({ view: "write", source: '), -strlen(', sanitize: false })'))))->toBe($source);

    $textareas = $dom->query('//textarea');
    expect($textareas->length)->toBe(2);
    foreach ($textareas as $textarea) {
        expect($textarea->getAttribute('x-model'))->toBe('source')
            ->and($textarea->getAttribute('name'))->toBe('notes')
            ->and($textarea->textContent)->toBe($source);
    }
    // Write's Textarea stays enabled on Preview, so the form posts the source on every tab.
    expect($textareas->item(0)->getAttribute('x-bind:disabled'))->toBe("view === 'split'")
        ->and($textareas->item(1)->getAttribute('x-bind:disabled'))->toBe("view !== 'split'")
        ->and($textareas->item(0)->getAttribute('x-on:invalid'))->toBe("select('write')")
        ->and($textareas->item(1)->hasAttribute('x-on:invalid'))->toBeFalse();

    // The preview panels show the draft, rendered in the browser by apuiMarkdown.
    $previews = $dom->query('//*[@data-scope="markdown"][@data-part="preview"]');
    expect($previews->length)->toBe(2);
    foreach ($previews as $preview) {
        expect($preview->getAttribute('x-html'))->toBe('html');
    }
});

test('native control attributes go to the Textareas, not the root', function (): void {
    $html = Blade::render(
        '<form><x-artisanpack-markdown source="" name="notes" mode="write" required disabled readonly placeholder="Notes…" maxlength="500" minlength="2" rows="6" autofocus data-test="root" /></form>',
    );
    $dom  = designSystemDom($html);
    $root = $dom->query('//*[contains(concat(" ", @class, " "), " apui-markdown ")]')->item(0);

    foreach (['name', 'required', 'disabled', 'readonly', 'placeholder', 'maxlength', 'minlength', 'rows', 'autofocus'] as $attribute) {
        expect($root->hasAttribute($attribute))->toBeFalse($attribute);
    }
    expect($root->getAttribute('data-test'))->toBe('root');

    $textareas = $dom->query('//textarea');
    expect($textareas->length)->toBe(2);
    foreach ($textareas as $textarea) {
        expect($textarea->getAttribute('name'))->toBe('notes')
            ->and($textarea->hasAttribute('required'))->toBeTrue()
            ->and($textarea->hasAttribute('disabled'))->toBeTrue()
            ->and($textarea->hasAttribute('readonly'))->toBeTrue()
            ->and($textarea->getAttribute('placeholder'))->toBe('Notes…')
            ->and($textarea->getAttribute('maxlength'))->toBe('500')
            ->and($textarea->getAttribute('minlength'))->toBe('2')
            ->and($textarea->getAttribute('rows'))->toBe('6')
            // Disabled stays disabled on every tab.
            ->and($textarea->getAttribute('x-bind:disabled'))->toBe('true');
    }
});

test('without native control attributes the Textareas are enabled and twelve rows', function (): void {
    $dom = designSystemDom(markdownHtml('mode="write" :disabled="false"'));

    foreach ($dom->query('//textarea') as $textarea) {
        expect($textarea->hasAttribute('disabled'))->toBeFalse()
            ->and($textarea->hasAttribute('required'))->toBeFalse()
            ->and($textarea->getAttribute('rows'))->toBe('12')
            ->and($textarea->getAttribute('x-bind:disabled'))->not->toBe('true');
    }
});

test('write mode opens on the Write tab', function (): void {
    $dom = designSystemDom(markdownHtml('mode="write"'));

    expect($dom->query('//*[@role="tab"][@aria-selected="true"]')->item(0)->getAttribute('data-value'))->toBe('write')
        ->and($dom->query('//body/div')->item(0)->getAttribute('data-mode'))->toBe('write');
});

test('labels and part classes', function (): void {
    $dom = designSystemDom(markdownHtml(
        'mode="split" tabs-label="Modo" write-label="Escribir" preview-label="Vista previa" split-label="Dividir" editor-label="Fuente" output-label="Vista" :classes="[ \'root\' => \'r\', \'editor\' => \'e\', \'preview\' => \'p\', \'split\' => \'s\' ]"',
    ));

    expect($dom->query('//*[@role="tablist"]')->item(0)->getAttribute('aria-label'))->toBe('Modo')
        ->and(array_map(fn ($tab) => trim($tab->textContent), iterator_to_array($dom->query('//*[@role="tab"]'))))->toBe(['Escribir', 'Vista previa', 'Dividir'])
        ->and($dom->query('//textarea')->item(0)->getAttribute('aria-label'))->toBe('Fuente')
        ->and($dom->query('//*[@data-part="preview"]')->item(0)->getAttribute('aria-label'))->toBe('Vista')
        ->and($dom->query('//*[@data-part="preview"]')->item(0)->getAttribute('class'))->toBe('apui-prose p')
        ->and($dom->query('//body/div')->item(0)->getAttribute('class'))->toBe('apui-markdown r')
        ->and($dom->query('//*[@data-part="editor"]')->item(0)->getAttribute('class'))->toBe('e')
        ->and($dom->query('//*[@data-part="split"]')->item(0)->getAttribute('class'))->toBe('s');
});

test('an unknown mode falls back to preview, and the 2.x EasyMDE attributes are dropped', function (): void {
    $html = markdownHtml('mode="tabs" label="Content" hint="Help" disk="s3" folder="x" :config="[]"');
    $root = designSystemDom($html)->query('//body/div')->item(0);

    expect($root->getAttribute('data-mode'))->toBe('preview')
        ->and($root->hasAttribute('label'))->toBeFalse()
        ->and($root->hasAttribute('disk'))->toBeFalse()
        ->and($html)->not->toContain('EasyMDE');
});

test('wire:model binds the source and goes to the Textareas', function (): void {
    $html = Livewire::test(MarkdownLivewireTestComponent::class)->html();
    $dom  = designSystemDom($html);
    $root = $dom->query('//*[contains(concat(" ", @class, " "), " apui-markdown ")]')->item(0);

    // Switching tabs sets the model from the Textarea that was showing before
    // committing, so an edit a debounced wire:model holds back goes with it.
    $handler = $root->getAttribute('x-on:value-change');
    $prefix  = substr($dom->query('//textarea')->item(0)->getAttribute('id'), 0, -strlen('write-source'));
    expect($root->hasAttribute('wire:model.live.debounce.500ms'))->toBeFalse()
        ->and($handler)->toContain('const from = view;')
        ->and($handler)->toContain("document.getElementById(\"{$prefix}\" + from + '-source')")
        ->and($handler)->toContain('if (input) $wire.$set("notes", input.value, false); if (\'write\' !== view) $wire.$commit()')
        // The set runs on every switch, Split to Write included: only the commit waits for a preview.
        ->and($handler)->not->toContain("if ('write' !== view && ")
        ->and(strpos($handler, '$wire.$set('))->toBeLessThan(strpos($handler, '$wire.$commit()'))
        ->and($dom->query('//*[@data-part="preview"][@x-html]')->length)->toBe(0);

    $textareas = $dom->query('//textarea');
    expect($textareas->length)->toBe(2)
        ->and($textareas->item(0)->getAttribute('wire:model.live.debounce.500ms'))->toBe('notes')
        ->and($textareas->item(0)->hasAttribute('x-model'))->toBeFalse()
        ->and($textareas->item(1)->hasAttribute('x-model'))->toBeFalse()
        ->and($root->getAttribute('x-data'))->toBe('{ view: "split" }')
        ->and($textareas->item(1)->textContent)->toBe("# Notes\n\n- [ ] Call")
        ->and($dom->query('//*[@data-part="preview"]//h1')->item(0)->textContent)->toBe('Notes')
        ->and($dom->query('//*[@data-part="tabs"]')->length)->toBe(0)
        ->and($dom->query('//*[contains(@class, "apui-tabs")]')->item(0)->hasAttribute('wire:model.live.debounce.500ms'))->toBeFalse();
});
