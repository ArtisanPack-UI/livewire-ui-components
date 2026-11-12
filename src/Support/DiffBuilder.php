<?php

/**
 * The line and word diff behind the design-system Diff.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Support;

/**
 * Diffs two texts into hunks (#61): a port of `diffText` and `splitRows` in
 * `@artisanpack-ui/behavior` (`src/data/diff.ts`), with the same
 * tie-breaking, so Blade renders the same rows as React and Vue. Keep the
 * two in step.
 *
 * Lines are compared by a longest common subsequence, after the common
 * prefix and suffix: within a change, deletions come before additions. A
 * change too big for the table (`MAX_CELLS`) deletes every old line, then
 * adds every new one. Hunks keep `context` unchanged lines
 * around each change. With word-level marks, the n-th deleted line of a
 * change pairs with its n-th added line, and their differing words are
 * marked, unless the two lines share nothing.
 *
 * A line is `['type' => 'ctx'|'add'|'del', 'oldNumber' => ?int,
 * 'newNumber' => ?int, 'segments' => list<array{text: string, changed: bool}>]`.
 *
 * @since 3.0.0
 */
final class DiffBuilder
{
    /**
     * The most cells the longest-common-subsequence table may have, after the
     * common prefix and suffix are taken off. Keep it equal to
     * `DIFF_MAX_CELLS` in the behavior package.
     *
     * @since 3.0.0
     */
    public const MAX_CELLS = 1000000;

    private const TOKEN = '/[\p{L}\p{N}_]+|\s+|[^\p{L}\p{N}_\s]/u';

    /**
     * The text's lines, with `\r\n` normalized and no empty line after a final newline.
     *
     * @since 3.0.0
     *
     * @return list<string>
     */
    public static function lines(?string $text): array
    {
        $normalized = preg_replace('/\r\n?/', "\n", $text ?? '') ?? '';

        if ('' === $normalized) {
            return [];
        }

        $lines = explode("\n", $normalized);

        if ('' === end($lines)) {
            array_pop($lines);
        }

        return $lines;
    }

    /**
     * The words, spaces and punctuation of a line.
     *
     * @since 3.0.0
     *
     * @return list<string>
     */
    public static function tokens(string $line): array
    {
        preg_match_all(self::TOKEN, $line, $matches);

        return $matches[0];
    }

    /**
     * The two lines as segments marking the tokens that differ, or null when
     * they share nothing.
     *
     * @since 3.0.0
     *
     * @return array{old: list<array{text: string, changed: bool}>, new: list<array{text: string, changed: bool}>}|null
     */
    public static function words(string $oldLine, string $newLine): ?array
    {
        $a   = self::tokens($oldLine);
        $b   = self::tokens($newLine);
        $ops = self::editScript($a, $b);

        if ([] === array_filter($ops, static fn (array $op): bool => 'ctx' === $op['type'])) {
            return null;
        }

        $build = static function (string $side) use ($ops, $a, $b): array {
            $segments = [];
            foreach ($ops as $op) {
                if ('old' === $side ? 'add' === $op['type'] : 'del' === $op['type']) {
                    continue;
                }
                $text    = 'old' === $side ? $a[$op['a']] : $b[$op['b']];
                $changed = 'ctx' !== $op['type'];
                $last    = count($segments) - 1;
                if ($last >= 0 && $segments[$last]['changed'] === $changed) {
                    $segments[$last]['text'] .= $text;
                } else {
                    $segments[] = ['text' => $text, 'changed' => $changed];
                }
            }

            return $segments;
        };

        return ['old' => $build('old'), 'new' => $build('new')];
    }

    /**
     * The unified diff of two texts.
     *
     * @since 3.0.0
     *
     * @return array{hunks: list<array{header: string, lines: list<array>}>, additions: int, deletions: int}
     */
    public static function diff(?string $oldText, ?string $newText, int $context = 3, bool $wordLevel = true): array
    {
        $context = max(0, $context);
        $a       = self::lines($oldText);
        $b       = self::lines($newText);
        $lines   = [];

        foreach (self::editScript($a, $b) as $op) {
            $lines[] = [
                'type'      => $op['type'],
                'oldNumber' => 'add' === $op['type'] ? null : $op['a'] + 1,
                'newNumber' => 'del' === $op['type'] ? null : $op['b'] + 1,
                'segments'  => [['text' => 'add' === $op['type'] ? $b[$op['b']] : $a[$op['a']], 'changed' => false]],
            ];
        }

        $total = count($lines);

        if ($wordLevel) {
            $index = 0;
            while ($index < $total) {
                if ('del' !== $lines[$index]['type']) {
                    $index++;

                    continue;
                }
                $delStart = $index;
                while ($index < $total && 'del' === $lines[$index]['type']) {
                    $index++;
                }
                $addStart = $index;
                while ($index < $total && 'add' === $lines[$index]['type']) {
                    $index++;
                }
                $pairs = min($addStart - $delStart, $index - $addStart);
                for ($k = 0; $k < $pairs; $k++) {
                    $words = self::words($lines[$delStart + $k]['segments'][0]['text'], $lines[$addStart + $k]['segments'][0]['text']);
                    if (null !== $words) {
                        $lines[$delStart + $k]['segments'] = $words['old'];
                        $lines[$addStart + $k]['segments'] = $words['new'];
                    }
                }
            }
        }

        $ranges = [];
        foreach ($lines as $index => $line) {
            if ('ctx' === $line['type']) {
                continue;
            }
            $start = max(0, $index - $context);
            $end   = min($total - 1, $index + $context);
            $last  = count($ranges) - 1;
            if ($last >= 0 && $start <= $ranges[$last][1] + 1) {
                $ranges[$last][1] = max($ranges[$last][1], $end);
            } else {
                $ranges[] = [$start, $end];
            }
        }

        $hunks = [];
        foreach ($ranges as [$start, $end]) {
            $before    = array_slice($lines, 0, $start);
            $slice     = array_slice($lines, $start, $end - $start + 1);
            $oldBefore = count(array_filter($before, static fn (array $line): bool => 'add' !== $line['type']));
            $newBefore = count(array_filter($before, static fn (array $line): bool => 'del' !== $line['type']));
            $oldCount  = count(array_filter($slice, static fn (array $line): bool => 'add' !== $line['type']));
            $newCount  = count(array_filter($slice, static fn (array $line): bool => 'del' !== $line['type']));
            $oldStart  = 0 === $oldCount ? $oldBefore : $oldBefore + 1;
            $newStart  = 0 === $newCount ? $newBefore : $newBefore + 1;
            $hunks[]   = [
                'header' => '@@ -'.self::range($oldStart, $oldCount).' +'.self::range($newStart, $newCount).' @@',
                'lines'  => $slice,
            ];
        }

        return [
            'hunks'     => $hunks,
            'additions' => count(array_filter($lines, static fn (array $line): bool => 'add' === $line['type'])),
            'deletions' => count(array_filter($lines, static fn (array $line): bool => 'del' === $line['type'])),
        ];
    }

    /**
     * A hunk's lines as split-view rows: changes pair deletions with additions.
     * A side is `['type' => 'ctx'|'add'|'del'|'empty', 'number' => ?int, 'segments' => list]`.
     *
     * @since 3.0.0
     *
     * @return list<array{old: array, new: array}>
     */
    public static function splitRows(array $lines): array
    {
        $empty = ['type' => 'empty', 'number' => null, 'segments' => []];
        $rows  = [];
        $total = count($lines);
        $index = 0;

        while ($index < $total) {
            $line = $lines[$index];
            if ('ctx' === $line['type']) {
                $rows[] = [
                    'old' => ['type' => 'ctx', 'number' => $line['oldNumber'], 'segments' => $line['segments']],
                    'new' => ['type' => 'ctx', 'number' => $line['newNumber'], 'segments' => $line['segments']],
                ];
                $index++;

                continue;
            }
            $removed = [];
            $added   = [];
            while ($index < $total && 'del' === $lines[$index]['type']) {
                $removed[] = $lines[$index++];
            }
            while ($index < $total && 'add' === $lines[$index]['type']) {
                $added[] = $lines[$index++];
            }
            for ($k = 0; $k < max(count($removed), count($added)); $k++) {
                $rows[] = [
                    'old' => isset($removed[$k]) ? ['type' => 'del', 'number' => $removed[$k]['oldNumber'], 'segments' => $removed[$k]['segments']] : $empty,
                    'new' => isset($added[$k]) ? ['type' => 'add', 'number' => $added[$k]['newNumber'], 'segments' => $added[$k]['segments']] : $empty,
                ];
            }
        }

        return $rows;
    }

    /**
     * The edit script turning `a` into `b`, by longest common subsequence.
     * The common prefix and suffix are matched first, so the table only
     * spans the part that changed; past `MAX_CELLS` the change deletes every
     * old item, then adds every new one.
     *
     * @return list<array{type: string, a: int, b: int}>
     */
    private static function editScript(array $a, array $b): array
    {
        $lengthA = count($a);
        $lengthB = count($b);
        $prefix  = 0;
        while ($prefix < $lengthA && $prefix < $lengthB && $a[$prefix] === $b[$prefix]) {
            $prefix++;
        }
        $suffix = 0;
        while ($suffix < $lengthA - $prefix && $suffix < $lengthB - $prefix && $a[$lengthA - 1 - $suffix] === $b[$lengthB - 1 - $suffix]) {
            $suffix++;
        }

        $ops = [];
        for ($k = 0; $k < $prefix; $k++) {
            $ops[] = ['type' => 'ctx', 'a' => $k, 'b' => $k];
        }

        $n = $lengthA - $prefix - $suffix;
        $m = $lengthB - $prefix - $suffix;

        if ($n * $m > self::MAX_CELLS) {
            for ($k = 0; $k < $n; $k++) {
                $ops[] = ['type' => 'del', 'a' => $prefix + $k, 'b' => $prefix];
            }
            for ($k = 0; $k < $m; $k++) {
                $ops[] = ['type' => 'add', 'a' => $prefix + $n, 'b' => $prefix + $k];
            }
        } else {
            $table = array_fill(0, $n + 1, array_fill(0, $m + 1, 0));

            for ($i = $n - 1; $i >= 0; $i--) {
                for ($j = $m - 1; $j >= 0; $j--) {
                    $table[$i][$j] = $a[$prefix + $i] === $b[$prefix + $j]
                        ? $table[$i + 1][$j + 1] + 1
                        : max($table[$i + 1][$j], $table[$i][$j + 1]);
                }
            }

            $i = 0;
            $j = 0;
            while ($i < $n || $j < $m) {
                if ($i < $n && $j < $m && $a[$prefix + $i] === $b[$prefix + $j]) {
                    $ops[] = ['type' => 'ctx', 'a' => $prefix + $i++, 'b' => $prefix + $j++];
                } elseif ($j >= $m || ($i < $n && $table[$i + 1][$j] >= $table[$i][$j + 1])) {
                    $ops[] = ['type' => 'del', 'a' => $prefix + $i++, 'b' => $prefix + $j];
                } else {
                    $ops[] = ['type' => 'add', 'a' => $prefix + $i, 'b' => $prefix + $j++];
                }
            }
        }

        for ($k = $suffix; $k > 0; $k--) {
            $ops[] = ['type' => 'ctx', 'a' => $lengthA - $k, 'b' => $lengthB - $k];
        }

        return $ops;
    }

    private static function range(int $start, int $count): string
    {
        return 1 === $count ? (string) $start : $start.','.$count;
    }
}
