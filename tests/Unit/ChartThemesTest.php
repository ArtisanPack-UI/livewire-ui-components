<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\ChartThemes;

describe('Chart Themes', function (): void {
    describe('Theme Validation', function (): void {
        it('returns valid themes list', function (): void {
            $themes = ChartThemes::getValidThemes();

            expect($themes)->toBeArray()
                ->toContain('artisanpack-light')
                ->toContain('artisanpack-dark')
                ->toContain('artisanpack-glass')
                ->toHaveCount(3);
        });

        it('validates theme names', function (): void {
            expect(ChartThemes::isValidTheme('artisanpack-light'))->toBeTrue();
            expect(ChartThemes::isValidTheme('artisanpack-dark'))->toBeTrue();
            expect(ChartThemes::isValidTheme('artisanpack-glass'))->toBeTrue();

            expect(ChartThemes::isValidTheme('invalid-theme'))->toBeFalse();
            expect(ChartThemes::isValidTheme(''))->toBeFalse();
            expect(ChartThemes::isValidTheme(null))->toBeFalse();
        });

        it('returns empty array for invalid theme', function (): void {
            expect(ChartThemes::get('invalid-theme'))->toBe([]);
        });
    });

    describe('Default Colors', function (): void {
        it('returns default color palette', function (): void {
            $colors = ChartThemes::getDefaultColors();

            expect($colors)->toBeArray()
                ->toHaveCount(8)
                ->toContain('#3b82f6')  // blue-500
                ->toContain('#8b5cf6'); // violet-500
        });
    });

    describe('Light Theme', function (): void {
        it('returns light theme configuration', function (): void {
            $theme = ChartThemes::get('artisanpack-light');

            expect($theme)->toBeArray()
                ->toHaveKey('colors')
                ->toHaveKey('chart')
                ->toHaveKey('theme')
                ->toHaveKey('grid')
                ->toHaveKey('tooltip')
                ->toHaveKey('legend')
                ->toHaveKey('fill')
                ->toHaveKey('stroke');

            expect($theme['theme']['mode'])->toBe('light');
            expect($theme['chart']['foreColor'])->toBe('#374151');
            expect($theme['tooltip']['theme'])->toBe('light');
        });

        it('has disabled drop shadow', function (): void {
            $theme = ChartThemes::getLightTheme();

            expect($theme['chart']['dropShadow']['enabled'])->toBeFalse();
        });
    });

    describe('Dark Theme', function (): void {
        it('returns dark theme configuration', function (): void {
            $theme = ChartThemes::get('artisanpack-dark');

            expect($theme)->toBeArray()
                ->toHaveKey('colors')
                ->toHaveKey('chart')
                ->toHaveKey('theme')
                ->toHaveKey('grid')
                ->toHaveKey('tooltip');

            expect($theme['theme']['mode'])->toBe('dark');
            expect($theme['chart']['foreColor'])->toBe('#e5e7eb');
            expect($theme['tooltip']['theme'])->toBe('dark');
            expect($theme['chart']['dropShadow']['enabled'])->toBeTrue();
        });

        it('has brighter colors than light theme', function (): void {
            $lightTheme = ChartThemes::getLightTheme();
            $darkTheme  = ChartThemes::getDarkTheme();

            // Dark theme should use brighter color variants (400 instead of 500)
            expect($darkTheme['colors'])->toContain('#60a5fa');  // blue-400
            expect($lightTheme['colors'])->toContain('#3b82f6'); // blue-500

            // Colors should be different between themes
            expect($lightTheme['colors'])->not->toBe($darkTheme['colors']);
        });
    });

    describe('Glass Theme', function (): void {
        it('returns glass theme configuration', function (): void {
            $theme = ChartThemes::get('artisanpack-glass');

            expect($theme)->toBeArray()
                ->toHaveKey('colors')
                ->toHaveKey('chart')
                ->toHaveKey('grid')
                ->toHaveKey('fill');

            expect($theme['chart']['background'])->toBe('transparent');
            expect($theme['chart']['foreColor'])->toBe('inherit');
        });

        it('uses gradient fill', function (): void {
            $theme = ChartThemes::getGlassTheme();

            expect($theme['fill']['type'])->toBe('gradient');
            expect($theme['fill']['gradient']['shadeIntensity'])->toBe(1);
            expect($theme['fill']['gradient']['opacityFrom'])->toBe(0.7);
            expect($theme['fill']['gradient']['opacityTo'])->toBe(0.2);
        });

        it('returns light mode overrides', function (): void {
            $overrides = ChartThemes::getGlassLightOverrides();

            expect($overrides)->toBeArray()
                ->toHaveKey('chart')
                ->toHaveKey('grid')
                ->toHaveKey('tooltip');

            expect($overrides['chart']['foreColor'])->toBe('#374151');
            expect($overrides['tooltip']['theme'])->toBe('light');
        });

        it('returns dark mode overrides', function (): void {
            $overrides = ChartThemes::getGlassDarkOverrides();

            expect($overrides)->toBeArray()
                ->toHaveKey('chart')
                ->toHaveKey('grid')
                ->toHaveKey('tooltip');

            expect($overrides['chart']['foreColor'])->toBe('#e5e7eb');
            expect($overrides['tooltip']['theme'])->toBe('dark');
        });
    });

    describe('Common Theme Properties', function (): void {
        it('all themes have smooth stroke curve', function (): void {
            $themes = ['artisanpack-light', 'artisanpack-dark', 'artisanpack-glass'];

            foreach ($themes as $themeName) {
                $theme = ChartThemes::get($themeName);
                expect($theme['stroke']['curve'])->toBe('smooth');
                expect($theme['stroke']['width'])->toBe(2);
            }
        });

        it('all themes have transparent background', function (): void {
            $themes = ['artisanpack-light', 'artisanpack-dark', 'artisanpack-glass'];

            foreach ($themes as $themeName) {
                $theme = ChartThemes::get($themeName);
                expect($theme['chart']['background'])->toBe('transparent');
            }
        });

        it('all themes have toolbar enabled', function (): void {
            $themes = ['artisanpack-light', 'artisanpack-dark', 'artisanpack-glass'];

            foreach ($themes as $themeName) {
                $theme = ChartThemes::get($themeName);
                expect($theme['chart']['toolbar']['show'])->toBeTrue();
            }
        });
    });
});
