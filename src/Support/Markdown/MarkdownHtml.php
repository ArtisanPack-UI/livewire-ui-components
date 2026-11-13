<?php

/**
 * The raw-HTML allowlist for the Markdown component.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support\Markdown;

use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use League\CommonMark\Util\RegexHelper;

/**
 * Filters raw HTML in Markdown with `sanitize` off: a port of
 * `filterMarkdownHtml` in `@artisanpack-ui/behavior` (`src/data/markdown.ts`),
 * so Blade writes the same HTML as React and Vue. Keep the two in step.
 *
 * Allowlisted tags are rebuilt with their allowed attributes (event
 * handlers, `style` and `class` never pass; `href` and `src` follow
 * {@see SafeUrl}), comments are dropped, and every other tag, and any stray
 * `<` or `>`, is escaped as text. It works on tags rather than a tree, the
 * way commonmark hands raw HTML over: one tag per inline node.
 *
 * @since 3.0.0
 */
final class MarkdownHtml
{
    /**
     * Tags raw HTML may keep.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const TAGS = [
        'a', 'abbr', 'b', 'blockquote', 'br', 'caption', 'cite', 'code', 'dd', 'del', 'details', 'dfn', 'div', 'dl', 'dt', 'em',
        'figcaption', 'figure', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'hr', 'i', 'img', 'ins', 'kbd', 'li', 'mark', 'ol', 'p', 'pre',
        'q', 's', 'samp', 'small', 'span', 'strong', 'sub', 'summary', 'sup', 'table', 'tbody', 'td', 'tfoot', 'th', 'thead', 'tr',
        'u', 'ul', 'var',
    ];

    /**
     * Attributes any allowed tag keeps.
     *
     * @var list<string>
     */
    private const GLOBAL_ATTRIBUTES = ['title', 'lang', 'dir'];

    /**
     * Attributes particular tags keep.
     *
     * @var array<string, list<string>>
     */
    private const TAG_ATTRIBUTES = [
        'a'       => ['href'],
        'img'     => ['src', 'alt', 'width', 'height'],
        'td'      => ['colspan', 'rowspan'],
        'th'      => ['colspan', 'rowspan'],
        'ol'      => ['start', 'reversed'],
        'li'      => ['value'],
        'details' => ['open'],
    ];

    private const TAG_PATTERN = '/<!--[\s\S]*?-->|<(\/?)([A-Za-z][A-Za-z0-9-]*)((?:\s+[^\s"\'>\/=]+(?:\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s"\'=<>`]+))?)*)\s*\/?>/';

    private const ATTRIBUTE_PATTERN = '/([^\s"\'>\/=]+)(?:\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s"\'=<>`]+)))?/';

    /**
     * Raw HTML filtered to the allowlist.
     *
     * @since 3.0.0
     *
     * @param  string  $html  A raw HTML block or inline tag.
     *
     * @return string Safe HTML.
     */
    public static function filter(string $html): string
    {
        $result = '';
        $last   = 0;

        preg_match_all(self::TAG_PATTERN, $html, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE | PREG_UNMATCHED_AS_NULL);

        foreach ($matches as $match) {
            [$whole, $index] = $match[0];
            $result .= self::escapeAngles(substr($html, $last, $index - $last));
            $last            = $index + strlen($whole);

            if (! isset($match[2][0])) {
                continue;
            }

            $tag = self::rebuildTag('/' === $match[1][0], $match[2][0], $match[3][0] ?? '');
            $result .= $tag ?? self::escapeAngles($whole);
        }

        return $result.self::escapeAngles(substr($html, $last));
    }

    /**
     * One allowed tag rebuilt with its allowed, escaped attributes; null when
     * the tag isn't allowed.
     *
     * @param  bool  $closing  Whether it's a closing tag.
     * @param  string  $rawName  The tag name as written.
     * @param  string  $rawAttributes  The attributes as written.
     *
     * @return string|null The tag.
     */
    private static function rebuildTag(bool $closing, string $rawName, string $rawAttributes): ?string
    {
        $name = strtolower($rawName);
        if (! in_array($name, self::TAGS, true)) {
            return null;
        }

        if ($closing) {
            return '</'.$name.'>';
        }

        $allowed    = [...self::GLOBAL_ATTRIBUTES, ...(self::TAG_ATTRIBUTES[$name] ?? [])];
        $seen       = [];
        $attributes = '';

        preg_match_all(self::ATTRIBUTE_PATTERN, $rawAttributes, $found, PREG_SET_ORDER | PREG_UNMATCHED_AS_NULL);

        foreach ($found as $match) {
            $attribute = strtolower((string) $match[1]);
            if (! in_array($attribute, $allowed, true) || isset($seen[$attribute])) {
                continue;
            }

            $seen[$attribute] = true;
            $value            = self::decodeAttribute((string) ($match[2] ?? $match[3] ?? $match[4] ?? ''));

            if ('href' === $attribute || 'src' === $attribute) {
                $value = 'href' === $attribute ? SafeUrl::href($value) : SafeUrl::media($value);
                // Images take the same data: formats as Markdown images: PNG, GIF, JPEG and WebP.
                if (null === $value || ('src' === $attribute && RegexHelper::isLinkPotentiallyUnsafe($value))) {
                    continue;
                }
            }

            $attributes .= ' '.$attribute.'="'.self::escape($value).'"';
        }

        return '<'.$name.$attributes.'>';
    }

    /**
     * Decodes the references an attribute value may use: numeric, amp, lt,
     * gt, quot and apos. Anything else stays as written and is escaped, so the
     * browser reads exactly the value that was checked.
     *
     * @param  string  $value  The value as written.
     *
     * @return string The decoded value.
     */
    private static function decodeAttribute(string $value): string
    {
        $named = ['amp' => '&', 'lt' => '<', 'gt' => '>', 'quot' => '"', 'apos' => "'"];

        return (string) preg_replace_callback(
            '/&(?:#[xX]([0-9a-fA-F]{1,6})|#([0-9]{1,7})|(amp|lt|gt|quot|apos));/',
            static function (array $match) use ($named): string {
                if (null !== $match[1] && '' !== $match[1]) {
                    return self::codePoint((int) hexdec($match[1]));
                }

                if (null !== $match[2] && '' !== $match[2]) {
                    return self::codePoint((int) $match[2]);
                }

                return $named[$match[3]];
            },
            $value,
            -1,
            $count,
            PREG_UNMATCHED_AS_NULL,
        );
    }

    /**
     * A code point as UTF-8, or U+FFFD where it isn't a character.
     *
     * @param  int  $value  The code point.
     *
     * @return string The character.
     */
    private static function codePoint(int $value): string
    {
        if (0 === $value || $value > 0x10FFFF || ($value >= 0xD800 && $value <= 0xDFFF)) {
            return "\u{FFFD}";
        }

        return mb_chr($value, 'UTF-8');
    }

    /**
     * An attribute value escaped as commonmark's `Xml::escape`.
     *
     * @param  string  $value  The value.
     *
     * @return string The escaped value.
     */
    private static function escape(string $value): string
    {
        return str_replace(['&', '<', '>', '"'], ['&amp;', '&lt;', '&gt;', '&quot;'], $value);
    }

    /**
     * Text with its angle brackets escaped.
     *
     * @param  string  $text  The text.
     *
     * @return string The escaped text.
     */
    private static function escapeAngles(string $text): string
    {
        return str_replace(['<', '>'], ['&lt;', '&gt;'], $text);
    }
}
