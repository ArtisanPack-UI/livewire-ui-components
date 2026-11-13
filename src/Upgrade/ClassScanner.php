<?php

/**
 * Finds raw DaisyUI classes and theme references.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Upgrade;

/**
 * Reports the DaisyUI classes, theme variables and configuration in a file.
 *
 * The upgrade never rewrites them: a `btn` in app markup needs a person to
 * pick the component or utility that replaces it. Classes are only matched
 * inside class attributes and `@class`, because many DaisyUI names are
 * ordinary words. The lists come from the design system's Phase 0 scanner.
 *
 * @since 3.0.0
 */
final class ClassScanner
{
    /**
     * Where class lists are written: `class="…"`, `:class="…"` and `@class([…])`.
     */
    private const SOURCES = [
        '~(?:^|[\s<"\'(])(?::class|x-bind:class|class)\s*=\s*(?:"[^"]*"|\'[^\']*\')~',
        '~@class\(\s*\[[\s\S]*?\]\s*\)~',
    ];

    /**
     * Creates the scanner.
     *
     * @since 3.0.0
     *
     * @param  UpgradeRules  $rules  The upgrade rules.
     */
    public function __construct(private readonly UpgradeRules $rules) {}

    /**
     * Classifies one class token, variants and all (`hover:btn-sm`).
     *
     * @since 3.0.0
     *
     * @param  string  $token  A class name.
     *
     * @return string|null `component`, `ambiguous`, `themeBase` or `themeContent`; null when it isn't DaisyUI's.
     */
    public function classify(string $token): ?string
    {
        $classes  = $this->rules->classes();
        $patterns = $classes['patterns'] ?? [];
        $name     = (string) preg_replace('/^(?:[a-z0-9-]+:)*!?/', '', $token);

        if (1 !== preg_match('~^[a-z][a-z0-9/-]*$~', $name)) {
            return null;
        }

        foreach (['themeBase', 'themeContent'] as $kind) {
            if (isset($patterns[$kind]) && 1 === preg_match('~'.$patterns[$kind].'~', $name)) {
                return $kind;
            }
        }

        // `bg-primary` survives through the Tailwind preset, so it isn't reported.
        if (isset($patterns['themeSemantic']) && 1 === preg_match('~'.$patterns['themeSemantic'].'~', $name)) {
            return null;
        }

        if ($this->matchesRoot($name, $classes['component'] ?? [])) {
            return 'component';
        }

        $lookalike = isset($patterns['lookalike']) && 1 === preg_match('~'.$patterns['lookalike'].'~', $name);

        return $this->matchesRoot($name, $classes['ambiguous'] ?? []) && ! $lookalike ? 'ambiguous' : null;
    }

    /**
     * Lists the DaisyUI usage in a file, in order.
     *
     * @since 3.0.0
     *
     * @param  string  $source  Blade, HTML or CSS source.
     *
     * @return array<int, Finding>
     */
    public function scan(string $source): array
    {
        $found    = [];
        $patterns = $this->rules->classes()['patterns'] ?? [];

        foreach (self::SOURCES as $pattern) {
            preg_match_all($pattern, $source, $matches, PREG_OFFSET_CAPTURE);

            foreach ($matches[0] as [$text, $offset]) {
                preg_match_all('~[A-Za-z0-9_:!/.\[\]-]+~', $text, $tokens, PREG_OFFSET_CAPTURE);

                foreach ($tokens[0] as [$token, $inner]) {
                    $kind = $this->classify($token);

                    if (null !== $kind) {
                        $found[$offset + $inner] = [$kind, $token];
                    }
                }
            }
        }

        foreach (['themeVariable', 'config'] as $kind) {
            if (! isset($patterns[$kind])) {
                continue;
            }

            preg_match_all('~'.$patterns[$kind].'~', $source, $matches, PREG_OFFSET_CAPTURE);

            foreach ($matches[0] as [$text, $offset]) {
                $found[$offset] = [$kind, trim($text)];
            }
        }

        ksort($found);
        $lines    = new LineIndex($source);
        $findings = [];

        foreach ($found as $offset => [$kind, $name]) {
            [$line, $column] = $lines->locate($offset);
            $findings[]      = new Finding(Finding::DAISY_CLASS, $line, $column, $this->message($kind, $name), null, $name);
        }

        return $findings;
    }

    /**
     * Whether a class is a root or one of its `root-*` modifiers.
     *
     * @param  array<int, string>  $roots
     */
    private function matchesRoot(string $name, array $roots): bool
    {
        foreach ($roots as $root) {
            if ($name === $root || str_starts_with($name, $root.'-')) {
                return true;
            }
        }

        return false;
    }

    /**
     * What to do about one kind of DaisyUI usage.
     */
    private function message(string $kind, string $name): string
    {
        return match ($kind) {
            'component'     => __('`:name` is a DaisyUI class. Use the component or an `apui-` class instead.', ['name' => $name]),
            'ambiguous'     => __('`:name` may be a DaisyUI class. Check whether it still has styles without DaisyUI.', ['name' => $name]),
            'themeBase'     => __('`:name` is a DaisyUI theme utility. Use a token, such as `var(--apui-surface-page)`, or the Tailwind preset\'s utilities.', ['name' => $name]),
            'themeContent'  => __('`:name` is a DaisyUI theme utility. Use the color\'s `-foreground` token.', ['name' => $name]),
            'themeVariable' => __('`:name` is a DaisyUI theme variable. Use an `--apui-*` token.', ['name' => $name]),
            default         => __('`:name` configures DaisyUI. See the upgrade guide\'s CSS section.', ['name' => $name]),
        };
    }
}
