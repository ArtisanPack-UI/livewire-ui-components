<?php

/**
 * Code Component
 *
 * The design-system Code (#65): read-only code, as a block or inline.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;

/**
 * Code Component Class
 *
 * Renders `.apui-code`: a block with a header (file name, language badge,
 * copy button and a hidden live region) and a labeled, focusable `pre` with
 * one span per line, or, with `inline`, a code element. lines() is the PHP
 * port of the behavior package's `codeLines`. No syntax highlighting. The
 * copy button runs on the shared `apuiClipboard` Alpine component, reading
 * the rendered lines back, as Clipboard and the React and Vue components
 * copy. The code stays LTR (D-004). Every attribute lands on the root.
 *
 * 2.x Code was an Ace editor bound with `wire:model`: use CodeEditor for
 * that. Its label, hint, theme, height, line-height and print-margin props
 * are gone, and are left off the root.
 *
 * @since 1.0.0
 */
class Code extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The 2.x editor props, left off the root.
     *
     * @since 3.0.0
     */
    public const DEPRECATED_ATTRIBUTES = ['hint', 'light-theme', 'lightTheme', 'dark-theme', 'darkTheme', 'light-class', 'lightClass', 'dark-class', 'darkClass', 'height', 'line-height', 'lineHeight', 'print-margin', 'printMargin'];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  string|null  $code  The code. The default slot replaces it when empty.
     * @param  string|null  $language  The language badge.
     * @param  string|null  $fileName  The file name or title.
     * @param  bool  $numbered  Show line numbers.
     * @param  array<int, mixed>  $highlight  Line numbers to emphasize.
     * @param  bool  $inline  Inline code in running text.
     * @param  bool  $copyable  Show the copy button.
     * @param  string|null  $label  Names the scroll region.
     * @param  string|null  $copyLabel  The copy button's label.
     * @param  string|null  $copiedLabel  The label after a copy.
     * @param  string|null  $copiedMessage  Announced after a copy.
     * @param  string|null  $errorMessage  Announced when the copy fails.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $code = null,
        public ?string $language = null,
        public ?string $fileName = null,
        public bool $numbered = false,
        public array $highlight = [],
        public bool $inline = false,
        public bool $copyable = true,
        public ?string $label = null,
        public ?string $copyLabel = null,
        public ?string $copiedLabel = null,
        public ?string $copiedMessage = null,
        public ?string $errorMessage = null,
        public array $classes = [],
    ) {
        $this->copyLabel ??= __('Copy');
        $this->copiedLabel ??= __('Copied');
        $this->copiedMessage ??= __('Copied to clipboard');
        $this->errorMessage ??= __('Couldn\'t copy. Select the text and copy it yourself.');
    }

    /**
     * The code's lines: split on any line ending, without the empty line a
     * final line ending would add. Matches `codeLines` in the behavior package.
     *
     * @since 3.0.0
     *
     * @param  string|null  $code  The code.
     *
     * @return list<string> The lines.
     */
    public static function lines(?string $code): array
    {
        if (null === $code || '' === $code) {
            return [];
        }

        $lines = preg_split('/\r\n|\r|\n/', $code) ?: [$code];
        if (count($lines) > 1 && '' === end($lines)) {
            array_pop($lines);
        }

        return array_values($lines);
    }

    /**
     * The code to show: the `code` prop, else the slot's text with one
     * leading and trailing line ending trimmed.
     *
     * @since 3.0.0
     *
     * @param  mixed  $slot  The default slot.
     */
    public function source(mixed $slot = null): string
    {
        if (null !== $this->code && '' !== $this->code) {
            return $this->code;
        }

        if ($slot instanceof Htmlable) {
            $text = html_entity_decode($slot->toHtml(), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            return (string) preg_replace('/^\r?\n|\r?\n[ \t]*$/', '', $text);
        }

        return '';
    }

    /**
     * The highlighted line numbers, as a lookup.
     *
     * @since 3.0.0
     *
     * @return array<int, true> Line numbers (1-based).
     */
    public function highlighted(): array
    {
        $lines = [];
        foreach ($this->highlight as $value) {
            if (is_int($value) || (is_string($value) && ctype_digit(trim($value)))) {
                $line = (int) $value;
                if ($line > 0) {
                    $lines[$line] = true;
                }
            }
        }

        return $lines;
    }

    /**
     * The scroll region's name: the label, else the file name, else the
     * language, else "Code".
     *
     * @since 3.0.0
     */
    public function regionLabel(): string
    {
        foreach ([$this->label, $this->fileName, $this->language] as $value) {
            if (is_string($value) && '' !== trim($value)) {
                return $value;
            }
        }

        return __('Code');
    }

    /**
     * Whether the header renders: a file name, a language or the copy button.
     *
     * @since 3.0.0
     */
    public function hasHeader(): bool
    {
        return $this->filled($this->fileName) || $this->filled($this->language) || $this->copyable;
    }

    /**
     * The copy button's `apuiClipboard` Alpine component options.
     *
     * @since 3.0.0
     */
    public function alpineData(): string
    {
        return Clipboard::clipboardData(2000, (string) $this->copyLabel, (string) $this->copiedLabel, (string) $this->copiedMessage, (string) $this->errorMessage);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.code');
    }
}
