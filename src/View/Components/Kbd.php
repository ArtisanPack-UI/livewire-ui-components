<?php

/**
 * Kbd Component
 *
 * The design-system Kbd (#65): a keyboard key or shortcut.
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
use Illuminate\Contracts\View\View;
use Illuminate\Support\Js;

/**
 * Kbd Component Class
 *
 * Renders keys as `kbd` elements. With `keys`, a combo (`.apui-kbd-combo`,
 * an outer kbd) around one `.apui-kbd` per key, spelled for the platform by
 * keyList(), the PHP port of the behavior package's `kbdKeys`: the server
 * renders the other platforms' keys (Ctrl for `mod`) and, with platform
 * `auto`, the `apuiKbd` Alpine component swaps in ⌘, ⌥ and ⇧ on Apple
 * platforms. Symbol keys carry their name as hidden text. Without keys, the
 * slot is one key, as in 2.x. Every attribute lands on the outer kbd.
 *
 * @since 1.0.0
 */
class Kbd extends BaseComponent
{
    use RendersDesignSystemParts;

    /**
     * Named keys, lowercased: the cap on Apple platforms, elsewhere, and the
     * name of each symbol. Keep in step with `kbdKeys` in the behavior package.
     *
     * @since 3.0.0
     *
     * @var array<string, array{0: string, 1: string, 2: string|null, 3: string|null}>
     */
    public const NAMED = [
        'mod'        => ['⌘', 'Ctrl', 'command', null],
        'cmd'        => ['⌘', '⌘', 'command', 'command'],
        'command'    => ['⌘', '⌘', 'command', 'command'],
        'meta'       => ['⌘', 'Meta', 'command', null],
        'ctrl'       => ['⌃', 'Ctrl', 'control', null],
        'control'    => ['⌃', 'Ctrl', 'control', null],
        'alt'        => ['⌥', 'Alt', 'option', null],
        'option'     => ['⌥', 'Alt', 'option', null],
        'opt'        => ['⌥', 'Alt', 'option', null],
        'shift'      => ['⇧', 'Shift', 'shift', null],
        'enter'      => ['↵', '↵', 'enter', 'enter'],
        'return'     => ['↵', '↵', 'enter', 'enter'],
        'esc'        => ['Esc', 'Esc', null, null],
        'escape'     => ['Esc', 'Esc', null, null],
        'tab'        => ['Tab', 'Tab', null, null],
        'space'      => ['Space', 'Space', null, null],
        'backspace'  => ['⌫', 'Backspace', 'backspace', null],
        'delete'     => ['Del', 'Del', null, null],
        'del'        => ['Del', 'Del', null, null],
        'up'         => ['↑', '↑', 'up', 'up'],
        'arrowup'    => ['↑', '↑', 'up', 'up'],
        'down'       => ['↓', '↓', 'down', 'down'],
        'arrowdown'  => ['↓', '↓', 'down', 'down'],
        'left'       => ['←', '←', 'left', 'left'],
        'arrowleft'  => ['←', '←', 'left', 'left'],
        'right'      => ['→', '→', 'right', 'right'],
        'arrowright' => ['→', '→', 'right', 'right'],
    ];

    /**
     * Symbols given as they are, and the name each one is read as.
     *
     * @since 3.0.0
     *
     * @var array<string, string>
     */
    public const SYMBOLS = [
        '⌘' => 'command',
        '⌃' => 'control',
        '⌥' => 'option',
        '⇧' => 'shift',
        '↵' => 'enter',
        '⏎' => 'enter',
        '⇥' => 'tab',
        '⎋' => 'escape',
        '⌫' => 'backspace',
        '↑' => 'up',
        '↓' => 'down',
        '←' => 'left',
        '→' => 'right',
    ];

    /**
     * Create a new component instance.
     *
     * @since 1.0.0
     *
     * @param  array<int, mixed>  $keys  A shortcut's keys, such as ['mod', 'k'].
     * @param  string  $size  sm or md.
     * @param  string  $platform  auto, mac or other.
     * @param  array<string, string>  $labels  The hidden names of symbol keys, by name.
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public array $keys = [],
        public string $size = 'md',
        public string $platform = 'auto',
        public array $labels = [],
        public array $classes = [],
    ) {}

    /**
     * The size, falling back to `md`.
     *
     * @since 3.0.0
     */
    public function sizeValue(): string
    {
        return 'sm' === $this->size ? 'sm' : 'md';
    }

    /**
     * The hidden names of symbol keys, translated, with `labels` on top.
     *
     * @since 3.0.0
     *
     * @return array<string, string> The names, by key name.
     */
    public function keyLabels(): array
    {
        $defaults = [
            'command'   => __('Command'),
            'control'   => __('Control'),
            'option'    => __('Option'),
            'shift'     => __('Shift'),
            'enter'     => __('Enter'),
            'tab'       => __('Tab'),
            'escape'    => __('Escape'),
            'backspace' => __('Backspace'),
            'up'        => __('Up arrow'),
            'down'      => __('Down arrow'),
            'left'      => __('Left arrow'),
            'right'     => __('Right arrow'),
        ];

        $given = array_filter($this->labels, static fn (mixed $label): bool => is_string($label) && '' !== $label);

        return array_merge($defaults, array_intersect_key($given, $defaults));
    }

    /**
     * The keys to show, as the behavior package's `kbdKeys` spells them.
     *
     * @since 3.0.0
     *
     * @param  bool  $apple  Spell them for Apple platforms.
     *
     * @return list<array{text: string, label: string|null}> The keys.
     */
    public function keyList(bool $apple = false): array
    {
        $labels = $this->keyLabels();
        $shown  = [];
        foreach ($this->keys as $raw) {
            if (! is_scalar($raw)) {
                continue;
            }
            $key = trim((string) $raw);
            if ('' === $key) {
                continue;
            }
            $rule = self::NAMED[mb_strtolower($key)] ?? null;
            if (null !== $rule) {
                $name    = $apple ? $rule[2] : $rule[3];
                $shown[] = ['text' => $apple ? $rule[0] : $rule[1], 'label' => null === $name ? null : $labels[$name]];

                continue;
            }
            if (isset(self::SYMBOLS[$key])) {
                $shown[] = ['text' => $key, 'label' => $labels[self::SYMBOLS[$key]]];

                continue;
            }
            $shown[] = ['text' => 1 === mb_strlen($key) ? mb_strtoupper($key) : $key, 'label' => null];
        }

        return $shown;
    }

    /**
     * Whether the server can't know the keys: platform `auto` and a key that
     * differs on Apple platforms.
     *
     * @since 3.0.0
     */
    public function dependsOnPlatform(): bool
    {
        if ('auto' !== $this->platform) {
            return false;
        }

        foreach ($this->keys as $raw) {
            $rule = is_scalar($raw) ? (self::NAMED[mb_strtolower(trim((string) $raw))] ?? null) : null;
            if (null !== $rule && $rule[0] !== $rule[1]) {
                return true;
            }
        }

        return false;
    }

    /**
     * The `apuiKbd` Alpine component's options.
     *
     * @since 3.0.0
     */
    public function alpineData(): string
    {
        return sprintf(
            'apuiKbd({ keys: %s, labels: %s })',
            Js::from(array_values(array_filter($this->keys, 'is_scalar')))->toHtml(),
            Js::from((object) $this->keyLabels())->toHtml(),
        );
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.kbd');
    }
}
