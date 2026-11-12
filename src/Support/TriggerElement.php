<?php

/**
 * The author's own element as an overlay's trigger part.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

/**
 * Tooltip and Popover take the author's control (a Button, a link) as their
 * trigger rather than wrapping it in one of their own (D-019): the trigger
 * part's attributes go on the first element of the rendered slot, the way
 * React clones the child and Vue binds its first vnode. The element keeps its
 * own id when it has one (so a morph still matches it, D-015); otherwise it
 * gets the part's id.
 *
 * Only the opening tag is touched. An attribute the element already has is
 * left alone, except `class` (the part's classes are appended) and
 * `aria-describedby` (the ids are joined).
 *
 * @since 3.0.0
 */
final class TriggerElement
{
    /**
     * Attributes whose values are joined with the element's own, not skipped.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    private const JOINED = ['class', 'aria-describedby'];

    /**
     * Adds `$attributes` to the first element of `$html`.
     *
     * @since 3.0.0
     *
     * @param  string  $html  The rendered slot.
     * @param  string  $id  The part's id, used when the element has none.
     * @param  array<string, bool|string|null>  $attributes  Attributes to add. `true` renders a bare attribute; null and false are skipped.
     *
     * @return array{html: string, id: string}|null The markup and the trigger's id, or null when the slot doesn't start with an element.
     */
    public static function inject(string $html, string $id, array $attributes): ?array
    {
        $html = trim($html);
        // Skip leading comments, as Blade components render them.
        while (str_starts_with($html, '<!--')) {
            $end = strpos($html, '-->');
            if (false === $end) {
                return null;
            }
            $html = ltrim(substr($html, $end + 3));
        }

        if (1 !== preg_match('/^<([a-zA-Z][a-zA-Z0-9-]*)(?=[\s>\/])/', $html, $match)) {
            return null;
        }

        $close = self::openingTagEnd($html, strlen($match[0]));
        if (null === $close) {
            return null;
        }

        $selfClosing = '/' === $html[$close - 1];
        $tagEnd      = $selfClosing ? $close - 1 : $close;
        $tag         = substr($html, 0, $tagEnd);
        $existing    = self::attributeNames($tag);

        $ownId = self::attributeValue($tag, 'id');
        if (null === $ownId || '' === $ownId) {
            $ownId           = $id;
            $attributes      = ['id' => $id] + $attributes;
        }

        foreach ($attributes as $name => $value) {
            if (null === $value || false === $value) {
                continue;
            }
            $lower = strtolower($name);
            if (in_array($lower, $existing, true)) {
                if (! in_array($lower, self::JOINED, true) || true === $value) {
                    continue;
                }
                $tag = self::joinAttribute($tag, $lower, (string) $value);

                continue;
            }
            $tag .= true === $value ? ' '.$name : ' '.$name.'="'.e((string) $value).'"';
        }

        return [
            'html' => rtrim($tag).($selfClosing ? ' /' : '').substr($html, $close),
            'id'   => $ownId,
        ];
    }

    /**
     * The offset of the `>` that ends the opening tag, skipping quoted values.
     *
     * @since 3.0.0
     */
    private static function openingTagEnd(string $html, int $from): ?int
    {
        $quote  = null;
        $length = strlen($html);
        for ($i = $from; $i < $length; $i++) {
            $char = $html[$i];
            if (null !== $quote) {
                if ($char === $quote) {
                    $quote = null;
                }

                continue;
            }
            if ('"' === $char || "'" === $char) {
                $quote = $char;
            } elseif ('>' === $char) {
                return $i;
            }
        }

        return null;
    }

    /**
     * The attributes of an opening tag (`<tag …` without the `>`), in order:
     * lowercased name, decoded value (null for a bare attribute), and the
     * offsets of the whole attribute.
     *
     * @since 3.0.0
     *
     * @return list<array{name: string, value: string|null, start: int, end: int}>
     */
    private static function parse(string $tag): array
    {
        preg_match('/^<[a-zA-Z][a-zA-Z0-9-]*/', $tag, $name);
        $offset     = strlen($name[0] ?? '');
        $attributes = [];
        $pattern    = '/\G(\s*)([^\s=\/>"\']+)(?:\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s>"\']+)))?/';
        while (1 === preg_match($pattern, $tag, $match, PREG_UNMATCHED_AS_NULL, $offset)) {
            $value        = $match[3] ?? $match[4] ?? $match[5];
            $attributes[] = [
                'name'  => strtolower((string) $match[2]),
                'value' => null === $value ? null : html_entity_decode($value, ENT_QUOTES | ENT_HTML5),
                'start' => $offset + strlen((string) $match[1]),
                'end'   => $offset + strlen((string) $match[0]),
            ];
            $offset += strlen((string) $match[0]);
        }

        return $attributes;
    }

    /**
     * The attribute names in an opening tag, lowercased.
     *
     * @since 3.0.0
     *
     * @return list<string>
     */
    private static function attributeNames(string $tag): array
    {
        return array_column(self::parse($tag), 'name');
    }

    /**
     * An attribute's value in an opening tag, decoded, or null.
     *
     * @since 3.0.0
     */
    private static function attributeValue(string $tag, string $name): ?string
    {
        foreach (self::parse($tag) as $attribute) {
            if ($attribute['name'] === strtolower($name)) {
                return $attribute['value'] ?? '';
            }
        }

        return null;
    }

    /**
     * Appends `$value` to an attribute's value, space-separated.
     *
     * @since 3.0.0
     */
    private static function joinAttribute(string $tag, string $name, string $value): string
    {
        foreach (self::parse($tag) as $attribute) {
            if ($attribute['name'] !== $name) {
                continue;
            }
            $joined = trim(($attribute['value'] ?? '').' '.$value);

            return substr($tag, 0, $attribute['start']).$name.'="'.e($joined).'"'.substr($tag, $attribute['end']);
        }

        return $tag;
    }
}
