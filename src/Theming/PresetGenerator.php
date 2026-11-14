<?php

/**
 * Token-based brand preset generator.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Theming;

use ArtisanPack\LivewireUiComponents\Exceptions\ThemeGeneratorException;

/**
 * Generates a brand preset (#27) from a few inputs: a primary color, optional
 * secondary and accent colors, a neutral treatment and font families. It
 * replaces the Tailwind-palette ColorGenerator with reassignments of the
 * design tokens, switched by `data-preset`.
 *
 * This is the PHP port of the design system's `apui-generate-preset`, and
 * produces the same output for the same inputs. Each generated ramp keeps the
 * input's hue and chroma but matches the WCAG luminance of the default ramp
 * step for step, so every pair the default theme passes still passes. The
 * input color lands exactly on the step nearest its luminance when that also
 * clears every check.
 *
 * @since 3.0.0
 */
final class PresetGenerator
{
    /**
     * Neutral treatments: grays tinted toward the primary, pure gray, or unchanged.
     */
    public const NEUTRALS = ['tinted', 'pure', 'keep'];

    /**
     * OKLCH literals at or below this chroma are tinted neutrals, not colors.
     */
    private const NEUTRAL_CHROMA = 0.02;

    /**
     * Generic CSS font families, written without quotes.
     */
    private const GENERIC_FAMILIES = [
        'serif', 'sans-serif', 'monospace', 'cursive', 'fantasy', 'system-ui', 'ui-serif',
        'ui-sans-serif', 'ui-monospace', 'ui-rounded', 'emoji', 'math', 'fangsong',
    ];

    /**
     * Creates the generator.
     *
     * @since 3.0.0
     *
     * @param  DesignTokens  $tokens  The design tokens to build on.
     */
    public function __construct(private readonly DesignTokens $tokens) {}

    /**
     * Creates a generator for the token map shipped with the package.
     *
     * @since 3.0.0
     */
    public static function make(): self
    {
        return new self(DesignTokens::load());
    }

    /**
     * Generates and checks a brand preset. Check `passes()` on the result
     * before writing it: a preset with failures must not ship.
     *
     * @since 3.0.0
     *
     * @param  array{name: string, primary?: string|null, secondary?: string|null, accent?: string|null, neutral?: string|null, fontSans?: array<int, string>|string|null, fontMono?: array<int, string>|string|null, fontDisplay?: array<int, string>|string|null, description?: string|null}  $options
     *
     * @throws ThemeGeneratorException When an input is invalid.
     */
    public function generate(array $options): GeneratedPreset
    {
        $exact = $this->build($options, true);
        $exact = $exact->withFailures($this->check($exact));
        if ($exact->passes()) {
            return $exact;
        }

        $matched = $this->build($options, false);

        return $matched->withFailures($this->check($matched));
    }

    /**
     * Builds a preset's reassignments without checking contrast.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $options  See generate().
     * @param  bool  $exact  Whether to place each input color exactly on its nearest step.
     *
     * @throws ThemeGeneratorException When an input is invalid.
     */
    public function build(array $options, bool $exact = true): GeneratedPreset
    {
        $name    = (string) ($options['name'] ?? '');
        $neutral = $options['neutral'] ?? null ?: 'tinted';
        if (! preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $name)) {
            throw new ThemeGeneratorException(__(':name is not a valid preset name (lowercase letters, digits, single hyphens)', ['name' => '"'.$name.'"']));
        }
        if (! in_array($neutral, self::NEUTRALS, true)) {
            throw new ThemeGeneratorException(__('neutral must be tinted, pure or keep, not :neutral', ['neutral' => '"'.$neutral.'"']));
        }
        if (empty($options['primary'])) {
            throw new ThemeGeneratorException(__('a primary color is required'));
        }

        $overrides = [];
        $anchors   = [];
        foreach (['primary', 'secondary', 'accent'] as $role) {
            if (empty($options[$role])) {
                continue;
            }
            $input                                   = ColorMath::parseHex((string) $options[$role]);
            $ramp                                    = $this->rampFor($role);
            ['steps' => $steps, 'anchor' => $anchor] = $this->generateRamp($ramp, $input);
            foreach ($steps as $step => $rgb) {
                $overrides["{$ramp}.{$step}"][''] = ColorMath::toHex((string) $step === $anchor && $exact ? $input : $rgb);
            }
            $anchors[$role] = ['ramp' => $ramp, 'step' => (string) $anchor, 'exact' => $exact];
        }

        if ('keep' !== $neutral) {
            [, $primaryChroma, $hue] = ColorMath::srgbToOklch(ColorMath::parseHex((string) $options['primary']));
            // A gray or near-gray primary has no hue worth tinting toward; tinting
            // toward its nominal hue would make the grays pink. Use pure grays.
            if ('tinted' === $neutral && $primaryChroma <= self::NEUTRAL_CHROMA) {
                $neutral = 'pure';
            }
            foreach ($this->rampSteps('gray') as $step => $rgb) {
                $luminance = ColorMath::luminance($rgb);
                if ($luminance <= 0 || $luminance >= 1) {
                    continue;
                }
                $chroma                        = 'pure' === $neutral ? 0.0 : ColorMath::srgbToOklch($rgb)[1];
                $overrides["gray.{$step}"][''] = ColorMath::toHex(ColorMath::matchLuminance($luminance, $chroma, $hue));
            }
            foreach ($this->tintedLiterals($hue, $neutral) as $path => $modes) {
                $overrides[$path] = $modes;
            }
        }

        foreach (['fontSans' => 'font-family.sans', 'fontMono' => 'font-family.mono', 'fontDisplay' => 'font-family.display'] as $key => $path) {
            if (! empty($options[$key])) {
                $overrides[$path][''] = $this->familiesToCss($options[$key]);
            }
        }

        $description = $options['description'] ?? null
            ?: __('Brand preset generated from :primary by artisanpack:generate-preset.', ['primary' => $options['primary']]);

        return new GeneratedPreset($this->tokens, $name, $description, $overrides, $anchors);
    }

    /**
     * The primitive ramp a semantic color role is built on, such as `steel`
     * for `primary`.
     *
     * @since 3.0.0
     *
     * @throws ThemeGeneratorException When the role does not alias a ramp.
     */
    public function rampFor(string $role): string
    {
        $path = "color.{$role}";
        while ($this->tokens->has($path)) {
            $token      = $this->tokens->get($path);
            $mode       = null === $token['axis'] ? null : $this->tokens->axes()[$token['axis']]['default'];
            $references = $this->tokens->referencesIn($this->tokens->cssValue($path, $mode));
            if (1 !== count($references)) {
                break;
            }
            $path = $references[0];
            if ('primitive' === $this->tokens->get($path)['tier']) {
                return explode('.', $path)[0];
            }
        }

        throw new ThemeGeneratorException(__('color.:role does not alias a primitive ramp', ['role' => $role]));
    }

    /**
     * Rebuilds a ramp around a color: the input's hue and chroma, the default
     * ramp's luminance per step.
     *
     * @since 3.0.0
     *
     * @param  array<int, float>  $input  The input color.
     *
     * @return array{steps: array<string, array{0: float, 1: float, 2: float}>, anchor: string}
     */
    public function generateRamp(string $ramp, array $input): array
    {
        $steps                      = $this->rampSteps($ramp);
        [, $inputChroma, $inputHue] = ColorMath::srgbToOklch($input);
        $inputLuminance             = ColorMath::luminance($input);

        $anchor = null;
        foreach ($steps as $step => $rgb) {
            if (null === $anchor || abs(ColorMath::luminance($rgb) - $inputLuminance) < abs(ColorMath::luminance($steps[$anchor]) - $inputLuminance)) {
                $anchor = $step;
            }
        }

        [, $anchorChroma, $anchorHue] = ColorMath::srgbToOklch($steps[$anchor]);
        $scale                        = $anchorChroma > 1e-4 ? $inputChroma / $anchorChroma : 0.0;
        $generated                    = [];
        foreach ($steps as $step => $rgb) {
            [, $chroma, $hue] = ColorMath::srgbToOklch($rgb);
            // Keep the default ramp's hue drift between steps, around the input's hue.
            $generated[$step] = ColorMath::matchLuminance(
                ColorMath::luminance($rgb),
                $chroma * $scale,
                fmod($inputHue + ($hue - $anchorHue) + 360, 360),
            );
        }

        return ['steps' => $generated, 'anchor' => (string) $anchor];
    }

    /**
     * Checks a preset against every contrast pair in every theme.
     *
     * @since 3.0.0
     *
     * @return array<int, array{theme: string, fg: string, bg: string, backdrop: string|null, ratio: float, min: float}>
     */
    public function check(GeneratedPreset $preset): array
    {
        return ContrastChecker::check(
            fn (string $path, string $theme): string => $preset->resolved($path, ['theme' => $theme]),
            $this->tokens->axes()['theme']['modes'],
        );
    }

    /**
     * The steps of a primitive color ramp, as `step => rgb`, in token order.
     *
     * @return array<string, array{0: float, 1: float, 2: float}>
     */
    private function rampSteps(string $ramp): array
    {
        $steps = [];
        foreach ($this->tokens->all() as $path => $token) {
            if ('primitive' === $token['tier'] && str_starts_with($path, $ramp.'.')) {
                $steps[substr($path, strlen($ramp) + 1)] = ColorMath::parseHex($token['resolved']);
            }
        }

        return $steps;
    }

    /**
     * Theme tokens with a literal, tinted-neutral OKLCH value (glass, hover
     * and pressed washes, the backdrop), re-hued or desaturated to follow the
     * neutral treatment.
     *
     * @return array<string, array<string, string>>
     */
    private function tintedLiterals(float $hue, string $treatment): array
    {
        $overrides = [];
        foreach ($this->tokens->all() as $path => $token) {
            if ('theme' !== $token['axis'] || 'color' !== $token['type']) {
                continue;
            }
            foreach ($token['value'] as $mode => $css) {
                if (! preg_match('/^oklch\(([\d.]+%) ([\d.]+) ([\d.]+)( \/ [\d.]+)?\)$/', $css, $m)) {
                    continue;
                }
                $chroma = (float) $m[2];
                if (0.0 === $chroma || $chroma > self::NEUTRAL_CHROMA) {
                    continue;
                }
                $components              = 'pure' === $treatment ? '0 0' : $m[2].' '.ColorMath::formatNumber(round($hue, 2));
                $overrides[$path][$mode] = "oklch({$m[1]} {$components}".($m[4] ?? '').')';
            }
        }

        return $overrides;
    }

    /**
     * A font family list as CSS.
     *
     * @param  array<int, string>|string  $input  Comma-separated names, or a list.
     *
     * @throws ThemeGeneratorException When the list is empty.
     */
    private function familiesToCss(string|array $input): string
    {
        $families = array_values(array_filter(array_map(
            fn (string $family): string => preg_replace('/^["\']|["\']$/', '', trim($family)),
            is_array($input) ? $input : explode(',', $input),
        ), fn (string $family): bool => '' !== $family));

        if ([] === $families) {
            throw new ThemeGeneratorException(__('a font family list must not be empty'));
        }
        foreach ($families as $family) {
            // These would end the declaration or block in the CSS output.
            if (preg_match('/[;{}<>\\\\\r\n]/', $family)) {
                throw new ThemeGeneratorException(__('font family names must not contain ; { } < > \\ or line breaks'));
            }
        }

        return implode(', ', array_map(
            fn (string $family): string => in_array($family, self::GENERIC_FAMILIES, true) || preg_match('/^[A-Za-z][\w-]*$/', $family)
                ? $family
                : '"'.str_replace('"', '\\"', $family).'"',
            $families,
        ));
    }
}
