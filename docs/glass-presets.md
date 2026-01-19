---
title: Glass Theme Presets
---

# Glass Theme Presets

ArtisanPack UI provides six pre-built glass theme presets that offer ready-to-use glassmorphism styles for your application. These presets can be applied globally or per-component, and they integrate seamlessly with the theme toggle for light/dark mode switching.

## Introduction to Glass Presets

Glass presets are pre-configured sets of CSS custom properties (design tokens) that create specific glassmorphism visual effects. Each preset is optimized for different use cases and visual styles.

### Key Features

- **Pre-built Configurations**: Six carefully designed glass themes ready to use
- **Global or Per-Component**: Apply presets at the page level or to individual components
- **Theme Toggle Compatible**: Works with light/dark mode switching
- **Customizable**: Use as starting points and override individual tokens as needed
- **CSS Classes**: Simple class-based application system

## Available Presets

### glass-frosted-light

A clean, professional frosted glass effect optimized for light backgrounds.

**Best for**: Corporate applications, professional dashboards, light-themed interfaces

**Characteristics**:
- High saturation (200%) for vibrant colors through the glass
- Moderate blur (14px base, 18px frosted variant)
- Light background colors with good opacity (0.75-0.85)

```blade
<div class="glass-frosted-light">
    <x-artisanpack-card>
        Clean, professional glass card
    </x-artisanpack-card>
</div>
```

### glass-frosted-dark

A sophisticated frosted glass effect optimized for dark backgrounds.

**Best for**: Dark-themed applications, night mode interfaces, media applications

**Characteristics**:
- Enhanced saturation (220%) for visibility in dark environments
- Increased blur (16px base, 22px frosted variant)
- Dark background colors with subtle transparency

```blade
<div class="glass-frosted-dark">
    <x-artisanpack-card>
        Sophisticated dark glass card
    </x-artisanpack-card>
</div>
```

### glass-liquid-light

A premium liquid glass effect for light backgrounds with smooth, flowing visuals.

**Best for**: Creative applications, portfolio sites, premium product showcases

**Characteristics**:
- Enhanced refraction effect (0.6) for liquid appearance
- High blur (28px liquid variant) for smooth transitions
- Prominent border glow (0.35) for depth

```blade
<div class="glass-liquid-light">
    <x-artisanpack-card>
        Flowing liquid glass effect
    </x-artisanpack-card>
</div>
```

### glass-liquid-dark

A premium liquid glass effect for dark backgrounds with enhanced glow effects.

**Best for**: Gaming interfaces, entertainment apps, immersive experiences

**Characteristics**:
- Maximum blur (32px liquid variant) for dramatic effect
- Enhanced border glow (0.45) for neon-like edges
- Deep transparency with prominent refraction

```blade
<div class="glass-liquid-dark">
    <x-artisanpack-card>
        Dramatic liquid glass with glow
    </x-artisanpack-card>
</div>
```

### glass-minimal

A subtle, understated glass effect for designs that need glass aesthetics without overwhelming visual presence.

**Best for**: Content-focused interfaces, reading applications, accessibility-conscious designs

**Characteristics**:
- Low blur (8px base) for subtle effect
- Reduced opacity (0.5) for transparency
- Minimal shadows and borders
- **Adaptive**: Includes automatic dark mode overrides

```blade
<div class="glass-minimal">
    <x-artisanpack-card>
        Subtle, content-friendly glass
    </x-artisanpack-card>
</div>
```

### glass-bold

A strong, prominent glass effect for elements that need to stand out with dramatic visual impact.

**Best for**: Call-to-action sections, hero components, marketing landing pages

**Characteristics**:
- High blur (20px base, 26px frosted, 36px liquid)
- High opacity (0.85) for solid presence
- Thick borders (2px) for definition
- Maximum saturation (250%) for vibrant colors
- **Adaptive**: Includes automatic dark mode overrides

```blade
<div class="glass-bold">
    <x-artisanpack-card>
        Dramatic, attention-grabbing glass
    </x-artisanpack-card>
</div>
```

## Using Glass Presets

### Method 1: CSS Classes

The simplest way to apply a preset is by adding its class to a container element:

```blade
{{-- Apply to a single component --}}
<div class="glass-frosted-light">
    <x-artisanpack-card>Content</x-artisanpack-card>
</div>

{{-- Apply to a section --}}
<section class="glass-liquid-dark p-8">
    <x-artisanpack-card>Card 1</x-artisanpack-card>
    <x-artisanpack-card>Card 2</x-artisanpack-card>
</section>
```

### Method 2: Global Application via Configuration

You can set a default preset in your configuration file:

```php
// config/livewire-ui-components.php
'glass' => [
    'presets' => [
        'default' => 'glass-frosted-light',
    ],
],
```

### Method 3: Generating Preset CSS Files

Use the Artisan command to generate standalone preset CSS files:

```bash
# Generate all presets
php artisan artisanpack:generate-theme --presets-only

# Generate a specific preset
php artisan artisanpack:generate-theme --preset=glass-minimal

# Include presets with the main theme
php artisan artisanpack:generate-theme --primary=blue --secondary=slate --accent=amber
```

## Preset Types

### Mode-Specific Presets

These presets are optimized for a specific color scheme and should be used when you have a fixed light or dark theme:

| Preset | Mode | Use Case |
|--------|------|----------|
| `glass-frosted-light` | Light | Light-themed applications |
| `glass-frosted-dark` | Dark | Dark-themed applications |
| `glass-liquid-light` | Light | Creative light interfaces |
| `glass-liquid-dark` | Dark | Immersive dark interfaces |

### Adaptive Presets

These presets automatically adjust when the theme changes between light and dark mode:

| Preset | Mode | Dark Mode Support |
|--------|------|-------------------|
| `glass-minimal` | Adaptive | Automatic dark mode overrides |
| `glass-bold` | Adaptive | Automatic dark mode overrides |

When using adaptive presets with the theme toggle:

```blade
{{-- Works automatically with theme toggle --}}
<div class="glass-minimal">
    <x-artisanpack-card>
        This card adapts to light/dark mode automatically
    </x-artisanpack-card>
</div>

<x-artisanpack-theme-toggle />
```

## Combining Presets with Glass Variants

Presets work seamlessly with the glass variant utility classes:

```blade
<div class="glass-frosted-light">
    {{-- Use base glass effect --}}
    <div class="glass">Base glass</div>

    {{-- Use frosted variant --}}
    <div class="glass-frosted">Enhanced frosted glass</div>

    {{-- Use liquid variant --}}
    <div class="glass-liquid">Liquid glass effect</div>

    {{-- Use transparent variant --}}
    <div class="glass-transparent">Subtle transparent glass</div>
</div>
```

## Customizing Presets

### Override Individual Tokens

You can override specific tokens while using a preset:

```css
/* Your custom CSS */
.glass-frosted-light.my-custom-card {
    --glass-blur: 20px; /* Override blur */
    --glass-opacity: 0.9; /* Override opacity */
}
```

### Create Custom Presets

Use the `GlassPresets` class programmatically to create variations:

```php
use ArtisanPack\LivewireUiComponents\Styling\GlassPresets;

$presets = new GlassPresets();

// Get tokens for a preset
$tokens = $presets->getPresetTokens('glass-minimal');

// Get preset metadata
$metadata = $presets->getPresetsMetadata();

// Generate CSS for a specific preset
$css = $presets->generatePresetCss('glass-bold');
```

## Configuration Options

### Preset Configuration

```php
// config/livewire-ui-components.php
'glass' => [
    'presets' => [
        // Enable/disable preset generation in theme CSS
        'enabled' => true,

        // Output path for standalone presets file
        'output_path' => resource_path('css/artisanpack-glass-presets.css'),

        // Default preset (null for no default)
        'default' => null,

        // Limit which presets to include (null includes all)
        'include' => ['glass-frosted-light', 'glass-minimal', 'glass-bold'],
    ],
],
```

### Including Presets in Your CSS

Import the generated presets file:

```css
/* resources/css/app.css */
@import './artisanpack-ui-theme.css';
@import './artisanpack-glass-presets.css';

@tailwind base;
@tailwind components;
@tailwind utilities;
```

Or include presets directly in the main theme file by running:

```bash
php artisan artisanpack:generate-theme --primary=blue
```

## Preset Token Reference

Each preset defines values for the following tokens:

### Base Tokens

| Token | Description |
|-------|-------------|
| `--glass-blur` | Base blur amount |
| `--glass-opacity` | Base opacity |
| `--glass-border-width` | Border thickness |
| `--glass-border-opacity` | Border transparency |
| `--glass-shadow-opacity` | Shadow intensity |

### Tint Tokens

| Token | Description |
|-------|-------------|
| `--glass-tint-color` | Color overlay |
| `--glass-tint-opacity` | Tint intensity |

### Frosted Variant Tokens

| Token | Description |
|-------|-------------|
| `--glass-frosted-blur` | Frosted blur amount |
| `--glass-frosted-opacity` | Frosted opacity |
| `--glass-frosted-saturation` | Color saturation boost |

### Liquid Variant Tokens

| Token | Description |
|-------|-------------|
| `--glass-liquid-blur` | Liquid blur amount |
| `--glass-liquid-opacity` | Liquid opacity |
| `--glass-liquid-refraction` | Light refraction effect |
| `--glass-liquid-border-glow` | Border glow intensity |

### Transparent Variant Tokens

| Token | Description |
|-------|-------------|
| `--glass-transparent-blur` | Transparent blur amount |
| `--glass-transparent-opacity` | Transparent opacity |

## Best Practices

### Choosing the Right Preset

1. **For professional/corporate apps**: Use `glass-frosted-light` or `glass-frosted-dark`
2. **For creative/portfolio sites**: Use `glass-liquid-light` or `glass-liquid-dark`
3. **For content-focused apps**: Use `glass-minimal`
4. **For marketing/landing pages**: Use `glass-bold`
5. **For apps with theme switching**: Use adaptive presets (`glass-minimal`, `glass-bold`)

### Performance Considerations

- Backdrop filters can be performance-intensive on older devices
- Use `glass-minimal` for performance-sensitive applications
- Limit the number of glass elements visible simultaneously
- Consider providing a reduced-motion alternative

### Accessibility

- `glass-minimal` is the most accessible preset with subtle effects
- Ensure sufficient contrast between text and glass backgrounds
- Test with users who have visual sensitivities
- Provide options to disable glass effects for users who need them

## Programmatic Access

### Getting Preset Information

```php
use ArtisanPack\LivewireUiComponents\Styling\GlassPresets;

$presets = new GlassPresets();

// List all presets
$available = $presets->getAvailablePresets();
// ['glass-frosted-light', 'glass-frosted-dark', ...]

// Check if a preset exists
$isValid = $presets->isValidPreset('glass-minimal');
// true

// Get preset description
$description = $presets->getPresetDescription('glass-bold');
// "Strong, prominent glass effect..."

// Get full metadata
$metadata = $presets->getPresetsMetadata();
```

### Generating CSS

```php
// Generate CSS for one preset (with selector)
$css = $presets->generatePresetCss('glass-frosted-light');

// Generate CSS without selector (for inline use)
$css = $presets->generatePresetCss('glass-frosted-light', false);

// Generate CSS for all presets
$allCss = $presets->generateAllPresetsCss();
```
