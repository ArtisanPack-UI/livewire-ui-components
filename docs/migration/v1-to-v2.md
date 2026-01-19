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

### Livewire 4 Features

See [Livewire 4 Features](livewire-4-features) for details on:

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

## Getting Help

- [GitLab Issues](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/issues)
- [Component Documentation](../components.md)
- [Email Support](mailto:me@jacobmartella.com)
