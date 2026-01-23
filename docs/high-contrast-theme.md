---
title: High Contrast Theme
---

# High Contrast Theme

ArtisanPack UI Livewire Components includes a comprehensive high-contrast theme system designed for accessibility. This system provides WCAG AAA compliant themes, automatic system preference detection, reduced motion support, enhanced focus indicators, and larger text options.

## Introduction

The high-contrast theme system is designed to meet the needs of users who require:

- **Maximum contrast ratios** for better readability
- **Enhanced focus indicators** for keyboard navigation
- **Reduced motion** for vestibular disorders
- **Larger text options** for visual impairments
- **System preference detection** via CSS media queries

### Key Features

- **WCAG AAA Compliance**: High-contrast presets achieve 7:1 contrast ratios for normal text
- **WCAG AA Compliance**: Enhanced contrast presets achieve 4.5:1 contrast ratios
- **Automatic Detection**: Respects `prefers-contrast` and `prefers-reduced-motion` media queries
- **Independent of Light/Dark Mode**: Works alongside your existing light/dark theme toggle
- **Screen Reader Friendly**: Semantic tokens and proper focus management

## Using the artisanpack:generate-theme Command

### Generate High-Contrast CSS Only

To generate only the high-contrast CSS file:

```bash
php artisan artisanpack:generate-theme --high-contrast-only
```

This creates a CSS file with all accessibility features at the configured output path (default: `resources/css/artisanpack-high-contrast.css`).

### Generate with Specific Accessibility Preset

Apply a specific preset as the default:

```bash
php artisan artisanpack:generate-theme --high-contrast-only --accessibility-preset=high-contrast-light
```

### Include High-Contrast with Full Theme

When generating your full theme, high-contrast CSS is included by default:

```bash
php artisan artisanpack:generate-theme --primary=blue --secondary=slate --accent=amber
```

This generates both your color theme and the high-contrast accessibility CSS.

### Exclude High-Contrast CSS

If you don't need the high-contrast file:

```bash
php artisan artisanpack:generate-theme --primary=blue --no-high-contrast
```

## Available Presets

| Preset | Description | WCAG Level | Mode |
|--------|-------------|------------|------|
| `high-contrast-light` | Maximum contrast on light backgrounds | AAA | Light |
| `high-contrast-dark` | Maximum contrast on dark backgrounds | AAA | Dark |
| `enhanced-contrast-light` | Improved contrast on light backgrounds | AA | Light |
| `enhanced-contrast-dark` | Improved contrast on dark backgrounds | AA | Dark |

### WCAG Compliance Levels

- **WCAG AAA**: 7:1 contrast ratio for normal text, 4.5:1 for large text
- **WCAG AA**: 4.5:1 contrast ratio for normal text, 3:1 for large text

## Importing the CSS

Import the high-contrast CSS file in your main CSS:

```css
/* resources/css/app.css */
@import './artisanpack-high-contrast.css';

@import "tailwindcss";

/* Your other styles */
```

## Using High-Contrast Presets

### Apply to Entire Page

Add the preset class to your HTML element:

```html
<html class="high-contrast-light">
    <body>
        <!-- All content inherits high-contrast tokens -->
    </body>
</html>
```

### Apply to Specific Sections

Apply presets to specific containers:

```html
<div class="high-contrast-dark p-6 rounded-lg">
    <h2>High Contrast Section</h2>
    <p>This section uses high-contrast dark mode.</p>
</div>
```

### Using Data Attributes

Toggle presets with data attributes for JavaScript-based switching:

```html
<html data-accessibility="high-contrast-light">
    <!-- Content -->
</html>
```

```javascript
// Toggle accessibility preset
function setAccessibilityPreset(preset) {
    document.documentElement.dataset.accessibility = preset;
    localStorage.setItem('accessibility-preset', preset);
}

// Load saved preference
const savedPreset = localStorage.getItem('accessibility-preset');
if (savedPreset) {
    setAccessibilityPreset(savedPreset);
}
```

## CSS Custom Properties

The high-contrast theme provides the following CSS custom properties:

### Color Tokens

```css
/* Base colors */
--hc-background: /* Background color */
--hc-foreground: /* Text color */
--hc-foreground-muted: /* Secondary text color */

/* Primary colors */
--hc-primary: /* Primary action color */
--hc-primary-foreground: /* Text on primary */
--hc-primary-hover: /* Primary hover state */
--hc-primary-active: /* Primary active state */

/* Secondary colors */
--hc-secondary: /* Secondary action color */
--hc-secondary-foreground: /* Text on secondary */

/* Accent colors */
--hc-accent: /* Accent color */
--hc-accent-foreground: /* Text on accent */

/* Semantic colors */
--hc-success: /* Success state */
--hc-warning: /* Warning state */
--hc-error: /* Error state */
--hc-info: /* Info state */

/* Border colors */
--hc-border: /* Default border */
--hc-border-muted: /* Subtle border */
--hc-border-focus: /* Focus border */

/* Surface colors */
--hc-surface: /* Card/panel background */
--hc-surface-elevated: /* Elevated surfaces */
--hc-surface-sunken: /* Sunken surfaces */
```

### Focus Indicator Tokens

```css
--hc-focus-ring-color: /* Focus ring color */
--hc-focus-ring-width: /* Focus ring width (default: 3px for AAA, 2px for AA) */
--hc-focus-ring-offset: /* Focus ring offset */
--hc-focus-ring-style: /* Focus ring style (solid) */
```

### Typography Tokens

```css
--hc-text-scale: /* Base text scale multiplier */
--hc-text-base: /* Base text size */
--hc-text-sm: /* Small text size */
--hc-text-lg: /* Large text size */
--hc-text-xl: /* Extra large text size */
--hc-line-height: /* Default line height */
--hc-letter-spacing: /* Default letter spacing */
```

### Accessibility Tokens

```css
--hc-min-target-size: /* Minimum interactive target size (44px) */
--hc-min-tap-target: /* Minimum tap target for touch (48px) */
--hc-disabled-opacity: /* Opacity for disabled elements */
```

## Automatic System Preference Detection

### prefers-contrast Media Query

The generated CSS automatically applies high-contrast styles when users have enabled high contrast in their system settings:

```css
@media (prefers-contrast: more) {
    :root {
        /* High contrast tokens applied automatically */
    }
}

@media (prefers-contrast: less) {
    :root {
        /* Enhanced contrast tokens for reduced contrast preference */
    }
}
```

This works with:
- Windows High Contrast Mode
- macOS Increase Contrast setting
- iOS/Android accessibility settings

### prefers-reduced-motion Support

Animations and transitions are automatically reduced when users request reduced motion:

```css
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}
```

## Enhanced Focus Indicators

The high-contrast theme includes enhanced focus indicators for keyboard navigation:

```css
.high-contrast-focus :focus-visible,
[data-high-contrast="true"] :focus-visible {
    outline: var(--hc-focus-ring-width) solid var(--hc-focus-ring-color);
    outline-offset: var(--hc-focus-ring-offset);
}
```

### Skip Link

A skip link component is included for keyboard users to bypass navigation:

```html
<a href="#main-content" class="skip-link">
    Skip to main content
</a>

<nav><!-- Navigation --></nav>

<main id="main-content">
    <!-- Main content -->
</main>
```

The skip link is visually hidden until focused:

```css
.skip-link {
    position: absolute;
    top: -100%;
    left: 0;
    z-index: 9999;
    /* ... */
}

.skip-link:focus {
    top: 0;
}
```

## Larger Text Options

Two text scaling classes are provided for users who need larger text:

### .text-larger (12.5% larger)

```html
<div class="text-larger">
    <p>This text is 12.5% larger than normal.</p>
</div>
```

### .text-extra-large (25% larger)

```html
<div class="text-extra-large">
    <p>This text is 25% larger than normal.</p>
</div>
```

### Implementing a Text Size Toggle

```html
<div class="flex gap-2">
    <button onclick="setTextSize('normal')" class="btn">Normal</button>
    <button onclick="setTextSize('larger')" class="btn">Larger</button>
    <button onclick="setTextSize('extra-large')" class="btn">Extra Large</button>
</div>

<script>
function setTextSize(size) {
    document.body.classList.remove('text-larger', 'text-extra-large');
    if (size !== 'normal') {
        document.body.classList.add(`text-${size}`);
    }
    localStorage.setItem('text-size', size);
}

// Load saved preference
const savedSize = localStorage.getItem('text-size');
if (savedSize && savedSize !== 'normal') {
    document.body.classList.add(`text-${savedSize}`);
}
</script>
```

## Interactive Mode

When running the theme generator in interactive mode, you can select accessibility presets:

```bash
php artisan artisanpack:generate-theme --interactive
```

The interactive wizard will prompt you to select an accessibility preset along with your color choices.

## JSON Export

When exporting theme configuration to JSON, accessibility settings are included:

```bash
php artisan artisanpack:generate-theme --primary=blue --accessibility-preset=high-contrast-light --json
```

The JSON file will include:

```json
{
    "version": "2.0.0",
    "generated": "2024-01-01T00:00:00+00:00",
    "colors": {
        "primary": "blue",
        "secondary": "slate",
        "accent": "amber"
    },
    "accessibility": {
        "preset": "high-contrast-light",
        "compliance": "AAA",
        "mode": "light",
        "tokens": {
            "hc-background": "#ffffff",
            "hc-foreground": "#000000",
            ...
        }
    }
}
```

## Configuration

Configure high-contrast settings in your published configuration file:

```php
// config/artisanpack/livewire-ui-components.php

return [
    // ... other settings ...

    'high_contrast' => [
        'enabled' => true,
        'output_path' => resource_path('css/artisanpack-high-contrast.css'),
    ],
];
```

## Best Practices

### 1. Always Test with Real Users

While these presets meet WCAG guidelines, always test with users who rely on accessibility features.

### 2. Don't Override System Preferences

The CSS respects system preferences via media queries. Avoid overriding these unless the user explicitly requests it.

### 3. Combine with Other Accessibility Features

High-contrast themes work best when combined with:
- Proper semantic HTML
- ARIA labels and roles
- Keyboard navigation support
- Screen reader testing

### 4. Provide User Controls

Give users the ability to toggle accessibility features:

```html
<div class="accessibility-controls">
    <label>
        <input type="checkbox" onchange="toggleHighContrast(this.checked)">
        High Contrast Mode
    </label>
    <label>
        <input type="checkbox" onchange="toggleLargerText(this.checked)">
        Larger Text
    </label>
    <label>
        <input type="checkbox" onchange="toggleReducedMotion(this.checked)">
        Reduce Motion
    </label>
</div>
```

### 5. Test Contrast Ratios

Use the built-in contrast verification:

```php
use ArtisanPack\LivewireUiComponents\Styling\HighContrastTheme;

$theme = new HighContrastTheme;

// Verify a color combination meets WCAG AAA
$isCompliant = $theme->verifyContrast('#000000', '#ffffff', 'AAA');

// Calculate the actual contrast ratio
$ratio = $theme->calculateContrastRatio('#000000', '#ffffff');
// Returns ~21.0 (maximum contrast)
```

## Troubleshooting

### High Contrast Not Applying

1. Ensure the CSS file is imported after your theme CSS
2. Check that the preset class or data attribute is applied to the correct element
3. Verify the CSS file was generated successfully

### System Preferences Not Detected

1. Check browser support for `prefers-contrast` and `prefers-reduced-motion`
2. Verify system accessibility settings are enabled
3. Some browsers may require a restart after changing system settings

### Focus Indicators Not Visible

1. Ensure the `.high-contrast-focus` or `[data-high-contrast="true"]` attribute is applied
2. Check for CSS that might be overriding the focus styles
3. Verify `--focus-ring-color` has sufficient contrast with the background
