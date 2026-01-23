<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;

beforeEach(function (): void {
    $this->colorGenerator = new ColorGenerator;
});

describe('Glass Token Defaults', function (): void {
    test('returns all base glass token defaults', function (): void {
        $defaults = $this->colorGenerator->getGlassTokenDefaults();

        // Base glass tokens
        expect($defaults)->toHaveKey('glass-blur');
        expect($defaults)->toHaveKey('glass-opacity');
        expect($defaults)->toHaveKey('glass-border-width');
        expect($defaults)->toHaveKey('glass-border-opacity');
        expect($defaults)->toHaveKey('glass-shadow-opacity');

        // Tint tokens
        expect($defaults)->toHaveKey('glass-tint-color');
        expect($defaults)->toHaveKey('glass-tint-opacity');

        // Frosted variant tokens
        expect($defaults)->toHaveKey('glass-frosted-blur');
        expect($defaults)->toHaveKey('glass-frosted-opacity');
        expect($defaults)->toHaveKey('glass-frosted-saturation');

        // Liquid variant tokens
        expect($defaults)->toHaveKey('glass-liquid-blur');
        expect($defaults)->toHaveKey('glass-liquid-opacity');
        expect($defaults)->toHaveKey('glass-liquid-refraction');
        expect($defaults)->toHaveKey('glass-liquid-border-glow');

        // Transparent variant tokens
        expect($defaults)->toHaveKey('glass-transparent-blur');
        expect($defaults)->toHaveKey('glass-transparent-opacity');
    });

    test('has correct default values for base tokens', function (): void {
        $defaults = $this->colorGenerator->getGlassTokenDefaults();

        expect($defaults['glass-blur'])->toBe('12px');
        expect($defaults['glass-opacity'])->toBe('0.7');
        expect($defaults['glass-border-width'])->toBe('1px');
        expect($defaults['glass-border-opacity'])->toBe('0.2');
        expect($defaults['glass-shadow-opacity'])->toBe('0.1');
    });

    test('has correct default values for tint tokens', function (): void {
        $defaults = $this->colorGenerator->getGlassTokenDefaults();

        expect($defaults['glass-tint-color'])->toBe('transparent');
        expect($defaults['glass-tint-opacity'])->toBe('0.15');
    });

    test('has correct default values for frosted variant', function (): void {
        $defaults = $this->colorGenerator->getGlassTokenDefaults();

        expect($defaults['glass-frosted-blur'])->toBe('16px');
        expect($defaults['glass-frosted-opacity'])->toBe('0.8');
        expect($defaults['glass-frosted-saturation'])->toBe('180%');
    });

    test('has correct default values for liquid variant', function (): void {
        $defaults = $this->colorGenerator->getGlassTokenDefaults();

        expect($defaults['glass-liquid-blur'])->toBe('24px');
        expect($defaults['glass-liquid-opacity'])->toBe('0.6');
        expect($defaults['glass-liquid-refraction'])->toBe('0.5');
        expect($defaults['glass-liquid-border-glow'])->toBe('0.3');
    });

    test('has correct default values for transparent variant', function (): void {
        $defaults = $this->colorGenerator->getGlassTokenDefaults();

        expect($defaults['glass-transparent-blur'])->toBe('8px');
        expect($defaults['glass-transparent-opacity'])->toBe('0.3');
    });
});

describe('Glass Token Dark Mode Defaults', function (): void {
    test('returns dark mode overrides', function (): void {
        $darkDefaults = $this->colorGenerator->getGlassTokenDarkDefaults();

        // Base glass token overrides
        expect($darkDefaults)->toHaveKey('glass-opacity');
        expect($darkDefaults)->toHaveKey('glass-border-opacity');
        expect($darkDefaults)->toHaveKey('glass-shadow-opacity');

        // Tint token overrides
        expect($darkDefaults)->toHaveKey('glass-tint-opacity');

        // Frosted variant overrides
        expect($darkDefaults)->toHaveKey('glass-frosted-opacity');
        expect($darkDefaults)->toHaveKey('glass-frosted-blur');
        expect($darkDefaults)->toHaveKey('glass-frosted-saturation');

        // Liquid variant overrides
        expect($darkDefaults)->toHaveKey('glass-liquid-opacity');
        expect($darkDefaults)->toHaveKey('glass-liquid-border-glow');

        // Transparent variant overrides
        expect($darkDefaults)->toHaveKey('glass-transparent-blur');
        expect($darkDefaults)->toHaveKey('glass-transparent-opacity');
    });

    test('has correct dark mode override values for base tokens', function (): void {
        $darkDefaults = $this->colorGenerator->getGlassTokenDarkDefaults();

        expect($darkDefaults['glass-opacity'])->toBe('0.75');
        expect($darkDefaults['glass-border-opacity'])->toBe('0.25');
        expect($darkDefaults['glass-shadow-opacity'])->toBe('0.25');
        expect($darkDefaults['glass-tint-opacity'])->toBe('0.2');
    });

    test('has correct dark mode override values for frosted variant', function (): void {
        $darkDefaults = $this->colorGenerator->getGlassTokenDarkDefaults();

        expect($darkDefaults['glass-frosted-opacity'])->toBe('0.85');
        expect($darkDefaults['glass-frosted-blur'])->toBe('20px');
        expect($darkDefaults['glass-frosted-saturation'])->toBe('200%');
    });

    test('has correct dark mode override values for liquid variant', function (): void {
        $darkDefaults = $this->colorGenerator->getGlassTokenDarkDefaults();

        expect($darkDefaults['glass-liquid-opacity'])->toBe('0.65');
        expect($darkDefaults['glass-liquid-border-glow'])->toBe('0.4');
    });

    test('has correct dark mode override values for transparent variant', function (): void {
        $darkDefaults = $this->colorGenerator->getGlassTokenDarkDefaults();

        expect($darkDefaults['glass-transparent-blur'])->toBe('12px');
        expect($darkDefaults['glass-transparent-opacity'])->toBe('0.35');
    });
});

describe('Generate Glass Tokens CSS', function (): void {
    test('generates valid CSS output', function (): void {
        $css = $this->colorGenerator->generateGlassTokensCss();

        expect($css)->toBeString();
        expect($css)->not->toBeEmpty();
    });

    test('includes header comment', function (): void {
        $css = $this->colorGenerator->generateGlassTokensCss();

        expect($css)->toContain('ArtisanPack UI - Glass Design Tokens');
        expect($css)->toContain('@since 2.0.0');
    });

    test('includes @theme directive for Tailwind CSS v4', function (): void {
        $css = $this->colorGenerator->generateGlassTokensCss();

        expect($css)->toContain('@theme {');
        expect($css)->toContain('--glass-blur:');
    });

    test('includes :root section with all tokens', function (): void {
        $css = $this->colorGenerator->generateGlassTokensCss();

        expect($css)->toContain(':root {');
        expect($css)->toContain('--glass-blur: 12px;');
        expect($css)->toContain('--glass-opacity: 0.7;');
        expect($css)->toContain('--glass-border-width: 1px;');
    });

    test('includes computed glass colors for light mode', function (): void {
        $css = $this->colorGenerator->generateGlassTokensCss();

        expect($css)->toContain('--glass-bg-color: rgba(255, 255, 255, var(--glass-opacity));');
        expect($css)->toContain('--glass-border-color: rgba(255, 255, 255, var(--glass-border-opacity));');
        expect($css)->toContain('--glass-shadow-color: rgba(0, 0, 0, var(--glass-shadow-opacity));');
    });

    test('includes dark mode overrides', function (): void {
        $css = $this->colorGenerator->generateGlassTokensCss();

        expect($css)->toContain('[data-theme="dark"] {');

        // Base token overrides
        expect($css)->toContain('--glass-opacity: 0.75;');
        expect($css)->toContain('--glass-border-opacity: 0.25;');
        expect($css)->toContain('--glass-shadow-opacity: 0.25;');
        expect($css)->toContain('--glass-tint-opacity: 0.2;');

        // Frosted variant overrides
        expect($css)->toContain('--glass-frosted-opacity: 0.85;');
        expect($css)->toContain('--glass-frosted-blur: 20px;');
        expect($css)->toContain('--glass-frosted-saturation: 200%;');

        // Liquid variant overrides
        expect($css)->toContain('--glass-liquid-opacity: 0.65;');
        expect($css)->toContain('--glass-liquid-border-glow: 0.4;');

        // Transparent variant overrides
        expect($css)->toContain('--glass-transparent-blur: 12px;');
        expect($css)->toContain('--glass-transparent-opacity: 0.35;');
    });

    test('includes computed glass colors for dark mode', function (): void {
        $css = $this->colorGenerator->generateGlassTokensCss();

        expect($css)->toContain('--glass-bg-color: rgba(30, 30, 30, var(--glass-opacity));');
    });

    test('accepts custom overrides', function (): void {
        $overrides = [
            'glass-blur'    => '20px',
            'glass-opacity' => '0.9',
        ];

        $css = $this->colorGenerator->generateGlassTokensCss($overrides);

        expect($css)->toContain('--glass-blur: 20px;');
        expect($css)->toContain('--glass-opacity: 0.9;');
    });

    test('merges overrides with defaults', function (): void {
        $overrides = [
            'glass-blur' => '20px',
        ];

        $css = $this->colorGenerator->generateGlassTokensCss($overrides);

        // Custom value
        expect($css)->toContain('--glass-blur: 20px;');
        // Default values still present
        expect($css)->toContain('--glass-opacity: 0.7;');
        expect($css)->toContain('--glass-frosted-blur: 16px;');
    });
});

describe('Generate Theme CSS with Glass Tokens', function (): void {
    test('includes glass tokens in generated theme', function (): void {
        $css = $this->colorGenerator->generateThemeCss('blue', 'slate', 'amber');

        expect($css)->toContain('/* --- Glass Design Tokens --- */');
        expect($css)->toContain('--glass-blur: 12px;');
        expect($css)->toContain('--glass-opacity: 0.7;');
    });

    test('includes computed glass colors in light mode section', function (): void {
        $css = $this->colorGenerator->generateThemeCss('blue', 'slate', 'amber');

        expect($css)->toContain('/* --- Computed Glass Colors (Light Mode) --- */');
        expect($css)->toContain('--glass-bg-color: rgba(255, 255, 255, var(--glass-opacity));');
    });

    test('includes glass token dark mode overrides', function (): void {
        $css = $this->colorGenerator->generateThemeCss('blue', 'slate', 'amber');

        expect($css)->toContain('/* --- Glass Design Token Dark Mode Overrides --- */');

        // Verify some key dark mode overrides are present
        expect($css)->toContain('--glass-frosted-opacity: 0.85;');
        expect($css)->toContain('--glass-transparent-blur: 12px;');
        expect($css)->toContain('--glass-tint-opacity: 0.2;');
    });

    test('includes computed glass colors in dark mode section', function (): void {
        $css = $this->colorGenerator->generateThemeCss('blue', 'slate', 'amber');

        expect($css)->toContain('/* --- Computed Glass Colors (Dark Mode) --- */');
        expect($css)->toContain('--glass-bg-color: rgba(30, 30, 30, var(--glass-opacity));');
    });

    test('includes all glass variant tokens', function (): void {
        $css = $this->colorGenerator->generateThemeCss('blue', 'slate', 'amber');

        // Frosted variant
        expect($css)->toContain('--glass-frosted-blur: 16px;');
        expect($css)->toContain('--glass-frosted-opacity: 0.8;');
        expect($css)->toContain('--glass-frosted-saturation: 180%;');

        // Liquid variant
        expect($css)->toContain('--glass-liquid-blur: 24px;');
        expect($css)->toContain('--glass-liquid-opacity: 0.6;');
        expect($css)->toContain('--glass-liquid-refraction: 0.5;');
        expect($css)->toContain('--glass-liquid-border-glow: 0.3;');

        // Transparent variant
        expect($css)->toContain('--glass-transparent-blur: 8px;');
        expect($css)->toContain('--glass-transparent-opacity: 0.3;');
    });
});

describe('Glass Utility Classes CSS File', function (): void {
    beforeEach(function (): void {
        $this->cssPath = __DIR__.'/../../resources/css/glass-tokens.css';

        if (! file_exists($this->cssPath) || ! is_readable($this->cssPath)) {
            $this->fail("CSS file not found or not readable: {$this->cssPath}");
        }

        $content = file_get_contents($this->cssPath);

        if (false === $content) {
            $this->fail("Failed to read CSS file: {$this->cssPath}");
        }

        $this->cssContent = $content;
    });

    test('CSS file exists', function (): void {
        expect(file_exists($this->cssPath))->toBeTrue();
    });

    test('includes base glass utility class', function (): void {
        expect($this->cssContent)->toContain('.glass {');
        expect($this->cssContent)->toContain('backdrop-filter: blur(var(--glass-blur))');
    });

    test('includes glass variant classes', function (): void {
        expect($this->cssContent)->toContain('.glass-frosted {');
        expect($this->cssContent)->toContain('.glass-liquid {');
        expect($this->cssContent)->toContain('.glass-transparent {');
    });

    test('frosted variant has saturation filter', function (): void {
        expect($this->cssContent)->toContain('saturate(var(--glass-frosted-saturation))');
    });

    test('liquid variant has border glow', function (): void {
        expect($this->cssContent)->toContain('--glass-liquid-border-glow-color');
    });

    test('includes semantic tint classes', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-primary {');
        expect($this->cssContent)->toContain('.glass-tint-secondary {');
        expect($this->cssContent)->toContain('.glass-tint-accent {');
        expect($this->cssContent)->toContain('.glass-tint-success {');
        expect($this->cssContent)->toContain('.glass-tint-warning {');
        expect($this->cssContent)->toContain('.glass-tint-error {');
        expect($this->cssContent)->toContain('.glass-tint-info {');
    });

    test('includes custom tint class', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-custom {');
        expect($this->cssContent)->toContain('var(--custom-tint, transparent)');
    });

    test('includes tint opacity modifiers', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-opacity-10 {');
        expect($this->cssContent)->toContain('.glass-tint-opacity-20 {');
        expect($this->cssContent)->toContain('.glass-tint-opacity-30 {');
        expect($this->cssContent)->toContain('.glass-tint-opacity-40 {');
        expect($this->cssContent)->toContain('.glass-tint-opacity-50 {');
        expect($this->cssContent)->toContain('.glass-tint-opacity-60 {');
        expect($this->cssContent)->toContain('.glass-tint-opacity-70 {');
        expect($this->cssContent)->toContain('.glass-tint-opacity-80 {');
        expect($this->cssContent)->toContain('.glass-tint-opacity-90 {');
        expect($this->cssContent)->toContain('.glass-tint-opacity-100 {');
    });

    test('includes tint overlay pseudo-element', function (): void {
        expect($this->cssContent)->toContain('[class*="glass-tint-"]::before');
        expect($this->cssContent)->toContain('background: var(--glass-tint-color)');
        expect($this->cssContent)->toContain('opacity: var(--glass-tint-opacity)');
    });

    test('tinted glass elements have relative positioning', function (): void {
        expect($this->cssContent)->toContain('[class*="glass-tint-"] {');
        expect($this->cssContent)->toContain('position: relative');
    });
});

describe('Tailwind Color Tint Classes', function (): void {
    beforeEach(function (): void {
        $this->cssPath = __DIR__.'/../../resources/css/glass-tokens.css';

        if (! file_exists($this->cssPath) || ! is_readable($this->cssPath)) {
            $this->fail("CSS file not found or not readable: {$this->cssPath}");
        }

        $content = file_get_contents($this->cssPath);

        if (false === $content) {
            $this->fail("Failed to read CSS file: {$this->cssPath}");
        }

        $this->cssContent = $content;
    });

    test('includes slate color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-slate-50 {');
        expect($this->cssContent)->toContain('.glass-tint-slate-500 {');
        expect($this->cssContent)->toContain('.glass-tint-slate-950 {');
    });

    test('includes gray color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-gray-50 {');
        expect($this->cssContent)->toContain('.glass-tint-gray-500 {');
        expect($this->cssContent)->toContain('.glass-tint-gray-950 {');
    });

    test('includes zinc color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-zinc-50 {');
        expect($this->cssContent)->toContain('.glass-tint-zinc-500 {');
        expect($this->cssContent)->toContain('.glass-tint-zinc-950 {');
    });

    test('includes neutral color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-neutral-50 {');
        expect($this->cssContent)->toContain('.glass-tint-neutral-500 {');
        expect($this->cssContent)->toContain('.glass-tint-neutral-950 {');
    });

    test('includes stone color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-stone-50 {');
        expect($this->cssContent)->toContain('.glass-tint-stone-500 {');
        expect($this->cssContent)->toContain('.glass-tint-stone-950 {');
    });

    test('includes red color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-red-50 {');
        expect($this->cssContent)->toContain('.glass-tint-red-500 {');
        expect($this->cssContent)->toContain('.glass-tint-red-950 {');
    });

    test('includes orange color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-orange-50 {');
        expect($this->cssContent)->toContain('.glass-tint-orange-500 {');
        expect($this->cssContent)->toContain('.glass-tint-orange-950 {');
    });

    test('includes amber color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-amber-50 {');
        expect($this->cssContent)->toContain('.glass-tint-amber-500 {');
        expect($this->cssContent)->toContain('.glass-tint-amber-950 {');
    });

    test('includes yellow color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-yellow-50 {');
        expect($this->cssContent)->toContain('.glass-tint-yellow-500 {');
        expect($this->cssContent)->toContain('.glass-tint-yellow-950 {');
    });

    test('includes lime color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-lime-50 {');
        expect($this->cssContent)->toContain('.glass-tint-lime-500 {');
        expect($this->cssContent)->toContain('.glass-tint-lime-950 {');
    });

    test('includes green color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-green-50 {');
        expect($this->cssContent)->toContain('.glass-tint-green-500 {');
        expect($this->cssContent)->toContain('.glass-tint-green-950 {');
    });

    test('includes emerald color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-emerald-50 {');
        expect($this->cssContent)->toContain('.glass-tint-emerald-500 {');
        expect($this->cssContent)->toContain('.glass-tint-emerald-950 {');
    });

    test('includes teal color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-teal-50 {');
        expect($this->cssContent)->toContain('.glass-tint-teal-500 {');
        expect($this->cssContent)->toContain('.glass-tint-teal-950 {');
    });

    test('includes cyan color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-cyan-50 {');
        expect($this->cssContent)->toContain('.glass-tint-cyan-500 {');
        expect($this->cssContent)->toContain('.glass-tint-cyan-950 {');
    });

    test('includes sky color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-sky-50 {');
        expect($this->cssContent)->toContain('.glass-tint-sky-500 {');
        expect($this->cssContent)->toContain('.glass-tint-sky-950 {');
    });

    test('includes blue color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-blue-50 {');
        expect($this->cssContent)->toContain('.glass-tint-blue-500 {');
        expect($this->cssContent)->toContain('.glass-tint-blue-950 {');
    });

    test('includes indigo color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-indigo-50 {');
        expect($this->cssContent)->toContain('.glass-tint-indigo-500 {');
        expect($this->cssContent)->toContain('.glass-tint-indigo-950 {');
    });

    test('includes violet color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-violet-50 {');
        expect($this->cssContent)->toContain('.glass-tint-violet-500 {');
        expect($this->cssContent)->toContain('.glass-tint-violet-950 {');
    });

    test('includes purple color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-purple-50 {');
        expect($this->cssContent)->toContain('.glass-tint-purple-500 {');
        expect($this->cssContent)->toContain('.glass-tint-purple-950 {');
    });

    test('includes fuchsia color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-fuchsia-50 {');
        expect($this->cssContent)->toContain('.glass-tint-fuchsia-500 {');
        expect($this->cssContent)->toContain('.glass-tint-fuchsia-950 {');
    });

    test('includes pink color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-pink-50 {');
        expect($this->cssContent)->toContain('.glass-tint-pink-500 {');
        expect($this->cssContent)->toContain('.glass-tint-pink-950 {');
    });

    test('includes rose color tints', function (): void {
        expect($this->cssContent)->toContain('.glass-tint-rose-50 {');
        expect($this->cssContent)->toContain('.glass-tint-rose-500 {');
        expect($this->cssContent)->toContain('.glass-tint-rose-950 {');
    });

    test('color tints have correct hex values', function (): void {
        // Spot check a few known Tailwind colors
        expect($this->cssContent)->toContain('#3b82f6'); // blue-500
        expect($this->cssContent)->toContain('#10b981'); // emerald-500
        expect($this->cssContent)->toContain('#8b5cf6'); // violet-500
        expect($this->cssContent)->toContain('#ef4444'); // red-500
        expect($this->cssContent)->toContain('#f59e0b'); // amber-500
    });

    test('all shades are present for each color', function (): void {
        $shades = ['50', '100', '200', '300', '400', '500', '600', '700', '800', '900', '950'];

        // Test blue as a representative color
        foreach ($shades as $shade) {
            expect($this->cssContent)->toContain(".glass-tint-blue-{$shade} {");
        }
    });
});
