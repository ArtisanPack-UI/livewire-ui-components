---
title: Pagination Component
---

# Pagination Component

The Pagination component provides a flexible user interface for navigating through paginated data, with support for multiple display variants ranging from simple to advanced layouts.

## Basic Usage

```blade
<x-artisanpack-pagination :rows="$users" wire:model.live="perPage" />
```

## Pagination Variants

The pagination component supports multiple variants to suit different use cases:

### Simple Variant
Perfect for basic data browsing with minimal UI.

```blade
<x-artisanpack-pagination 
    :rows="$users" 
    wire:model.live="perPage"
    :simple="true" 
/>
```

**Features:**
- Previous/Next buttons only
- Current page indicator
- No per-page selector
- Compact layout

### Compact Variant
Optimized for mobile and tight spaces.

```blade
<x-artisanpack-pagination 
    :rows="$users" 
    wire:model.live="perPage"
    :compact="true"
    :size="'sm'"
/>
```

**Features:**
- Condensed button sizes
- Icons instead of text labels
- Limited page numbers
- Mobile-optimized layout

### Advanced Variant
Feature-rich pagination for data-heavy applications.

```blade
<x-artisanpack-pagination 
    :rows="$users" 
    wire:model.live="perPage"
    :advanced="true"
    :show-jump-to="true"
    :on-each-side="2"
    :per-page-values="[10, 25, 50, 100, 250]"
/>
```

**Features:**
- Jump-to-page input field
- Extended per-page options
- Bulk navigation (jump by 10, 50, 100 pages)
- Quick navigation shortcuts
- Detailed pagination controls

### Minimal Variant
Clean, distraction-free design focused on typography.

```blade
<x-artisanpack-pagination 
    :rows="$users" 
    wire:model.live="perPage"
    :minimal="true"
    :hide-per-page="true"
/>
```

**Features:**
- Just page numbers with clean typography
- Subtle hover effects
- No additional controls
- Elegant ellipsis handling

### Default Variant (Enhanced)
The standard implementation with enhanced features.

```blade
<x-artisanpack-pagination 
    :rows="$users" 
    wire:model.live="perPage"
    :show-page-info="true"
    :page-info-template="'Displaying {from} to {to} of {total} records'"
/>
```

**Features:**
- Per-page selector
- Page numbers with ellipsis
- Customizable page information
- Enhanced version of current implementation

## Customization Options

### Size Variants
Control the overall size of pagination controls:

```blade
<!-- Small size -->
<x-artisanpack-pagination :rows="$users" :size="'sm'" />

<!-- Large size -->
<x-artisanpack-pagination :rows="$users" :size="'lg'" />
```

### Page Information Templates
Customize the page information display:

```blade
<x-artisanpack-pagination 
    :rows="$users" 
    :page-info-template="'Items {from}-{to} of {total} total'"
/>
```

Available placeholders:
- `{from}` - First item number on current page
- `{to}` - Last item number on current page
- `{total}` - Total number of items
- `{current}` - Current page number
- `{last}` - Last page number

### Hiding Components
Control visibility of specific elements:

```blade
<x-artisanpack-pagination 
    :rows="$users" 
    :hide-per-page="true"
    :hide-page-info="true"
/>
```

## Usage in Livewire Component

```php
class UserList extends Component
{
    public $perPage = 10;
    
    public function render()
    {
        return view('livewire.user-list', [
            'users' => User::paginate($this->perPage)
        ]);
    }
}
```

```blade
<div>
    <div class="overflow-x-auto">
        <table class="table table-zebra">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('M j, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Choose your preferred variant -->
    <x-artisanpack-pagination :rows="$users" wire:model.live="perPage" />
</div>
```

## Real-World Examples

### Mobile-First Design
```blade
<x-artisanpack-pagination 
    :rows="$products" 
    wire:model.live="perPage"
    :compact="true"
    :size="'sm'"
    :hide-page-info="true"
/>
```

### Admin Panel
```blade
<x-artisanpack-pagination 
    :rows="$reports" 
    wire:model.live="perPage"
    :advanced="true"
    :show-jump-to="true"
    :per-page-values="[25, 50, 100, 250, 500]"
    :page-info-template="'Records {from} to {to} of {total}'"
/>
```

### Blog or Content Site
```blade
<x-artisanpack-pagination 
    :rows="$posts" 
    wire:model.live="perPage"
    :minimal="true"
    :hide-per-page="true"
    :on-each-side="2"
/>
```

### Dashboard Widget
```blade
<x-artisanpack-pagination 
    :rows="$activities" 
    wire:model.live="perPage"
    :simple="true"
/>
```

## Props Reference

| Name | Type | Default | Description |
|------|------|---------|-------------|
| **Basic Props** | | | |
| id | string | null | Optional identifier for the pagination component |
| rows | array\|ArrayAccess | | The paginated data to display |
| perPageValues | array | [10, 20, 50, 100] | Available options for items per page |
| **Variant Props** | | | |
| simple | bool | false | Enable simple variant (prev/next only) |
| compact | bool | false | Enable compact variant (mobile-optimized) |
| advanced | bool | false | Enable advanced variant (feature-rich) |
| minimal | bool | false | Enable minimal variant (clean typography) |
| **Display Options** | | | |
| hidePerPage | bool | false | Hide the per-page selector |
| hidePageInfo | bool | false | Hide the page information display |
| showFirstLast | bool | true | Show first/last page buttons (compact variant) |
| showJumpTo | bool | false | Show jump-to-page input (advanced variant) |
| showPageInfo | bool | true | Show page information section |
| **Customization** | | | |
| onEachSide | int | 1 | Number of page links on each side of current page |
| size | string | 'default' | Size variant ('sm', 'default', 'lg') |
| pageInfoTemplate | string | 'Showing {from} to {to} of {total} results' | Template for page information display |

## Accessibility Features

All pagination variants include:
- Proper ARIA labels and roles
- Keyboard navigation support
- Screen reader compatibility
- Semantic HTML structure
- Focus management

## Migration Guide

### From Previous Version
The component maintains full backward compatibility. Existing implementations will work unchanged:

```blade
<!-- This continues to work exactly as before -->
<x-artisanpack-pagination :rows="$users" wire:model.live="perPage" />
```

### Adopting New Variants
You can gradually adopt new variants:

```blade
<!-- Start with simple changes -->
<x-artisanpack-pagination :rows="$users" wire:model.live="perPage" :size="'sm'" />

<!-- Then try different variants -->
<x-artisanpack-pagination :rows="$users" wire:model.live="perPage" :compact="true" />
```

## Performance Considerations

- Variants are rendered conditionally, so unused features don't impact performance
- JavaScript is only included for advanced variant when `show-jump-to` is enabled
- All variants support Laravel's built-in pagination caching
- CSS classes are optimized for minimal specificity conflicts

## Notes

- Only one variant can be active at a time (priority: simple > compact > advanced > minimal > default)
- The component requires a Livewire model binding (using `wire:model.live`) for per-page functionality
- The component automatically displays Laravel pagination links for `LengthAwarePaginator` instances
- Page information is automatically hidden for simple and minimal variants
- All variants respect Laravel's pagination URL generation and maintain query parameters
