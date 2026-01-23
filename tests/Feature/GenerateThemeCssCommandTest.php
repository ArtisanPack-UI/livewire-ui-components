<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    // Set up test output paths in config
    config()->set('artisanpack.livewire-ui-components.theme_output_path', storage_path('test-theme.css'));
    config()->set('artisanpack.livewire-ui-components.glass.enabled', true);
    config()->set('artisanpack.livewire-ui-components.glass.output_path', storage_path('test-glass-tokens.css'));
    config()->set('artisanpack.livewire-ui-components.design_tokens.enabled', true);
    config()->set('artisanpack.livewire-ui-components.design_tokens.output_path', storage_path('test-design-tokens.css'));
    config()->set('artisanpack.livewire-ui-components.glass.presets.enabled', true);
    config()->set('artisanpack.livewire-ui-components.glass.presets.output_path', storage_path('test-glass-presets.css'));
});

afterEach(function (): void {
    // Clean up test files
    $testFiles = [
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

test('command generates basic theme with default colors', function (): void {
    $this->artisan('artisanpack:generate-theme')
        ->assertExitCode(0);

    expect(File::exists(storage_path('test-theme.css')))->toBeTrue();
});

test('command accepts custom primary, secondary, and accent colors', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary'   => 'blue',
        '--secondary' => 'gray',
        '--accent'    => 'rose',
    ])
        ->assertExitCode(0);

    $cssContent = File::get(storage_path('test-theme.css'));

    // The theme uses palette variables like --p-500 for primary
    expect($cssContent)->toContain('--p-');
    expect($cssContent)->toContain('--s-');
    expect($cssContent)->toContain('--a-');
});

test('command accepts hex color codes', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary'   => '#3b82f6',
        '--secondary' => '#64748b',
        '--accent'    => '#f59e0b',
    ])
        ->assertExitCode(0);

    expect(File::exists(storage_path('test-theme.css')))->toBeTrue();
});

test('command generates glass tokens with tint color option', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary'    => 'blue',
        '--tint-color' => 'sky',
    ])
        ->assertExitCode(0);

    $cssContent = File::get(storage_path('test-theme.css'));

    expect($cssContent)->toContain('--glass-tint-color:');
});

test('command generates glass tokens with hex tint color option', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary'    => 'blue',
        '--tint-color' => '#60a5fa',
    ])
        ->assertExitCode(0);

    $cssContent = File::get(storage_path('test-theme.css'));

    expect($cssContent)->toContain('--glass-tint-color: #60a5fa');
});

test('command accepts tint intensity option', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary'        => 'blue',
        '--tint-color'     => '#3b82f6',
        '--tint-intensity' => '0.25',
    ])
        ->assertExitCode(0);

    $cssContent = File::get(storage_path('test-theme.css'));

    expect($cssContent)->toContain('--glass-tint-opacity: 0.25');
});

test('command exports theme configuration to JSON', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary'   => 'blue',
        '--secondary' => 'slate',
        '--accent'    => 'amber',
        '--json'      => true,
    ])
        ->assertExitCode(0);

    $jsonPath = storage_path('test-theme.json');
    expect(File::exists($jsonPath))->toBeTrue();

    $jsonContent = json_decode(File::get($jsonPath), true);

    expect($jsonContent)->toHaveKey('version');
    expect($jsonContent)->toHaveKey('colors');
    expect($jsonContent['colors']['primary'])->toBe('blue');
    expect($jsonContent['colors']['secondary'])->toBe('slate');
    expect($jsonContent['colors']['accent'])->toBe('amber');
});

test('command exports theme with glass configuration in JSON', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary'        => 'blue',
        '--preset'         => 'glass-frosted-light',
        '--tint-color'     => 'sky',
        '--tint-intensity' => '0.20',
        '--json'           => true,
    ])
        ->assertExitCode(0);

    $jsonPath    = storage_path('test-theme.json');
    $jsonContent = json_decode(File::get($jsonPath), true);

    expect($jsonContent)->toHaveKey('glass');
    expect($jsonContent['glass']['preset'])->toBe('glass-frosted-light');
    expect($jsonContent['glass']['tint']['color'])->toBe('sky');
    expect($jsonContent['glass']['tint']['intensity'])->toBe(0.20);
    expect($jsonContent['glass'])->toHaveKey('tokens');
});

test('command generates preview URL when requested', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary'   => 'blue',
        '--secondary' => 'slate',
        '--accent'    => 'amber',
        '--preview'   => true,
    ])
        ->expectsOutputToContain('Theme Preview URL:')
        ->assertExitCode(0);
});

test('command applies glass preset', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary' => 'blue',
        '--preset'  => 'glass-frosted-light',
    ])
        ->assertExitCode(0);

    // Check that presets file was generated
    $presetsFile = storage_path('test-glass-presets.css');
    expect(File::exists($presetsFile))->toBeTrue();
    
    $cssContent = File::get($presetsFile);
    expect($cssContent)->toContain('.glass-frosted-light');
});

test('command can generate only glass tokens', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--glass-only' => true,
    ])
        ->assertExitCode(0);

    expect(File::exists(storage_path('test-glass-tokens.css')))->toBeTrue();
});

test('command can generate only design tokens', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--tokens-only' => true,
    ])
        ->assertExitCode(0);

    expect(File::exists(storage_path('test-design-tokens.css')))->toBeTrue();
});

test('command can generate only glass presets', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--presets-only' => true,
    ])
        ->assertExitCode(0);

    expect(File::exists(storage_path('test-glass-presets.css')))->toBeTrue();
});

test('command can exclude glass tokens', function (): void {
    // First delete any existing file
    if (File::exists(storage_path('test-glass-tokens.css'))) {
        File::delete(storage_path('test-glass-tokens.css'));
    }

    $this->artisan('artisanpack:generate-theme', [
        '--primary'  => 'blue',
        '--no-glass' => true,
    ])
        ->assertExitCode(0);

    // Theme should be generated, but glass tokens should not
    expect(File::exists(storage_path('test-theme.css')))->toBeTrue();
    expect(File::exists(storage_path('test-glass-tokens.css')))->toBeFalse();
});

test('command can exclude design tokens', function (): void {
    // First delete any existing file
    if (File::exists(storage_path('test-design-tokens.css'))) {
        File::delete(storage_path('test-design-tokens.css'));
    }

    $this->artisan('artisanpack:generate-theme', [
        '--primary'   => 'blue',
        '--no-tokens' => true,
    ])
        ->assertExitCode(0);

    // Theme should be generated, but design tokens should not
    expect(File::exists(storage_path('test-theme.css')))->toBeTrue();
    expect(File::exists(storage_path('test-design-tokens.css')))->toBeFalse();
});

test('command can exclude glass presets', function (): void {
    // First delete any existing file
    if (File::exists(storage_path('test-glass-presets.css'))) {
        File::delete(storage_path('test-glass-presets.css'));
    }

    $this->artisan('artisanpack:generate-theme', [
        '--primary'    => 'blue',
        '--no-presets' => true,
    ])
        ->assertExitCode(0);

    // Theme should be generated, but presets should not
    expect(File::exists(storage_path('test-theme.css')))->toBeTrue();
    expect(File::exists(storage_path('test-glass-presets.css')))->toBeFalse();
});

test('JSON export includes theme version', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary' => 'blue',
        '--json'    => true,
    ])
        ->assertExitCode(0);

    $jsonPath    = storage_path('test-theme.json');
    $jsonContent = json_decode(File::get($jsonPath), true);

    expect($jsonContent['version'])->toBe('2.0.0');
});

test('JSON export includes generation timestamp', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary' => 'blue',
        '--json'    => true,
    ])
        ->assertExitCode(0);

    $jsonPath    = storage_path('test-theme.json');
    $jsonContent = json_decode(File::get($jsonPath), true);

    expect($jsonContent)->toHaveKey('generated');
    expect($jsonContent['generated'])->toBeString();
});

test('tint color overrides are applied to standalone glass tokens', function (): void {
    $this->artisan('artisanpack:generate-theme', [
        '--primary'        => 'blue',
        '--tint-color'     => '#ff5500',
        '--tint-intensity' => '0.30',
    ])
        ->assertExitCode(0);

    $glassTokensFile = storage_path('test-glass-tokens.css');

    // Assert file was created
    expect(File::exists($glassTokensFile))->toBeTrue();

    // Assert content contains tint overrides
    $cssContent = File::get($glassTokensFile);
    expect($cssContent)->toContain('--glass-tint-color:');
    expect($cssContent)->toContain('--glass-tint-opacity:');
});

test('command handles all tailwind color names', function (): void {
    $tailwindColors = [
        'sky', 'blue', 'indigo', 'purple', 'pink', 'red', 'orange',
        'amber', 'yellow', 'lime', 'green', 'emerald', 'teal', 'cyan',
        'slate', 'gray', 'zinc', 'neutral', 'stone', 'fuchsia', 'rose',
    ];

    foreach ($tailwindColors as $color) {
        $this->artisan('artisanpack:generate-theme', [
            '--primary' => $color,
        ])
            ->assertExitCode(0);
    }
})->group('slow');

test('preview URL includes all color parameters', function (): void {
    config()->set('app.url', 'https://example.test');

    $this->artisan('artisanpack:generate-theme', [
        '--primary'   => 'blue',
        '--secondary' => 'slate',
        '--accent'    => 'amber',
        '--preview'   => true,
    ])
        ->expectsOutputToContain('primary=blue')
        ->assertExitCode(0);
});

test('preview URL includes glass preset when specified', function (): void {
    config()->set('app.url', 'https://example.test');

    $this->artisan('artisanpack:generate-theme', [
        '--primary' => 'blue',
        '--preset'  => 'glass-minimal',
        '--preview' => true,
    ])
        ->expectsOutputToContain('preset=glass-minimal')
        ->assertExitCode(0);
});

test('preview URL includes tint parameters when specified', function (): void {
    config()->set('app.url', 'https://example.test');

    $this->artisan('artisanpack:generate-theme', [
        '--primary'        => 'blue',
        '--tint-color'     => '#3b82f6',
        '--tint-intensity' => '0.25',
        '--preview'        => true,
    ])
        ->expectsOutputToContain('tint=')
        ->assertExitCode(0);
});
