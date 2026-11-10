<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Exceptions\ThemeGeneratorException;
use ArtisanPack\LivewireUiComponents\Theming\ColorMath;
use ArtisanPack\LivewireUiComponents\Theming\DesignTokens;
use ArtisanPack\LivewireUiComponents\Theming\GlassSurface;
use ArtisanPack\LivewireUiComponents\Theming\TokenThemes;

test('lists the token themes', function (): void {
    expect(TokenThemes::make()->themes())->toBe(['light', 'dark', 'high-contrast']);
});

test('builds the theme, density and preset attributes', function (): void {
    expect(TokenThemes::make()->attributes('high-contrast', 'compact', 'ledger'))->toBe([
        'data-theme'   => 'high-contrast',
        'data-density' => 'compact',
        'data-preset'  => 'ledger',
    ]);
});

test('rejects unknown themes and densities', function (): void {
    expect(fn () => TokenThemes::make()->attributes('sepia'))->toThrow(ThemeGeneratorException::class, 'Unknown theme sepia');
    expect(fn () => TokenThemes::make()->attributes(null, 'cozy'))->toThrow(ThemeGeneratorException::class, 'Unknown density cozy');
});

test('every theme passes every contrast pair', function (string $theme): void {
    expect(TokenThemes::make()->failures($theme))->toBe([]);
})->with(['light', 'dark', 'high-contrast']);

test('applies high contrast when the system asks and the page sets no theme', function (): void {
    $css    = TokenThemes::make()->prefersContrastCss();
    $tokens = DesignTokens::load();

    expect($css)->toContain('@media (prefers-contrast: more)')
        ->toContain(':root:not([data-theme])')
        ->toContain('--apui-color-primary: '.$tokens->cssValue('color.primary', 'high-contrast').';')
        ->toContain('--apui-selected-bg: var(--apui-color-primary-soft);')
        ->not->toContain('--apui-panel-padding:');
});

test('applies dark when the system prefers it', function (): void {
    expect(TokenThemes::make()->prefersDarkCss())
        ->toContain('@media (prefers-color-scheme: dark)')
        ->toContain('--apui-surface-page: '.DesignTokens::load()->cssValue('surface.page', 'dark').';');
});

test('resolves token values through aliases', function (): void {
    $tokens = DesignTokens::load();

    expect($tokens->resolved('color.primary', ['theme' => 'dark']))->toBe($tokens->get('color.primary')['resolved']['dark'])
        ->and($tokens->resolved('card.padding', ['density' => 'compact']))->toBe('16px')
        ->and($tokens->pathFor('--apui-color-primary'))->toBe('color.primary')
        ->and($tokens->referencesIn('var(--apui-gray-0), var(--apui-nope)'))->toBe(['gray.0']);
});

test('fails clearly on a missing token map', function (): void {
    expect(fn () => DesignTokens::load('/nope/tokens.php'))->toThrow(ThemeGeneratorException::class, 'not found');
    expect(fn () => new DesignTokens(['tokens' => []]))->toThrow(ThemeGeneratorException::class);
});

test('puts supported components on glass', function (): void {
    $glass = GlassSurface::make();

    expect($glass->attributes('card'))->toBe(['data-surface' => 'glass'])
        ->and($glass->supports('dropdown-menu'))->toBeTrue()
        ->and($glass->supports('button'))->toBeFalse()
        ->and(fn () => $glass->attributes('button'))->toThrow(ThemeGeneratorException::class, 'button has no glass surface');
});

test('exposes the glass tokens per theme, opaque in high contrast', function (): void {
    $glass = GlassSurface::make();

    expect($glass->tokens('light'))->toHaveKeys(['--apui-surface-glass', '--apui-text-muted-glass', '--apui-elevation-glass'])
        ->and($glass->tokens('light')['--apui-surface-glass'])->toContain('/ 0.8')
        ->and(ColorMath::parseCss($glass->tokens('high-contrast')['--apui-surface-glass'])[3])->toBe(1.0);
});

test('ships a token map in sync with the generator fixtures', function (): void {
    $fixture = json_decode((string) file_get_contents(dirname(__DIR__, 2).'/Fixtures/design-system/generator/forest.json'), true);

    foreach ($fixture['data']['tokens'] as $path => $token) {
        expect(DesignTokens::load()->has($path))->toBeTrue();
    }
});
