<?php

/**
 * ColorPicker Component
 *
 * The design-system ColorPicker (#53): a color field whose swatch opens a
 * popover with a saturation and brightness area, hue and opacity sliders, a
 * format select, an eyedropper and preset swatches, on the behavior
 * engine's color-picker machine. Replaces the deprecated `colorpicker`.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;
use Illuminate\View\ComponentAttributeBag;

/**
 * ColorPicker Component Class
 *
 * Renders `apuiColorPicker`: the `.apui-color-picker` field (label, box with
 * the swatch trigger and the text value, hint and error) and the popover
 * (area, hue and opacity sliders, format select, eyedropper, swatches).
 * `wire:model` binds the text value through `$wire.entangle()`. `class`
 * lands on the root, event listeners (`x-on:*`, `wire:*`) on the root where
 * the widget dispatches, and every other attribute on the text input.
 *
 * Registered as `color-picker`, plus the deprecated `colorpicker` alias.
 *
 * @since 3.0.0
 */
class ColorPicker extends BaseComponent
{
    use HasStablePartIds, InteractsWithFieldControl {
        HasStablePartIds::modelName insteadof InteractsWithFieldControl;
    }
    use RendersDesignSystemParts;

    /**
     * The text formats, in the format select's order, with their labels.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public const FORMATS = ['hex' => 'HEX', 'rgb' => 'RGB', 'hsl' => 'HSL', 'oklch' => 'OKLCH'];

    /**
     * Attributes of the 2.x `colorpicker` that no longer exist. They're
     * accepted and ignored, so old markup doesn't leak them onto the input.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const LEGACY_ATTRIBUTES = [
        'icon', 'icon-right', 'iconRight', 'prefix', 'suffix', 'inline', 'clearable', 'random',
        'hint-class', 'hintClass', 'omit-error', 'omitError', 'first-error-only', 'firstErrorOnly',
    ];

    /**
     * The prefix of generated ids.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'color-picker';

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'color-picker';

    /**
     * Props that change between renders without changing the id: state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = ['value', 'open', 'label', 'hint', 'error', 'invalid'];

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string|null  $value  The color as text in any format. `wire:model` binds it instead.
     * @param  string  $format  The text format: hex, rgb, hsl or oklch.
     * @param  bool  $alpha  Offers opacity.
     * @param  iterable<string>  $swatches  Preset colors as text.
     * @param  string  $size  Control height: sm, md or lg.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the field and the popover's controls.
     * @param  bool  $required  Marks the field required.
     * @param  bool  $open  Whether the popover is open.
     * @param  bool  $portal  Renders the popover at the end of the body (x-teleport).
     * @param  string|null  $triggerLabel  The swatch button's and popover's name. Defaults to "Choose a color", translated.
     * @param  string|null  $areaLabel  The area's name. Defaults to "Saturation and brightness", translated.
     * @param  string|null  $hueLabel  The hue slider's name. Defaults to "Hue", translated.
     * @param  string|null  $alphaLabel  The opacity slider's name. Defaults to "Opacity", translated.
     * @param  string|null  $formatLabel  The format select's name. Defaults to "Format", translated.
     * @param  string|null  $eyeDropperLabel  The eyedropper's name. Defaults to "Pick a color from the screen", translated.
     * @param  string|null  $swatchesLabel  The swatch group's name. Defaults to "Swatches", translated.
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public ?string $value = null,
        public string $format = 'hex',
        public bool $alpha = true,
        public iterable $swatches = [],
        public string $size = 'md',
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $required = false,
        public bool $open = false,
        public bool $portal = true,
        public ?string $triggerLabel = null,
        public ?string $areaLabel = null,
        public ?string $hueLabel = null,
        public ?string $alphaLabel = null,
        public ?string $formatLabel = null,
        public ?string $eyeDropperLabel = null,
        public ?string $swatchesLabel = null,
        public ?string $errorField = null,
        public array $classes = [],
    ) {}

    /**
     * The text input's id: the `id` attribute, else the base id.
     *
     * @since 3.0.0
     *
     * @return string The id.
     */
    public function controlId(): string
    {
        return $this->partId('control');
    }

    /**
     * The format, or hex when it isn't one of the four.
     *
     * @since 3.0.0
     *
     * @return string The format.
     */
    public function resolvedFormat(): string
    {
        return array_key_exists($this->format, self::FORMATS) ? $this->format : 'hex';
    }

    /**
     * The preset swatches, as non-empty strings.
     *
     * @since 3.0.0
     *
     * @return list<string> The colors.
     */
    public function presetSwatches(): array
    {
        $colors = [];
        foreach ($this->swatches as $color) {
            $color = trim((string) $color);
            if ('' !== $color && self::isSafeColor($color)) {
                $colors[] = $color;
            }
        }

        return $colors;
    }

    /**
     * The value the server renders: the `wire:model` property of the
     * Livewire component rendering this one, else the `value` prop.
     *
     * @since 3.0.0
     *
     * @return string|null The color text.
     */
    public function currentValue(): ?string
    {
        $model     = $this->modelName();
        $component = null !== $model ? $this->livewireComponent() : null;
        if (null !== $component) {
            $value = data_get($component, $model);

            return is_scalar($value) && '' !== (string) $value ? (string) $value : null;
        }

        return $this->value;
    }

    /**
     * The value for the server render's swatch fill, when it's safe CSS.
     *
     * @since 3.0.0
     *
     * @return string|null The color.
     */
    public function fillColor(): ?string
    {
        $value = trim((string) $this->currentValue());

        return self::isSafeColor($value) ? $value : null;
    }

    /**
     * The server render of what the machine draws: the area's hue, the
     * thumbs' positions and fills, the opacity gradient and the sliders'
     * ARIA values. The machine takes over once Alpine runs; until then (and
     * in the quality gates, which render server markup) the popover shows
     * the same as React and Vue.
     *
     * @since 3.0.0
     *
     * @return array{hue: float, saturation: float, brightness: float, alpha: float, css: string, opaque: string, transparent: string, pure: string} The values.
     */
    public function machineView(): array
    {
        $rgba            = self::parseColor((string) $this->currentValue()) ?? [0, 0, 0, 1.0];
        [$r, $g, $b, $a] = $rgba;
        if (! $this->alpha) {
            $a = 1.0;
        }

        $max   = max($r, $g, $b) / 255.0;
        $min   = min($r, $g, $b) / 255.0;
        $delta = $max - $min;
        $hue   = 0.0;
        if ($delta > 0) {
            $rn  = $r / 255.0;
            $gn  = $g / 255.0;
            $bn  = $b / 255.0;
            $hue = match (true) {
                $max === $rn => fmod((($gn - $bn) / $delta) + 6, 6),
                $max === $gn => (($bn - $rn) / $delta) + 2,
                default      => (($rn - $gn) / $delta) + 4,
            } * 60;
        }
        $saturation = $max <= 0.0 ? 0.0 : $delta / $max;
        $lightness  = ($max + $min) / 2;
        $hslSat     = $delta <= 0.0 ? 0.0 : $delta / (1 - abs(2 * $lightness - 1));
        $hsl        = sprintf('%s, %s%%, %s%%', self::number($hue), self::number($hslSat * 100), self::number($lightness * 100));

        return [
            'hue'         => round($hue, 2),
            'saturation'  => round($saturation * 100, 2),
            'brightness'  => round($max * 100, 2),
            'alpha'       => round($a, 2),
            'css'         => 'hsla('.$hsl.', '.self::number($a).')',
            'opaque'      => 'hsla('.$hsl.', 1)',
            'transparent' => 'hsla('.$hsl.', 0)',
            'pure'        => 'hsl('.self::number($hue).', 100%, 50%)',
        ];
    }

    /**
     * Color text as sRGB: `#rgb`, `#rgba`, `#rrggbb`, `#rrggbbaa`, `rgb()`,
     * `hsl()` and `oklch()` (spaces or commas, `/ alpha`). Mirrors
     * `parseColorText()` in the behavior engine.
     *
     * @since 3.0.0
     *
     * @param  string  $text  The text.
     *
     * @return array{0: int, 1: int, 2: int, 3: float}|null The channels and alpha, or null.
     */
    public static function parseColor(string $text): ?array
    {
        $text = strtolower(trim($text));
        if (1 === preg_match('/^#?([0-9a-f]{3,4}|[0-9a-f]{6}|[0-9a-f]{8})$/', $text, $hex)) {
            $digits = $hex[1];
            if (strlen($digits) <= 4) {
                $digits = implode('', array_map(static fn (string $d): string => $d.$d, str_split($digits)));
            }
            $n = static fn (int $i): int => (int) hexdec(substr($digits, $i, 2));

            return [$n(0), $n(2), $n(4), 8 === strlen($digits) ? round($n(6) / 255, 3) : 1.0];
        }

        if (1 !== preg_match('/^(rgba?|hsla?|oklch)\((.*)\)$/', $text, $fn)) {
            return null;
        }
        $parts = preg_split('/\s*[,\/]\s*|\s+/', trim($fn[2]), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        if (count($parts) < 3 || count($parts) > 4) {
            return null;
        }
        foreach ($parts as $part) {
            if (1 !== preg_match('/^[-+]?(?:\d+\.?\d*|\.\d+)(?:%|deg)?$/', $part)) {
                return null;
            }
        }
        $amount = static fn (string $part, float $scale): float => str_ends_with($part, '%') ? (float) $part / 100 * $scale : (float) $part;
        $alpha  = isset($parts[3]) ? max(0.0, min(1.0, $amount($parts[3], 1))) : 1.0;
        $clamp  = static fn (float $v): int => (int) max(0, min(255, round($v)));

        if (str_starts_with($fn[1], 'rgb')) {
            return [$clamp($amount($parts[0], 255)), $clamp($amount($parts[1], 255)), $clamp($amount($parts[2], 255)), $alpha];
        }

        if (str_starts_with($fn[1], 'hsl')) {
            $h = fmod(fmod((float) $parts[0], 360) + 360, 360);
            $s = max(0, min(100, (float) $parts[1])) / 100;
            $l = max(0, min(100, (float) $parts[2])) / 100;
            $f = static function (int $n) use ($h, $s, $l): float {
                $k = fmod($n + $h / 30, 12);

                return $l - $s * min($l, 1 - $l) * max(-1, min($k - 3, 9 - $k, 1));
            };

            return [$clamp($f(0) * 255), $clamp($f(8) * 255), $clamp($f(4) * 255), $alpha];
        }

        $lightness = max(0.0, min(1.0, $amount($parts[0], 1)));
        $chroma    = max(0.0, $amount($parts[1], 0.4));
        $rad       = deg2rad(fmod(fmod((float) $parts[2], 360) + 360, 360));
        $oa        = $chroma * cos($rad);
        $ob        = $chroma * sin($rad);
        $l         = ($lightness + 0.3963377774 * $oa + 0.2158037573 * $ob) ** 3;
        $m         = ($lightness - 0.1055613458 * $oa - 0.0638541728 * $ob) ** 3;
        $sv        = ($lightness - 0.0894841775 * $oa - 1.291485548 * $ob) ** 3;
        $gamma     = static function (float $c) use ($clamp): int {
            $v = $c <= 0.0031308 ? 12.92 * $c : 1.055 * ($c ** (1 / 2.4)) - 0.055;

            return $clamp($v * 255);
        };

        return [
            $gamma(4.0767416621 * $l - 3.3077115913 * $m + 0.2309699292 * $sv),
            $gamma(-1.2684380046 * $l + 2.6097574011 * $m - 0.3413193965 * $sv),
            $gamma(-0.0041960863 * $l - 0.7034186147 * $m + 1.707614701 * $sv),
            $alpha,
        ];
    }

    /**
     * The value as an Alpine expression: `$wire.entangle()` of the
     * `wire:model` property (`.live` with `wire:model.live`), else the prop.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function valueExpression(): string
    {
        $model = $this->modelName();
        if (null === $model) {
            return Js::from($this->value)->toHtml();
        }

        $live = [] !== array_filter(
            array_keys($this->attributes->whereStartsWith('wire:model')->getAttributes()),
            static fn (string $key): bool => str_contains($key, '.live'),
        );

        return '$wire.entangle('.Js::from($model)->toHtml().')'.($live ? '.live' : '');
    }

    /**
     * The `x-data` expression: `apuiColorPicker()` with the machine id, the
     * bound value and the options, labels translated.
     *
     * @since 3.0.0
     *
     * @param  bool  $isInvalid  Whether the field renders invalid.
     *
     * @return string The expression.
     */
    public function alpineData(bool $isInvalid = false): string
    {
        $options = [
            'id'       => $this->baseId(),
            'open'     => $this->open,
            'format'   => $this->resolvedFormat(),
            'alpha'    => $this->alpha,
            'disabled' => $this->disabled,
            'required' => $this->required,
            'invalid'  => $isInvalid,
            'labels'   => $this->labels(),
        ];

        return sprintf(
            'apuiColorPicker({ ...%s, value: %s })',
            Js::from($options)->toHtml(),
            $this->valueExpression(),
        );
    }

    /**
     * The translated built-in names, as the widget's `labels`.
     *
     * @since 3.0.0
     *
     * @return array<string, string> The labels.
     */
    public function labels(): array
    {
        return [
            'trigger'    => $this->builtInName('triggerLabel', 'Choose a color'),
            'area'       => $this->builtInName('areaLabel', 'Saturation and brightness'),
            'hue'        => $this->builtInName('hueLabel', 'Hue'),
            'alpha'      => $this->builtInName('alphaLabel', 'Opacity'),
            'format'     => $this->builtInName('formatLabel', 'Format'),
            'eyeDropper' => $this->builtInName('eyeDropperLabel', 'Pick a color from the screen'),
            'swatches'   => $this->builtInName('swatchesLabel', 'Swatches'),
        ];
    }

    /**
     * A built-in label: the prop when set, else the English default, translated.
     *
     * @since 3.0.0
     *
     * @param  string  $prop  The prop name.
     * @param  string  $default  The English default.
     *
     * @return string The label.
     */
    public function builtInName(string $prop, string $default): string
    {
        $value = $this->{$prop};

        return is_string($value) && '' !== $value ? $value : __($default);
    }

    /**
     * The attributes for the root: event listeners (`x-on:*`, `@*`,
     * `wire:*` other than `wire:model`), where the widget dispatches.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function listenerAttributes(): ComponentAttributeBag
    {
        return $this->attributes->filter(static fn (mixed $value, string $key): bool => self::isListener($key));
    }

    /**
     * The attributes for the text input: everything except `class`, `id`,
     * `wire:model`, `aria-describedby` and the listeners.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function controlAttributes(): ComponentAttributeBag
    {
        return $this->attributes
            ->except(['class', 'id', 'aria-describedby', ...self::LEGACY_ATTRIBUTES])
            ->filter(static fn (mixed $value, string $key): bool => ! self::isListener($key) && ! str_starts_with($key, 'wire:model'));
    }

    /**
     * Get the view that represents the component.
     *
     * @since 3.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.color-picker');
    }

    /**
     * A number with at most two decimals, as Zag writes them.
     *
     * @since 3.0.0
     *
     * @param  float  $value  The number.
     */
    private static function number(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }

    /**
     * Whether text is a color that is safe to write into a style: a hex
     * color, a color function of numbers, or a named color.
     *
     * @since 3.0.0
     *
     * @param  string  $color  The text.
     */
    private static function isSafeColor(string $color): bool
    {
        return 1 === preg_match('/^(?:#[0-9a-f]{3,8}|(?:rgba?|hsla?|oklch)\([0-9.,%\/\s+-]*(?:deg)?[0-9.,%\/\s+-]*\)|[a-z]+)$/i', $color);
    }

    /**
     * Whether an attribute is an event listener that belongs on the root.
     *
     * @since 3.0.0
     *
     * @param  string  $key  The attribute name.
     */
    private static function isListener(string $key): bool
    {
        return str_starts_with($key, 'x-on:') || str_starts_with($key, '@')
            || (str_starts_with($key, 'wire:') && ! str_starts_with($key, 'wire:model'));
    }
}
