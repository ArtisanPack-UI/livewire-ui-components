<?php

/**
 * Turns offsets into lines and columns.
 *
 * @since      3.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Upgrade;

/**
 * Finds the 1-based line and column of a byte offset in a source file.
 *
 * @since 3.0.0
 */
final class LineIndex
{
    /**
     * The offset each line starts at.
     *
     * @var array<int, int>
     */
    private array $starts = [0];

    /**
     * Indexes a source file.
     *
     * @since 3.0.0
     *
     * @param  string  $source  The file's contents.
     */
    public function __construct(string $source)
    {
        $offset = 0;

        while (false !== ($offset = strpos($source, "\n", $offset))) {
            $this->starts[] = ++$offset;
        }
    }

    /**
     * The line and column of an offset.
     *
     * @since 3.0.0
     *
     * @param  int  $offset  A byte offset.
     *
     * @return array{0: int, 1: int} The 1-based line and column.
     */
    public function locate(int $offset): array
    {
        $low  = 0;
        $high = count($this->starts) - 1;

        while ($low < $high) {
            $middle = intdiv($low + $high + 1, 2);

            if ($this->starts[$middle] <= $offset) {
                $low = $middle;
            } else {
                $high = $middle - 1;
            }
        }

        return [$low + 1, $offset - $this->starts[$low] + 1];
    }
}
