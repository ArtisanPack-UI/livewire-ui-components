---
title: ProgressRadial Component
---

# ProgressRadial Component

The ProgressRadial component displays a circular progress indicator to visualize the completion status of a task or process.

## Basic Usage

```blade
<x-artisanpack-progress-radial :value="50" />
```

## With Different Values

```blade
<x-artisanpack-progress-radial :value="25" />
<x-artisanpack-progress-radial :value="50" />
<x-artisanpack-progress-radial :value="75" />
<x-artisanpack-progress-radial :value="100" />
```

## With Custom Unit

```blade
<x-artisanpack-progress-radial :value="42" unit="MB" />
```

## With Different Colors

```blade
<x-artisanpack-progress-radial :value="40" class="text-primary" />
<x-artisanpack-progress-radial :value="40" class="text-secondary" />
<x-artisanpack-progress-radial :value="40" class="text-accent" />
<x-artisanpack-progress-radial :value="40" class="text-info" />
<x-artisanpack-progress-radial :value="40" class="text-success" />
<x-artisanpack-progress-radial :value="40" class="text-warning" />
<x-artisanpack-progress-radial :value="40" class="text-error" />
```

## With Different Sizes

```blade
<x-artisanpack-progress-radial :value="75" class="text-primary text-xs" />
<x-artisanpack-progress-radial :value="75" class="text-primary text-sm" />
<x-artisanpack-progress-radial :value="75" class="text-primary text-base" />
<x-artisanpack-progress-radial :value="75" class="text-primary text-lg" />
<x-artisanpack-progress-radial :value="75" class="text-primary text-xl" />
```

## With Livewire

```blade
<div>
    <x-artisanpack-progress-radial :value="$progress" />
    <x-artisanpack-button label="Start Process" wire:click="startProcess" />
</div>
```

```php
class ProcessExample extends Component
{
    public $progress = 0;
    
    public function startProcess()
    {
        $this->progress = 0;
        
        while ($this->progress < 100) {
            $this->progress += 10;
            $this->dispatch('refresh');
            sleep(1);
        }
    }
}
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the radial progress |
| value | float | 0 | Current value of the radial progress (0-100) |
| unit | string | '%' | Unit to display after the value |

## CSS Classes

The component accepts all standard HTML attributes, including CSS classes. The component uses DaisyUI's radial-progress classes for styling. You can customize the appearance using:

- Text color classes: `text-primary`, `text-secondary`, `text-accent`, `text-info`, `text-success`, `text-warning`, `text-error`
- Text size classes: `text-xs`, `text-sm`, `text-base`, `text-lg`, `text-xl`
- Size classes: `w-16`, `w-20`, `w-24`, etc.

The component automatically sets the `--value` CSS variable based on the provided value prop.


## Accessibility

The Progress Radial component is designed with accessibility in mind and follows WCAG 2.1 AA standards.

### Semantic HTML

- Uses appropriate semantic HTML elements
- Maintains proper document structure
- Decorative elements are hidden from screen readers

### Screen Reader Behavior

- Component content is announced appropriately
- Semantic meaning is preserved
- Visual styling does not affect accessibility

### Color Contrast

- All text meets WCAG AA color contrast requirements (4.5:1 minimum)
- Visual indicators have sufficient contrast

### Best Practices

1. **Use semantic HTML**: Leverage native elements when possible
2. **Proper hierarchy**: Maintain logical document structure
3. **Sufficient contrast**: Ensure all content is readable
4. **Decorative vs informative**: Mark decorative elements appropriately

### Example: Accessible Progress Radial

```blade
<x-artisanpack-progress-radial>
    Accessible content
</x-artisanpack-progress-radial>
```

### Testing

Run accessibility tests:
```bash
php artisan test --filter Progress RadialAccessibilityTest
```

### Additional Resources

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Accessibility Guidelines](../accessibility/guidelines.md)

