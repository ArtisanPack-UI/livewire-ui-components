<?php

/**
 * CodeEditor Component
 *
 * The design-system CodeEditor (#53): editable code in a native textarea
 * with file tabs, a language select, line numbers, diagnostics and a status
 * bar.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;

/**
 * CodeEditor Component Class
 *
 * Renders `.apui-code-editor`: the label, the box (header with tabs and the
 * language select, the gutter beside the textarea, the diagnostics and the
 * status bar), the hint and the error. The textarea is the native control,
 * so `wire:model` binds it without Alpine (D-018); `apuiCodeEditor` adds Tab
 * indentation, the gutter, the caret position and the tablist. `class` and
 * `x-on:*` listeners land on the root, where `tab-change` and
 * `language-change` are dispatched; every other attribute lands on the
 * textarea.
 *
 * @since 3.0.0
 */
class CodeEditor extends BaseComponent
{
    use InteractsWithFieldControl;
    use RendersDesignSystemParts;

    /**
     * The `*Label` prop for each built-in string.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public const LABEL_PROPS = [
        'tabs'            => 'tabsLabel',
        'language'        => 'languageLabel',
        'position'        => 'positionLabel',
        'spaces'          => 'spacesLabel',
        'error'           => 'errorCountLabel',
        'errors'          => 'errorsCountLabel',
        'warning'         => 'warningCountLabel',
        'warnings'        => 'warningsCountLabel',
        'problems'        => 'problemsLabel',
        'line'            => 'lineLabel',
        'severityError'   => 'severityErrorLabel',
        'severityWarning' => 'severityWarningLabel',
        'severityInfo'    => 'severityInfoLabel',
        'instructions'    => 'instructionsLabel',
    ];

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'code-editor';

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $language  The language, shown in the status bar and selected in the select.
     * @param  array<int, array{value: string, label?: string}|string>  $languages  Options for the language select.
     * @param  array<int, array{value: string, label?: string}|string>  $tabs  File tabs.
     * @param  string|null  $tab  The selected tab's value. Defaults to the first tab.
     * @param  array<int, array{line: int, column?: int, message: string, severity?: string}>  $diagnostics  Problems by line.
     * @param  int  $tabSize  Spaces Tab inserts.
     * @param  int  $rows  The fewest lines shown.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the control.
     * @param  bool  $readonly  Makes the code read-only.
     * @param  bool  $required  Marks the field required.
     * @param  string|null  $placeholder  Placeholder text.
     * @param  string|null  $value  The code. Livewire binds it with wire:model.
     * @param  string|null  $tabsLabel  The file tablist's accessible name. Defaults to __( 'Open files' ).
     * @param  string|null  $languageLabel  The language select's accessible name. Defaults to __( 'Language' ).
     * @param  string|null  $positionLabel  The caret position in the status bar; :line and :column are numbers. Defaults to __( 'Ln :line, Col :column' ).
     * @param  string|null  $spacesLabel  The indentation in the status bar; :count is tabSize. Defaults to __( 'Spaces: :count' ).
     * @param  string|null  $errorCountLabel  The status bar's count for one error. Defaults to __( ':count error' ).
     * @param  string|null  $errorsCountLabel  The status bar's error count; :count is the number. Defaults to __( ':count errors' ).
     * @param  string|null  $warningCountLabel  The status bar's count for one warning. Defaults to __( ':count warning' ).
     * @param  string|null  $warningsCountLabel  The status bar's warning count; :count is the number. Defaults to __( ':count warnings' ).
     * @param  string|null  $problemsLabel  The diagnostics list's accessible name. Defaults to __( 'Problems' ).
     * @param  string|null  $lineLabel  A diagnostic's line; :line is the number. Defaults to __( 'Line :line' ).
     * @param  string|null  $severityErrorLabel  The visually hidden severity name of an error. Defaults to __( 'Error' ).
     * @param  string|null  $severityWarningLabel  The visually hidden severity name of a warning. Defaults to __( 'Warning' ).
     * @param  string|null  $severityInfoLabel  The visually hidden severity name of an info diagnostic. Defaults to __( 'Info' ).
     * @param  string|null  $instructionsLabel  The visually hidden keyboard instructions linked to the control. Defaults to __( 'Tab inserts indentation. To leave the editor, press Escape, then Tab.' ).
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $language = null,
        public array $languages = [],
        public array $tabs = [],
        public ?string $tab = null,
        public array $diagnostics = [],
        public int $tabSize = 2,
        public int $rows = 12,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $required = false,
        public ?string $placeholder = null,
        public ?string $value = null,
        public ?string $tabsLabel = null,
        public ?string $languageLabel = null,
        public ?string $positionLabel = null,
        public ?string $spacesLabel = null,
        public ?string $errorCountLabel = null,
        public ?string $errorsCountLabel = null,
        public ?string $warningCountLabel = null,
        public ?string $warningsCountLabel = null,
        public ?string $problemsLabel = null,
        public ?string $lineLabel = null,
        public ?string $severityErrorLabel = null,
        public ?string $severityWarningLabel = null,
        public ?string $severityInfoLabel = null,
        public ?string $instructionsLabel = null,
        public ?string $errorField = null,
        public array $classes = [],
    ) {}

    /**
     * Options given as strings or `[ 'value' => …, 'label' => … ]`.
     *
     * @since 3.0.0
     *
     * @param  array<int, mixed>  $options  The options.
     *
     * @return list<array{value: string, label: string}> The normalized options.
     */
    public static function options(array $options): array
    {
        $normalized = [];
        foreach ($options as $option) {
            if (is_string($option) || is_int($option)) {
                $normalized[] = ['value' => (string) $option, 'label' => (string) $option];
            } elseif (is_array($option) && isset($option['value'])) {
                $normalized[] = ['value' => (string) $option['value'], 'label' => (string) ($option['label'] ?? $option['value'])];
            }
        }

        return $normalized;
    }

    /**
     * The file tabs.
     *
     * @since 3.0.0
     *
     * @return list<array{value: string, label: string}>
     */
    public function tabList(): array
    {
        return self::options($this->tabs);
    }

    /**
     * The language options.
     *
     * @since 3.0.0
     *
     * @return list<array{value: string, label: string}>
     */
    public function languageList(): array
    {
        return self::options($this->languages);
    }

    /**
     * The selected tab: `tab`, else the first tab's value.
     *
     * @since 3.0.0
     */
    public function selectedTab(): ?string
    {
        return $this->tab ?? ($this->tabList()[0]['value'] ?? null);
    }

    /**
     * The language as the status bar shows it: its option label, else the value.
     *
     * @since 3.0.0
     */
    public function languageName(): ?string
    {
        if (null === $this->language || '' === $this->language) {
            return null;
        }
        foreach ($this->languageList() as $option) {
            if ($option['value'] === $this->language) {
                return $option['label'];
            }
        }

        return $this->language;
    }

    /**
     * The diagnostics that have a line and a message.
     *
     * @since 3.0.0
     *
     * @return list<array{line: int, column: int|null, message: string, severity: string}>
     */
    public function diagnosticList(): array
    {
        $list = [];
        foreach ($this->diagnostics as $diagnostic) {
            if (! is_array($diagnostic) || ! isset($diagnostic['line'], $diagnostic['message'])) {
                continue;
            }
            $severity = in_array($diagnostic['severity'] ?? 'error', ['error', 'warning', 'info'], true) ? ($diagnostic['severity'] ?? 'error') : 'error';
            $list[]   = [
                'line'     => max(1, (int) $diagnostic['line']),
                'column'   => isset($diagnostic['column']) ? (int) $diagnostic['column'] : null,
                'message'  => (string) $diagnostic['message'],
                'severity' => $severity,
            ];
        }

        return $list;
    }

    /**
     * The most severe diagnostic on each line. Mirrors `severityByLine()`.
     *
     * @since 3.0.0
     *
     * @return array<int, string> Severity by line.
     */
    public function severityByLine(): array
    {
        $rank  = ['error' => 3, 'warning' => 2, 'info' => 1];
        $lines = [];
        foreach ($this->diagnosticList() as $diagnostic) {
            $current = $lines[$diagnostic['line']] ?? null;
            if (null === $current || $rank[$diagnostic['severity']] > $rank[$current]) {
                $lines[$diagnostic['line']] = $diagnostic['severity'];
            }
        }

        return $lines;
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
                'tabs'            => __('Open files'),
                'language'        => __('Language'),
                'position'        => __('Ln :line, Col :column'),
                'spaces'          => __('Spaces: :count'),
                'error'           => __(':count error'),
                'errors'          => __(':count errors'),
                'warning'         => __(':count warning'),
                'warnings'        => __(':count warnings'),
                'problems'        => __('Problems'),
                'line'            => __('Line :line'),
                'severityError'   => __('Error'),
                'severityWarning' => __('Warning'),
                'severityInfo'    => __('Info'),
                'instructions'    => __('Tab inserts indentation. To leave the editor, press Escape, then Tab.'),
            ],
            $this->labelOverrides(),
        );
    }

    /**
     * The status bar's problem count, e.g. "1 error · 2 warnings". Mirrors `problemsLabel()`.
     *
     * @since 3.0.0
     */
    public function problemsLabel(): string
    {
        $strings  = $this->strings();
        $list     = $this->diagnosticList();
        $errors   = count(array_filter($list, static fn (array $d): bool => 'error' === $d['severity']));
        $warnings = count(array_filter($list, static fn (array $d): bool => 'warning' === $d['severity']));
        $parts    = [];
        if ($errors > 0) {
            $parts[] = str_replace(':count', (string) $errors, 1 === $errors ? $strings['error'] : $strings['errors']);
        }
        if ($warnings > 0) {
            $parts[] = str_replace(':count', (string) $warnings, 1 === $warnings ? $strings['warning'] : $strings['warnings']);
        }

        return implode(' · ', $parts);
    }

    /**
     * The number of gutter lines: the value's lines, never fewer than `rows`.
     *
     * @since 3.0.0
     */
    public function lineTotal(): int
    {
        return max(substr_count((string) $this->value, "\n") + 1, max(1, $this->rows));
    }

    /**
     * The `x-data` expression for `apuiCodeEditor()`.
     *
     * @since 3.0.0
     */
    public function alpineData(): string
    {
        return sprintf(
            'apuiCodeEditor({ tabSize: %d, rows: %d, tab: %s, translations: %s })',
            max(1, $this->tabSize),
            max(1, $this->rows),
            Js::from($this->selectedTab())->toHtml(),
            Js::from($this->strings())->toHtml(),
        );
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
        return view('livewire-ui-components::components.code-editor');
    }
}
