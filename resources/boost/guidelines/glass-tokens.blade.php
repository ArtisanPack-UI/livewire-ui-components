## Glass Design Tokens

The Glass Design Token System provides foundational CSS custom properties for glassmorphism effects in ArtisanPack UI components. This system enables beautiful, modern glass effects with three distinct variants: frosted, liquid, and transparent.

### Overview

Glass tokens are CSS custom properties that control:
- Blur intensity for backdrop effects
- Opacity levels for glass surfaces
- Border styling and glow effects
- Color tinting with Tailwind color integration
- Dark mode adaptations

### Installation

Glass tokens are automatically included when you generate a theme:

@verbatim
<code-snippet name="Generate theme with glass tokens" lang="bash">
php artisan artisanpack:generate-theme
</code-snippet>
@endverbatim

To generate only the glass tokens:

@verbatim
<code-snippet name="Generate glass tokens only" lang="bash">
php artisan artisanpack:generate-theme --glass-only
</code-snippet>
@endverbatim

To exclude glass tokens from theme generation:

@verbatim
<code-snippet name="Generate theme without glass tokens" lang="bash">
php artisan artisanpack:generate-theme --no-glass
</code-snippet>
@endverbatim

### Publishing Glass Tokens

Publish the glass tokens CSS file for customization:

@verbatim
<code-snippet name="Publish glass tokens" lang="bash">
php artisan vendor:publish --tag=artisanpack-glass-tokens
</code-snippet>
@endverbatim

### Available Tokens

#### Base Glass Tokens

| Token | Default | Description |
|-------|---------|-------------|
| `--glass-blur` | `12px` | Blur intensity for base glass effect |
| `--glass-opacity` | `0.7` | Background opacity for glass surfaces |
| `--glass-border-width` | `1px` | Border width for glass elements |
| `--glass-border-opacity` | `0.2` | Border opacity |
| `--glass-shadow-opacity` | `0.1` | Shadow opacity for depth |

#### Tint Tokens

| Token | Default | Description |
|-------|---------|-------------|
| `--glass-tint-color` | `transparent` | Color tint for glass surface |
| `--glass-tint-opacity` | `0.15` | Opacity of the color tint overlay |

#### Frosted Variant

| Token | Default | Description |
|-------|---------|-------------|
| `--glass-frosted-blur` | `16px` | Higher blur for strong frosted effect |
| `--glass-frosted-opacity` | `0.8` | Higher opacity for prominent surface |
| `--glass-frosted-saturation` | `180%` | Backdrop saturation enhancement |

#### Liquid Variant

| Token | Default | Description |
|-------|---------|-------------|
| `--glass-liquid-blur` | `24px` | Maximum blur for smooth liquid effect |
| `--glass-liquid-opacity` | `0.6` | Lower opacity for transparency |
| `--glass-liquid-refraction` | `0.5` | Refraction effect intensity (0-1) |
| `--glass-liquid-border-glow` | `0.3` | Border glow intensity (0-1) |

#### Transparent Variant

| Token | Default | Description |
|-------|---------|-------------|
| `--glass-transparent-blur` | `8px` | Minimal blur for subtle effect |
| `--glass-transparent-opacity` | `0.3` | Low opacity for maximum transparency |

### Using Glass Utility Classes

Apply glass effects directly with utility classes:

@verbatim
<code-snippet name="Glass utility classes" lang="blade">
<!-- Base glass effect -->
<div class="glass rounded-lg p-4">
    Base glass content
</div>

<!-- Frosted glass variant -->
<div class="glass-frosted rounded-lg p-4">
    Frosted glass content
</div>

<!-- Liquid glass variant -->
<div class="glass-liquid rounded-lg p-4">
    Liquid glass content
</div>

<!-- Transparent glass variant -->
<div class="glass-transparent rounded-lg p-4">
    Transparent glass content
</div>
</code-snippet>
@endverbatim

### Tinted Glass Effects

Add color tints to glass surfaces:

@verbatim
<code-snippet name="Tinted glass examples" lang="blade">
<!-- Primary tinted glass -->
<div class="glass glass-tint-primary rounded-lg p-4">
    Primary tinted content
</div>

<!-- Success tinted frosted glass -->
<div class="glass-frosted glass-tint-success rounded-lg p-4">
    Success tinted frosted content
</div>

<!-- Custom tint opacity -->
<div class="glass glass-tint-accent glass-tint-opacity-30 rounded-lg p-4">
    30% accent tinted content
</div>
</code-snippet>
@endverbatim

Available tint classes:
- `glass-tint-primary`
- `glass-tint-secondary`
- `glass-tint-accent`
- `glass-tint-success`
- `glass-tint-warning`
- `glass-tint-error`
- `glass-tint-info`

Tint opacity modifiers: `glass-tint-opacity-10` through `glass-tint-opacity-100` (in increments of 10)

### Tailwind CSS v4 Integration

Override glass tokens using the `@theme` directive in your `app.css`:

@verbatim
<code-snippet name="Tailwind @theme customization" lang="css">
@import 'tailwindcss';
@import './artisanpack-ui-theme.css';

@theme {
    /* Customize base glass tokens */
    --glass-blur: 16px;
    --glass-opacity: 0.8;

    /* Customize frosted variant */
    --glass-frosted-blur: 20px;
    --glass-frosted-saturation: 200%;

    /* Customize liquid variant */
    --glass-liquid-border-glow: 0.5;
}
</code-snippet>
@endverbatim

### Configuration

Customize glass token defaults in your configuration file:

@verbatim
<code-snippet name="Config customization" lang="php">
// config/artisanpack/livewire-ui-components.php
return [
    'glass' => [
        'enabled' => true,
        'output_path' => resource_path('css/artisanpack-glass-tokens.css'),
        'tokens' => [
            'blur' => '14px',
            'opacity' => '0.75',
            'frosted-blur' => '18px',
            'liquid-border-glow' => '0.4',
        ],
    ],
];
</code-snippet>
@endverbatim

### Dark Mode Support

Glass tokens automatically adapt to dark mode when using `[data-theme="dark"]`:

| Token | Light Mode | Dark Mode |
|-------|------------|-----------|
| `--glass-frosted-opacity` | `0.8` | `0.7` |
| `--glass-border-opacity` | `0.2` | `0.15` |
| `--glass-shadow-opacity` | `0.1` | `0.2` |

The computed glass colors also adjust automatically:
- Light mode: White-based glass surfaces
- Dark mode: Dark gray-based glass surfaces

### Best Practices

1. **Use appropriate variants** - Choose the glass variant that matches your design intent:
   - `glass`: Standard glass effect for most use cases
   - `glass-frosted`: Premium, opaque surfaces like cards and modals
   - `glass-liquid`: Fluid, modern surfaces with glow effects
   - `glass-transparent`: Subtle overlays and backgrounds

2. **Consider contrast** - Ensure text remains readable on glass surfaces by using appropriate text colors

3. **Layer appropriately** - Glass effects work best over colorful or image backgrounds

4. **Performance** - Backdrop filters can be performance-intensive; use sparingly on mobile devices

5. **Fallback support** - The glass classes include `-webkit-backdrop-filter` for Safari compatibility

### Component Integration

Glass effects will be integrated into components in future releases:

@verbatim
<code-snippet name="Future component glass props" lang="blade">
<!-- Coming in v2.0 component updates -->
<x-artisanpack-card glass="frosted">
    Frosted glass card
</x-artisanpack-card>

<x-artisanpack-modal glass="liquid" glass-tint="primary" glass-tint-opacity="20">
    Liquid glass modal with primary tint
</x-artisanpack-modal>
</code-snippet>
@endverbatim
