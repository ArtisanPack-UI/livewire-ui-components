<?php

/**
 * Clipboard Component
 *
 * The design-system Clipboard (#65): copies a value with clear confirmation.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;

/**
 * Clipboard Component Class
 *
 * Renders `.apui-clipboard` with data-variant (button, icon, field),
 * data-size and data-state: a neutral copy button, a role=status live
 * region (the tooltip part) and, for the field variant, the value in a
 * read-only box. The copy runs on the behavior package's `apuiClipboard`
 * Alpine component, as Code's copy button and the React and Vue components
 * do: the label reads "Copied" for the timeout and the result, or the
 * failure, is announced. The button reads the value from its `value`
 * attribute when clicked, so a value Livewire changes copies the new one.
 * The root dispatches `copy` (detail: the value) or `error`. Every
 * attribute lands on the root.
 *
 * @since 3.0.0
 */
class Clipboard extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * The variants the markup contract knows.
     *
     * @since 3.0.0
     */
    public const VARIANTS = ['button', 'icon', 'field'];

    /**
     * The sizes the markup contract knows.
     *
     * @since 3.0.0
     */
    public const SIZES = ['xs', 'sm', 'md'];

    /**
     * Create a new component instance.
     *
     * @since 3.0.0
     *
     * @param  string|null  $value  What to copy.
     * @param  int  $timeout  How long "Copied" shows, in ms.
     * @param  string  $variant  button, icon or field.
     * @param  string  $size  xs, sm or md.
     * @param  string|null  $label  The button's label.
     * @param  string|null  $copiedLabel  The label after a copy.
     * @param  string|null  $copiedMessage  Announced after a copy.
     * @param  string|null  $errorMessage  Announced when the copy fails.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $value = null,
        public int $timeout = 2000,
        public string $variant = 'button',
        public string $size = 'sm',
        public ?string $label = null,
        public ?string $copiedLabel = null,
        public ?string $copiedMessage = null,
        public ?string $errorMessage = null,
        public array $classes = [],
    ) {
        $this->label ??= __('Copy');
        $this->copiedLabel ??= __('Copied');
        $this->copiedMessage ??= __('Copied to clipboard');
        $this->errorMessage ??= __('Couldn\'t copy. Select the text and copy it yourself.');
    }

    /**
     * The variant, falling back to `button`.
     *
     * @since 3.0.0
     */
    public function variantValue(): string
    {
        return in_array($this->variant, self::VARIANTS, true) ? $this->variant : 'button';
    }

    /**
     * The size, falling back to `sm`.
     *
     * @since 3.0.0
     */
    public function sizeValue(): string
    {
        return in_array($this->size, self::SIZES, true) ? $this->size : 'sm';
    }

    /**
     * The `apuiClipboard` Alpine component's options.
     *
     * @since 3.0.0
     */
    public function alpineData(): string
    {
        return self::clipboardData($this->timeout, (string) $this->label, (string) $this->copiedLabel, (string) $this->copiedMessage, (string) $this->errorMessage);
    }

    /**
     * An `apuiClipboard({ … })` expression, shared with Code's copy button.
     *
     * @since 3.0.0
     *
     * @param  int  $timeout  How long "Copied" shows, in ms.
     * @param  string  $copy  The button's label.
     * @param  string  $copied  The label after a copy.
     * @param  string  $copiedMessage  Announced after a copy.
     * @param  string  $errorMessage  Announced when the copy fails.
     */
    public static function clipboardData(int $timeout, string $copy, string $copied, string $copiedMessage, string $errorMessage): string
    {
        return sprintf(
            'apuiClipboard({ timeout: %d, labels: %s })',
            max(0, $timeout),
            Js::from([
                'copy'          => $copy,
                'copied'        => $copied,
                'copiedMessage' => $copiedMessage,
                'errorMessage'  => $errorMessage,
            ])->toHtml(),
        );
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.clipboard');
    }
}
