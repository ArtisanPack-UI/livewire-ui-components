# Pagination Component Demo Setup

This guide explains how to integrate the pagination variants demo page into your ArtisanPack UI demo application.

## Overview

The `pagination-demo.blade.php` file contains a comprehensive Volt component that demonstrates all 5 pagination variants:

- **Default Variant** - Standard pagination with per-page selection
- **Simple Variant** - Previous/Next buttons only
- **Compact Variant** - Mobile-optimized with icons
- **Advanced Variant** - Feature-rich with jump-to-page
- **Minimal Variant** - Clean typography for content sites

## Installation Steps

### 1. Copy Demo File

Copy the `pagination-demo.blade.php` file to your demo application's views directory:

```bash
# If using Volt (recommended)
cp stubs/pagination-demo.blade.php resources/views/livewire/components/pagination.blade.php

# Or for a standard Livewire component
cp stubs/pagination-demo.blade.php app/Livewire/Components/Pagination.php
```

### 2. Add Route

Add a route in your demo application's `routes/web.php` file:

```php
// For Volt component
Route::get('/components/pagination', function () {
    return view('livewire.components.pagination');
});

// Or for Livewire component
use App\Livewire\Components\Pagination;
Route::get('/components/pagination', Pagination::class);
```

### 3. Update Navigation (Optional)

If you have a navigation menu, add a link to the pagination demo:

```blade
<x-artisanpack-menu-item title="Pagination" icon="o-document-text" link="/components/pagination" />
```

## Demo Features

### Realistic Sample Data

The demo includes multiple datasets:

- **150 Employee records** for default variant
- **75 User records** for compact variant  
- **500 Employee records** for advanced variant
- **100 Blog posts** for minimal variant
- **50 Products** for simple variant

### Interactive Examples

Each variant includes:

- ✅ Live pagination controls
- ✅ Different data layouts (tables, cards, lists)
- ✅ Appropriate use case demonstrations
- ✅ Per-page selection where applicable
- ✅ Responsive design examples

### Implementation Guide

The demo includes a comprehensive implementation guide with:

- Code examples for each variant
- Props reference table
- Quick start instructions
- Best practice recommendations

## Customization

### Modify Sample Data

You can customize the sample data by editing the methods in the Volt component:

```php
public function generateSampleData(int $total = 150): Collection
{
    // Customize departments, positions, names, etc.
    $departments = ['Your', 'Custom', 'Departments'];
    // ...
}
```

### Add More Examples

To add additional examples:

1. Create a new data generation method
2. Add it to the `with()` method
3. Create a new card section in the template

### Styling Customization

The demo uses ArtisanPack UI components and can be styled using:

- Tailwind CSS classes
- DaisyUI theme variables
- ArtisanPack UI component props

## Troubleshooting

### Common Issues

1. **Faker not working**: Make sure `fakerphp/faker` is installed and `fake()` helper is available
2. **Missing icons**: Ensure Heroicons or your icon set is properly configured
3. **Styling issues**: Check that Tailwind CSS and DaisyUI are properly installed

### Dependencies

The demo requires:

- ✅ Laravel 11+
- ✅ Livewire 3
- ✅ ArtisanPack UI Components
- ✅ Tailwind CSS
- ✅ DaisyUI
- ✅ Faker (for sample data)

## Testing the Demo

### Manual Testing Checklist

- [ ] All 5 variants render correctly
- [ ] Pagination controls work properly
- [ ] Per-page selectors change data correctly
- [ ] Jump-to-page functionality works (advanced variant)
- [ ] Mobile responsiveness works
- [ ] Sample data generates without errors

### Browser Testing

Test the demo in:

- [ ] Chrome/Edge (desktop & mobile)
- [ ] Firefox (desktop & mobile)  
- [ ] Safari (desktop & mobile)
- [ ] Various screen sizes (320px to 1920px)

## Advanced Configuration

### Environment-Specific Data

For production demos, consider:

```php
public function generateSampleData(int $total = 150): Collection
{
    if (app()->environment('production')) {
        // Use static, curated demo data
        return collect($this->getCuratedDemoData());
    }
    
    // Use random generated data for development
    return $this->generateRandomData($total);
}
```

### Performance Optimization

For large datasets:

```php
// Use pagination with chunking for better performance
public function advancedUsers(): LengthAwarePaginator
{
    $currentPage = LengthAwarePaginator::resolveCurrentPage('advanced_page');
    $perPage = $this->advancedPerPage;
    
    // Generate only the data needed for current page
    $offset = ($currentPage - 1) * $perPage;
    $data = $this->generateSampleData(500)->slice($offset, $perPage);
    
    return new LengthAwarePaginator(
        $data,
        500, // Total count
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'pageName' => 'advanced_page']
    );
}
```

## Support

For issues with the pagination variants implementation:

1. Check the main documentation in `docs/components/pagination.md`
2. Review the test files in `tests/Feature/PaginationVariantsTest.php`
3. Examine the component source in `src/View/Components/Pagination.php`

## Live Example

Once set up, your demo will be available at:
```
https://your-domain.test/components/pagination
```

The demo provides a comprehensive showcase of all pagination variants with realistic use cases and implementation examples.