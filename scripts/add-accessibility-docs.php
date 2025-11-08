#!/usr/bin/env php
<?php

/**
 * Batch update component documentation files with accessibility sections
 */

$componentsPath = __DIR__ . '/docs/components';

// Components already updated (skip these)
$alreadyUpdated = ['button', 'alert', 'avatar', 'badge', 'breadcrumbs'];

// Component categories with tailored accessibility templates
$templates = [
    'form' => [
        'components' => ['checkbox', 'input', 'password', 'radio', 'range', 'select', 'textarea', 'toggle', 'file', 'pin', 'colorpicker', 'datepicker', 'datetime', 'rating', 'tags'],
        'template' => '
## Accessibility

The {COMPONENT} component is designed with accessibility in mind and follows WCAG 2.1 AA standards.

### ARIA Attributes

The {COMPONENT} component supports the following accessibility attributes:

| Attribute | Type | Description |
|-----------|------|-------------|
| `aria-label` | string | Provides accessible label when visual label is not sufficient |
| `aria-describedby` | string | References help text or error messages |
| `aria-required` | boolean | Indicates if field is required (automatically set) |
| `aria-invalid` | boolean | Indicates validation state (automatically set) |
| `aria-disabled` | boolean | Indicates disabled state (automatically set) |

### Semantic HTML

- Uses native HTML form elements for proper semantics
- Associates labels with inputs using `for` and `id` attributes
- Groups related fields with `<fieldset>` when appropriate
- Provides clear error messages associated with fields

### Screen Reader Behavior

- Label is announced when field receives focus
- Required state is announced
- Invalid/error state is announced
- Help text is announced via `aria-describedby`
- Current value is announced

### Keyboard Support

| Key | Action |
|-----|--------|
| Tab | Move focus to/from the field |
| Space | Toggle (for checkboxes/toggles) |
| Arrow Keys | Navigate options (for selects/radios) |
| Enter | Submit form (when in input field) |

### Example: Accessible Form Field

```blade
<x-artisanpack-{COMPONENT_LOWER}
    label="{COMPONENT} Label"
    hint="This field is required and must be valid"
    required
    aria-describedby="{COMPONENT_LOWER}-help"
/>
<span id="{COMPONENT_LOWER}-help" class="sr-only">
    Additional context for screen reader users
</span>
```

### Color Contrast

- Label text: 4.5:1 minimum contrast ratio
- Input text: 4.5:1 minimum contrast ratio
- Error text: 4.5:1 minimum contrast ratio
- Focus indicators: Clear 2px outline with 3:1 contrast

### Best Practices

1. **Always provide labels**: Every form field must have a visible label
2. **Use aria-describedby for help text**: Associate hints and errors with fields
3. **Clear error messages**: Describe what went wrong and how to fix it
4. **Required indicators**: Mark required fields both visually and semantically
5. **Group related fields**: Use fieldsets for related form controls

### Common Accessibility Issues to Avoid

❌ **Don\'t**: Use placeholder as the only label
```blade
<x-artisanpack-{COMPONENT_LOWER} placeholder="Enter your name" />
```

✅ **Do**: Always provide a proper label
```blade
<x-artisanpack-{COMPONENT_LOWER}
    label="Name"
    placeholder="Enter your name"
/>
```

❌ **Don\'t**: Generic error messages
```blade
<x-artisanpack-{COMPONENT_LOWER}
    label="Email"
    error="Invalid"
/>
```

✅ **Do**: Descriptive error messages
```blade
<x-artisanpack-{COMPONENT_LOWER}
    label="Email"
    error="Please enter a valid email address (e.g., name@example.com)"
/>
```

### Testing

Run accessibility tests:
```bash
php artisan test --filter {COMPONENT}AccessibilityTest
```

### Additional Resources

- [WCAG 2.1 Forms](https://www.w3.org/WAI/WCAG21/quickref/#forms)
- [WebAIM Forms](https://webaim.org/techniques/forms/)
- [Accessibility Guidelines](../accessibility/guidelines.md)
'
    ],

    'interactive' => [
        'components' => ['modal', 'drawer', 'dropdown', 'tabs', 'accordion', 'collapse', 'carousel', 'menu', 'menu-item', 'menu-sub', 'menu-separator', 'menu-title', 'popover', 'toast', 'swap'],
        'template' => '
## Accessibility

The {COMPONENT} component is designed with accessibility in mind and follows WCAG 2.1 AA standards.

### ARIA Attributes

The {COMPONENT} component supports the following accessibility attributes:

| Attribute | Type | Description |
|-----------|------|-------------|
| `role` | string | Defines the component role for assistive technologies |
| `aria-label` | string | Provides accessible label for the component |
| `aria-expanded` | boolean | Indicates expanded/collapsed state (automatically managed) |
| `aria-controls` | string | References controlled element IDs |
| `aria-hidden` | boolean | Hides from screen readers when not visible |

### Semantic HTML

- Uses appropriate semantic elements
- Maintains logical heading hierarchy
- Proper focus management with focus trap when needed
- Clear visual focus indicators

### Screen Reader Behavior

- Component role and state are announced
- State changes are announced to users
- Keyboard shortcuts are discoverable
- Content is accessible when expanded

### Keyboard Support

| Key | Action |
|-----|--------|
| Enter/Space | Activate/toggle component |
| Escape | Close/collapse component |
| Tab | Navigate to next focusable element |
| Arrow Keys | Navigate within component (when applicable) |

### Focus Management

- Focus is trapped within modal/drawer when open
- Focus returns to trigger element when closed
- First focusable element receives focus when opened
- Focus is clearly visible at all times

### Example: Accessible {COMPONENT}

```blade
<x-artisanpack-{COMPONENT_LOWER}
    aria-label="Descriptive label for {COMPONENT}"
    role="dialog"
>
    <!-- Content -->
</x-artisanpack-{COMPONENT_LOWER}>
```

### Color Contrast

- All text meets 4.5:1 minimum contrast ratio
- Interactive elements have 3:1 contrast
- Focus indicators are clearly visible

### Best Practices

1. **Clear labels**: Provide descriptive aria-labels
2. **Keyboard accessible**: All functionality available via keyboard
3. **Focus management**: Trap and restore focus appropriately
4. **Announce changes**: Use aria-live for dynamic updates
5. **Escape closes**: Always allow Escape key to dismiss

### Common Accessibility Issues to Avoid

❌ **Don\'t**: Auto-open without user action
```blade
<x-artisanpack-{COMPONENT_LOWER} :open="true" />
```

✅ **Do**: Open in response to user interaction
```blade
<x-artisanpack-button @click="open = true">Open {COMPONENT}</x-artisanpack-button>
<x-artisanpack-{COMPONENT_LOWER} x-show="open" />
```

### Testing

Run accessibility tests:
```bash
php artisan test --filter {COMPONENT}AccessibilityTest
```

### Additional Resources

- [WAI-ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [Accessibility Guidelines](../accessibility/guidelines.md)
'
    ],

    'simple' => [
        'components' => ['code', 'diff', 'heading', 'icon', 'kbd', 'link', 'loading', 'progress', 'progress-radial', 'separator', 'stat', 'step', 'subheading', 'text', 'timeline-item', 'nav', 'main', 'group', 'fieldset'],
        'template' => '
## Accessibility

The {COMPONENT} component is designed with accessibility in mind and follows WCAG 2.1 AA standards.

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

### Example: Accessible {COMPONENT}

```blade
<x-artisanpack-{COMPONENT_LOWER}>
    Accessible content
</x-artisanpack-{COMPONENT_LOWER}>
```

### Testing

Run accessibility tests:
```bash
php artisan test --filter {COMPONENT}AccessibilityTest
```

### Additional Resources

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Accessibility Guidelines](../accessibility/guidelines.md)
'
    ],

    'advanced' => [
        'components' => ['pagination', 'steps', 'image-gallery', 'image-library', 'image-slider', 'chart', 'choices', 'choices-offline', 'markdown', 'list-item', 'header', 'profile', 'theme-toggle', 'card', 'table', 'editor', 'form', 'calendar', 'calendar-enhanced', 'signature'],
        'template' => '
## Accessibility

The {COMPONENT} component is designed with accessibility in mind and follows WCAG 2.1 AA standards.

### ARIA Attributes

The {COMPONENT} component uses appropriate ARIA attributes to ensure screen reader compatibility and follows WAI-ARIA authoring practices.

### Keyboard Navigation

All interactive elements are fully keyboard accessible following standard keyboard interaction patterns.

### Screen Reader Support

The component provides proper announcements and context for screen reader users, including state changes and dynamic updates.

### Color Contrast

All visual elements meet WCAG AA color contrast requirements with a minimum ratio of 4.5:1 for text and 3:1 for UI components.

### Best Practices

1. Use semantic HTML and ARIA attributes appropriately
2. Ensure all functionality is keyboard accessible
3. Provide clear labels and instructions
4. Test with screen readers and keyboard-only navigation
5. Maintain sufficient color contrast

### Testing

Run accessibility tests:
```bash
php artisan test --filter {COMPONENT}AccessibilityTest
```

### Additional Resources

- [WAI-ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Accessibility Guidelines](../accessibility/guidelines.md)
'
    ],
];

// Get all component documentation files
$files = glob($componentsPath . '/*.md');
$updated = [];
$skipped = [];
$failed = [];

foreach ($files as $file) {
    $filename = basename($file, '.md');

    // Skip already updated files
    if (in_array($filename, $alreadyUpdated)) {
        $skipped[] = $filename;
        continue;
    }

    // Find which category this component belongs to
    $category = null;
    $componentName = ucwords(str_replace(['-', '_'], ' ', $filename));
    $componentLower = strtolower($filename);

    foreach ($templates as $cat => $data) {
        if (in_array($filename, $data['components'])) {
            $category = $cat;
            break;
        }
    }

    if (!$category) {
        echo "⚠️  No category found for: $filename\n";
        $failed[] = $filename;
        continue;
    }

    // Read the file
    $content = file_get_contents($file);

    // Check if accessibility section already exists
    if (strpos($content, '## Accessibility') !== false) {
        echo "⏭️  Skipped (already has accessibility): $filename\n";
        $skipped[] = $filename;
        continue;
    }

    // Prepare the accessibility template
    $accessibilitySection = str_replace(
        ['{COMPONENT}', '{COMPONENT_LOWER}'],
        [$componentName, $componentLower],
        $templates[$category]['template']
    );

    // Append the accessibility section
    $content .= "\n" . $accessibilitySection . "\n";

    // Write back to file
    if (file_put_contents($file, $content)) {
        echo "✅ Updated: $filename ($category)\n";
        $updated[] = $filename;
    } else {
        echo "❌ Failed to update: $filename\n";
        $failed[] = $filename;
    }
}

// Summary
echo "\n" . str_repeat('=', 60) . "\n";
echo "Summary\n";
echo str_repeat('=', 60) . "\n";
echo "✅ Updated: " . count($updated) . " files\n";
echo "⏭️  Skipped: " . count($skipped) . " files\n";
echo "❌ Failed: " . count($failed) . " files\n";
echo str_repeat('=', 60) . "\n";

if (count($updated) > 0) {
    echo "\nUpdated files:\n";
    foreach ($updated as $file) {
        echo "  - $file\n";
    }
}

if (count($failed) > 0) {
    echo "\nFailed files:\n";
    foreach ($failed as $file) {
        echo "  - $file\n";
    }
}

echo "\nDone!\n";
