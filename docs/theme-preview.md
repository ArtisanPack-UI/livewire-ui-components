---
title: Theme Preview Tool
---

# Theme Preview Tool

ArtisanPack UI Livewire Components includes a browser-based theme preview tool that enables real-time color adjustment, component preview gallery, glass effect customization, and export functionality.

## Introduction

The Theme Preview Tool is designed to help developers and designers:

- **Preview themes in real-time** without writing any code
- **Explore component gallery** with live theme updates
- **Customize glass effects** with various presets and tint options
- **Apply accessibility presets** for WCAG compliance
- **Export configurations** as CSS or JSON
- **Share preview URLs** with team members

## Accessing the Theme Preview Tool

The theme preview tool is available at `/artisanpack/theme-preview` route:

```bash
# Access the preview tool
https://your-app.test/artisanpack/theme-preview
```

You can also access it via the named route:

```php
route('artisanpack.theme-preview')
```

## Features

### Real-Time Color Adjustment

Choose from Tailwind CSS colors or enter custom hex values for primary, secondary, and accent colors.

**Tailwind Color Selection:**
- Click on color swatches to select from 21 Tailwind colors
- Instantly see changes reflected across all preview components

**Custom Hex Colors:**
1. Toggle "Use Custom Hex Colors" checkbox
2. Use the color picker or enter hex values directly
3. Colors update in real-time as you type

### Component Preview Gallery

The preview gallery includes components organized into categories:

| Category | Components |
|----------|------------|
| Form | button, input, checkbox, select, toggle |
| Feedback | alert, badge, progress |
| Layout | card, modal |
| Data | table, stat |

Click on any component name to preview it with your current theme settings.

### Glass Effect Customization

Apply glass effects to see how your theme looks with glassmorphism:

**Available Glass Presets:**
- `glass-frosted-light` - Light mode frosted glass
- `glass-frosted-dark` - Dark mode frosted glass
- `glass-liquid-light` - Light mode liquid glass
- `glass-liquid-dark` - Dark mode liquid glass
- `glass-minimal` - Subtle glass effects
- `glass-bold` - Strong glass effects

**Tint Options:**
- Select a tint color from the Tailwind palette
- Adjust tint intensity from 0% to 100%

### Accessibility Presets

Apply accessibility presets to ensure WCAG compliance:

| Preset | Compliance | Description |
|--------|------------|-------------|
| `high-contrast-light` | WCAG AAA | Maximum contrast on light backgrounds |
| `high-contrast-dark` | WCAG AAA | Maximum contrast on dark backgrounds |
| `enhanced-contrast-light` | WCAG AA | Improved contrast on light |
| `enhanced-contrast-dark` | WCAG AA | Improved contrast on dark |

### Dark Mode Preview

Toggle dark mode to preview your theme in both light and dark contexts.

## Exporting Your Theme

### Download CSS

Click "Download CSS" to export the generated CSS variables:

```css
:root {
    --color-primary-50: #eff6ff;
    --color-primary-100: #dbeafe;
    /* ... more variables ... */
}
```

### Download JSON

Export your complete theme configuration as JSON:

```json
{
    "version": "2.0.0",
    "generated": "2024-01-01T00:00:00+00:00",
    "colors": {
        "primary": "blue",
        "secondary": "slate",
        "accent": "amber"
    },
    "glass": {
        "preset": "glass-frosted-light",
        "tint": {
            "color": "blue",
            "intensity": 0.15
        }
    },
    "accessibility": {
        "preset": "high-contrast-light"
    },
    "preview_url": "https://your-app.test/artisanpack/theme-preview?..."
}
```

### Artisan Command

Copy the generated artisan command to apply your theme programmatically:

```bash
php artisan artisanpack:generate-theme --primary=blue --secondary=slate --accent=amber --preset=glass-frosted-light
```

## Shareable Preview URLs

Share your theme configuration with team members using the auto-generated URL:

```
https://your-app.test/artisanpack/theme-preview?primary=blue&secondary=slate&accent=amber&preset=glass-frosted-light&dark=1
```

**URL Parameters:**

| Parameter | Description | Example |
|-----------|-------------|---------|
| `primary` | Tailwind color name | `blue`, `sky`, `indigo` |
| `secondary` | Tailwind color name | `slate`, `gray`, `zinc` |
| `accent` | Tailwind color name | `amber`, `orange`, `red` |
| `primary_hex` | Custom hex (without #) | `ff5733` |
| `secondary_hex` | Custom hex | `64748b` |
| `accent_hex` | Custom hex | `f59e0b` |
| `preset` | Glass preset name | `glass-frosted-light` |
| `tint` | Tint color | `blue` |
| `tint_intensity` | Tint intensity (0-1) | `0.15` |
| `accessibility` | Accessibility preset | `high-contrast-light` |
| `dark` | Dark mode | `1` for enabled |

## Using the Livewire Component Directly

You can also embed the theme preview component in your own pages:

```blade
<livewire:theme-preview />
```

Or with initial values:

```blade
<livewire:theme-preview
    :primary-color="'blue'"
    :secondary-color="'slate'"
    :accent-color="'amber'"
/>
```

## API Reference

### Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `primaryColor` | string | `'sky'` | Primary Tailwind color name |
| `secondaryColor` | string | `'slate'` | Secondary Tailwind color name |
| `accentColor` | string | `'amber'` | Accent Tailwind color name |
| `primaryHex` | string | `''` | Custom primary hex color |
| `secondaryHex` | string | `''` | Custom secondary hex color |
| `accentHex` | string | `''` | Custom accent hex color |
| `useCustomColors` | bool | `false` | Use custom hex colors |
| `glassPreset` | string | `''` | Selected glass preset |
| `tintColor` | string | `'transparent'` | Glass tint color |
| `tintIntensity` | float | `0.15` | Tint intensity (0-1) |
| `accessibilityPreset` | string | `''` | Accessibility preset |
| `darkMode` | bool | `false` | Dark mode toggle |
| `activeSection` | string | `'colors'` | Active control section |
| `selectedComponent` | string | `'button'` | Selected preview component |

### Computed Properties

| Property | Description |
|----------|-------------|
| `effectivePrimaryColor` | Returns hex color (custom or Tailwind) |
| `effectiveSecondaryColor` | Returns hex color (custom or Tailwind) |
| `effectiveAccentColor` | Returns hex color (custom or Tailwind) |
| `shareableUrl` | URL with all current settings |
| `generatedCss` | Full CSS output |
| `previewCssVariables` | CSS variables for live preview |
| `artisanCommand` | Artisan command for current settings |
| `glassPresets` | Available glass presets |
| `accessibilityPresets` | Available accessibility presets |
| `colorOptions` | Formatted color options |
| `tintColorOptions` | Formatted tint color options |

### Methods

| Method | Description |
|--------|-------------|
| `exportJson()` | Returns theme configuration as array |
| `downloadJson()` | Triggers JSON file download |
| `downloadCss()` | Triggers CSS file download |
| `copyShareableUrl()` | Copies URL to clipboard |
| `resetTheme()` | Resets all settings to defaults |

## Best Practices

### 1. Start with Tailwind Colors

Begin by selecting Tailwind colors to establish a color palette, then fine-tune with custom hex if needed.

### 2. Test All Components

Click through each component category to ensure your theme works well across all UI elements.

### 3. Check Accessibility

Apply an accessibility preset to verify your theme meets WCAG guidelines.

### 4. Preview Dark Mode

Always toggle dark mode to ensure your theme works in both contexts.

### 5. Export and Apply

Once satisfied, export the CSS and integrate it into your application:

```css
/* resources/css/app.css */
@import './artisanpack-theme.css';

@import "tailwindcss";
```

## Troubleshooting

### Colors Not Updating

1. Ensure JavaScript is enabled in your browser
2. Check for browser console errors
3. Verify Livewire is properly configured

### Export Not Working

1. Check browser popup blockers
2. Verify the download permissions in your browser
3. Try using a different browser

### Route Not Found

1. Ensure the package routes are loaded
2. Check your route prefix configuration
3. Clear route cache: `php artisan route:clear`

### Glass Effects Not Visible

1. Make sure you've selected a glass preset
2. Verify your browser supports CSS backdrop-filter
3. Check that no parent elements have overflow:hidden

## Configuration

The theme preview route prefix can be configured in your published configuration:

```php
// config/artisanpack/livewire-ui-components.php

return [
    'route_prefix' => '', // Add a prefix if needed
    // ... other settings
];
```
