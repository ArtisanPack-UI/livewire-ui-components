<?php

/**
 * ThemePreview Component
 *
 * The design-system ThemePreview (#65, Livewire only, D-003): selectable cards
 * with a live miniature of each theme or preset.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      2.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\View\ComponentAttributeBag;

/**
 * ThemePreview Component Class
 *
 * A `.apui-theme-preview` radio group of `.apui-theme-card` cards. Each card
 * is a label holding the frame, with a decorative miniature drawn by the real
 * tokens (it carries `data-theme` and `data-preset`), and a native radio with
 * the option's name. `class`, `aria-label` and `aria-labelledby` land on
 * the root; every other attribute, `wire:model` included, lands on every
 * radio, so it binds natively. Replaces
 * the 2.x `<livewire:theme-preview>` theme builder tool.
 *
 * @since 2.0.0
 */
class ThemePreview extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The themes a miniature can show.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const THEMES = ['light', 'dark', 'high-contrast', 'system'];

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'theme-preview';

    /**
     * Props that change between renders without changing the id: state, data and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['value', 'options', 'label'];

    /**
     * Creates the component.
     *
     * @since 2.0.0
     *
     * @param  array<int, array<string, mixed>>  $options  The cards: `{ value, label, theme, preset, disabled }`.
     * @param  string|null  $value  The chosen option's value. `wire:model` binds it instead.
     * @param  string|null  $name  The radios' name. Defaults to the `wire:model` property, else a generated one.
     * @param  string|null  $label  Names the radio group. Defaults to __( 'Theme' ).
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public array $options = [],
        public ?string $value = null,
        public ?string $name = null,
        public ?string $label = null,
        public array $classes = [],
    ) {
        $this->label ??= __('Theme');
    }

    /**
     * The options as cards: value, label, the miniature's theme (light,
     * dark, high-contrast or system), its preset and whether it's disabled.
     * Options without a value are skipped.
     *
     * @since 3.0.0
     *
     * @return list<array{value: string, label: string, theme: string, preset: string|null, disabled: bool}> The cards.
     */
    public function cards(): array
    {
        $cards = [];
        foreach ($this->options as $option) {
            if (! is_array($option)) {
                continue;
            }

            $value = $option['value'] ?? null;
            if (! is_scalar($value) || '' === (string) $value) {
                continue;
            }

            $value  = (string) $value;
            $theme  = $option['theme'] ?? null;
            $preset = $option['preset'] ?? null;

            $cards[] = [
                'value'    => $value,
                'label'    => is_scalar($option['label'] ?? null) ? (string) $option['label'] : $value,
                'theme'    => in_array($theme, self::THEMES, true) ? $theme : (in_array($value, self::THEMES, true) ? $value : 'light'),
                'preset'   => is_string($preset) && '' !== $preset ? $preset : null,
                'disabled' => (bool) ($option['disabled'] ?? false),
            ];
        }

        return $cards;
    }

    /**
     * The value the server renders checked: the bound Livewire property
     * (nothing when it's null), else `value` when nothing is bound.
     *
     * @since 3.0.0
     *
     * @return string|null The value.
     */
    public function selectedValue(): ?string
    {
        $model     = $this->modelName();
        $component = null !== $model ? $this->livewireComponent() : null;
        if (null !== $component) {
            $bound = data_get($component, $model);

            return is_scalar($bound) ? (string) $bound : null;
        }

        return $this->value;
    }

    /**
     * The radio group's name: `aria-labelledby` when set, plus `aria-label`
     * (the attribute, else `label`) unless only `aria-labelledby` is set.
     * Both stay off the radios, which their cards name.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function groupLabelAttributes(): ComponentAttributeBag
    {
        $labelledBy = $this->attributes->get('aria-labelledby');
        $label      = $this->attributes->get('aria-label');
        $names      = [];
        if (is_string($labelledBy) && '' !== $labelledBy) {
            $names['aria-labelledby'] = $labelledBy;
        }
        if (is_string($label) && '' !== $label) {
            $names['aria-label'] = $label;
        } elseif ([] === $names) {
            $names['aria-label'] = (string) $this->label;
        }

        return new ComponentAttributeBag($names);
    }

    /**
     * The radios' name: `name`, else the `wire:model` property, else the id.
     *
     * @since 3.0.0
     *
     * @return string The name.
     */
    public function groupName(): string
    {
        if (is_string($this->name) && '' !== $this->name) {
            return $this->name;
        }

        return $this->modelName() ?? $this->baseId();
    }

    /**
     * Get the view that represents the component.
     *
     * @since 2.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.theme-preview');
    }
}
