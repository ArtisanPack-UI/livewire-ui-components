---
title: Upgrading from v1.x to v2.0
---

# Upgrading from v1.x to v2.0

This guide covers the upgrade process from ArtisanPack UI Livewire Components v1.x to v2.0.

## Good News: No Breaking Changes

Version 2.0 is **fully backwards compatible** with v1.x. All your existing code will continue to work without any modifications. This release adds new features while maintaining complete API compatibility.

## Quick Upgrade

```bash
# Update the package
composer require artisanpack-ui/livewire-ui-components:^2.0

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

That's it! Your application should work exactly as before.

## What's New in v2.0

### Dashboard Components

New components specifically designed for building dashboards:

#### KpiCard

Display key performance indicators with optional sparklines and trend indicators:

```php
<x-artisanpack-kpi-card
    title="Total Revenue"
    value="$45,231"
    icon="o-currency-dollar"
    :change="12.5"
    change-label="vs last month"
    :sparkline-data="[1200, 1350, 1100, 1500, 1400, 1600, 1800]" />
```

Key features:
- Automatic trend indicator (green for positive, red for negative)
- Integrated sparkline visualization
- Glass effect support for modern UI

#### WidgetGrid

Responsive grid helper for dashboard layouts:

```php
<x-artisanpack-widget-grid :cols="4" :gap="4">
    <x-artisanpack-kpi-card title="Revenue" value="$45,231" />
    <x-artisanpack-kpi-card title="Users" value="2,345" />
    <x-artisanpack-kpi-card title="Orders" value="1,234" />
    <x-artisanpack-kpi-card title="Conversion" value="3.24%" />
</x-artisanpack-widget-grid>
```

### Enhanced Stat Component

The existing Stat component now includes trend indicators and embedded sparklines:

#### Trend Indicators

Display percentage changes with automatic color coding:

```php
{{-- Positive trend (green with up arrow) --}}
<x-artisanpack-stat
    title="Revenue"
    value="$45,231"
    :change="12.5"
    icon="o-currency-dollar" />

{{-- Negative trend (red with down arrow) --}}
<x-artisanpack-stat
    title="Bounce Rate"
    value="32.4%"
    :change="-8.3"
    icon="o-arrow-trending-down" />

{{-- With custom label --}}
<x-artisanpack-stat
    title="Monthly Sales"
    value="$12,450"
    :change="5.4"
    change-label="vs last month" />
```

#### Embedded Sparklines

Add sparkline visualizations directly to stats:

```php
<x-artisanpack-stat
    title="Revenue"
    value="$45,231"
    :change="12.5"
    :sparkline-data="[10, 15, 8, 20, 18, 25, 30, 28, 35]"
    sparkline-type="area"
    sparkline-color="success" />
```

Sparkline types:
- `line` - Line chart (default)
- `area` - Filled area chart
- `bar` - Bar chart

**Requirements:** Sparklines require ApexCharts. See the [Sparkline component documentation](Components-Sparkline) for installation instructions.

### Table Data Export

The Table component now supports exporting data to CSV, XLSX, and PDF formats:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    exportable
    :export-formats="['csv', 'xlsx', 'pdf']"
    export-filename="users-export" />
```

#### Using Table Export in Livewire Components

1. Add the `WithTableExport` trait to your component:

```php
use ArtisanPack\LivewireUiComponents\Traits\WithTableExport;

class UsersTable extends Component
{
    use WithTableExport;

    public function getTableExportData(string $tableId = 'default'): array
    {
        return [
            'headers' => [
                ['key' => 'id', 'label' => 'ID'],
                ['key' => 'name', 'label' => 'Name'],
                ['key' => 'email', 'label' => 'Email'],
            ],
            'rows' => User::all()->toArray(),
            'filename' => 'users-export',
        ];
    }
}
```

2. Enable export on your table:

```php
<x-artisanpack-table
    :headers="$headers"
    :rows="$users"
    exportable />
```

#### Optional Dependencies for Export

XLSX and PDF exports require optional packages:

```bash
# For Excel export
composer require phpoffice/phpspreadsheet

# For PDF export
composer require barryvdh/laravel-dompdf
```

CSV export works without any additional dependencies.

### Streaming Content (Livewire 4+)

New component for displaying real-time streaming content, perfect for AI chat interfaces:

```php
<x-artisanpack-streamable-content
    target="ai-response"
    prose
    placeholder="Waiting for response..."
    show-cursor />
```

Use with Livewire 4's `$this->stream()` method:

```php
// In your Livewire component
public function generateResponse(): void
{
    foreach ($this->getAiStream() as $chunk) {
        $this->stream('ai-response', $chunk);
    }
}
```

**Note:** Streaming requires Livewire 4. On Livewire 3, the component renders statically.

### Glass Effects

New utility class for creating frosted glass UI effects:

```php
<x-artisanpack-kpi-card
    glass="frost"
    glass-tint="primary"
    :glass-tint-opacity="15" />
```

Glass variants:
- `frost` - Strong frosted glass effect
- `blur` - Medium blur effect  
- `subtle` - Light glass effect

### Theme Preview Tool

New browser-based theme customization tool for development environments:

```bash
# Enable in .env file (development only)
ARTISANPACK_ENABLE_THEME_PREVIEW=true
```

Access at `/v2-features/theme-preview` to:
- Live preview theme colors and glass effects
- Export themes as CSS or JSON
- Share theme configurations via URL
- Test themes with interactive component examples

**Security Notice**: The theme preview route is disabled by default and should only be enabled in development/local environments. See the [Theme Preview Tool documentation](Theme-Preview-Tool) for complete details.

### Livewire 4 Features

See [Livewire 4 Features](Livewire-4-Features) for details on:

- `wire:sort` - Native drag-and-drop sorting for tables
- `wire:intersect` - Infinite scroll support
- `wire:stream` - Real-time content streaming
- `data-loading` CSS variants for loading states

## Optional Migration Tasks

While not required, consider these improvements:

### 1. Add Export to Existing Tables

If you have tables that would benefit from export functionality, add the `exportable` prop and implement `WithTableExport`.

### 2. Replace Custom Dashboard Layouts

If you have custom grid layouts for dashboards, consider using `WidgetGrid` for consistent responsive behavior.

### 3. Upgrade to Livewire 4

To take advantage of streaming content, `wire:sort`, and `wire:intersect`, consider upgrading to Livewire 4.

### 4. Enable Theme Preview Tool (Development Only)

If you want to use the theme preview tool for development:

1. Add to your `.env` file:
   ```env
   ARTISANPACK_ENABLE_THEME_PREVIEW=true
   ```

2. Clear config cache:
   ```bash
   php artisan config:clear
   ```

3. Access at `/v2-features/theme-preview`

**Important**: Never enable this in production environments.

## Troubleshooting

### Export Buttons Don't Appear

Make sure you've added the `exportable` prop:

```php
{{-- Won't show export buttons --}}
<x-artisanpack-table :headers="$headers" :rows="$users" />

{{-- Will show export buttons --}}
<x-artisanpack-table :headers="$headers" :rows="$users" exportable />
```

### XLSX/PDF Export Fails

Check that optional dependencies are installed:

```bash
# Check for PhpSpreadsheet
composer show phpoffice/phpspreadsheet

# Check for DomPDF
composer show barryvdh/laravel-dompdf
```

### Streaming Content Not Working

- Verify you're using Livewire 4+
- Check that the `target` prop matches your `$this->stream()` target

### Sparklines Not Rendering

**Symptom**: Console error "Can't find variable: artisanpackSparkline"

**Solution**: Ensure you've imported the sparkline component in your `resources/js/app.js`:

```javascript
// Import Sparkline Alpine component
import '../../vendor/artisanpack-ui/livewire-ui-components/resources/js/sparkline.js';
```

Then rebuild assets:

```bash
npm run build
```

For symlinked package development, the path is correct as shown above. For production Composer installations, adjust the path based on your vendor directory structure.

### Theme Preview Route Not Found

**Symptom**: 404 error when accessing `/v2-features/theme-preview`

**Solution**: The route is disabled by default. Enable it in your `.env` file:

```env
ARTISANPACK_ENABLE_THEME_PREVIEW=true
```

Then clear the config cache:

```bash
php artisan config:clear
```

**Important**: Only enable in development/local environments.

## Getting Help

- [GitLab Issues](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/issues)
- [Component Documentation](Components)
- [Email Support](Mailto:Me@Jacobmartella.Com)
