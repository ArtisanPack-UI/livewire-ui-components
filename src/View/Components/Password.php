<?php

/**
 * Password Component
 *
 * The design-system Password (#53): password entry with a reveal toggle, an
 * optional strength meter and live requirements.
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

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

/**
 * Password Component Class
 *
 * Renders `.apui-password`: the label, the box (icon, control, reveal), the
 * strength meter and its label, the requirements, the hint and the error.
 * `class` lands on the root; every other attribute, `wire:model` included,
 * lands on the native input. Alpine reveals the value and scores it as
 * people type; the server renders the same state for the initial value.
 *
 * @since 1.0.0
 */
class Password extends BaseComponent
{
    use InteractsWithFieldControl;
    use RendersDesignSystemParts;

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'password';

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $size  Control height: sm, md or lg.
     * @param  string  $autocomplete  current-password or new-password.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text under the box. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the control and the reveal button.
     * @param  bool  $readonly  Makes the control read-only.
     * @param  bool  $required  Marks the field required.
     * @param  string|null  $placeholder  Placeholder text.
     * @param  string|null  $value  The value. Livewire binds it with wire:model.
     * @param  string|null  $leadingIcon  Icon name before the value. The `icon` slot overrides it.
     * @param  bool  $strengthMeter  Shows the strength meter and its label.
     * @param  array<int, array{label: string, pattern: string}>  $requirements  Rules checked as people type.
     * @param  string|null  $revealLabel  The reveal button's name. Defaults to a translated "Show password".
     * @param  array<string, string>  $labels  Overrides for the strength and requirement text.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $size = 'md',
        public string $autocomplete = 'current-password',
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public bool $required = false,
        public ?string $placeholder = null,
        public ?string $value = null,
        public ?string $leadingIcon = null,
        public bool $strengthMeter = false,
        public array $requirements = [],
        public ?string $revealLabel = null,
        public array $labels = [],
        public ?string $errorField = null,
        public array $classes = [],
    ) {
        $this->revealLabel ??= __('Show password');
        $this->labels = array_merge([
            'strength' => __('Strength'),
            'weak'     => __('Weak'),
            'fair'     => __('Fair'),
            'good'     => __('Good'),
            'strong'   => __('Strong'),
            'met'      => __('Met'),
            'unmet'    => __('Not met'),
        ], array_filter($labels, static fn (mixed $text): bool => is_string($text)));
    }

    /**
     * The strength level of a value, the same in every stack: one point each
     * for 8+ and 12+ characters, mixed case, a digit and a symbol, less one,
     * kept between 1 and 4. Empty is 0.
     *
     * @since 3.0.0
     *
     * @param  string  $value  The password.
     *
     * @return int 0 to 4.
     */
    public static function strengthLevel(string $value): int
    {
        if ('' === $value) {
            return 0;
        }

        $length = mb_strlen($value);
        $points = (int) ($length >= 8)
            + (int) ($length >= 12)
            + (int) (1 === preg_match('/[a-z]/', $value) && 1 === preg_match('/[A-Z]/', $value))
            + (int) (1 === preg_match('/\d/', $value))
            + (int) (1 === preg_match('/[^A-Za-z0-9]/', $value));

        return max(1, min(4, $points - 1));
    }

    /**
     * Whether a value meets a requirement's pattern, a JavaScript-style
     * regular expression source. An invalid pattern is never met.
     *
     * @since 3.0.0
     *
     * @param  string  $pattern  The pattern source, such as `.{12,}`.
     * @param  string  $value  The password.
     *
     * @return bool True when it matches.
     */
    public static function meets(string $pattern, string $value): bool
    {
        return 1 === @preg_match('~'.str_replace('~', '\~', $pattern).'~u', $value);
    }

    /**
     * The level's name, or an empty string for level 0.
     *
     * @since 3.0.0
     *
     * @param  int  $level  0 to 4.
     *
     * @return string The name.
     */
    public function levelName(int $level): string
    {
        return [1 => $this->labels['weak'], 2 => $this->labels['fair'], 3 => $this->labels['good'], 4 => $this->labels['strong']][$level] ?? '';
    }

    /**
     * The requirements as `{ label, pattern }` lists with string values.
     *
     * @since 3.0.0
     *
     * @return list<array{label: string, pattern: string}> The requirements.
     */
    public function rules(): array
    {
        $rules = [];
        foreach ($this->requirements as $requirement) {
            if (is_array($requirement) && isset($requirement['label'], $requirement['pattern'])) {
                $rules[] = ['label' => (string) $requirement['label'], 'pattern' => (string) $requirement['pattern']];
            }
        }

        return $rules;
    }

    /**
     * The reveal button's glyphs: the eye, then the crossed eye, each marked
     * with `data-icon`. CSS shows the one for aria-pressed.
     *
     * @since 3.0.0
     *
     * @return HtmlString The markup.
     */
    public function revealIcons(): HtmlString
    {
        return new HtmlString(IconRegistry::svg('eye', ['data-icon' => 'eye']).IconRegistry::svg('eye-off', ['data-icon' => 'eye-off']));
    }

    /**
     * A requirement's glyphs: the check, then the cross. CSS shows one.
     *
     * @since 3.0.0
     *
     * @return HtmlString The markup.
     */
    public function requirementIcons(): HtmlString
    {
        return new HtmlString(IconRegistry::svg('check', ['data-icon' => 'check']).IconRegistry::svg('x', ['data-icon' => 'x']));
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.password');
    }
}
