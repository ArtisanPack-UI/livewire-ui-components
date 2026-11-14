<?php

/**
 * ThemeToggle Component
 *
 * The design-system ThemeToggle (#65): light, dark, high contrast or system,
 * applied before first paint and persisted.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArtisanPack\LivewireUiComponents\Support\ThemeScript;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
use Illuminate\View\ComponentAttributeBag;

/**
 * ThemeToggle Component Class
 *
 * Renders `apuiThemeToggle` on the `.apui-theme-toggle` root. Segmented: the
 * root is also `.apui-tabs`, holding a radiogroup list of `role=radio`
 * triggers. Icon: a ghost icon-only Button that switches light and dark.
 * Menu: an outline Button trigger and a DropdownMenu of radio items, on the
 * menu machine. A choice sets `data-theme` on `<html>`, persists in
 * localStorage (read before first paint by `@apuiThemeScript`) and
 * dispatches `value-change`; `wire:model` binds it through
 * `$wire.entangle()`. The 2.x `light` and `dark` labels still work.
 *
 * @since 1.0.0
 */
class ThemeToggle extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The registry icon of each choice.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public const ICONS = [
        'light'         => 'sun',
        'dark'          => 'moon',
        'high-contrast' => 'contrast',
        'system'        => 'monitor',
    ];

    /**
     * 2.x attributes that are gone: the daisyUI theme names and html classes.
     * They're dropped rather than falling through to the root.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    private const REMOVED_ATTRIBUTES = [
        'light', 'dark',
        'light-theme', 'lightTheme', 'dark-theme', 'darkTheme',
        'light-class', 'lightClass', 'dark-class', 'darkClass',
        'with-label', 'withLabel',
    ];

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'theme-toggle';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = [
        'value', 'label', 'lightLabel', 'darkLabel', 'highContrastLabel', 'systemLabel',
        'matchSystemLabel', 'switchToLightLabel', 'switchToDarkLabel',
    ];

    /**
     * Creates the component.
     *
     * @since 1.0.0
     *
     * @param  string|null  $value  The choice: light, dark, high-contrast or system. `wire:model` binds it instead. Without either, the stored choice shows.
     * @param  string  $variant  segmented, icon or menu.
     * @param  string  $size  sm or md.
     * @param  array<int, string>  $choices  The choices segmented and menu offer. Defaults to all four.
     * @param  bool  $iconOnly  Segmented shows only the icons; the names stay visually hidden.
     * @param  string|null  $label  Names the radio group and the menu; the menu trigger's text. Defaults to __( 'Theme' ).
     * @param  string|null  $lightLabel  The light choice's name. Defaults to __( 'Light' ).
     * @param  string|null  $darkLabel  The dark choice's name. Defaults to __( 'Dark' ).
     * @param  string|null  $highContrastLabel  The high contrast choice's name. Defaults to __( 'High contrast' ).
     * @param  string|null  $systemLabel  The system choice's name. Defaults to __( 'System' ).
     * @param  string|null  $matchSystemLabel  The system choice's name in the menu. Defaults to __( 'Match system' ).
     * @param  string|null  $switchToLightLabel  The icon button's name while it switches to light.
     * @param  string|null  $switchToDarkLabel  The icon button's name while it switches to dark.
     * @param  bool  $persist  Stores the choice in localStorage.
     * @param  string  $storageKey  The localStorage key; pass the same one to `@apuiThemeScript`.
     * @param  array<string, string>  $classes  Extra classes per part: root, list, option, button, trigger, menu, item.
     */
    public function __construct(
        public ?string $value = null,
        public string $variant = 'segmented',
        public string $size = 'md',
        public array $choices = [],
        public bool $iconOnly = false,
        public ?string $label = null,
        public ?string $lightLabel = null,
        public ?string $darkLabel = null,
        public ?string $highContrastLabel = null,
        public ?string $systemLabel = null,
        public ?string $matchSystemLabel = null,
        public ?string $switchToLightLabel = null,
        public ?string $switchToDarkLabel = null,
        public bool $persist = true,
        public string $storageKey = ThemeScript::STORAGE_KEY,
        public array $classes = [],
    ) {
        $this->variant = in_array($this->variant, ['segmented', 'icon', 'menu'], true) ? $this->variant : 'segmented';
        $this->size    = in_array($this->size, ['sm', 'md'], true) ? $this->size : 'md';
        if ('' === trim($this->storageKey)) {
            $this->storageKey = ThemeScript::STORAGE_KEY;
        }
    }

    /**
     * The strings, keyed by choice and purpose: the set props, then the 2.x
     * `light` and `dark` attributes, then the translated defaults, through
     * the `ap.livewireUiComponents.themeColors` filter (`{ light: { label },
     * dark: { label }, … }`).
     *
     * @since 3.0.0
     *
     * @return array{label: string, light: string, dark: string, high-contrast: string, system: string, matchSystem: string, switchToLight: string, switchToDark: string} The strings.
     */
    public function labels(): array
    {
        $legacy = fn (string $name): ?string => is_string($this->attributes?->get($name)) && '' !== $this->attributes->get($name) ? (string) $this->attributes->get($name) : null;

        $themes = applyFilters('ap.livewireUiComponents.themeColors', [
            'light'         => ['label' => $this->lightLabel ?? $legacy('light') ?? __('Light')],
            'dark'          => ['label' => $this->darkLabel ?? $legacy('dark') ?? __('Dark')],
            'high-contrast' => ['label' => $this->highContrastLabel ?? __('High contrast')],
            'system'        => ['label' => $this->systemLabel ?? __('System')],
        ]);

        $name = static fn (string $choice, string $fallback): string => is_array($themes[$choice] ?? null) && is_scalar($themes[$choice]['label'] ?? null)
            ? (string) $themes[$choice]['label']
            : $fallback;

        return [
            'label'         => $this->label ?? __('Theme'),
            'light'         => $name('light', __('Light')),
            'dark'          => $name('dark', __('Dark')),
            'high-contrast' => $name('high-contrast', __('High contrast')),
            'system'        => $name('system', __('System')),
            'matchSystem'   => $this->matchSystemLabel ?? __('Match system'),
            'switchToLight' => $this->switchToLightLabel ?? __('Switch to light theme'),
            'switchToDark'  => $this->switchToDarkLabel ?? __('Switch to dark theme'),
        ];
    }

    /**
     * The choices offered, in the canonical order: `choices` filtered to
     * known ones, or all four.
     *
     * @since 3.0.0
     *
     * @return list<string> The choices.
     */
    public function offeredChoices(): array
    {
        $picked = array_values(array_filter(
            ThemeScript::CHOICES,
            fn (string $choice): bool => in_array($choice, $this->choices, true),
        ));

        return [] !== $picked ? $picked : ThemeScript::CHOICES;
    }

    /**
     * The choice the server renders: the bound Livewire property, else
     * `value`, else system (the client shows the stored one once it starts).
     *
     * @since 3.0.0
     *
     * @return string The choice.
     */
    public function currentValue(): string
    {
        $value = $this->explicitValue();

        return null !== $value ? $value : 'system';
    }

    /**
     * The choice the radio group's Tab stop starts on: the current one when
     * offered, else the first.
     *
     * @since 3.0.0
     *
     * @return string The choice.
     */
    public function tabStop(): string
    {
        $current = $this->currentValue();
        $choices = $this->offeredChoices();

        return in_array($current, $choices, true) ? $current : $choices[0];
    }

    /**
     * What the icon button switches to, as the server renders it: light from
     * dark, else dark (system renders as light until the client starts).
     *
     * @since 3.0.0
     *
     * @return string light or dark.
     */
    public function nextTheme(): string
    {
        return 'dark' === $this->currentValue() ? 'light' : 'dark';
    }

    /**
     * An icon's `<svg>` shown while an Alpine expression holds: hidden with
     * `display: none` until then when the server renders it hidden.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The icon name.
     * @param  string  $expression  The `x-show` expression.
     * @param  bool  $visible  Whether the server renders it shown.
     *
     * @return HtmlString The markup.
     */
    public function shownIcon(string $name, string $expression, bool $visible): HtmlString
    {
        $svg = $this->iconSvg($name)->toHtml();

        return new HtmlString((string) preg_replace(
            '/^<svg\b/',
            '<svg x-show="'.e($expression).'"'.($visible ? '' : ' style="display: none"'),
            $svg,
        ));
    }

    /**
     * The `x-data` expression: `apuiThemeToggle()` with the choice
     * (`$wire.entangle()` of the `wire:model` property, `.live` with
     * `wire:model.live`), the offered choices, persistence and the key.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        $model = $this->modelName();
        if (null !== $model) {
            $live  = [] !== array_filter(
                array_keys($this->attributes->whereStartsWith('wire:model')->getAttributes()),
                static fn (string $key): bool => str_contains($key, '.live'),
            );
            $value = '$wire.entangle('.Js::from($model)->toHtml().')'.($live ? '.live' : '');
        } else {
            $value = Js::from($this->explicitValue())->toHtml();
        }

        return sprintf(
            'apuiThemeToggle({ value: %s, choices: %s, persist: %s, storageKey: %s%s })',
            $value,
            Js::from($this->offeredChoices())->toHtml(),
            $this->persist ? 'true' : 'false',
            Js::from($this->storageKey)->toHtml(),
            null !== $model ? ', bound: true' : '',
        );
    }

    /**
     * The menu's `x-data` expression: `apuiDropdownMenu()` with its id.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function menuAlpineData(): string
    {
        return sprintf("apuiDropdownMenu({ id: %s, open: false, align: 'start' })", Js::from($this->partId('menu'))->toHtml());
    }

    /**
     * The attributes for the root: everything but `wire:model`, `id` and
     * the removed 2.x props.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): ComponentAttributeBag
    {
        return $this->attributes->whereDoesntStartWith('wire:model')->except(['id', ...self::REMOVED_ATTRIBUTES]);
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.theme-toggle');
    }

    /**
     * The choice set from outside: the bound property (none when it isn't a
     * choice), else `value` when nothing is bound.
     *
     * @since 3.0.0
     *
     * @return string|null The choice.
     */
    private function explicitValue(): ?string
    {
        $model     = $this->modelName();
        $component = null !== $model ? $this->livewireComponent() : null;
        if (null !== $component) {
            $bound = data_get($component, $model);

            return ThemeScript::isChoice($bound) ? $bound : null;
        }

        return ThemeScript::isChoice($this->value) ? $this->value : null;
    }
}
