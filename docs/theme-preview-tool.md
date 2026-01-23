---
title: Theme Preview Tool
---

# Theme Preview Tool

The Theme Preview Tool is a browser-based theme customization interface that provides real-time preview and export capabilities for custom theme configurations. It's designed for development and testing environments to help you create and visualize custom themes before deploying them to production.

## Overview

The Theme Preview Tool allows you to:

- **Live Preview**: See theme changes in real-time with interactive component examples
- **Color Customization**: Adjust primary, secondary, and accent colors with visual color pickers
- **Glass Effects**: Fine-tune backdrop blur, opacity, saturation, and tint intensity
- **URL Sharing**: Share theme configurations via URL parameters
- **Export Options**: Export themes as CSS or JSON for easy integration

## Security Notice

**IMPORTANT:** The Theme Preview route is disabled by default for production safety. This tool should only be enabled in development/local environments.

## Installation & Setup

### Enable the Route

The theme preview route is controlled by an environment variable. To enable it:

1. Add to your `.env` file:

```env
ARTISANPACK_ENABLE_THEME_PREVIEW=true
```

2. Clear your configuration cache:

```bash
php artisan config:clear
```

3. Access the tool at: `/v2-features/theme-preview`

**Production Warning**: Always set `ARTISANPACK_ENABLE_THEME_PREVIEW=false` (or remove the variable entirely) in production environments to prevent public access.

### Configuration

The route is registered in your `config/artisanpack.php`:

```php
'livewire-ui-components' => [
    'development' => [
        'enable_theme_preview' => env('ARTISANPACK_ENABLE_THEME_PREVIEW', false),
    ],
],
```

## Using the Theme Preview Tool

### Accessing the Tool

Once enabled, navigate to:

```
https://your-app.test/v2-features/theme-preview
```

### Color Customization

The tool provides three color pickers:

- **Primary Color**: Main brand color used for primary buttons, links, and accents
- **Secondary Color**: Supporting color for secondary UI elements
- **Accent Color**: Highlight color for call-to-action elements

Each color picker features:
- Visual color swatch showing the current color
- Text input for manual hex code entry
- Click the swatch to open the native color picker

### Glass Effect Settings

Fine-tune glass morphism effects with these controls:

#### Blur Amount (0-30px)
Controls the backdrop blur intensity. Higher values create more pronounced glass effects.

**Recommended ranges:**
- Subtle glass: 6-10px
- Medium glass: 12-16px
- Strong glass: 18-24px

#### Opacity (0-1)
Controls the transparency of the glass surface.

**Recommended ranges:**
- Light themes: 0.7-0.85
- Dark themes: 0.6-0.8

#### Saturation (0-300%)
Controls color saturation through the glass.

**Recommended ranges:**
- Natural look: 100-150%
- Vibrant colors: 180-220%

#### Tint Intensity (0-0.5)
Controls how much the primary color tints the glass surface.

**Recommended ranges:**
- Subtle tint: 0.05-0.15
- Visible tint: 0.2-0.35

### Live Preview

The tool displays a live preview section showing:

- Buttons styled with primary, secondary, and accent colors
- Cards with glass effects applied
- Interactive elements demonstrating hover states
- Real-time updates as you adjust settings

### Sharing Configurations

#### Copy Share URL

Click the "Copy Share URL" button to generate a URL containing all current theme settings:

```
https://your-app.test/v2-features/theme-preview?primary=%236366f1&secondary=%238b5cf6&accent=%23ec4899&blur=12&opacity=0.7&saturation=180
```

Share this URL with team members to show exact theme configurations.

#### URL Parameters

The tool supports these URL parameters for pre-populating settings:

- `primary` - Primary color hex code (e.g., `%236366f1`)
- `secondary` - Secondary color hex code
- `accent` - Accent color hex code
- `blur` - Blur amount in pixels
- `opacity` - Opacity value (0-1)
- `saturation` - Saturation percentage

### Exporting Themes

#### CSS Export

Exports theme as CSS custom properties:

```css
[data-theme="custom"] {
    /* Colors */
    --primary: #6366f1;
    --secondary: #8b5cf6;
    --accent: #ec4899;

    /* Glass */
    --glass-blur: 12px;
    --glass-opacity: 0.7;
    --glass-saturation: 180%;
    --glass-tint-intensity: 0.15;
}
```

**Usage:**
1. Click "Export as CSS"
2. Save the downloaded `theme.css` file
3. Include in your application's CSS
4. Apply `data-theme="custom"` to elements

#### JSON Export

Exports theme as structured JSON:

```json
{
  "colors": {
    "primary": "#6366f1",
    "secondary": "#8b5cf6",
    "accent": "#ec4899"
  },
  "glass": {
    "blur": 12,
    "opacity": 0.7,
    "saturation": 180,
    "tintIntensity": 0.15
  }
}
```

**Usage:**
1. Click "Export as JSON"
2. Save the downloaded `theme.json` file
3. Parse and apply programmatically in your application

### Reset to Defaults

Click "Reset to Defaults" to restore the original theme settings:

- Primary: `#6366f1` (Indigo)
- Secondary: `#8b5cf6` (Violet)
- Accent: `#ec4899` (Pink)
- Blur: 12px
- Opacity: 0.7
- Saturation: 180%
- Tint Intensity: 0.15

## Integration with Applications

### Applying Exported Themes

#### Using CSS Export

1. Export your theme as CSS
2. Add to your application's stylesheet:

```css
/* resources/css/custom-theme.css */
[data-theme="custom"] {
    --primary: #6366f1;
    --secondary: #8b5cf6;
    /* ... rest of theme variables */
}
```

3. Apply to your layout:

```blade
<html data-theme="custom">
    <!-- Your app content -->
</html>
```

#### Using JSON Export

1. Export your theme as JSON
2. Store in your application's config:

```php
// config/theme.php
return [
    'custom' => [
        'colors' => [
            'primary' => '#6366f1',
            'secondary' => '#8b5cf6',
            'accent' => '#ec4899',
        ],
        'glass' => [
            'blur' => 12,
            'opacity' => 0.7,
            'saturation' => 180,
            'tintIntensity' => 0.15,
        ],
    ],
];
```

3. Generate CSS dynamically:

```php
public function generateThemeCSS(): string
{
    $theme = config('theme.custom');

    return "[data-theme=\"custom\"] {
        --primary: {$theme['colors']['primary']};
        --secondary: {$theme['colors']['secondary']};
        --accent: {$theme['colors']['accent']};
        --glass-blur: {$theme['glass']['blur']}px;
        --glass-opacity: {$theme['glass']['opacity']};
        --glass-saturation: {$theme['glass']['saturation']}%;
        --glass-tint-intensity: {$theme['glass']['tintIntensity']};
    }";
}
```

## Technical Implementation

### Alpine Component

The tool is powered by an Alpine.js component (`themePreview`) registered in `resources/js/theme-preview.js`. The component provides:

- Reactive state management for all theme settings
- Computed properties for live preview styles
- Export functionality for CSS and JSON formats
- URL parameter initialization and sharing

### Route Registration

The route is conditionally registered based on configuration:

```php
// routes/web.php
if (config('artisanpack.livewire-ui-components.development.enable_theme_preview', false)) {
    Route::view('/theme-preview', 'v2-features.theme-preview')->name('theme-preview');
}
```

## Best Practices

### Development Workflow

1. **Enable in Development Only**: Only enable the route in local/development environments
2. **Test Theme Thoroughly**: Preview theme with various components before exporting
3. **Document Custom Themes**: Keep track of custom theme configurations in your project documentation
4. **Version Control**: Store exported theme files in version control
5. **Team Collaboration**: Use share URLs to collaborate on theme designs

### Security

1. **Never Enable in Production**: Always disable the route in production environments
2. **Environment-Specific Configuration**: Use environment variables to control access
3. **Access Control**: Consider adding authentication middleware if you need the route in staging

### Performance

1. **Export and Cache**: Export themes and cache CSS rather than generating dynamically
2. **Minimize Preview Usage**: Use the tool for design/testing, not as a runtime theme switcher
3. **Static Assets**: Prefer static CSS files over dynamic theme generation

## Troubleshooting

### Route Not Found (404)

**Cause**: Theme preview route is disabled.

**Solution**: Enable in `.env` file:
```env
ARTISANPACK_ENABLE_THEME_PREVIEW=true
```

Then clear config cache:
```bash
php artisan config:clear
```

### Console Errors About `themePreview`

**Cause**: Alpine component not loaded.

**Solution**: Ensure `resources/js/theme-preview.js` is imported in your `resources/js/app.js`:

```javascript
// Import Theme Preview Alpine component
import './theme-preview.js';
```

Then rebuild assets:
```bash
npm run build
```

### Preview Not Updating

**Cause**: Browser caching or assets not rebuilt.

**Solution**: Hard refresh (Cmd/Ctrl + Shift + R) or rebuild assets:
```bash
npm run build
```

### Color Picker Not Working

**Cause**: Browser doesn't support native color input or JavaScript error.

**Solution**:
- Check browser console for errors
- Try a different modern browser
- Ensure Alpine.js is loaded correctly

## See Also

- [Glass Theme Presets](glass-presets) - Pre-built glass theme configurations
- [High Contrast Theme](high-contrast-theme) - Accessibility-focused themes
- [Customization Guide](customization) - General customization documentation
- [Color System](advanced/color-system) - Understanding the color system
