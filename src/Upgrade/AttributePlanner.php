<?php

/**
 * Decides what one attribute becomes under an upgrade rule.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Upgrade;

/**
 * The PHP twin of `planAttribute()` in `@artisanpack-ui/codemods`, so Blade,
 * React and Vue rewrite an attribute the same way.
 *
 * A value is described as `['type' => 'flag']` for a bare attribute,
 * `['type' => 'literal', 'value' => …, 'string' => bool]` for a string,
 * boolean or number the upgrade can read, and `['type' => 'dynamic']` for any
 * other expression.
 *
 * @since 3.0.0
 */
final class AttributePlanner
{
    /**
     * Plans one attribute.
     *
     * The plan's `type` is `none`, `rename` (with `to` and maybe `value`),
     * `set` (`to="value"`), `boolean` (`to` set to `value`), `number` (`to`
     * bound to the integer `value`), `negate`, `part-class` (`part`), `wrap`,
     * `content` or `remove`.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The attribute as written, for messages.
     * @param  array<string, mixed>  $rule  The rule.
     * @param  array{type: string, value?: string, string?: bool}  $value  How the value was written.
     *
     * @return array{plan: array<string, mixed>, manual?: string, review?: string}
     */
    public static function plan(string $name, array $rule, array $value): array
    {
        $note    = isset($rule['note']) ? ' '.$rule['note'] : '';
        $literal = 'literal' === $value['type'] && ($value['string'] ?? false);
        $isTrue  = 'flag' === $value['type'] || ('literal' === $value['type'] && ! ($value['string'] ?? false) && 'true' === ($value['value'] ?? null));
        $isFalse = 'literal' === $value['type'] && ! ($value['string'] ?? false) && 'false' === ($value['value'] ?? null);
        $to      = isset($rule['to']) ? UpgradeRules::kebab((string) $rule['to']) : '';

        switch ($rule['kind']) {
            case 'rename':
                // The name is still a 3.0 attribute with these values, so they stay (Pin `size`).
                if ($literal && in_array($value['value'] ?? '', $rule['keep'] ?? [], true)) {
                    return ['plan' => ['type' => 'none']];
                }

                $review = ($rule['review'] ?? false) ? ($rule['note'] ?? __('Check this change.')) : null;

                if (! isset($rule['values'])) {
                    return array_filter(['plan' => ['type' => 'rename', 'to' => $to], 'review' => $review]);
                }

                if ($literal) {
                    return array_filter([
                        'plan'   => array_filter(['type' => 'rename', 'to' => $to, 'value' => $rule['values'][$value['value'] ?? ''] ?? null], fn ($item): bool => null !== $item),
                        'review' => $review,
                    ]);
                }

                $pairs = [];
                foreach ($rule['values'] as $from => $new) {
                    $pairs[] = "`{$from}` is now `{$new}`";
                }

                return array_filter([
                    'plan'   => ['type' => 'rename', 'to' => $to],
                    'review' => 'dynamic' === $value['type'] ? __('Check the value: :pairs.', ['pairs' => implode(', ', $pairs)]) : $review,
                ]);

            case 'value':
                if ($isTrue) {
                    return ['plan' => ['type' => 'set', 'to' => $to, 'value' => $rule['value']]];
                }

                if ($isFalse) {
                    return ['plan' => ['type' => 'remove']];
                }

                return [
                    'plan'   => ['type' => 'none'],
                    'manual' => __('`:name` is now `:to=":value"`; set `:to` from this expression.', ['name' => $name, 'to' => $to, 'value' => $rule['value']]).$note,
                ];

            case 'split':
                if ($literal) {
                    foreach ($rule['cases'] as $case) {
                        if (in_array($value['value'] ?? '', $case['values'], true)) {
                            return ['plan' => ['type' => 'rename', 'to' => UpgradeRules::kebab($case['to'])]];
                        }
                    }

                    if (in_array($value['value'] ?? '', $rule['keep'] ?? [], true)) {
                        return ['plan' => ['type' => 'none']];
                    }
                }

                $targets = implode(' or ', array_map(fn (array $case): string => '`'.UpgradeRules::kebab($case['to']).'`', $rule['cases']));

                return [
                    'plan'   => ['type' => 'none'],
                    'manual' => __('`:name` may now be :targets, depending on its value.', ['name' => $name, 'targets' => $targets]).$note,
                ];

            case 'invert':
                if ($isTrue || $isFalse) {
                    return ['plan' => ['type' => 'boolean', 'to' => $to, 'value' => $isFalse]];
                }

                if ('dynamic' === $value['type']) {
                    return ['plan' => ['type' => 'negate', 'to' => $to]];
                }

                return [
                    'plan'   => ['type' => 'none'],
                    'manual' => __('`:name` is now `:to`, with the opposite meaning.', ['name' => $name, 'to' => $to]),
                ];

            case 'part-class':
                return ['plan' => ['type' => 'part-class', 'part' => $rule['part']]];

            case 'duration':
                $milliseconds = 'literal' === $value['type'] ? self::durationMs((string) ($value['value'] ?? '')) : null;

                if (null !== $milliseconds) {
                    return ['plan' => ['type' => 'number', 'to' => $to, 'value' => $milliseconds]];
                }

                return [
                    'plan'   => ['type' => 'none'],
                    'manual' => __('`:name` is now integer milliseconds on `:to`; convert this value.', ['name' => $name, 'to' => $to]).$note,
                ];

            case 'keep':
                return ['plan' => ['type' => 'none']];

            case 'wrap':
                return ['plan' => ['type' => 'wrap']];

            case 'content':
                return ['plan' => ['type' => 'content']];

            case 'remove':
                return ['plan' => ['type' => 'remove']];

            default:
                return ['plan' => ['type' => 'none'], 'manual' => $rule['note'] ?? __('`:name` changed.', ['name' => $name])];
        }
    }

    /**
     * A duration (`250ms`, `1s` or `250`) in milliseconds, the twin of
     * `durationMs()` in `@artisanpack-ui/codemods`.
     *
     * @since 3.0.0
     *
     * @param  string  $text  The duration as written.
     *
     * @return int|null The milliseconds, or null when it isn't a duration.
     */
    public static function durationMs(string $text): ?int
    {
        if (1 !== preg_match('/^\s*(\d+(?:\.\d+)?)\s*(ms|s)?\s*$/i', $text, $matches)) {
            return null;
        }

        $multiplier = 's' === strtolower($matches[2] ?? '') ? 1000 : 1;

        return (int) round((float) $matches[1] * $multiplier);
    }
}
