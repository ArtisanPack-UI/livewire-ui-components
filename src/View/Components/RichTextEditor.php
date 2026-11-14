<?php

/**
 * RichTextEditor Component
 *
 * The design-system RichTextEditor (#53): formatted text editing with a
 * toolbar over the shared prose styles, on TinyMCE when the page loads it.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\RichTextHtml;
use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Js;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Livewire;
use Throwable;

/**
 * RichTextEditor Component Class
 *
 * Renders `.apui-rich-text-editor`: the label, the box (toolbar, content,
 * placeholder and footer), the hint and the error. The `apuiRichTextEditor`
 * Alpine component runs the toolbar on TinyMCE (inline, under the
 * design-system toolbar) when the page has loaded it, else on the built-in
 * contentEditable engine React and Vue use (D-003). `wire:model` binds the
 * value through `$wire.entangle()`. `class` lands on the root, `name` on a
 * hidden input so plain forms submit the value, and other attributes on the
 * root.
 *
 * @since 3.0.0
 */
class RichTextEditor extends BaseComponent
{
    use InteractsWithFieldControl;
    use RendersDesignSystemParts;

    /**
     * Each command's icon, and whether it's a toggle.
     *
     * @since 3.0.0
     *
     * @var array<string, array{icon: string, toggle: bool}>
     */
    public const COMMANDS = [
        'bold'        => ['icon' => 'bold', 'toggle' => true],
        'italic'      => ['icon' => 'italic', 'toggle' => true],
        'underline'   => ['icon' => 'underline', 'toggle' => true],
        'strike'      => ['icon' => 'strikethrough', 'toggle' => true],
        'heading2'    => ['icon' => 'heading-2', 'toggle' => true],
        'heading3'    => ['icon' => 'heading-3', 'toggle' => true],
        'bulletList'  => ['icon' => 'list', 'toggle' => true],
        'orderedList' => ['icon' => 'list-ordered', 'toggle' => true],
        'quote'       => ['icon' => 'quote', 'toggle' => true],
        'code'        => ['icon' => 'code', 'toggle' => true],
        'link'        => ['icon' => 'link', 'toggle' => true],
        'image'       => ['icon' => 'image', 'toggle' => false],
        'undo'        => ['icon' => 'undo-2', 'toggle' => false],
        'redo'        => ['icon' => 'redo-2', 'toggle' => false],
    ];

    /**
     * The toolbar presets.
     *
     * @since 3.0.0
     *
     * @var array<string, list<string>>
     */
    public const TOOLBARS = [
        'default' => ['bold', 'italic', 'underline', 'strike', '|', 'heading2', 'heading3', '|', 'bulletList', 'orderedList', 'quote', 'code', '|', 'link', 'image', '|', 'undo', 'redo'],
        'compact' => ['bold', 'italic', 'link', '|', 'bulletList'],
        'minimal' => ['bold', 'italic', 'link'],
    ];

    /**
     * The `*Label` prop for each built-in string.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public const LABEL_PROPS = [
        'toolbar'        => 'toolbarLabel',
        'bold'           => 'boldLabel',
        'italic'         => 'italicLabel',
        'underline'      => 'underlineLabel',
        'strike'         => 'strikeLabel',
        'heading2'       => 'heading2Label',
        'heading3'       => 'heading3Label',
        'bulletList'     => 'bulletListLabel',
        'orderedList'    => 'orderedListLabel',
        'quote'          => 'quoteLabel',
        'code'           => 'codeLabel',
        'link'           => 'linkLabel',
        'image'          => 'imageLabel',
        'undo'           => 'undoLabel',
        'redo'           => 'redoLabel',
        'linkPrompt'     => 'linkPromptLabel',
        'imagePrompt'    => 'imagePromptLabel',
        'imageAltPrompt' => 'imageAltPromptLabel',
        'shortcuts'      => 'shortcutsLabel',
        'word'           => 'wordLabel',
        'words'          => 'wordsLabel',
        'character'      => 'characterLabel',
        'characters'     => 'charactersLabel',
        'limit'          => 'limitLabel',
    ];

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'rich-text-editor';

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  array<int, string>|string  $toolbar  A preset (default, compact, minimal) or a list of commands and | dividers.
     * @param  string  $output  The value's format: html or markdown.
     * @param  int|null  $maxLength  The most characters allowed.
     * @param  bool  $counter  Shows the word and character count.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables editing and the toolbar.
     * @param  bool  $readonly  Shows the content without editing it.
     * @param  bool  $required  Marks the field required.
     * @param  string|null  $placeholder  Text shown while the content is empty.
     * @param  string|null  $value  The value in the output format. Livewire binds it with wire:model.
     * @param  string|null  $toolbarLabel  The toolbar's accessible name. Defaults to __( 'Formatting' ).
     * @param  string|null  $boldLabel  The Bold button's accessible name. Defaults to __( 'Bold' ).
     * @param  string|null  $italicLabel  The Italic button's accessible name. Defaults to __( 'Italic' ).
     * @param  string|null  $underlineLabel  The Underline button's accessible name. Defaults to __( 'Underline' ).
     * @param  string|null  $strikeLabel  The Strikethrough button's accessible name. Defaults to __( 'Strikethrough' ).
     * @param  string|null  $heading2Label  The Heading 2 button's accessible name. Defaults to __( 'Heading 2' ).
     * @param  string|null  $heading3Label  The Heading 3 button's accessible name. Defaults to __( 'Heading 3' ).
     * @param  string|null  $bulletListLabel  The bulleted list button's accessible name. Defaults to __( 'Bulleted list' ).
     * @param  string|null  $orderedListLabel  The numbered list button's accessible name. Defaults to __( 'Numbered list' ).
     * @param  string|null  $quoteLabel  The Quote button's accessible name. Defaults to __( 'Quote' ).
     * @param  string|null  $codeLabel  The Code button's accessible name. Defaults to __( 'Code' ).
     * @param  string|null  $linkLabel  The Link button's accessible name. Defaults to __( 'Link' ).
     * @param  string|null  $imageLabel  The Image button's accessible name. Defaults to __( 'Image' ).
     * @param  string|null  $undoLabel  The Undo button's accessible name. Defaults to __( 'Undo' ).
     * @param  string|null  $redoLabel  The Redo button's accessible name. Defaults to __( 'Redo' ).
     * @param  string|null  $linkPromptLabel  The prompt that asks for a link URL (built-in engine). Defaults to __( 'Link URL' ).
     * @param  string|null  $imagePromptLabel  The prompt that asks for an image URL (built-in engine). Defaults to __( 'Image URL' ).
     * @param  string|null  $imageAltPromptLabel  The prompt that asks for an image description (built-in engine). Defaults to __( 'Describe the image' ).
     * @param  string|null  $shortcutsLabel  The footer note about Markdown shortcuts. Defaults to __( 'Markdown shortcuts on' ).
     * @param  string|null  $wordLabel  The footer's word count for one word; :count is the number. Defaults to __( ':count word' ).
     * @param  string|null  $wordsLabel  The footer's word count; :count is the number. Defaults to __( ':count words' ).
     * @param  string|null  $characterLabel  The footer's character count for one character. Defaults to __( ':count character' ).
     * @param  string|null  $charactersLabel  The footer's character count; :count is the number. Defaults to __( ':count characters' ).
     * @param  string|null  $limitLabel  The footer's count with maxLength; :count and :max are numbers. Defaults to __( ':count / :max characters' ).
     * @param  string  $engine  tinymce (when the page loads it) or native.
     * @param  array<string, mixed>|null  $config  Extra TinyMCE settings.
     * @param  string|null  $disk  The storage disk for TinyMCE image uploads. Null lets the upload route pick the first `uploads.disks` entry.
     * @param  string|null  $folder  The folder on the disk for uploads. Null means `editor`.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string|array $toolbar = 'default',
        public string $output = 'html',
        public ?int $maxLength = null,
        public bool $counter = false,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $required = false,
        public ?string $placeholder = null,
        public ?string $value = null,
        public ?string $toolbarLabel = null,
        public ?string $boldLabel = null,
        public ?string $italicLabel = null,
        public ?string $underlineLabel = null,
        public ?string $strikeLabel = null,
        public ?string $heading2Label = null,
        public ?string $heading3Label = null,
        public ?string $bulletListLabel = null,
        public ?string $orderedListLabel = null,
        public ?string $quoteLabel = null,
        public ?string $codeLabel = null,
        public ?string $linkLabel = null,
        public ?string $imageLabel = null,
        public ?string $undoLabel = null,
        public ?string $redoLabel = null,
        public ?string $linkPromptLabel = null,
        public ?string $imagePromptLabel = null,
        public ?string $imageAltPromptLabel = null,
        public ?string $shortcutsLabel = null,
        public ?string $wordLabel = null,
        public ?string $wordsLabel = null,
        public ?string $characterLabel = null,
        public ?string $charactersLabel = null,
        public ?string $limitLabel = null,
        public string $engine = 'tinymce',
        public ?array $config = [],
        public ?string $disk = null,
        public ?string $folder = 'editor',
        public ?string $errorField = null,
        public array $classes = [],
    ) {}

    /**
     * The toolbar to render: commands and `|` dividers, without formats the
     * output can't hold or stray dividers. Mirrors `resolveToolbar()`.
     *
     * @since 3.0.0
     *
     * @return list<string> The items.
     */
    public function toolbarItems(): array
    {
        $list  = is_array($this->toolbar) ? array_values($this->toolbar) : (self::TOOLBARS[$this->toolbar] ?? self::TOOLBARS['default']);
        $items = array_values(array_filter(
            $list,
            fn (mixed $item): bool => '|' === $item || (is_string($item) && isset(self::COMMANDS[$item]) && ! ('markdown' === $this->outputFormat() && 'underline' === $item)),
        ));

        $kept = [];
        foreach ($items as $i => $item) {
            if ('|' === $item && (0 === $i || $i === count($items) - 1 || '|' === $items[$i - 1])) {
                continue;
            }
            $kept[] = $item;
        }

        return $kept;
    }

    /**
     * A command's icon name.
     *
     * @since 3.0.0
     */
    public function commandIcon(string $command): string
    {
        return self::COMMANDS[$command]['icon'] ?? $command;
    }

    /**
     * Whether a command is a toggle, with aria-pressed.
     *
     * @since 3.0.0
     */
    public function isToggle(string $command): bool
    {
        return self::COMMANDS[$command]['toggle'] ?? false;
    }

    /**
     * The output format, html unless markdown.
     *
     * @since 3.0.0
     */
    public function outputFormat(): string
    {
        return 'markdown' === $this->output ? 'markdown' : 'html';
    }

    /**
     * The built-in strings, translated, with the `*Label` props over them.
     *
     * @since 3.0.0
     *
     * @return array<string, string> The strings by key.
     */
    public function strings(): array
    {
        return array_merge(
            [
                'toolbar'        => __('Formatting'),
                'bold'           => __('Bold'),
                'italic'         => __('Italic'),
                'underline'      => __('Underline'),
                'strike'         => __('Strikethrough'),
                'heading2'       => __('Heading 2'),
                'heading3'       => __('Heading 3'),
                'bulletList'     => __('Bulleted list'),
                'orderedList'    => __('Numbered list'),
                'quote'          => __('Quote'),
                'code'           => __('Code'),
                'link'           => __('Link'),
                'image'          => __('Image'),
                'undo'           => __('Undo'),
                'redo'           => __('Redo'),
                'linkPrompt'     => __('Link URL'),
                'imagePrompt'    => __('Image URL'),
                'imageAltPrompt' => __('Describe the image'),
                'shortcuts'      => __('Markdown shortcuts on'),
                'word'           => __(':count word'),
                'words'          => __(':count words'),
                'character'      => __(':count character'),
                'characters'     => __(':count characters'),
                'limit'          => __(':count / :max characters'),
            ],
            $this->labelOverrides(),
        );
    }

    /**
     * The value now: the bound Livewire property, else `value`.
     *
     * @since 3.0.0
     */
    public function currentValue(): string
    {
        $model = $this->modelName();
        if (null !== $model) {
            try {
                $component = class_exists(Livewire::class) ? Livewire::current() : null;
            } catch (Throwable) {
                $component = null;
            }
            if (is_object($component)) {
                $bound = data_get($component, $model);

                return is_string($bound) ? $bound : '';
            }
        }

        return $this->value ?? '';
    }

    /**
     * The content's initial HTML, sanitized.
     *
     * @since 3.0.0
     */
    public function contentHtml(): string
    {
        return RichTextHtml::toHtml($this->currentValue(), $this->outputFormat());
    }

    /**
     * The footer's count, e.g. "78 words · 486 characters". Mirrors `countLabel()`.
     *
     * @since 3.0.0
     *
     * @param  string  $html  The content.
     */
    public function countLabel(string $html): string
    {
        $strings    = $this->strings();
        $text       = html_entity_decode(strip_tags((string) preg_replace('/<\/(p|h[2-4]|li|blockquote|pre)>|<br>/', "$0\n", $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $trimmed    = trim((string) preg_replace('/\s+/u', ' ', $text));
        $words      = '' === $trimmed ? 0 : count(explode(' ', $trimmed));
        $characters = mb_strlen(str_replace("\n", '', $text));
        $wordLabel  = str_replace(':count', (string) $words, 1 === $words ? $strings['word'] : $strings['words']);
        $charLabel  = null !== $this->maxLength
            ? str_replace([':count', ':max'], [(string) $characters, (string) $this->maxLength], $strings['limit'])
            : str_replace(':count', (string) $characters, 1 === $characters ? $strings['character'] : $strings['characters']);

        return $wordLabel.' · '.$charLabel;
    }

    /**
     * The value as JavaScript: `$wire.entangle('model')` (with `.live` for
     * `wire:model.live`) when there's a wire:model, else the `value` prop.
     *
     * @since 3.0.0
     */
    public function valueExpression(): string
    {
        $model = $this->modelName();
        if (null === $model) {
            return Js::from($this->value ?? '')->toHtml();
        }

        $live = [] !== array_filter(
            array_keys($this->attributes->whereStartsWith('wire:model')->getAttributes()),
            static fn (string $key): bool => str_contains($key, '.live'),
        );

        return '$wire.entangle('.Js::from($model)->toHtml().')'.($live ? '.live' : '');
    }

    /**
     * The `x-data` expression for `apuiRichTextEditor()`.
     *
     * @since 3.0.0
     */
    public function alpineData(): string
    {
        $uploadUrl = Route::has('artisanpack.upload') ? route('artisanpack.upload', absolute: false) : null;

        return sprintf(
            'apuiRichTextEditor({ value: %s, output: %s, engine: %s, maxLength: %s, disabled: %s, readonly: %s, translations: %s, config: %s, uploadUrl: %s, uploadFields: %s })',
            $this->valueExpression(),
            Js::from($this->outputFormat())->toHtml(),
            Js::from('native' === $this->engine ? 'native' : 'tinymce')->toHtml(),
            null === $this->maxLength ? 'null' : (string) $this->maxLength,
            $this->disabled ? 'true' : 'false',
            $this->readonly ? 'true' : 'false',
            Js::from($this->strings())->toHtml(),
            Js::from((object) ($this->config ?? []))->toHtml(),
            Js::from($uploadUrl)->toHtml(),
            Js::from($this->uploadFields())->toHtml(),
        );
    }

    /**
     * The fields sent with each TinyMCE upload: the folder, and the disk only
     * when one is set, so the upload route falls back to its first allowed disk.
     *
     * @since 3.0.0
     *
     * @return array<string, string> The fields.
     */
    public function uploadFields(): array
    {
        $fields = ['folder' => null !== $this->folder && '' !== $this->folder ? $this->folder : 'editor'];
        if (null !== $this->disk && '' !== $this->disk) {
            $fields['disk'] = $this->disk;
        }

        return $fields;
    }

    /**
     * The root's attributes: everything but `wire:model`, `id` and `name`.
     *
     * @since 3.0.0
     */
    public function rootAttributes(): ComponentAttributeBag
    {
        return $this->attributes->whereDoesntStartWith('wire:model')->except(['id', 'name', 'aria-describedby']);
    }

    /**
     * The built-in strings the `*Label` props set.
     *
     * @since 3.0.0
     *
     * @return array<string, string> The strings by key.
     */
    public function labelOverrides(): array
    {
        $labels = [];
        foreach (self::LABEL_PROPS as $key => $prop) {
            $text = $this->{$prop};
            if (is_string($text) && '' !== $text) {
                $labels[$key] = $text;
            }
        }

        return $labels;
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.rich-text-editor');
    }
}
