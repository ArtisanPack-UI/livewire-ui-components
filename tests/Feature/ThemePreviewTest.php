<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\ThemePreview;
use Livewire\Livewire;

test('theme preview component can be rendered', function (): void {
    Livewire::test(ThemePreview::class)
        ->assertStatus(200)
        ->assertSee(__('Theme Preview Tool'));
});

test('theme preview has default color values', function (): void {
    Livewire::test(ThemePreview::class)
        ->assertSet('primaryColor', 'sky')
        ->assertSet('secondaryColor', 'slate')
        ->assertSet('accentColor', 'amber')
        ->assertSet('useCustomColors', false);
});

test('theme preview can change primary color', function (): void {
    Livewire::test(ThemePreview::class)
        ->set('primaryColor', 'blue')
        ->assertSet('primaryColor', 'blue');
});

test('theme preview can change secondary color', function (): void {
    Livewire::test(ThemePreview::class)
        ->set('secondaryColor', 'gray')
        ->assertSet('secondaryColor', 'gray');
});

test('theme preview can change accent color', function (): void {
    Livewire::test(ThemePreview::class)
        ->set('accentColor', 'red')
        ->assertSet('accentColor', 'red');
});

test('theme preview can toggle custom colors mode', function (): void {
    Livewire::test(ThemePreview::class)
        ->set('useCustomColors', true)
        ->assertSet('useCustomColors', true);
});

test('theme preview can set custom hex colors', function (): void {
    Livewire::test(ThemePreview::class)
        ->set('useCustomColors', true)
        ->set('primaryHex', '#ff5733')
        ->set('secondaryHex', '#33ff57')
        ->set('accentHex', '#3357ff')
        ->assertSet('primaryHex', '#ff5733')
        ->assertSet('secondaryHex', '#33ff57')
        ->assertSet('accentHex', '#3357ff');
});

test('theme preview returns effective primary color for tailwind color', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('primaryColor', 'sky')
        ->set('useCustomColors', false);

    expect($component->get('effectivePrimaryColor'))->toBe('#0ea5e9');
});

test('theme preview returns effective primary color for custom hex', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('useCustomColors', true)
        ->set('primaryHex', '#ff5733');

    expect($component->get('effectivePrimaryColor'))->toBe('#ff5733');
});

test('theme preview returns effective secondary color', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('secondaryColor', 'slate')
        ->set('useCustomColors', false);

    expect($component->get('effectiveSecondaryColor'))->toBe('#64748b');
});

test('theme preview returns effective accent color', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('accentColor', 'amber')
        ->set('useCustomColors', false);

    expect($component->get('effectiveAccentColor'))->toBe('#f59e0b');
});

test('theme preview can toggle dark mode', function (): void {
    Livewire::test(ThemePreview::class)
        ->set('darkMode', true)
        ->assertSet('darkMode', true);
});

test('theme preview can change active section', function (): void {
    Livewire::test(ThemePreview::class)
        ->assertSet('activeSection', 'colors')
        ->set('activeSection', 'glass')
        ->assertSet('activeSection', 'glass')
        ->set('activeSection', 'accessibility')
        ->assertSet('activeSection', 'accessibility')
        ->set('activeSection', 'export')
        ->assertSet('activeSection', 'export');
});

test('theme preview can change selected component', function (): void {
    Livewire::test(ThemePreview::class)
        ->assertSet('selectedComponent', 'button')
        ->set('selectedComponent', 'input')
        ->assertSet('selectedComponent', 'input')
        ->set('selectedComponent', 'card')
        ->assertSet('selectedComponent', 'card');
});

test('theme preview can set glass preset', function (): void {
    Livewire::test(ThemePreview::class)
        ->set('glassPreset', 'glass-frosted-light')
        ->assertSet('glassPreset', 'glass-frosted-light');
});

test('theme preview can set tint color', function (): void {
    Livewire::test(ThemePreview::class)
        ->set('tintColor', 'blue')
        ->assertSet('tintColor', 'blue');
});

test('theme preview can set tint intensity', function (): void {
    Livewire::test(ThemePreview::class)
        ->set('tintIntensity', 0.3)
        ->assertSet('tintIntensity', 0.3);
});

test('theme preview can set accessibility preset', function (): void {
    Livewire::test(ThemePreview::class)
        ->set('accessibilityPreset', 'high-contrast-light')
        ->assertSet('accessibilityPreset', 'high-contrast-light');
});

test('theme preview reset method restores defaults', function (): void {
    Livewire::test(ThemePreview::class)
        ->set('primaryColor', 'red')
        ->set('secondaryColor', 'blue')
        ->set('accentColor', 'green')
        ->set('useCustomColors', true)
        ->set('darkMode', true)
        ->set('glassPreset', 'glass-frosted-light')
        ->set('accessibilityPreset', 'high-contrast-light')
        ->call('resetTheme')
        ->assertSet('primaryColor', 'sky')
        ->assertSet('secondaryColor', 'slate')
        ->assertSet('accentColor', 'amber')
        ->assertSet('useCustomColors', false)
        ->assertSet('darkMode', false)
        ->assertSet('glassPreset', '')
        ->assertSet('accessibilityPreset', '');
});

test('theme preview generates shareable URL with tailwind colors', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('primaryColor', 'blue')
        ->set('secondaryColor', 'gray')
        ->set('accentColor', 'red')
        ->set('useCustomColors', false);

    $url = $component->get('shareableUrl');

    expect($url)->toContain('primary=blue');
    expect($url)->toContain('secondary=gray');
    expect($url)->toContain('accent=red');
});

test('theme preview generates shareable URL with custom hex colors', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('useCustomColors', true)
        ->set('primaryHex', '#ff5733')
        ->set('secondaryHex', '#33ff57')
        ->set('accentHex', '#3357ff');

    $url = $component->get('shareableUrl');

    expect($url)->toContain('primary_hex=ff5733');
    expect($url)->toContain('secondary_hex=33ff57');
    expect($url)->toContain('accent_hex=3357ff');
});

test('theme preview generates shareable URL with dark mode', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('darkMode', true);

    $url = $component->get('shareableUrl');

    expect($url)->toContain('dark=1');
});

test('theme preview generates shareable URL with glass preset', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('glassPreset', 'glass-frosted-light');

    $url = $component->get('shareableUrl');

    expect($url)->toContain('preset=glass-frosted-light');
});

test('theme preview generates shareable URL with accessibility preset', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('accessibilityPreset', 'high-contrast-light');

    $url = $component->get('shareableUrl');

    expect($url)->toContain('accessibility=high-contrast-light');
});

test('theme preview generates artisan command', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('primaryColor', 'blue')
        ->set('secondaryColor', 'gray')
        ->set('accentColor', 'red')
        ->set('useCustomColors', false);

    $command = $component->get('artisanCommand');

    expect($command)->toContain('php artisan artisanpack:generate-theme');
    expect($command)->toContain('--primary=blue');
    expect($command)->toContain('--secondary=gray');
    expect($command)->toContain('--accent=red');
});

test('theme preview generates artisan command with custom hex colors', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('useCustomColors', true)
        ->set('primaryHex', '#ff5733')
        ->set('secondaryHex', '#33ff57')
        ->set('accentHex', '#3357ff');

    $command = $component->get('artisanCommand');

    expect($command)->toContain('--primary=#ff5733');
    expect($command)->toContain('--secondary=#33ff57');
    expect($command)->toContain('--accent=#3357ff');
});

test('theme preview generates artisan command with glass preset', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('glassPreset', 'glass-frosted-light');

    $command = $component->get('artisanCommand');

    expect($command)->toContain('--preset=glass-frosted-light');
});

test('theme preview generates artisan command with tint color', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('tintColor', 'blue')
        ->set('tintIntensity', 0.3);

    $command = $component->get('artisanCommand');

    expect($command)->toContain('--tint-color=blue');
    expect($command)->toContain('--tint-intensity=0.3');
});

test('theme preview generates artisan command with accessibility preset', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('accessibilityPreset', 'high-contrast-light');

    $command = $component->get('artisanCommand');

    expect($command)->toContain('--accessibility-preset=high-contrast-light');
});

test('theme preview has exportJson method', function (): void {
    // Verify the exportJson method exists and can be called
    Livewire::test(ThemePreview::class)
        ->set('primaryColor', 'blue')
        ->set('secondaryColor', 'gray')
        ->set('accentColor', 'red')
        ->call('exportJson')
        ->assertStatus(200);
});

test('theme preview generates CSS variables', function (): void {
    $component = Livewire::test(ThemePreview::class)
        ->set('primaryColor', 'blue');

    $css = $component->get('previewCssVariables');

    expect($css)->toContain(':root');
    expect($css)->toContain('--primary:');
    expect($css)->toContain('--secondary:');
    expect($css)->toContain('--accent:');
});

test('theme preview has all tailwind colors available', function (): void {
    $component = Livewire::test(ThemePreview::class);
    $colors    = $component->get('tailwindColors');

    expect($colors)->toBeArray();
    expect($colors)->toHaveKey('sky');
    expect($colors)->toHaveKey('blue');
    expect($colors)->toHaveKey('red');
    expect($colors)->toHaveKey('green');
    expect($colors)->toHaveKey('amber');
    expect($colors)->toHaveKey('slate');
});

test('theme preview has component categories defined', function (): void {
    $component  = Livewire::test(ThemePreview::class);
    $categories = $component->get('componentCategories');

    expect($categories)->toBeArray();
    expect($categories)->toHaveKey('form');
    expect($categories)->toHaveKey('feedback');
    expect($categories)->toHaveKey('layout');
    expect($categories)->toHaveKey('data');
    expect($categories['form'])->toContain('button');
    expect($categories['form'])->toContain('input');
});

test('theme preview copyShareableUrl dispatches events', function (): void {
    Livewire::test(ThemePreview::class)
        ->call('copyShareableUrl')
        ->assertDispatched('copy-to-clipboard')
        ->assertDispatched('notify');
});

test('theme preview glassPresets returns array of presets', function (): void {
    $component = Livewire::test(ThemePreview::class);
    $presets   = $component->get('glassPresets');

    expect($presets)->toBeArray();
    expect($presets)->toHaveKey('');
});

test('theme preview accessibilityPresets returns array of presets', function (): void {
    $component = Livewire::test(ThemePreview::class);
    $presets   = $component->get('accessibilityPresets');

    expect($presets)->toBeArray();
    expect($presets)->toHaveKey('');
});

test('theme preview colorOptions returns formatted options', function (): void {
    $component = Livewire::test(ThemePreview::class);
    $options   = $component->get('colorOptions');

    expect($options)->toBeArray();
    expect($options[0])->toHaveKey('value');
    expect($options[0])->toHaveKey('label');
    expect($options[0])->toHaveKey('color');
});

test('theme preview tintColorOptions includes transparent option', function (): void {
    $component = Livewire::test(ThemePreview::class);
    $options   = $component->get('tintColorOptions');

    expect($options)->toBeArray();
    expect($options[0]['value'])->toBe('transparent');
});

test('theme preview route is defined', function (): void {
    // Verify that the route is defined
    expect(route('artisanpack.theme-preview'))->toContain('artisanpack/theme-preview');
});

test('theme preview loads from URL with primary color parameter', function (): void {
    Livewire::withQueryParams(['primary' => 'red'])
        ->test(ThemePreview::class)
        ->assertSet('primaryColor', 'red');
});

test('theme preview loads from URL with secondary color parameter', function (): void {
    Livewire::withQueryParams(['secondary' => 'blue'])
        ->test(ThemePreview::class)
        ->assertSet('secondaryColor', 'blue');
});

test('theme preview loads from URL with accent color parameter', function (): void {
    Livewire::withQueryParams(['accent' => 'green'])
        ->test(ThemePreview::class)
        ->assertSet('accentColor', 'green');
});

test('theme preview loads from URL with custom hex colors', function (): void {
    Livewire::withQueryParams([
        'primary_hex'   => '#ff5733',
        'secondary_hex' => '#33ff57',
        'accent_hex'    => '#3357ff',
    ])
        ->test(ThemePreview::class)
        ->assertSet('primaryHex', '#ff5733')
        ->assertSet('secondaryHex', '#33ff57')
        ->assertSet('accentHex', '#3357ff')
        ->assertSet('useCustomColors', true);
});

test('theme preview loads from URL with dark mode parameter', function (): void {
    Livewire::withQueryParams(['dark' => '1'])
        ->test(ThemePreview::class)
        ->assertSet('darkMode', true);
});

test('theme preview loads from URL with glass preset parameter', function (): void {
    Livewire::withQueryParams(['preset' => 'glass-frosted-light'])
        ->test(ThemePreview::class)
        ->assertSet('glassPreset', 'glass-frosted-light');
});

test('theme preview loads from URL with accessibility preset parameter', function (): void {
    Livewire::withQueryParams(['accessibility' => 'high-contrast-light'])
        ->test(ThemePreview::class)
        ->assertSet('accessibilityPreset', 'high-contrast-light');
});
