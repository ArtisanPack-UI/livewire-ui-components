<?php

/**
 * The blocking head snippet that applies the stored theme before first paint.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\HtmlString;
use Throwable;

/**
 * The theme snippet (#65): a port of `themeScript()` in
 * `@artisanpack-ui/behavior` (`src/theme.ts`), so `@apuiThemeScript` prints
 * the same bytes React's and Vue's ThemeScript do. Keep the two in step.
 *
 * It reads the choice ThemeToggle stores in localStorage (guarded: blocked
 * storage falls back to the default), resolves `system` against
 * `prefers-color-scheme` and sets `data-theme` on `<html>`, the attribute the
 * tokens switch on. Put it in `<head>`, before the stylesheets.
 *
 * @since 3.0.0
 */
final class ThemeScript
{
    /**
     * The choices, in the order the toggles offer them.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const CHOICES = ['light', 'dark', 'high-contrast', 'system'];

    /**
     * The localStorage key ThemeToggle stores the choice under.
     *
     * @since 3.0.0
     */
    public const STORAGE_KEY = 'apui-theme';

    /**
     * Whether a value is a theme choice.
     *
     * @since 3.0.0
     *
     * @param  mixed  $value  The value.
     *
     * @return bool True for light, dark, high-contrast or system.
     */
    public static function isChoice(mixed $value): bool
    {
        return is_string($value) && in_array($value, self::CHOICES, true);
    }

    /**
     * The snippet's JavaScript.
     *
     * @since 3.0.0
     *
     * @param  string  $storageKey  The localStorage key.
     * @param  string  $default  The choice without a stored one.
     *
     * @return string The JavaScript, without the script tag.
     */
    public static function source(string $storageKey = self::STORAGE_KEY, string $default = 'system'): string
    {
        $key      = str_replace('<', '\\u003c', (string) json_encode($storageKey, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        $fallback = (string) json_encode(self::isChoice($default) ? $default : 'system');

        return '(function(){try{var c=null;try{c=localStorage.getItem('.$key.')}catch(e){}'
            .'if(["light","dark","high-contrast","system"].indexOf(c)<0)c='.$fallback.';'
            .'if(c==="system")c=window.matchMedia&&matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light";'
            .'document.documentElement.setAttribute("data-theme",c)}catch(e){}})();';
    }

    /**
     * The snippet as an inline script tag, with Vite's CSP nonce when one is set.
     *
     * @since 3.0.0
     *
     * @param  array{storageKey?: string, default?: string}  $options  The storage key and the default choice.
     *
     * @return HtmlString The tag.
     */
    public static function tag(array $options = []): HtmlString
    {
        $nonce = null;
        try {
            $nonce = Vite::cspNonce();
        } catch (Throwable) {
            $nonce = null;
        }

        $storageKey = is_string($options['storageKey'] ?? null) && '' !== $options['storageKey'] ? $options['storageKey'] : self::STORAGE_KEY;
        $default    = is_string($options['default'] ?? null) ? $options['default'] : 'system';

        return new HtmlString(
            '<script'.(is_string($nonce) && '' !== $nonce ? ' nonce="'.e($nonce).'"' : '').'>'
            .self::source($storageKey, $default)
            .'</script>',
        );
    }
}
