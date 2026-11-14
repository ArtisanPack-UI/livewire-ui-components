<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Upgrade\UnifiedDiff;

/*
 * UnifiedDiff (design-system #67): the diff a dry run prints, with no diff
 * library to install.
 */

test('is empty when nothing changed', function (): void {
    expect(UnifiedDiff::lines("x\n", "x\n"))->toBe([]);
});

test('shows a changed line with its context', function (): void {
    $before = implode("\n", ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'])."\n";

    expect(UnifiedDiff::lines($before, str_replace('five', 'FIVE', $before), 2))
        ->toBe(['@@ -3,5 +3,5 @@', ' three', ' four', '-five', '+FIVE', ' six', ' seven']);
});

test('splits distant changes into hunks and counts added and removed lines', function (): void {
    $lines    = array_map(fn (int $number): string => "line {$number}", range(1, 30));
    $after    = $lines;
    $after[1] = 'changed 2';
    array_splice($after, 24, 1);
    $after[] = 'added';

    $diff = UnifiedDiff::lines(implode("\n", $lines)."\n", implode("\n", $after)."\n", 1);

    expect($diff)->toContain('@@ -1,3 +1,3 @@', '@@ -24,3 +24,2 @@', '-line 25', '+added')
        ->and(array_filter($diff, fn (string $line): bool => str_starts_with($line, '@@')))->toHaveCount(3);
});

test('handles empty files and a missing final newline', function (): void {
    expect(UnifiedDiff::lines('', "b\n"))->toBe(['@@ -0,0 +1,1 @@', '+b'])
        ->and(UnifiedDiff::lines("a\n", "b\n"))->toBe(['@@ -1,1 +1,1 @@', '-a', '+b'])
        ->and(UnifiedDiff::lines('a', "a\n"))->toBe(['@@ -1,1 +1,1 @@', "-a\n\\ No newline at end of file", '+a']);
});

test('stays within memory on a huge change', function (): void {
    $before = implode("\n", array_map(fn (int $number): string => "old {$number}", range(1, 6000)));
    $after  = implode("\n", array_map(fn (int $number): string => "new {$number}", range(1, 6000)));
    $diff   = UnifiedDiff::lines($before."\n", $after."\n");

    expect($diff[0])->toBe('@@ -1,6000 +1,6000 @@')
        ->and($diff)->toContain('-old 6000', '+new 6000');
});
