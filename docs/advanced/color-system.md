---
title: Color System
---

# Color System

The ArtisanPack UI Components package includes a powerful, flexible color system that provides consistent theming across all components. This system supports predefined variants, the full Tailwind color palette, custom hex colors, and advanced color adjustments.

## Overview

The color system is built around the `ColorGenerator` class, which provides intelligent color resolution and CSS class generation for all components. It offers three levels of color customization:

1. **Predefined Variants** - Consistent theme colors (primary, secondary, accent, etc.)
2. **Tailwind Color Palette** - Full access to Tailwind's color system with intensity levels
3. **Custom Hex Colors** - Complete freedom with custom color codes

## Supported Components

All major UI components support the color system:

- [Button](Components-Button)
- [Badge](Components-Badge)
- [Alert](Components-Alert)
- [Avatar](Components-Avatar)
- [Toast](Components-Toast)

## Color Input Types

### Predefined Variants

Use semantic color names for consistent theming:

```php
// Theme colors
color="primary"      // Primary brand color
color="secondary"    // Secondary brand color
color="accent"       // Accent brand color

// State colors
color="info"         // Information blue
color="success"      // Success green
color="warning"      // Warning yellow/orange
color="error"        // Error red
```

### Tailwind Color Palette

Access the full Tailwind color palette with intensity levels:

```php
// Basic colors with intensity
color="red-500"      // Medium red
color="blue-600"     // Dark blue
color="green-400"    // Light green
color="purple-700"   // Very dark purple

// Extended palette
color="emerald-500"  // Emerald
color="cyan-400"     // Cyan
color="rose-600"     // Rose
color="amber-300"    // Amber
color="slate-800"    // Slate
```

**Available Tailwind Colors:**
- `red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`
- `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`
- `slate`, `gray`, `zinc`, `neutral`, `stone`

**Intensity Levels:** `50`, `100`, `200`, `300`, `400`, `500`, `600`, `700`, `800`, `900`

### Custom Hex Colors

Use any custom hex color code:

```php
color="#ff6b6b"      // Custom red
color="#4ecdc4"      // Custom teal
color="#ffe66d"      // Custom yellow
color="#a8e6cf"      // Custom mint
color="#ff8b94"      // Custom pink
```

Both 3-digit (`#rgb`) and 6-digit (`#rrggbb`) hex codes are supported.

## Color Adjustments

Fine-tune the background appearance with color adjustments:

### Available Adjustments

- `lighter` - Creates a lighter background version
- `darker` - Creates a darker background version  
- `transparent` - Transparent background with colored border
- `subtle` - Very light background version

### Examples

```php
// Lighter background
<x-artisanpack-button color="blue-500" color-adjustment="lighter">
    Lighter Background
</x-artisanpack-button>

// Darker background
<x-artisanpack-alert color="green-500" color-adjustment="darker" title="Darker Alert" />

// Transparent background with colored border
<x-artisanpack-badge value="Badge" color="purple-600" color-adjustment="transparent" />

// Subtle background
<x-artisanpack-avatar placeholder="JS" color="red-500" color-adjustment="subtle" />
```

## Component Usage Examples

### Button Component

```php
<!-- Predefined variant -->
<x-artisanpack-button color="primary">Primary Button</x-artisanpack-button>

<!-- Tailwind color -->
<x-artisanpack-button color="emerald-500">Emerald Button</x-artisanpack-button>

<!-- Custom hex with adjustment -->
<x-artisanpack-button color="#ff6b6b" color-adjustment="lighter">Custom Button</x-artisanpack-button>
```

### Alert Component

```php
<!-- State variant -->
<x-artisanpack-alert color="warning" title="Warning" description="Important message" />

<!-- Tailwind color with adjustment -->
<x-artisanpack-alert 
    color="purple-600" 
    color-adjustment="subtle"
    title="Custom Alert" 
    description="With subtle background" 
/>
```

### Badge Component

```php
<!-- Simple variant -->
<x-artisanpack-badge value="New" color="success" />

<!-- Custom color with transparency -->
<x-artisanpack-badge 
    value="Beta" 
    color="#4ecdc4" 
    color-adjustment="transparent" 
/>
```

### Avatar Component

```php
<!-- Placeholder with color -->
<x-artisanpack-avatar 
    placeholder="JD" 
    alt="John Doe"
    color="blue-500" 
/>

<!-- With color adjustment -->
<x-artisanpack-avatar 
    placeholder="SM" 
    alt="Sarah Miller"
    color="purple-600"
    color-adjustment="lighter"
/>
```

### Toast Component

```php
// In your Livewire component
$this->success('Success!', 'Operation completed', null, null, null, 3000, null, 'green-600');

// With color adjustment
$this->info('Information', 'Custom notification', null, null, null, 5000, null, '#4ecdc4', 'subtle');
```

## ColorGenerator Class

The `ColorGenerator` class is the heart of the color system. While primarily used internally by components, it's also available for custom implementations.

### Key Methods

#### `resolveComponentColor()`

Resolves color input to appropriate CSS classes for a specific component:

```php
use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;

$generator = new ColorGenerator();
$classes = $generator->resolveComponentColor('blue-500', 'lighter', 'button');
```

#### `isVariant()`, `isTailwindColorWithIntensity()`, `isHexColor()`

Validation methods to check color input types:

```php
$generator->isVariant('primary');              // true
$generator->isTailwindColorWithIntensity('blue-500'); // true  
$generator->isHexColor('#ff6b6b');            // true
```

## Best Practices

### 1. Use Semantic Variants for Consistency

Prefer predefined variants for common use cases:

```php
<!-- Good -->
<x-artisanpack-button color="primary">Submit</x-artisanpack-button>
<x-artisanpack-alert color="error" title="Error occurred" />

<!-- Avoid when semantic variants exist -->
<x-artisanpack-button color="blue-600">Submit</x-artisanpack-button>
```

### 2. Leverage Color Adjustments

Use color adjustments for subtle variations:

```php
<!-- Create visual hierarchy -->
<x-artisanpack-alert color="info" title="Primary Info" />
<x-artisanpack-alert color="info" color-adjustment="subtle" title="Secondary Info" />
```

### 3. Maintain Accessibility

Ensure sufficient contrast, especially with custom colors:

```php
<!-- Good contrast -->
<x-artisanpack-button color="blue-700">Dark Blue Button</x-artisanpack-button>

<!-- Consider adjustment for better contrast -->
<x-artisanpack-button color="yellow-200" color-adjustment="darker">Readable Yellow</x-artisanpack-button>
```

### 4. Brand Consistency

Use custom hex codes for brand-specific colors:

```php
<!-- Brand colors -->
<x-artisanpack-button color="#1a365d">Brand Primary</x-artisanpack-button>
<x-artisanpack-badge value="Pro" color="#38a169" />
```

## Fallback Behavior

The color system includes intelligent fallback behavior:

1. **Invalid Colors**: Fall back to component defaults or DaisyUI classes
2. **Unsupported Adjustments**: Ignore adjustment and use base color
3. **Missing Dependencies**: Graceful degradation to basic styling

## Browser Support

The color system generates standard CSS classes compatible with all modern browsers. Custom hex colors are converted to appropriate CSS color values with broad support.

## Integration with DaisyUI

The color system works alongside DaisyUI, providing enhanced flexibility while maintaining compatibility with existing DaisyUI themes and classes.

## Performance Considerations

- Color resolution is performed once during component rendering
- Generated CSS classes are optimized for Tailwind's purging system  
- No runtime color calculations in the browser
- Minimal performance impact on component rendering

## Troubleshooting

### Common Issues

**Colors not applying**: Ensure Tailwind CSS is properly configured and the color name/intensity is valid.

**Custom hex colors not working**: Verify the hex format (`#rgb` or `#rrggbb`) and ensure no special characters.

**Adjustments not visible**: Some adjustments may be subtle - try different base colors or adjustment types.

### Debug Mode

Enable Laravel's debug mode to see detailed error messages for color system issues.

## Migration from Manual Classes

If you're migrating from manual CSS classes:

```php
<!-- Old approach -->
<x-artisanpack-badge value="Status" class="badge-primary" />

<!-- New approach -->
<x-artisanpack-badge value="Status" color="primary" />
```

The new approach provides better consistency, flexibility, and maintainability.