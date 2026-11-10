<?php

/**
 * A generated brand preset.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Theming;

/**
 * The result of PresetGenerator: token reassignments on top of the design
 * tokens, plus everything needed to write them out as CSS or data.
 *
 * The CSS and data match the design system's `apui-generate-preset` output
 * for the same inputs.
 *
 * @since 3.0.0
 */
final class GeneratedPreset
{
    /**
     * The attribute that switches presets.
     */
    public const ATTRIBUTE = 'data-preset';

    /**
     * Creates the result.
     *
     * @since 3.0.0
     *
     * @param  DesignTokens  $tokens  The design tokens the preset reassigns.
     * @param  string  $name  The `data-preset` value.
     * @param  string|null  $description  The description written into the CSS and data.
     * @param  array<string, array<string, string>>  $overrides  CSS values by path, then by mode (`''` for tokens with one value).
     * @param  array<string, array{ramp: string, step: string, exact: bool}>  $anchors  Where each input color landed.
     * @param  array<int, array{theme: string, fg: string, bg: string, backdrop: string|null, ratio: float, min: float}>  $failures  Contrast failures; empty when the preset passes.
     */
    public function __construct(
        public readonly DesignTokens $tokens,
        public readonly string $name,
        public readonly ?string $description,
        public readonly array $overrides,
        public readonly array $anchors = [],
        public readonly array $failures = [],
    ) {}

    /**
     * Whether the preset passes every contrast check in every theme.
     *
     * @since 3.0.0
     */
    public function passes(): bool
    {
        return [] === $this->failures;
    }

    /**
     * A copy with contrast failures recorded.
     *
     * @since 3.0.0
     *
     * @param  array<int, array{theme: string, fg: string, bg: string, backdrop: string|null, ratio: float, min: float}>  $failures
     */
    public function withFailures(array $failures): self
    {
        return new self($this->tokens, $this->name, $this->description, $this->overrides, $this->anchors, $failures);
    }

    /**
     * A token's CSS value in a mode, with the preset applied.
     *
     * @since 3.0.0
     */
    public function cssValue(string $path, ?string $mode = null): string
    {
        $axis = $this->tokens->get($path)['axis'];
        $key  = null === $axis ? '' : ($mode ?? $this->tokens->axes()[$axis]['default']);

        return $this->overrides[$path][$key] ?? $this->tokens->cssValue($path, '' === $key ? null : $key);
    }

    /**
     * A token's resolved CSS value in a mode context, with the preset applied.
     *
     * @since 3.0.0
     *
     * @param  array<string, string>  $context
     */
    public function resolved(string $path, array $context = []): string
    {
        $axis = $this->tokens->get($path)['axis'];
        $mode = null === $axis ? null : ($context[$axis] ?? $this->tokens->axes()[$axis]['default']);

        return $this->tokens->resolveCss($this->cssValue($path, $mode), $this->cssValue(...), $context);
    }

    /**
     * Tokens whose own value differs from the design tokens.
     *
     * @since 3.0.0
     *
     * @return array<string, true>
     */
    public function changed(): array
    {
        $changed = [];
        foreach (array_keys($this->tokens->all()) as $path) {
            foreach ($this->tokens->modesOf($path) as $mode) {
                if ($this->cssValue($path, $mode) !== $this->tokens->cssValue($path, $mode)) {
                    $changed[$path] = true;
                    break;
                }
            }
        }

        return $changed;
    }

    /**
     * The changed tokens plus every token that references one of them,
     * directly or not. A custom property is computed where it is declared, so
     * each of these is declared again under `data-preset`.
     *
     * @since 3.0.0
     *
     * @return array<string, true>
     */
    public function affected(): array
    {
        $dependents = [];
        foreach (array_keys($this->tokens->all()) as $path) {
            foreach ($this->tokens->modesOf($path) as $mode) {
                foreach ($this->tokens->referencesIn($this->cssValue($path, $mode)) as $reference) {
                    $dependents[$reference][$path] = true;
                }
            }
        }

        $affected = $this->changed();
        $queue    = array_keys($affected);
        while ([] !== $queue) {
            foreach (array_keys($dependents[array_pop($queue)] ?? []) as $dependent) {
                if (! isset($affected[$dependent])) {
                    $affected[$dependent] = true;
                    $queue[]              = $dependent;
                }
            }
        }

        return $affected;
    }

    /**
     * The preset's CSS: `[data-preset]` blocks in the `apui.tokens` layer.
     *
     * @since 3.0.0
     *
     * @param  string  $source  The line that says where the file came from.
     */
    public function css(string $source = 'Generated by php artisan artisanpack:generate-preset. Regenerate rather than edit by hand.'): string
    {
        $affected = $this->affected();
        $entries  = array_filter($this->tokens->all(), fn (string $path): bool => isset($affected[$path]), ARRAY_FILTER_USE_KEY);
        $preset   = '['.self::ATTRIBUTE.'="'.$this->name.'"]';
        $declare  = fn (string $path, ?string $mode = null): string => $entries[$path]['cssVar'].': '.$this->cssValue($path, $mode).';';

        $blocks = [];
        $flat   = [];
        foreach ($entries as $path => $token) {
            if (null === $token['varies']) {
                $flat[] = $declare($path);
            }
        }
        if ([] !== $flat) {
            $blocks[] = $this->block([$preset], $flat);
        }

        foreach ($this->tokens->axes() as $axis => $config) {
            $own       = array_keys(array_filter($entries, fn (array $token): bool => $token['axis'] === $axis));
            $dependent = array_keys(array_filter($entries, fn (array $token): bool => null === $token['axis'] && $token['varies'] === $axis));
            if ([] === $own && [] === $dependent) {
                continue;
            }

            $modes = [$config['default'], ...array_values(array_filter($config['modes'], fn (string $mode): bool => $mode !== $config['default']))];
            foreach ($modes as $mode) {
                $attribute = '['.$config['attribute'].'="'.$mode.'"]';
                $selectors = [
                    ...($mode === $config['default'] ? [$preset] : []),
                    $preset.$attribute,
                    $attribute.' '.$preset,
                    $preset.' '.$attribute,
                ];
                $blocks[] = $this->block($selectors, [
                    ...array_map(fn (string $path): string => $declare($path, $mode), $own),
                    ...array_map(fn (string $path): string => $declare($path), $dependent),
                ]);
            }
        }

        $description = null === $this->description ? '' : "\n * ".self::commentSafe($this->description);

        return "/*\n * @artisanpack-ui/tokens preset \"{$this->name}\".{$description}\n * {$source}\n */\n"
            .'@layer apui.tokens {'."\n".implode("\n\n", $blocks)."\n}\n";
    }

    /**
     * The preset as data: every affected token with its CSS value and its
     * resolved value, one per mode when it varies. The same shape as the
     * design system's `presets` map.
     *
     * @since 3.0.0
     *
     * @return array{name: string, description: string|null, attribute: string, tokens: array<string, array{changed: bool, value: array<string, string>|string, resolved: array<string, string>|string}>}
     */
    public function toArray(): array
    {
        $changed  = $this->changed();
        $affected = $this->affected();
        $axes     = $this->tokens->axes();
        $tokens   = [];
        foreach ($this->tokens->all() as $path => $token) {
            if (! isset($affected[$path])) {
                continue;
            }
            $value = null === $token['axis']
                ? $this->cssValue($path)
                : array_combine($axes[$token['axis']]['modes'], array_map(fn (string $mode): string => $this->cssValue($path, $mode), $axes[$token['axis']]['modes']));
            $resolved = null === $token['varies']
                ? $this->resolved($path)
                : array_combine($axes[$token['varies']]['modes'], array_map(fn (string $mode): string => $this->resolved($path, [$token['varies'] => $mode]), $axes[$token['varies']]['modes']));
            $tokens[$path] = ['changed' => isset($changed[$path]), 'value' => $value, 'resolved' => $resolved];
        }

        return [
            'name'        => $this->name,
            'description' => $this->description,
            'attribute'   => self::ATTRIBUTE,
            'tokens'      => $tokens,
        ];
    }

    /**
     * Text that can't end the CSS comment it sits in, on one line.
     *
     * @since 3.0.0
     */
    public static function commentSafe(string $text): string
    {
        return (string) preg_replace('/\s*[\r\n]+\s*/', ' ', str_replace('*/', '* /', $text));
    }

    /**
     * One CSS block.
     *
     * @param  array<int, string>  $selectors
     * @param  array<int, string>  $declarations
     */
    private function block(array $selectors, array $declarations): string
    {
        return '  '.implode(",\n  ", $selectors)." {\n"
            .implode("\n", array_map(fn (string $line): string => '    '.$line, $declarations))
            ."\n  }";
    }
}
