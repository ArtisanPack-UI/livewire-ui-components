## Design Token System

The Design Token System provides foundational CSS custom properties for ArtisanPack UI components. This comprehensive system enables consistent styling across typography, spacing, borders, shadows, animations, and glass effects.

### Overview

Design tokens are CSS custom properties that provide:
- Consistent typography (fonts, sizes, weights, line heights)
- Predictable spacing scale (0 through 96)
- Unified border radius system
- Elevation/shadow system with semantic variants
- Animation timing and easing functions
- Glass/glassmorphism effects

### Installation

Design tokens are automatically included when you generate a theme:

@verbatim
<code-snippet name="Generate theme with design tokens" lang="bash">
php artisan artisanpack:generate-theme
</code-snippet>
@endverbatim

To generate only the design tokens:

@verbatim
<code-snippet name="Generate design tokens only" lang="bash">
php artisan artisanpack:generate-theme --tokens-only
</code-snippet>
@endverbatim

To exclude design tokens from theme generation:

@verbatim
<code-snippet name="Generate theme without design tokens" lang="bash">
php artisan artisanpack:generate-theme --no-tokens
</code-snippet>
@endverbatim

### Publishing Design Tokens

Publish the design tokens CSS file for customization:

@verbatim
<code-snippet name="Publish design tokens" lang="bash">
php artisan vendor:publish --tag=artisanpack-design-tokens
</code-snippet>
@endverbatim

---

## Typography Tokens

Typography tokens define the typographic scale and font properties for consistent text styling.

### Font Families

| Token | Default | Description |
|-------|---------|-------------|
| `--font-family-sans` | `ui-sans-serif, system-ui, ...` | Sans-serif font stack |
| `--font-family-serif` | `ui-serif, Georgia, ...` | Serif font stack |
| `--font-family-mono` | `ui-monospace, SFMono-Regular, ...` | Monospace font stack |

### Font Sizes

| Token | Default | Pixels | Description |
|-------|---------|--------|-------------|
| `--font-size-xs` | `0.75rem` | 12px | Extra small text |
| `--font-size-sm` | `0.875rem` | 14px | Small text |
| `--font-size-base` | `1rem` | 16px | Base/body text |
| `--font-size-lg` | `1.125rem` | 18px | Large text |
| `--font-size-xl` | `1.25rem` | 20px | Extra large text |
| `--font-size-2xl` | `1.5rem` | 24px | 2x large (h3) |
| `--font-size-3xl` | `1.875rem` | 30px | 3x large (h2) |
| `--font-size-4xl` | `2.25rem` | 36px | 4x large (h1) |
| `--font-size-5xl` | `3rem` | 48px | 5x large (display) |
| `--font-size-6xl` | `3.75rem` | 60px | 6x large (hero) |

### Font Weights

| Token | Default | Description |
|-------|---------|-------------|
| `--font-weight-thin` | `100` | Thin weight |
| `--font-weight-extralight` | `200` | Extra light weight |
| `--font-weight-light` | `300` | Light weight |
| `--font-weight-normal` | `400` | Normal weight |
| `--font-weight-medium` | `500` | Medium weight |
| `--font-weight-semibold` | `600` | Semibold weight |
| `--font-weight-bold` | `700` | Bold weight |
| `--font-weight-extrabold` | `800` | Extra bold weight |
| `--font-weight-black` | `900` | Black weight |

### Line Heights

| Token | Default | Description |
|-------|---------|-------------|
| `--line-height-none` | `1` | No extra line height |
| `--line-height-tight` | `1.25` | Tight line spacing |
| `--line-height-snug` | `1.375` | Snug line spacing |
| `--line-height-normal` | `1.5` | Normal line spacing |
| `--line-height-relaxed` | `1.625` | Relaxed line spacing |
| `--line-height-loose` | `2` | Loose line spacing |

### Letter Spacing

| Token | Default | Description |
|-------|---------|-------------|
| `--letter-spacing-tighter` | `-0.05em` | Tighter tracking |
| `--letter-spacing-tight` | `-0.025em` | Tight tracking |
| `--letter-spacing-normal` | `0em` | Normal tracking |
| `--letter-spacing-wide` | `0.025em` | Wide tracking |
| `--letter-spacing-wider` | `0.05em` | Wider tracking |
| `--letter-spacing-widest` | `0.1em` | Widest tracking |

### Typography Usage Examples

@verbatim
<code-snippet name="Typography token usage" lang="css">
.heading {
    font-family: var(--font-family-sans);
    font-size: var(--font-size-3xl);
    font-weight: var(--font-weight-bold);
    line-height: var(--line-height-tight);
    letter-spacing: var(--letter-spacing-tight);
}

.body-text {
    font-family: var(--font-family-sans);
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-normal);
    line-height: var(--line-height-normal);
}

.code-block {
    font-family: var(--font-family-mono);
    font-size: var(--font-size-sm);
}
</code-snippet>
@endverbatim

---

## Spacing Tokens

Spacing tokens provide a consistent scale based on 0.25rem (4px) increments.

### Available Spacing Values

| Token | Default | Pixels | Description |
|-------|---------|--------|-------------|
| `--spacing-0` | `0` | 0px | No spacing |
| `--spacing-px` | `1px` | 1px | 1 pixel |
| `--spacing-0-5` | `0.125rem` | 2px | Half unit |
| `--spacing-1` | `0.25rem` | 4px | 1 unit |
| `--spacing-1-5` | `0.375rem` | 6px | 1.5 units |
| `--spacing-2` | `0.5rem` | 8px | 2 units |
| `--spacing-3` | `0.75rem` | 12px | 3 units |
| `--spacing-4` | `1rem` | 16px | 4 units |
| `--spacing-5` | `1.25rem` | 20px | 5 units |
| `--spacing-6` | `1.5rem` | 24px | 6 units |
| `--spacing-8` | `2rem` | 32px | 8 units |
| `--spacing-10` | `2.5rem` | 40px | 10 units |
| `--spacing-12` | `3rem` | 48px | 12 units |
| `--spacing-16` | `4rem` | 64px | 16 units |
| `--spacing-20` | `5rem` | 80px | 20 units |
| `--spacing-24` | `6rem` | 96px | 24 units |
| `--spacing-32` | `8rem` | 128px | 32 units |
| `--spacing-40` | `10rem` | 160px | 40 units |
| `--spacing-48` | `12rem` | 192px | 48 units |
| `--spacing-64` | `16rem` | 256px | 64 units |
| `--spacing-80` | `20rem` | 320px | 80 units |
| `--spacing-96` | `24rem` | 384px | 96 units |

### Spacing Usage Examples

@verbatim
<code-snippet name="Spacing token usage" lang="css">
.card {
    padding: var(--spacing-4);
    margin-bottom: var(--spacing-6);
}

.card-header {
    padding: var(--spacing-3) var(--spacing-4);
    margin-bottom: var(--spacing-4);
}

.button-group {
    gap: var(--spacing-2);
}

.section {
    padding-top: var(--spacing-16);
    padding-bottom: var(--spacing-16);
}
</code-snippet>
@endverbatim

---

## Border Radius Tokens

Border radius tokens define a consistent rounded corner system.

### Available Radius Values

| Token | Default | Pixels | Description |
|-------|---------|--------|-------------|
| `--radius-none` | `0` | 0px | No rounding |
| `--radius-sm` | `0.125rem` | 2px | Small rounding |
| `--radius-base` | `0.25rem` | 4px | Base rounding |
| `--radius-md` | `0.375rem` | 6px | Medium rounding |
| `--radius-lg` | `0.5rem` | 8px | Large rounding |
| `--radius-xl` | `0.75rem` | 12px | Extra large rounding |
| `--radius-2xl` | `1rem` | 16px | 2x large rounding |
| `--radius-3xl` | `1.5rem` | 24px | 3x large rounding |
| `--radius-full` | `9999px` | - | Full rounding (pill) |

### Border Radius Usage Examples

@verbatim
<code-snippet name="Border radius token usage" lang="css">
.button {
    border-radius: var(--radius-md);
}

.card {
    border-radius: var(--radius-lg);
}

.avatar {
    border-radius: var(--radius-full);
}

.modal {
    border-radius: var(--radius-xl);
}
</code-snippet>
@endverbatim

---

## Shadow Tokens

Shadow tokens define an elevation system for creating depth effects.

### Standard Shadows

| Token | Description |
|-------|-------------|
| `--shadow-none` | No shadow |
| `--shadow-sm` | Small shadow for subtle elevation |
| `--shadow-base` | Base shadow for cards and containers |
| `--shadow-md` | Medium shadow for dropdowns |
| `--shadow-lg` | Large shadow for modals |
| `--shadow-xl` | Extra large shadow for dialogs |
| `--shadow-2xl` | Maximum shadow for overlays |
| `--shadow-inner` | Inset shadow for pressed states |

### Colored Shadows

| Token | Color | Description |
|-------|-------|-------------|
| `--shadow-primary` | Blue | Primary action emphasis |
| `--shadow-success` | Green | Success state emphasis |
| `--shadow-warning` | Orange | Warning state emphasis |
| `--shadow-error` | Red | Error state emphasis |
| `--shadow-info` | Sky | Info state emphasis |

### Glow Effects

| Token | Description |
|-------|-------------|
| `--shadow-glow-sm` | Small glow effect |
| `--shadow-glow-md` | Medium glow effect |
| `--shadow-glow-lg` | Large glow effect |

### Dark Mode Shadows

Shadows automatically adjust in dark mode with increased opacity for better visibility against dark backgrounds.

### Shadow Usage Examples

@verbatim
<code-snippet name="Shadow token usage" lang="css">
.card {
    box-shadow: var(--shadow-base);
}

.card:hover {
    box-shadow: var(--shadow-md);
}

.dropdown {
    box-shadow: var(--shadow-lg);
}

.modal {
    box-shadow: var(--shadow-xl);
}

.button-primary:focus {
    box-shadow: var(--shadow-glow-md);
}

.success-indicator {
    box-shadow: var(--shadow-success);
}
</code-snippet>
@endverbatim

---

## Animation Tokens

Animation tokens define durations and easing functions for smooth transitions.

### Durations

| Token | Default | Description |
|-------|---------|-------------|
| `--duration-0` | `0ms` | No duration |
| `--duration-75` | `75ms` | Ultra fast |
| `--duration-100` | `100ms` | Very fast |
| `--duration-150` | `150ms` | Fast |
| `--duration-200` | `200ms` | Standard |
| `--duration-300` | `300ms` | Medium |
| `--duration-500` | `500ms` | Slow |
| `--duration-700` | `700ms` | Very slow |
| `--duration-1000` | `1000ms` | Ultra slow |

### Standard Easing Functions

| Token | Value | Description |
|-------|-------|-------------|
| `--ease-linear` | `linear` | Constant speed |
| `--ease-in` | `cubic-bezier(0.4, 0, 1, 1)` | Accelerate |
| `--ease-out` | `cubic-bezier(0, 0, 0.2, 1)` | Decelerate |
| `--ease-in-out` | `cubic-bezier(0.4, 0, 0.2, 1)` | Accelerate then decelerate |

### Expressive Easing Functions

| Token | Value | Description |
|-------|-------|-------------|
| `--ease-spring` | `cubic-bezier(0.175, 0.885, 0.32, 1.275)` | Spring overshoot effect |
| `--ease-bounce` | `cubic-bezier(0.68, -0.55, 0.265, 1.55)` | Bounce effect |
| `--ease-elastic` | `cubic-bezier(0.68, -0.6, 0.32, 1.6)` | Elastic overshoot |

### Transition Presets

| Token | Description |
|-------|-------------|
| `--transition-none` | No transition |
| `--transition-all` | All properties with standard timing |
| `--transition-default` | Common interactive properties |
| `--transition-colors` | Color-related properties |
| `--transition-opacity` | Opacity only |
| `--transition-shadow` | Box shadow only |
| `--transition-transform` | Transform only |

### Animation Usage Examples

@verbatim
<code-snippet name="Animation token usage" lang="css">
.button {
    transition-property: var(--transition-colors);
    transition-duration: var(--duration-200);
    transition-timing-function: var(--ease-in-out);
}

.modal {
    transition-property: opacity, transform;
    transition-duration: var(--duration-300);
    transition-timing-function: var(--ease-out);
}

.bouncy-button:hover {
    transition-timing-function: var(--ease-spring);
    transform: scale(1.05);
}

.fade-in {
    transition-property: var(--transition-opacity);
    transition-duration: var(--duration-200);
}
</code-snippet>
@endverbatim

---

## Glass Tokens

Glass tokens enable glassmorphism effects with blur, opacity, and tinting. See the dedicated [Glass Tokens Documentation](glass-tokens.md) for comprehensive details.

### Quick Reference

| Variant | Token Prefix | Description |
|---------|--------------|-------------|
| Base | `--glass-*` | Standard glass effect |
| Frosted | `--glass-frosted-*` | Higher blur, more opaque |
| Liquid | `--glass-liquid-*` | Maximum blur with glow |
| Transparent | `--glass-transparent-*` | Subtle, low opacity |

---

## Tailwind CSS v4 Integration

Override design tokens using the `@theme` directive in your `app.css`:

@verbatim
<code-snippet name="Tailwind @theme customization" lang="css">
@import 'tailwindcss';
@import './artisanpack-design-tokens.css';

@theme {
    /* Typography customization */
    --font-size-base: 1.125rem;
    --font-family-sans: "Inter", ui-sans-serif, system-ui, sans-serif;

    /* Spacing customization */
    --spacing-4: 1.25rem;

    /* Radius customization */
    --radius-lg: 1rem;

    /* Shadow customization */
    --shadow-lg: 0 12px 20px -4px rgb(0 0 0 / 0.15);

    /* Animation customization */
    --duration-200: 250ms;
    --ease-in-out: cubic-bezier(0.45, 0, 0.55, 1);
}
</code-snippet>
@endverbatim

---

## Configuration

Customize design token defaults in your configuration file:

@verbatim
<code-snippet name="Config customization" lang="php">
// config/artisanpack/livewire-ui-components.php
return [
    'design_tokens' => [
        'enabled' => true,
        'output_path' => resource_path('css/artisanpack-design-tokens.css'),

        'typography' => [
            'font-size-base' => '1.125rem',
        ],

        'spacing' => [
            'spacing-4' => '1.25rem',
        ],

        'radius' => [
            'radius-lg' => '1rem',
        ],

        'shadow' => [
            // Custom shadows
        ],

        'animation' => [
            'duration-200' => '250ms',
        ],
    ],
];
</code-snippet>
@endverbatim

---

## Utility Classes

The design tokens file includes ready-to-use utility classes:

### Typography Utilities
- `.font-sans` - Apply sans-serif font family
- `.font-serif` - Apply serif font family
- `.font-mono` - Apply monospace font family

### Glass Utilities
- `.glass` - Base glass effect
- `.glass-frosted` - Frosted glass effect
- `.glass-liquid` - Liquid glass effect
- `.glass-transparent` - Transparent glass effect

### Shadow Utilities
- `.shadow-glow-sm` - Small glow effect
- `.shadow-glow` - Medium glow effect
- `.shadow-glow-lg` - Large glow effect

### Transition Utilities
- `.transition-smooth` - Standard smooth transition
- `.transition-fast` - Fast transition (100ms)
- `.transition-slow` - Slow transition (500ms)
- `.transition-spring` - Spring easing transition
- `.transition-bounce` - Bounce easing transition

---

## Best Practices

1. **Use tokens consistently** - Always use design tokens instead of hardcoded values for maintainability

2. **Respect the scale** - Use spacing tokens that exist in the scale rather than arbitrary values

3. **Consider dark mode** - Shadow tokens automatically adjust for dark mode, but test your implementations

4. **Layer animations** - Combine duration and easing tokens for polished interactions

5. **Typography hierarchy** - Use the font size scale to establish clear visual hierarchy

6. **Semantic shadows** - Use colored shadows (primary, success, error) for meaningful feedback

---

## Component Integration

Design tokens are integrated into ArtisanPack UI components:

@verbatim
<code-snippet name="Component design token integration" lang="blade">
<!-- Components automatically use design tokens -->
<x-artisanpack-card class="shadow-lg">
    Card with design token shadow
</x-artisanpack-card>

<x-artisanpack-button class="transition-smooth">
    Button with smooth transition
</x-artisanpack-button>

<x-artisanpack-modal class="glass-frosted">
    Modal with frosted glass effect
</x-artisanpack-modal>
</code-snippet>
@endverbatim
