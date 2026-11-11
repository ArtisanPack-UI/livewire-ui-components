<?php

/**
 * Signature Component
 *
 * The design-system Signature (#53): capture a signature by drawing, typing
 * or uploading.
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

use ArtisanPack\LivewireUiComponents\View\Concerns\InteractsWithFieldControl;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;

/**
 * Signature Component Class
 *
 * Renders `.apui-signature`: the label, the mode switch, the pad (signing
 * line, ink canvas, typed name, upload trigger and tools), the hint and the
 * error. The `apuiSignature` Alpine component draws on the canvas and turns
 * every mode into the same value, a PNG data URL, which `wire:model` binds
 * through `$wire.entangle()`. `class` lands on the root, `name` on a hidden
 * input so plain forms submit the value, and other attributes on the root.
 *
 * @since 1.0.0
 */
class Signature extends BaseComponent
{
    use InteractsWithFieldControl;
    use RendersDesignSystemParts;

    /**
     * Every mode, in the order the switch lists them.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const MODES = ['draw', 'type', 'upload'];

    /**
     * The `*Label` prop for each built-in string.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public const LABEL_PROPS = [
        'modes'         => 'modesLabel',
        'draw'          => 'drawLabel',
        'type'          => 'typeLabel',
        'upload'        => 'uploadLabel',
        'pad'           => 'padLabel',
        'typed'         => 'typedLabel',
        'uploadTrigger' => 'uploadTriggerLabel',
        'undo'          => 'undoLabel',
        'clear'         => 'clearLabel',
        'cleared'       => 'clearedLabel',
    ];

    /**
     * The component name used in derived ids.
     *
     * @since 3.0.0
     */
    protected string $fieldName = 'signature';

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  string  $mode  The mode shown first: draw, type or upload.
     * @param  array<int, string>|null  $modes  The modes offered. Type is always added.
     * @param  string  $penColor  The ink: default or a semantic color.
     * @param  string|null  $label  The visible label. A `label` slot overrides it.
     * @param  string|null  $hint  Help text under the pad. A `hint` slot overrides it.
     * @param  string|null  $error  Validation message. Defaults to the first `$errors` message for errorField.
     * @param  bool  $invalid  Marks the field invalid without a message.
     * @param  bool  $disabled  Disables the pad, the inputs and the tools.
     * @param  bool  $required  Marks the field required.
     * @param  string|null  $placeholder  The text under the line. Defaults to "Sign above the line", translated.
     * @param  string|null  $value  The value, a PNG data URL. Livewire binds it with wire:model.
     * @param  string|null  $modesLabel  The mode switch group's accessible name. Defaults to __( 'Signature method' ).
     * @param  string|null  $drawLabel  The draw mode button. Defaults to __( 'Draw' ).
     * @param  string|null  $typeLabel  The type mode button. Defaults to __( 'Type' ).
     * @param  string|null  $uploadLabel  The upload mode button. Defaults to __( 'Upload' ).
     * @param  string|null  $padLabel  The canvas's accessible name. Defaults to __( 'Signature pad. Draw with a mouse, finger or stylus, or choose Type.' ).
     * @param  string|null  $typedLabel  The typed-name input's accessible name. Defaults to __( 'Type your full name' ).
     * @param  string|null  $uploadTriggerLabel  The upload trigger's text. Defaults to __( 'Choose an image' ).
     * @param  string|null  $undoLabel  The icon-only undo button's accessible name. Defaults to __( 'Undo stroke' ).
     * @param  string|null  $clearLabel  The clear button's text. Defaults to __( 'Clear' ).
     * @param  string|null  $clearedLabel  Announced in the live region after clearing. Defaults to __( 'Signature cleared.' ).
     * @param  string|null  $errorField  The `$errors` key. Defaults to the wire:model name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public string $mode = 'draw',
        public ?array $modes = null,
        public string $penColor = 'default',
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $error = null,
        public bool $invalid = false,
        public bool $disabled = false,
        public bool $required = false,
        public ?string $placeholder = null,
        public ?string $value = null,
        public ?string $modesLabel = null,
        public ?string $drawLabel = null,
        public ?string $typeLabel = null,
        public ?string $uploadLabel = null,
        public ?string $padLabel = null,
        public ?string $typedLabel = null,
        public ?string $uploadTriggerLabel = null,
        public ?string $undoLabel = null,
        public ?string $clearLabel = null,
        public ?string $clearedLabel = null,
        public ?string $errorField = null,
        public array $classes = [],
    ) {}

    /**
     * The modes to offer, in their standard order, always with type: the
     * keyboard-accessible path. Mirrors `signatureModes()` in the behavior engine.
     *
     * @since 3.0.0
     *
     * @return list<string> The modes.
     */
    public function offeredModes(): array
    {
        $wanted   = null === $this->modes || [] === $this->modes ? self::MODES : $this->modes;
        $wanted[] = 'type';

        return array_values(array_filter(self::MODES, static fn (string $mode): bool => in_array($mode, $wanted, true)));
    }

    /**
     * The mode shown first: `mode` when it's offered, else the first offered one.
     *
     * @since 3.0.0
     *
     * @return string The mode.
     */
    public function initialMode(): string
    {
        $modes = $this->offeredModes();

        return in_array($this->mode, $modes, true) ? $this->mode : $modes[0];
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
                'modes'         => __('Signature method'),
                'draw'          => __('Draw'),
                'type'          => __('Type'),
                'upload'        => __('Upload'),
                'pad'           => __('Signature pad. Draw with a mouse, finger or stylus, or choose Type.'),
                'typed'         => __('Type your full name'),
                'uploadTrigger' => __('Choose an image'),
                'undo'          => __('Undo stroke'),
                'clear'         => __('Clear'),
                'placeholder'   => __('Sign above the line'),
                'cleared'       => __('Signature cleared.'),
            ],
            $this->labelOverrides(),
        );
    }

    /**
     * The text under the signing line.
     *
     * @since 3.0.0
     *
     * @return string The text.
     */
    public function placeholderText(): string
    {
        return null !== $this->placeholder && '' !== $this->placeholder ? $this->placeholder : $this->strings()['placeholder'];
    }

    /**
     * The value as JavaScript: `$wire.entangle('model')` (with `.live` for
     * `wire:model.live`) when there's a wire:model, else the `value` prop.
     *
     * @since 3.0.0
     *
     * @return string The expression.
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
     * The `x-data` expression: `apuiSignature()` with the value, the modes,
     * the disabled state and the translated strings.
     *
     * @since 3.0.0
     *
     * @return string The expression.
     */
    public function alpineData(): string
    {
        return sprintf(
            'apuiSignature({ value: %s, mode: %s, modes: %s, disabled: %s, translations: %s })',
            $this->valueExpression(),
            Js::from($this->initialMode())->toHtml(),
            Js::from($this->offeredModes())->toHtml(),
            $this->disabled ? 'true' : 'false',
            Js::from($this->strings())->toHtml(),
        );
    }

    /**
     * The root's attributes: everything but `wire:model`, `id` and `name`
     * (which goes to the hidden input).
     *
     * @since 3.0.0
     *
     * @return \Illuminate\View\ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): \Illuminate\View\ComponentAttributeBag
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
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.signature');
    }
}
