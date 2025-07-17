# Generating Color Themes

ArtisanPack UI Livewire Components includes a powerful theme generation system that allows you to create custom color themes for your application. This guide will walk you through the process of generating and customizing themes.

## Introduction to Theme Generation

ArtisanPack UI Livewire Components uses a theme system based on CSS variables that integrates seamlessly with DaisyUI. The theme generator creates a CSS file with all the necessary variables for your application's color scheme.

### Key Features

- **Dynamic Color Palette Generation**: Generate full color palettes from base colors
- **DaisyUI Integration**: Compatible with DaisyUI's theming system
- **Tailwind Color Support**: Use Tailwind color names or custom hex codes
- **Accessible Contrast**: Automatically generates accessible text colors
- **Light and Dark Mode**: Supports both light and dark themes

## Using the artisanpack:generate-theme Command

The package includes an Artisan command that generates a CSS theme file based on your specified colors.

### Basic Usage

```bash
php artisan artisanpack:generate-theme
```

Running the command without options will use the default colors:
- Primary: sky (#0ea5e9)
- Secondary: slate (#64748b)
- Accent: amber (#f59e0b)

### Available Options

You can customize the colors by providing options:

```bash
php artisan artisanpack:generate-theme --primary=blue --secondary=zinc --accent=rose
```

#### Option Reference

| Option | Description | Default |
|--------|-------------|---------|
| `--primary` | The primary color name or hex code | sky |
| `--secondary` | The secondary color name or hex code | slate |
| `--accent` | The accent color name or hex code | amber |

### Using Tailwind Color Names

You can use any of the following Tailwind color names:

- sky
- blue
- indigo
- purple
- pink
- red
- orange
- amber
- yellow
- lime
- green
- emerald
- teal
- cyan
- fuchsia
- rose
- stone
- slate
- zinc
- neutral
- gray

Example:
```bash
php artisan artisanpack:generate-theme --primary=indigo --secondary=gray --accent=emerald
```

### Using Hex Codes

You can also use hex color codes for more precise control:

```bash
php artisan artisanpack:generate-theme --primary=#3b82f6 --secondary=#64748b --accent=#f59e0b
```

### Examples with Different Color Combinations

#### Corporate Theme
```bash
php artisan artisanpack:generate-theme --primary=blue --secondary=slate --accent=sky
```

#### Playful Theme
```bash
php artisan artisanpack:generate-theme --primary=purple --secondary=zinc --accent=pink
```

#### Nature Theme
```bash
php artisan artisanpack:generate-theme --primary=emerald --secondary=stone --accent=amber
```

## Theme Output

The generated theme file is saved to the location specified in your configuration file, which by default is:

```
resources/css/artisanpack-ui-theme.css
```

You can change this location by publishing the configuration file and updating the `theme_output_path` value:

```bash
php artisan vendor:publish --tag=livewire-ui-components.config
```

### Understanding the Generated CSS File

The generated CSS file contains several sections:

#### 1. Color Palette Variables

```css
:root {
    /* --- ArtisanPack UI Color Palette --- */
    --p-50: #eff6ff;
    --p-100: #dbeafe;
    --p-200: #bfdbfe;
    --p-300: #93c5fd;
    --p-400: #60a5fa;
    --p-500: #3b82f6;
    --p-600: #2563eb;
    --p-700: #1d4ed8;
    --p-800: #1e40af;
    --p-900: #1e3a8a;
    
    --s-50: #f8fafc;
    --s-100: #f1f5f9;
    --s-200: #e2e8f0;
    --s-300: #cbd5e1;
    --s-400: #94a3b8;
    --s-500: #64748b;
    --s-600: #475569;
    --s-700: #334155;
    --s-800: #1e293b;
    --s-900: #0f172a;
    
    --a-50: #fffbeb;
    --a-100: #fef3c7;
    --a-200: #fde68a;
    --a-300: #fcd34d;
    --a-400: #fbbf24;
    --a-500: #f59e0b;
    --a-600: #d97706;
    --a-700: #b45309;
    --a-800: #92400e;
    --a-900: #78350f;
}
```

These variables provide a full color palette for your primary, secondary, and accent colors, following the Tailwind CSS naming convention.

#### 2. DaisyUI Color Variables

```css
:root {
    /* --- DaisyUI Color Variables --- */
    --primary: #3b82f6;
    --primary-content: #ffffff;
    --secondary: #64748b;
    --secondary-content: #ffffff;
    --accent: #f59e0b;
    --accent-content: #ffffff;
    --neutral: #737373;
    --neutral-content: #ffffff;
    --base-100: #ffffff;
    --base-200: #f3f4f6;
    --base-300: #e5e7eb;
    --base-content: #1f2937;
    --info: #0ea5e9;
    --info-content: #ffffff;
    --success: #22c55e;
    --success-content: #ffffff;
    --warning: #f97316;
    --warning-content: #ffffff;
    --error: #ef4444;
    --error-content: #ffffff;
}
```

These variables are used by DaisyUI for its component styling.

#### 3. DaisyUI Utility Variables

```css
:root {
    /* --- DaisyUI Global Utility Variables --- */
    --rounded-box: 1rem;
    --rounded-btn: 0.5rem;
    --rounded-badge: 1.9rem;
    --animation-btn: 0.25s;
    --animation-input: 0.2s;
    --btn-focus-scale: 0.95;
    --border-btn: 1px;
    --tab-border: 1px;
    --tab-radius: 0.5rem;
}
```

These variables control various aspects of DaisyUI components.

#### 4. Component-Specific Variables

The file also includes commented-out sections for component-specific variables that you can uncomment and customize:

```css
/* --- Button --- */
/*
.btn {
    --btn-color: oklch(var(--b2));
    --btn-fg: oklch(var(--bc));
    --size: 3rem;
}
*/
```

### How to Import the Theme File

To use the generated theme, import it in your main CSS file:

```css
/* resources/css/app.css */
@import './artisanpack-ui-theme.css';

@tailwind base;
@tailwind components;
@tailwind utilities;

/* Your other styles */
```

## Customizing Themes

### Modifying the Generated Theme

You can manually edit the generated CSS file to fine-tune your theme. However, note that running the `artisanpack:generate-theme` command again will overwrite your changes.

For persistent customizations, consider:

1. Creating a separate CSS file for your customizations
2. Modifying your build process to apply your customizations after the theme is generated

### Component-Specific Styling

The generated theme file includes commented-out sections for component-specific variables. To customize a specific component, uncomment the relevant section and modify the variables:

```css
/* --- Button --- */
.btn {
    --btn-color: oklch(var(--b2));
    --btn-fg: oklch(var(--bc));
    --size: 2.5rem; /* Changed from default 3rem */
}
```

### Dark Mode Considerations

The theme generator creates variables that work with DaisyUI's dark mode. To enable dark mode in your application:

1. Make sure your `app.css` includes the dark mode variant:
   ```css
   @custom-variant dark (&:where(.dark, .dark *));
   ```

2. Use the ThemeToggle component to allow users to switch between light and dark mode:
   ```php
   <x-artisanpack-theme-toggle />
   ```

3. Or manually toggle dark mode with JavaScript:
   ```js
   // Toggle dark mode
   document.documentElement.classList.toggle('dark');
   
   // Check if dark mode is active
   const isDarkMode = document.documentElement.classList.contains('dark');
   ```

### Advanced Theme Customization

For more advanced customization, you can:

1. **Create Multiple Themes**: Generate multiple theme files with different color schemes
2. **Extend the Color Generator**: Create your own color generator by extending the `ColorGenerator` class
3. **Customize DaisyUI Themes**: Use DaisyUI's theming system alongside ArtisanPack UI

Example of creating a custom theme switcher:

```php
<div class="theme-selector">
    <button onclick="setTheme('light')" class="theme-btn light">Light</button>
    <button onclick="setTheme('dark')" class="theme-btn dark">Dark</button>
    <button onclick="setTheme('corporate')" class="theme-btn corporate">Corporate</button>
</div>

<script>
function setTheme(theme) {
    // Remove all theme classes
    document.documentElement.classList.remove('light', 'dark', 'corporate');
    
    // Add the selected theme class
    document.documentElement.classList.add(theme);
    
    // Store the preference
    localStorage.setItem('theme', theme);
}

// Load saved theme
const savedTheme = localStorage.getItem('theme') || 'light';
setTheme(savedTheme);
</script>
```
