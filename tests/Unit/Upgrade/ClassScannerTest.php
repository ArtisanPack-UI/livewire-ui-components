<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Upgrade\ClassScanner;
use ArtisanPack\LivewireUiComponents\Upgrade\Finding;
use ArtisanPack\LivewireUiComponents\Upgrade\UpgradeRules;

/*
 * ClassScanner (design-system #67): raw DaisyUI classes and theme references
 * are reported with their position and never rewritten.
 */

test('classifies class names', function (string $token, ?string $kind): void {
    expect((new ClassScanner(UpgradeRules::load()))->classify($token))->toBe($kind);
})->with([
    ['btn', 'component'],
    ['btn-primary', 'component'],
    ['hover:badge-sm', 'component'],
    ['card-body', 'component'],
    ['input', 'ambiguous'],
    ['table-sm', 'ambiguous'],
    ['table-auto', null],
    ['select-none', null],
    ['bg-base-200', 'themeBase'],
    ['text-base-content/60', 'themeBase'],
    ['text-primary-content', 'themeContent'],
    ['bg-primary', null],
    ['flex', null],
    ['apui-button', null],
    ['buttons', null],
    ['[&:hover]:btn', 'component'],
    ['[&>*]:md:badge-sm', 'component'],
    ['w-[120px]', null],
]);

test('reports classes where they are, in class attributes only', function (): void {
    $source = <<<'BLADE'
    <p>Press the btn in the card.</p>
    <div class="card bg-base-100 p-4">
        <a :class="$active ? 'btn btn-primary' : 'link'" @class(['badge', 'badge-sm' => $small])>Go</a>
    </div>
    <style>.x { color: var(--color-base-content); }</style>
    <html data-theme="dark">
    BLADE;

    $findings = (new ClassScanner(UpgradeRules::load()))->scan($source);

    expect(array_map(fn (Finding $finding): string => "{$finding->line}:{$finding->member}", $findings))->toBe([
        '2:card', '2:bg-base-100', '3:btn', '3:btn-primary', '3:link', '3:badge', '3:badge-sm', '5:--color-base-content', '6:data-theme=',
    ])
        ->and($findings[0]->kind)->toBe(Finding::DAISY_CLASS)
        ->and($findings[0]->column)->toBe(13)
        ->and($findings[0]->message)->toContain('DaisyUI class')
        ->and($findings[4]->message)->toContain('may be');
});

test('reads the string literals of an expression, and only those', function (): void {
    $source = <<<'BLADE'
    <i class="{{ $on ? "btn" : "btn-outline" }} [&:hover]:badge"></i>
    <a :class="[$card, 'btn-primary' => $badge]" @class([$alert, 'alert-soft' => $tab])>Go</a>
    BLADE;

    $findings = (new ClassScanner(UpgradeRules::load()))->scan($source);

    expect(array_map(fn (Finding $finding): string => "{$finding->line}:{$finding->column}:{$finding->member}", $findings))->toBe([
        '1:21:btn', '1:29:btn-outline', '1:45:[&:hover]:badge', '2:21:btn-primary', '2:63:alert-soft',
    ]);
});
