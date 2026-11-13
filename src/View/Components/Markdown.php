<?php

/**
 * Markdown Component
 *
 * The design-system Markdown (#65): GitHub-flavored Markdown in the shared
 * prose styles, with optional write and split modes.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\Markdown\MarkdownRenderer;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;

/**
 * Markdown Component Class
 *
 * Renders the `.apui-markdown` markup contract: the Markdown as HTML from
 * {@see MarkdownRenderer} (league/commonmark, GFM, raw HTML escaped, unsafe
 * URLs dropped), the same HTML React and Vue write with the behavior
 * package's `renderMarkdown`. In preview mode the root carries `.apui-prose`
 * and holds the HTML, rendered on the server with no script.
 *
 * `mode="write"` or `"split"` adds the design-system Tabs (Write, Preview,
 * Split) over a Textarea. `wire:model` on the component binds the source: it
 * goes to the Textarea, and `source` defaults to the bound property, so a
 * Livewire update redraws the preview. Switching to Preview or Split sends a
 * deferred `wire:model` first. Without `wire:model` (a plain form) the
 * Textareas share Alpine state and `apuiMarkdown` renders the draft with the
 * behavior package's `renderMarkdown`, so Preview and Split show it. Native
 * control attributes (`name`, `required`, `disabled`, `placeholder`,
 * `maxlength`, `rows`, …) go to the Textareas, not the root. The 2.x EasyMDE editor (config, uploads,
 * label, hint and the error props) is gone.
 *
 * @since 1.0.0
 */
class Markdown extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * 2.x EasyMDE attributes the component drops instead of passing to the root.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const REMOVED_ATTRIBUTES = [
        'label', 'hint', 'hint-class', 'hintClass', 'disk', 'folder', 'config',
        'error-field', 'errorField', 'error-class', 'errorClass', 'omit-error', 'omitError',
        'first-error-only', 'firstErrorOnly',
    ];

    /**
     * The modes.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const MODES = ['preview', 'write', 'split'];

    /**
     * Native attributes that go to the Textareas' control, not the root,
     * besides `wire:model`.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const CONTROL_ATTRIBUTES = [
        'name', 'maxlength', 'minlength', 'autofocus', 'autocomplete', 'autocapitalize', 'spellcheck', 'form',
        'inputmode', 'enterkeyhint', 'wrap', 'dirname',
    ];

    /**
     * Native attributes that go to the Textareas as props.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const CONTROL_PROPS = ['disabled', 'readonly', 'required', 'placeholder', 'rows'];

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'markdown';

    /**
     * Props that change between renders without changing the id.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['source', 'tabsLabel', 'writeLabel', 'previewLabel', 'splitLabel', 'editorLabel', 'outputLabel'];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  string|null  $source  The Markdown; defaults to the `wire:model` property.
     * @param  string  $mode  preview, write or split.
     * @param  bool  $sanitize  Escape raw HTML (true) or keep allowlisted formatting tags (false).
     * @param  string|null  $tabsLabel  Names the mode tabs.
     * @param  string|null  $writeLabel  The Write tab.
     * @param  string|null  $previewLabel  The Preview tab.
     * @param  string|null  $splitLabel  The Split tab.
     * @param  string|null  $editorLabel  Names the source Textarea.
     * @param  string|null  $outputLabel  Names the preview region in write and split modes.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $source = null,
        public string $mode = 'preview',
        public bool $sanitize = true,
        public ?string $tabsLabel = null,
        public ?string $writeLabel = null,
        public ?string $previewLabel = null,
        public ?string $splitLabel = null,
        public ?string $editorLabel = null,
        public ?string $outputLabel = null,
        public array $classes = [],
    ) {
        $this->tabsLabel ??= __('Markdown mode');
        $this->writeLabel ??= __('Write');
        $this->previewLabel ??= __('Preview');
        $this->splitLabel ??= __('Split');
        $this->editorLabel ??= __('Markdown source');
        $this->outputLabel ??= __('Rendered preview');
    }

    /**
     * The mode, falling back to preview.
     *
     * @since 3.0.0
     *
     * @return string preview, write or split.
     */
    public function modeValue(): string
    {
        return in_array($this->mode, self::MODES, true) ? $this->mode : 'preview';
    }

    /**
     * Whether the tabs and the Textarea render.
     *
     * @since 3.0.0
     *
     * @return bool True in write and split modes.
     */
    public function editing(): bool
    {
        return 'preview' !== $this->modeValue();
    }

    /**
     * The Markdown: `source`, else the bound Livewire property.
     *
     * @since 3.0.0
     *
     * @return string The source.
     */
    public function sourceText(): string
    {
        if (null !== $this->source) {
            return $this->source;
        }

        $model = $this->modelName();
        if (null !== $model) {
            $component = $this->livewireComponent();
            $bound     = null !== $component ? data_get($component, $model) : null;

            return is_scalar($bound) ? (string) $bound : '';
        }

        return '';
    }

    /**
     * The rendered Markdown.
     *
     * @since 3.0.0
     *
     * @return HtmlString The HTML.
     */
    public function html(): HtmlString
    {
        return new HtmlString(MarkdownRenderer::render($this->sourceText(), $this->sanitize));
    }

    /**
     * The attributes for the root: everything but the Textarea's and the
     * removed 2.x ones.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): ComponentAttributeBag
    {
        return $this->attributes
            ->whereDoesntStartWith('wire:model')
            ->except([...self::CONTROL_ATTRIBUTES, ...self::CONTROL_PROPS, ...self::REMOVED_ATTRIBUTES]);
    }

    /**
     * The attributes the Textareas pass to the native control: `wire:model`
     * (any modifiers), `name` and the other {@see self::CONTROL_ATTRIBUTES}.
     * The view reads them before rendering the Tabs, because Blade removes a
     * child component's constructor parameter names (Tab's `name`) from the
     * parent's `$attributes`.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function controlAttributes(): ComponentAttributeBag
    {
        return $this->attributes->filter(
            fn ($value, $key) => str_starts_with($key, 'wire:model') || in_array($key, self::CONTROL_ATTRIBUTES, true),
        );
    }

    /**
     * The Textarea props given on the component: `disabled`, `readonly`,
     * `required`, `placeholder` and `rows` (default 12). Read with
     * {@see self::controlAttributes()}, before the Tabs render.
     *
     * @since 3.0.0
     *
     * @return array{disabled: bool, readonly: bool, required: bool, placeholder: string|null, rows: int} The props.
     */
    public function controlProps(): array
    {
        $placeholder = $this->attributes->get('placeholder');
        $rows        = $this->attributes->get('rows');

        return [
            'disabled'    => $this->flag('disabled'),
            'readonly'    => $this->flag('readonly'),
            'required'    => $this->flag('required'),
            'placeholder' => is_scalar($placeholder) && '' !== (string) $placeholder ? (string) $placeholder : null,
            'rows'        => is_numeric($rows) && (int) $rows > 0 ? (int) $rows : 12,
        ];
    }

    /**
     * The root's Alpine state in write and split modes: the selected tab,
     * which decides which Textarea is enabled, so a form posts the source
     * once. Without `wire:model` it's `apuiMarkdown` from the behavior
     * package, which also holds the one `source` both Textareas bind to and
     * renders it, so Preview and Split show the draft.
     *
     * @since 3.0.0
     *
     * @return string The x-data expression.
     */
    public function alpineData(): string
    {
        $view = json_encode($this->modeValue());
        if ($this->bindsLocally()) {
            $source = self::js($this->sourceText());

            return 'apuiMarkdown({ view: '.$view.', source: '.$source.', sanitize: '.($this->sanitize ? 'true' : 'false').' })';
        }

        return '{ view: '.$view.' }';
    }

    /**
     * The Textarea's disabled binding for a tab's panel. Write's Textarea
     * stays enabled on Preview, so a form posts the source whichever tab is
     * selected; Split's is enabled only on Split.
     *
     * @since 3.0.0
     *
     * @param  string  $tab  write or split.
     *
     * @return string The x-bind:disabled expression.
     */
    public function disabledBinding(string $tab): string
    {
        if ($this->flag('disabled')) {
            return 'true';
        }

        return 'split' === $tab ? "view !== 'split'" : "view === 'split'";
    }

    /**
     * Whether the Textareas share Alpine's `source` (x-model) instead of a
     * Livewire property: true when there is no `wire:model`.
     *
     * @since 3.0.0
     *
     * @return bool True without `wire:model`.
     */
    public function bindsLocally(): bool
    {
        return null === $this->modelName();
    }

    /**
     * The Alpine handler for the tabs' value-change: follows the tab and,
     * with `wire:model`, sends the source so the preview catches up. The
     * Textarea that was showing is read on every switch (Split to Write
     * too), so an edit a debounced `wire:model` hasn't set yet isn't lost;
     * only a switch to a tab that shows the preview commits.
     *
     * @since 3.0.0
     *
     * @return string The handler.
     */
    public function tabChangeHandler(): string
    {
        $handler = "if (\$event.target.matches('.apui-tabs')) { const from = view; view = \$event.detail.value";
        $model   = $this->modelName();
        if (null !== $model) {
            $handler .= "; if (\$el.closest('[wire\\\\:id]')) {"
                ." const input = 'preview' === from ? null : document.getElementById(".self::js($this->baseId().'-')." + from + '-source');"
                .' if (input) $wire.$set('.self::js($model).', input.value, false);'
                ." if ('write' !== view) \$wire.\$commit() }";
        }

        return $handler.' }';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.markdown');
    }

    /**
     * Whether a boolean attribute is on: present, and not `false` or `"false"`.
     *
     * @param  string  $key  The attribute.
     *
     * @return bool True when set.
     */
    private function flag(string $key): bool
    {
        if (! $this->attributes->has($key)) {
            return false;
        }

        $value = $this->attributes->get($key);

        return ! (false === $value || null === $value || (is_string($value) && in_array(strtolower($value), ['false', '0'], true)));
    }

    /**
     * A value as a JavaScript literal safe inside an HTML attribute.
     *
     * @param  mixed  $value  The value.
     *
     * @return string The literal.
     */
    private static function js(mixed $value): string
    {
        return (string) json_encode(
            $value,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE,
        );
    }
}
