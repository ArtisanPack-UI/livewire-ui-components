---
title: Progress Component
---

# Progress Component

The Progress component displays a horizontal progress bar to visualize the completion status of a task or process.

## Basic Usage

```blade
<x-artisanpack-progress :value="50" />
```

## With Different Values

```blade
<x-artisanpack-progress :value="25" />
<x-artisanpack-progress :value="50" />
<x-artisanpack-progress :value="75" />
<x-artisanpack-progress :value="100" />
```

## With Custom Maximum Value

```blade
<x-artisanpack-progress :value="5" :max="10" />
```

## With Different Colors

```blade
<x-artisanpack-progress :value="40" class="progress-primary" />
<x-artisanpack-progress :value="40" class="progress-secondary" />
<x-artisanpack-progress :value="40" class="progress-accent" />
<x-artisanpack-progress :value="40" class="progress-info" />
<x-artisanpack-progress :value="40" class="progress-success" />
<x-artisanpack-progress :value="40" class="progress-warning" />
<x-artisanpack-progress :value="40" class="progress-error" />
```

## Indeterminate Progress

```blade
<x-artisanpack-progress indeterminate />
```

## With Livewire

```blade
<div>
    <x-artisanpack-progress :value="$progress" />
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
| id | string | null | Optional identifier for the progress bar |
| value | float | 0 | Current value of the progress bar |
| max | float | 100 | Maximum value of the progress bar |
| indeterminate | boolean | false | Whether the progress bar should be in an indeterminate state |

## CSS Classes

The component accepts all standard HTML attributes, including CSS classes. The component uses DaisyUI's progress classes for styling. Here are some of the available classes:

- `progress-primary`, `progress-secondary`, `progress-accent`: Brand colors
- `progress-info`, `progress-success`, `progress-warning`, `progress-error`: State colors

You can also customize the height of the progress bar using standard Tailwind height classes like `h-1`, `h-2`, etc.


## Accessibility

The Progress component is designed with accessibility in mind and follows WCAG 2.1 AA standards.

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

### Example: Accessible Progress

```blade
<x-artisanpack-progress>
    Accessible content
</x-artisanpack-progress>
```

### Testing

Run accessibility tests:
```bash
php artisan test --filter ProgressAccessibilityTest
```

### Additional Resources

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Accessibility Guidelines](../accessibility/guidelines.md)

