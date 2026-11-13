<?php

/**
 * A small unified diff.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Upgrade;

/**
 * Writes the unified diff `artisanpack:upgrade-ui --dry-run` shows, with no
 * diff library: the command has to run in any app that installs the package.
 *
 * @since 3.0.0
 */
final class UnifiedDiff
{
    /**
     * The most cells the comparison table may hold. Past it, the changed
     * region is shown as one block removed and one added, so a huge file
     * can't exhaust memory.
     */
    private const MAX_CELLS = 4_000_000;

    /**
     * The hunks of a unified diff, without the `---` and `+++` header lines.
     *
     * @since 3.0.0
     *
     * @param  string  $before  The file as it was.
     * @param  string  $after  The file as it will be.
     * @param  int  $context  Unchanged lines to show around each change.
     *
     * @return array<int, string> One entry per output line; empty when nothing changed.
     */
    public static function lines(string $before, string $after, int $context = 3): array
    {
        if ($before === $after) {
            return [];
        }

        $ops     = self::operations(self::split($before), self::split($after));
        $count   = count($ops);
        $out     = [];
        $index   = 0;
        $oldLine = 1;
        $newLine = 1;

        while ($index < $count) {
            if (' ' === $ops[$index][0]) {
                $index++;
                $oldLine++;
                $newLine++;

                continue;
            }

            $start = max(0, $index - $context);
            $end   = $index;
            $quiet = 0;

            // A hunk runs until `context * 2` unchanged lines separate it from the next change.
            for ($k = $index; $k < $count; $k++) {
                if (' ' === $ops[$k][0]) {
                    if (++$quiet > $context * 2) {
                        break;
                    }
                } else {
                    $quiet = 0;
                    $end   = $k;
                }
            }

            $end     = min($count - 1, $end + $context);
            $hunk    = array_slice($ops, $start, $end - $start + 1);
            $back    = $index - $start;
            $removed = count(array_filter($hunk, fn (array $op): bool => '+' !== $op[0]));
            $added   = count(array_filter($hunk, fn (array $op): bool => '-' !== $op[0]));

            // An empty side starts at line 0, as `diff -u` writes it.
            $out[] = sprintf(
                '@@ -%d,%d +%d,%d @@',
                0 === $removed ? $oldLine - $back - 1 : $oldLine - $back,
                $removed,
                0 === $added ? $newLine - $back - 1 : $newLine - $back,
                $added,
            );

            foreach ($hunk as [$type, $line]) {
                $out[] = $type.$line;
            }

            for ($k = $index; $k <= $end; $k++) {
                $oldLine += '+' !== $ops[$k][0] ? 1 : 0;
                $newLine += '-' !== $ops[$k][0] ? 1 : 0;
            }

            $index = $end + 1;
        }

        return $out;
    }

    /**
     * A file's lines. A final line without a newline is marked, so it differs
     * from the same line with one.
     *
     * @return array<int, string>
     */
    private static function split(string $text): array
    {
        if ('' === $text) {
            return [];
        }

        $lines = explode("\n", $text);

        if (str_ends_with($text, "\n")) {
            array_pop($lines);
        } else {
            $lines[count($lines) - 1] .= "\n\\ No newline at end of file";
        }

        return $lines;
    }

    /**
     * The edit script: each line kept (` `), removed (`-`) or added (`+`).
     *
     * @param  array<int, string>  $before
     * @param  array<int, string>  $after
     *
     * @return array<int, array{0: string, 1: string}>
     */
    private static function operations(array $before, array $after): array
    {
        $beforeCount = count($before);
        $afterCount  = count($after);

        // Trim what both sides share, so the table only covers what changed.
        $head = 0;
        while ($head < $beforeCount && $head < $afterCount && $before[$head] === $after[$head]) {
            $head++;
        }

        $tail = 0;
        while ($tail < $beforeCount - $head && $tail < $afterCount - $head && $before[$beforeCount - 1 - $tail] === $after[$afterCount - 1 - $tail]) {
            $tail++;
        }

        $a   = array_slice($before, $head, $beforeCount - $head - $tail);
        $b   = array_slice($after, $head, $afterCount - $head - $tail);
        $n   = count($a);
        $m   = count($b);
        $ops = array_map(fn (string $line): array => [' ', $line], array_slice($before, 0, $head));

        if ($n * $m > self::MAX_CELLS) {
            foreach ($a as $line) {
                $ops[] = ['-', $line];
            }
            foreach ($b as $line) {
                $ops[] = ['+', $line];
            }
        } else {
            $table = array_fill(0, $n + 1, array_fill(0, $m + 1, 0));

            for ($i = $n - 1; $i >= 0; $i--) {
                for ($j = $m - 1; $j >= 0; $j--) {
                    $table[$i][$j] = $a[$i] === $b[$j] ? $table[$i + 1][$j + 1] + 1 : max($table[$i + 1][$j], $table[$i][$j + 1]);
                }
            }

            $i = 0;
            $j = 0;

            while ($i < $n && $j < $m) {
                if ($a[$i] === $b[$j]) {
                    $ops[] = [' ', $a[$i]];
                    $i++;
                    $j++;
                } elseif ($table[$i + 1][$j] >= $table[$i][$j + 1]) {
                    $ops[] = ['-', $a[$i++]];
                } else {
                    $ops[] = ['+', $b[$j++]];
                }
            }

            while ($i < $n) {
                $ops[] = ['-', $a[$i++]];
            }

            while ($j < $m) {
                $ops[] = ['+', $b[$j++]];
            }
        }

        foreach (array_slice($before, $beforeCount - $tail) as $line) {
            $ops[] = [' ', $line];
        }

        return $ops;
    }
}
