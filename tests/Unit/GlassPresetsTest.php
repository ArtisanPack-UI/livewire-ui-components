<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Styling\GlassPresets;

beforeEach(function (): void {
    $this->glassPresets = new GlassPresets;
});

describe('Glass Presets Availability', function (): void {
    test('returns all available preset names', function (): void {
        $presets = $this->glassPresets->getAvailablePresets();

        expect($presets)->toBeArray();
        expect($presets)->toContain('glass-frosted-light');
        expect($presets)->toContain('glass-frosted-dark');
        expect($presets)->toContain('glass-liquid-light');
        expect($presets)->toContain('glass-liquid-dark');
        expect($presets)->toContain('glass-minimal');
        expect($presets)->toContain('glass-bold');
        expect($presets)->toHaveCount(6);
    });

    test('validates preset names correctly', function (): void {
        expect($this->glassPresets->isValidPreset('glass-frosted-light'))->toBeTrue();
        expect($this->glassPresets->isValidPreset('glass-frosted-dark'))->toBeTrue();
        expect($this->glassPresets->isValidPreset('glass-liquid-light'))->toBeTrue();
        expect($this->glassPresets->isValidPreset('glass-liquid-dark'))->toBeTrue();
        expect($this->glassPresets->isValidPreset('glass-minimal'))->toBeTrue();
        expect($this->glassPresets->isValidPreset('glass-bold'))->toBeTrue();
    });

    test('returns false for invalid preset names', function (): void {
        expect($this->glassPresets->isValidPreset('invalid-preset'))->toBeFalse();
        expect($this->glassPresets->isValidPreset('glass-invalid'))->toBeFalse();
        expect($this->glassPresets->isValidPreset(''))->toBeFalse();
        expect($this->glassPresets->isValidPreset('frosted-light'))->toBeFalse();
    });
});

describe('Glass Frosted Light Preset', function (): void {
    test('returns correct tokens for glass-frosted-light preset', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-frosted-light');

        expect($tokens)->toBeArray();
        expect($tokens)->toHaveKey('glass-blur');
        expect($tokens)->toHaveKey('glass-opacity');
        expect($tokens)->toHaveKey('glass-frosted-blur');
        expect($tokens)->toHaveKey('glass-frosted-opacity');
        expect($tokens)->toHaveKey('glass-frosted-saturation');
    });

    test('has appropriate frosted light values', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-frosted-light');

        expect($tokens['glass-blur'])->toBe('14px');
        expect($tokens['glass-opacity'])->toBe('0.75');
        expect($tokens['glass-frosted-blur'])->toBe('18px');
        expect($tokens['glass-frosted-opacity'])->toBe('0.85');
        expect($tokens['glass-frosted-saturation'])->toBe('200%');
    });

    test('has light background colors', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-frosted-light');

        expect($tokens['glass-bg-color'])->toContain('255, 255, 255');
        expect($tokens['glass-frosted-bg-color'])->toContain('255, 255, 255');
    });
});

describe('Glass Frosted Dark Preset', function (): void {
    test('returns correct tokens for glass-frosted-dark preset', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-frosted-dark');

        expect($tokens)->toBeArray();
        expect($tokens)->toHaveKey('glass-blur');
        expect($tokens)->toHaveKey('glass-opacity');
        expect($tokens)->toHaveKey('glass-frosted-blur');
        expect($tokens)->toHaveKey('glass-frosted-opacity');
        expect($tokens)->toHaveKey('glass-frosted-saturation');
    });

    test('has enhanced blur for dark mode', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-frosted-dark');

        expect($tokens['glass-blur'])->toBe('16px');
        expect($tokens['glass-frosted-blur'])->toBe('22px');
        expect($tokens['glass-frosted-saturation'])->toBe('220%');
    });

    test('has dark background colors', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-frosted-dark');

        expect($tokens['glass-bg-color'])->toContain('20, 20, 25');
        expect($tokens['glass-frosted-bg-color'])->toContain('20, 20, 25');
    });
});

describe('Glass Liquid Light Preset', function (): void {
    test('returns correct tokens for glass-liquid-light preset', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-liquid-light');

        expect($tokens)->toBeArray();
        expect($tokens)->toHaveKey('glass-liquid-blur');
        expect($tokens)->toHaveKey('glass-liquid-opacity');
        expect($tokens)->toHaveKey('glass-liquid-refraction');
        expect($tokens)->toHaveKey('glass-liquid-border-glow');
    });

    test('has enhanced liquid variant values', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-liquid-light');

        expect($tokens['glass-liquid-blur'])->toBe('28px');
        expect($tokens['glass-liquid-opacity'])->toBe('0.55');
        expect($tokens['glass-liquid-refraction'])->toBe('0.6');
        expect($tokens['glass-liquid-border-glow'])->toBe('0.35');
    });
});

describe('Glass Liquid Dark Preset', function (): void {
    test('returns correct tokens for glass-liquid-dark preset', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-liquid-dark');

        expect($tokens)->toBeArray();
        expect($tokens)->toHaveKey('glass-liquid-blur');
        expect($tokens)->toHaveKey('glass-liquid-opacity');
        expect($tokens)->toHaveKey('glass-liquid-refraction');
        expect($tokens)->toHaveKey('glass-liquid-border-glow');
    });

    test('has enhanced glow for dark mode', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-liquid-dark');

        expect($tokens['glass-liquid-blur'])->toBe('32px');
        expect($tokens['glass-liquid-border-glow'])->toBe('0.45');
    });
});

describe('Glass Minimal Preset', function (): void {
    test('returns correct tokens for glass-minimal preset', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-minimal');

        expect($tokens)->toBeArray();
        expect($tokens)->toHaveKey('glass-blur');
        expect($tokens)->toHaveKey('glass-opacity');
    });

    test('has subtle values for minimal effect', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-minimal');

        expect($tokens['glass-blur'])->toBe('8px');
        expect($tokens['glass-opacity'])->toBe('0.5');
        expect($tokens['glass-shadow-opacity'])->toBe('0.05');
        expect($tokens['glass-frosted-saturation'])->toBe('120%');
    });

    test('has dark mode overrides', function (): void {
        $darkOverrides = $this->glassPresets->getDarkModeOverrides('glass-minimal');

        expect($darkOverrides)->toBeArray();
        expect($darkOverrides)->not->toBeEmpty();
        expect($darkOverrides)->toHaveKey('glass-opacity');
        expect($darkOverrides)->toHaveKey('glass-bg-color');
    });
});

describe('Glass Bold Preset', function (): void {
    test('returns correct tokens for glass-bold preset', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-bold');

        expect($tokens)->toBeArray();
        expect($tokens)->toHaveKey('glass-blur');
        expect($tokens)->toHaveKey('glass-opacity');
    });

    test('has strong values for bold effect', function (): void {
        $tokens = $this->glassPresets->getPresetTokens('glass-bold');

        expect($tokens['glass-blur'])->toBe('20px');
        expect($tokens['glass-opacity'])->toBe('0.85');
        expect($tokens['glass-border-width'])->toBe('2px');
        expect($tokens['glass-frosted-saturation'])->toBe('250%');
    });

    test('has dark mode overrides', function (): void {
        $darkOverrides = $this->glassPresets->getDarkModeOverrides('glass-bold');

        expect($darkOverrides)->toBeArray();
        expect($darkOverrides)->not->toBeEmpty();
        expect($darkOverrides)->toHaveKey('glass-opacity');
        expect($darkOverrides['glass-opacity'])->toBe('0.9');
    });
});

describe('Preset Descriptions', function (): void {
    test('returns description for each preset', function (): void {
        foreach ($this->glassPresets->getAvailablePresets() as $preset) {
            $description = $this->glassPresets->getPresetDescription($preset);

            expect($description)->toBeString();
            expect($description)->not->toBeEmpty();
        }
    });

    test('has descriptive content for frosted light', function (): void {
        $description = $this->glassPresets->getPresetDescription('glass-frosted-light');

        expect($description)->toContain('light');
    });

    test('has descriptive content for minimal', function (): void {
        $description = $this->glassPresets->getPresetDescription('glass-minimal');

        expect($description)->toContain('Subtle');
    });

    test('has descriptive content for bold', function (): void {
        $description = $this->glassPresets->getPresetDescription('glass-bold');

        expect($description)->toContain('Strong');
    });
});

describe('Dark Mode Overrides', function (): void {
    test('mode-specific presets return empty dark overrides', function (): void {
        expect($this->glassPresets->getDarkModeOverrides('glass-frosted-light'))->toBeEmpty();
        expect($this->glassPresets->getDarkModeOverrides('glass-frosted-dark'))->toBeEmpty();
        expect($this->glassPresets->getDarkModeOverrides('glass-liquid-light'))->toBeEmpty();
        expect($this->glassPresets->getDarkModeOverrides('glass-liquid-dark'))->toBeEmpty();
    });

    test('adaptive presets return dark overrides', function (): void {
        expect($this->glassPresets->getDarkModeOverrides('glass-minimal'))->not->toBeEmpty();
        expect($this->glassPresets->getDarkModeOverrides('glass-bold'))->not->toBeEmpty();
    });
});

describe('CSS Generation', function (): void {
    test('generates CSS for a single preset', function (): void {
        $css = $this->glassPresets->generatePresetCss('glass-frosted-light');

        expect($css)->toBeString();
        expect($css)->toContain('.glass-frosted-light');
        expect($css)->toContain('--glass-blur');
        expect($css)->toContain('--glass-opacity');
    });

    test('generates CSS without selector when requested', function (): void {
        $css = $this->glassPresets->generatePresetCss('glass-minimal', false);

        expect($css)->toBeString();
        expect($css)->not->toContain('.glass-minimal');
        expect($css)->toContain('--glass-blur');
    });

    test('includes dark mode overrides for adaptive presets', function (): void {
        $css = $this->glassPresets->generatePresetCss('glass-minimal');

        expect($css)->toContain('[data-theme="dark"]');
        expect($css)->toContain('.glass-minimal');
    });

    test('generates CSS for all presets', function (): void {
        $css = $this->glassPresets->generateAllPresetsCss();

        expect($css)->toBeString();
        expect($css)->toContain('.glass-frosted-light');
        expect($css)->toContain('.glass-frosted-dark');
        expect($css)->toContain('.glass-liquid-light');
        expect($css)->toContain('.glass-liquid-dark');
        expect($css)->toContain('.glass-minimal');
        expect($css)->toContain('.glass-bold');
    });

    test('all presets CSS includes documentation header', function (): void {
        $css = $this->glassPresets->generateAllPresetsCss();

        expect($css)->toContain('ArtisanPack UI - Glass Theme Presets');
        expect($css)->toContain('@since 2.0.0');
    });
});

describe('Preset Metadata', function (): void {
    test('returns metadata for all presets', function (): void {
        $metadata = $this->glassPresets->getPresetsMetadata();

        expect($metadata)->toBeArray();
        expect($metadata)->toHaveCount(6);

        foreach ($this->glassPresets->getAvailablePresets() as $preset) {
            expect($metadata)->toHaveKey($preset);
            expect($metadata[$preset])->toHaveKey('name');
            expect($metadata[$preset])->toHaveKey('description');
            expect($metadata[$preset])->toHaveKey('mode');
            expect($metadata[$preset])->toHaveKey('intensity');
            expect($metadata[$preset])->toHaveKey('tokens');
            expect($metadata[$preset])->toHaveKey('has_dark_overrides');
        }
    });

    test('identifies correct mode for light presets', function (): void {
        $metadata = $this->glassPresets->getPresetsMetadata();

        expect($metadata['glass-frosted-light']['mode'])->toBe('light');
        expect($metadata['glass-liquid-light']['mode'])->toBe('light');
    });

    test('identifies correct mode for dark presets', function (): void {
        $metadata = $this->glassPresets->getPresetsMetadata();

        expect($metadata['glass-frosted-dark']['mode'])->toBe('dark');
        expect($metadata['glass-liquid-dark']['mode'])->toBe('dark');
    });

    test('identifies correct mode for adaptive presets', function (): void {
        $metadata = $this->glassPresets->getPresetsMetadata();

        expect($metadata['glass-minimal']['mode'])->toBe('adaptive');
        expect($metadata['glass-bold']['mode'])->toBe('adaptive');
    });

    test('identifies correct intensity levels', function (): void {
        $metadata = $this->glassPresets->getPresetsMetadata();

        expect($metadata['glass-minimal']['intensity'])->toBe('subtle');
        expect($metadata['glass-bold']['intensity'])->toBe('bold');
        expect($metadata['glass-frosted-light']['intensity'])->toBe('normal');
    });
});

describe('Error Handling', function (): void {
    test('throws exception for invalid preset', function (): void {
        expect(fn () => $this->glassPresets->getPresetTokens('invalid-preset'))
            ->toThrow(\InvalidArgumentException::class);
    });

    test('exception message includes available presets', function (): void {
        try {
            $this->glassPresets->getPresetTokens('invalid-preset');
        } catch (\InvalidArgumentException $e) {
            expect($e->getMessage())->toContain('glass-frosted-light');
            expect($e->getMessage())->toContain('glass-minimal');
        }
    });
});
