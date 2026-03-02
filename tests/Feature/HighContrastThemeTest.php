<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Styling\HighContrastTheme;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    // Set up test output paths in config
    config()->set('artisanpack.livewire-ui-components.high_contrast.enabled', true);
    config()->set('artisanpack.livewire-ui-components.high_contrast.output_path', storage_path('test-high-contrast.css'));
    config()->set('artisanpack.livewire-ui-components.theme_output_path', storage_path('test-theme.css'));
});

afterEach(function (): void {
    // Clean up test files
    $testFiles = [
        storage_path('test-high-contrast.css'),
        storage_path('test-theme.css'),
        storage_path('test-theme.json'),
        storage_path('test-glass-tokens.css'),
        storage_path('test-design-tokens.css'),
        storage_path('test-glass-presets.css'),
    ];

    foreach ($testFiles as $file) {
        if (File::exists($file)) {
            File::delete($file);
        }
    }
});

test('high contrast theme has expected presets', function (): void {
    $theme = new HighContrastTheme;

    $presets = $theme->getAvailablePresets();

    expect($presets)->toContain('high-contrast-light');
    expect($presets)->toContain('high-contrast-dark');
    expect($presets)->toContain('enhanced-contrast-light');
    expect($presets)->toContain('enhanced-contrast-dark');
    expect($presets)->toHaveCount(4);
});

test('isValidPreset returns true for valid presets', function (): void {
    $theme = new HighContrastTheme;

    expect($theme->isValidPreset('high-contrast-light'))->toBeTrue();
    expect($theme->isValidPreset('high-contrast-dark'))->toBeTrue();
    expect($theme->isValidPreset('enhanced-contrast-light'))->toBeTrue();
    expect($theme->isValidPreset('enhanced-contrast-dark'))->toBeTrue();
});

test('isValidPreset returns false for invalid presets', function (): void {
    $theme = new HighContrastTheme;

    expect($theme->isValidPreset('invalid-preset'))->toBeFalse();
    expect($theme->isValidPreset(''))->toBeFalse();
    expect($theme->isValidPreset('random-string'))->toBeFalse();
});

test('getPresetTokens returns tokens for valid presets', function (): void {
    $theme = new HighContrastTheme;

    $tokens = $theme->getPresetTokens('high-contrast-light');

    expect($tokens)->toBeArray();
    expect($tokens)->not->toBeEmpty();
    expect($tokens)->toHaveKey('hc-background');
    expect($tokens)->toHaveKey('hc-foreground');
    expect($tokens)->toHaveKey('hc-primary');
    expect($tokens)->toHaveKey('hc-focus-ring-color');
});

test('getPresetTokens returns empty array for invalid presets', function (): void {
    $theme = new HighContrastTheme;

    $tokens = $theme->getPresetTokens('invalid-preset');

    expect($tokens)->toBeArray();
    expect($tokens)->toBeEmpty();
});

test('getPresetDescription returns descriptions for all presets', function (): void {
    $theme = new HighContrastTheme;

    foreach ($theme->getAvailablePresets() as $preset) {
        $description = $theme->getPresetDescription($preset);
        expect($description)->toBeString();
        expect($description)->not->toBeEmpty();
    }
});

test('getComplianceLevel returns correct WCAG levels', function (): void {
    $theme = new HighContrastTheme;

    expect($theme->getComplianceLevel('high-contrast-light'))->toBe('AAA');
    expect($theme->getComplianceLevel('high-contrast-dark'))->toBe('AAA');
    expect($theme->getComplianceLevel('enhanced-contrast-light'))->toBe('AA');
    expect($theme->getComplianceLevel('enhanced-contrast-dark'))->toBe('AA');
    expect($theme->getComplianceLevel('invalid'))->toBe('none');
});

test('getPresetMode returns correct light/dark mode', function (): void {
    $theme = new HighContrastTheme;

    expect($theme->getPresetMode('high-contrast-light'))->toBe('light');
    expect($theme->getPresetMode('high-contrast-dark'))->toBe('dark');
    expect($theme->getPresetMode('enhanced-contrast-light'))->toBe('light');
    expect($theme->getPresetMode('enhanced-contrast-dark'))->toBe('dark');
    expect($theme->getPresetMode('invalid'))->toBe('unknown');
});

test('calculateContrastRatio returns expected values', function (): void {
    $theme = new HighContrastTheme;

    // Black on white should have maximum contrast ratio (21:1)
    $ratio = $theme->calculateContrastRatio('#000000', '#ffffff');
    expect($ratio)->toBeGreaterThanOrEqual(21.0);

    // White on white should have minimum contrast ratio (1:1)
    $ratioSame = $theme->calculateContrastRatio('#ffffff', '#ffffff');
    expect($ratioSame)->toBeGreaterThanOrEqual(1.0);
    expect($ratioSame)->toBeLessThanOrEqual(1.1);
});

test('verifyContrast returns true for WCAG AAA compliant colors', function (): void {
    $theme = new HighContrastTheme;

    // Black on white easily passes AAA
    expect($theme->verifyContrast('#000000', '#ffffff', 'AAA'))->toBeTrue();

    // Near-white on white should fail AAA
    expect($theme->verifyContrast('#eeeeee', '#ffffff', 'AAA'))->toBeFalse();
});

test('verifyContrast accounts for large text', function (): void {
    $theme = new HighContrastTheme;

    // Test with colors that pass for large text but not normal text
    // Using a mid-gray on white
    $passes = $theme->verifyContrast('#767676', '#ffffff', 'AAA', true);
    expect($passes)->toBeTrue();
});

test('getReducedMotionTokens returns motion tokens', function (): void {
    $theme = new HighContrastTheme;

    $tokens = $theme->getReducedMotionTokens();

    expect($tokens)->toBeArray();
    expect($tokens)->toHaveKey('hc-animation-duration');
    expect($tokens)->toHaveKey('hc-transition-duration');
    expect($tokens)->toHaveKey('hc-scroll-behavior');
});

test('getLargerTextTokens returns text sizing tokens', function (): void {
    $theme = new HighContrastTheme;

    $largeTokens = $theme->getLargerTextTokens('large');
    expect($largeTokens)->toBeArray();
    expect($largeTokens)->toHaveKey('hc-text-scale');
    expect($largeTokens)->toHaveKey('hc-text-base');

    $extraLargeTokens = $theme->getLargerTextTokens('extra-large');
    expect($extraLargeTokens)->toBeArray();
    expect($extraLargeTokens)->toHaveKey('hc-text-scale');
});

test('getPresetsMetadata returns complete metadata for all presets', function (): void {
    $theme = new HighContrastTheme;

    $metadata = $theme->getPresetsMetadata();

    expect($metadata)->toHaveCount(4);

    foreach ($metadata as $presetName => $data) {
        expect($data)->toHaveKey('name');
        expect($data)->toHaveKey('description');
        expect($data)->toHaveKey('mode');
        expect($data)->toHaveKey('compliance');
        expect($data)->toHaveKey('tokens');
        expect($data['name'])->toBe($presetName);
    }
});

test('generatePresetCss generates valid CSS', function (): void {
    $theme = new HighContrastTheme;

    $css = $theme->generatePresetCss('high-contrast-light');

    expect($css)->toContain('.high-contrast-light');
    expect($css)->toContain('--hc-background');
    expect($css)->toContain('--hc-foreground');
    expect($css)->toContain('{');
    expect($css)->toContain('}');
});

test('generatePresetCss without selector generates only variables', function (): void {
    $theme = new HighContrastTheme;

    $css = $theme->generatePresetCss('high-contrast-light', false);

    expect($css)->not->toContain('.high-contrast-light');
    expect($css)->toContain('--hc-background');
});

test('generateAllPresetsCss includes all presets', function (): void {
    $theme = new HighContrastTheme;

    $css = $theme->generateAllPresetsCss();

    expect($css)->toContain('.high-contrast-light');
    expect($css)->toContain('.high-contrast-dark');
    expect($css)->toContain('.enhanced-contrast-light');
    expect($css)->toContain('.enhanced-contrast-dark');
    expect($css)->toContain('WCAG');
});

test('generatePrefersContrastCss includes media queries', function (): void {
    $theme = new HighContrastTheme;

    $css = $theme->generatePrefersContrastCss();

    expect($css)->toContain('@media (prefers-contrast: more)');
    expect($css)->toContain('@media (prefers-contrast: less)');
    expect($css)->toContain(':root');
    expect($css)->toContain('[data-theme="dark"]');
});

test('generateReducedMotionCss includes reduced motion media query', function (): void {
    $theme = new HighContrastTheme;

    $css = $theme->generateReducedMotionCss();

    expect($css)->toContain('@media (prefers-reduced-motion: reduce)');
    expect($css)->toContain('animation-duration');
    expect($css)->toContain('transition-duration');
    expect($css)->toContain('scroll-behavior');
});

test('generateFocusIndicatorsCss includes focus styles', function (): void {
    $theme = new HighContrastTheme;

    $css = $theme->generateFocusIndicatorsCss();

    expect($css)->toContain(':focus-visible');
    expect($css)->toContain('--focus-ring-color');
    expect($css)->toContain('--focus-ring-width');
    expect($css)->toContain('.skip-link');
});

test('generateLargerTextCss includes text scaling classes', function (): void {
    $theme = new HighContrastTheme;

    $css = $theme->generateLargerTextCss();

    expect($css)->toContain('.text-larger');
    expect($css)->toContain('.text-extra-large');
    expect($css)->toContain('--hc-text-base');
    expect($css)->toContain('--hc-line-height');
});

test('generateCompleteCss includes all features by default', function (): void {
    $theme = new HighContrastTheme;

    $css = $theme->generateCompleteCss();

    expect($css)->toContain('.high-contrast-light');
    expect($css)->toContain('@media (prefers-contrast: more)');
    expect($css)->toContain('@media (prefers-reduced-motion: reduce)');
    expect($css)->toContain(':focus-visible');
    expect($css)->toContain('.text-larger');
});

test('generateCompleteCss respects options', function (): void {
    $theme = new HighContrastTheme;

    $css = $theme->generateCompleteCss([
        'include_presets'          => false,
        'include_prefers_contrast' => false,
        'include_reduced_motion'   => true,
        'include_focus_indicators' => false,
        'include_larger_text'      => false,
    ]);

    expect($css)->not->toContain('.high-contrast-light');
    expect($css)->not->toContain('@media (prefers-contrast: more)');
    expect($css)->toContain('@media (prefers-reduced-motion: reduce)');
    expect($css)->not->toContain('.text-larger');
});

test('command can generate only high-contrast CSS', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--high-contrast-only' => true,
    ])
        ->assertExitCode(0);

    expect(File::exists(storage_path('test-high-contrast.css')))->toBeTrue();

    $cssContent = File::get(storage_path('test-high-contrast.css'));
    expect($cssContent)->toContain('.high-contrast-light');
    expect($cssContent)->toContain('prefers-contrast');
});

test('command can generate high-contrast with specific preset', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--high-contrast-only'     => true,
        '--accessibility-preset'   => 'high-contrast-dark',
    ])
        ->assertExitCode(0);

    $cssContent = File::get(storage_path('test-high-contrast.css'));
    expect($cssContent)->toContain('Default Accessibility Preset: high-contrast-dark');
    expect($cssContent)->toContain('WCAG AAA Compliant');
});

test('command excludes high-contrast when --no-high-contrast is passed', function (): void {
    // First delete any existing file
    if (File::exists(storage_path('test-high-contrast.css'))) {
        File::delete(storage_path('test-high-contrast.css'));
    }

    $this->artisan('artisanpack:generate-theme', [
        '--primary'          => 'blue',
        '--no-high-contrast' => true,
    ])
        ->assertExitCode(0);

    expect(File::exists(storage_path('test-theme.css')))->toBeTrue();
    expect(File::exists(storage_path('test-high-contrast.css')))->toBeFalse();
});

test('JSON export includes accessibility configuration', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary'              => 'blue',
        '--accessibility-preset' => 'high-contrast-light',
        '--json'                 => true,
    ])
        ->assertExitCode(0);

    $jsonPath = storage_path('test-theme.json');
    expect(File::exists($jsonPath))->toBeTrue();

    $jsonContent = json_decode(File::get($jsonPath), true);

    expect($jsonContent)->toHaveKey('accessibility');
    expect($jsonContent['accessibility']['preset'])->toBe('high-contrast-light');
    expect($jsonContent['accessibility'])->toHaveKey('tokens');
    expect($jsonContent['accessibility']['compliance'])->toBe('AAA');
    expect($jsonContent['accessibility']['mode'])->toBe('light');
});

test('high contrast light preset has maximum contrast colors', function (): void {
    $theme = new HighContrastTheme;

    $tokens = $theme->getPresetTokens('high-contrast-light');

    // Light mode should have white background and black foreground for maximum contrast
    expect($tokens['hc-background'])->toBe('#ffffff');
    expect($tokens['hc-foreground'])->toBe('#000000');
});

test('high contrast dark preset has maximum contrast colors', function (): void {
    $theme = new HighContrastTheme;

    $tokens = $theme->getPresetTokens('high-contrast-dark');

    // Dark mode should have black background and white foreground for maximum contrast
    expect($tokens['hc-background'])->toBe('#000000');
    expect($tokens['hc-foreground'])->toBe('#ffffff');
});

test('all high contrast presets have required accessibility tokens', function (): void {
    $theme = new HighContrastTheme;

    $requiredTokens = [
        'hc-background',
        'hc-foreground',
        'hc-primary',
        'hc-primary-foreground',
        'hc-focus-ring-color',
        'hc-focus-ring-width',
        'hc-min-target-size',
    ];

    foreach ($theme->getAvailablePresets() as $preset) {
        $tokens = $theme->getPresetTokens($preset);

        foreach ($requiredTokens as $required) {
            expect($tokens)->toHaveKey($required);
        }
    }
});
