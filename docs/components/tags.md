---
title: Tags Component
---

# Tags Component

The Tags component is a form element that allows users to create and manage multiple tags or labels. It provides an intuitive interface for adding, editing, and removing tags, with support for validation, suggestions, and customization.

## Basic Usage

```php
<x-artisanpack-tags label="Skills" />
```

## Examples

### Simple Tags Input

```php
<x-artisanpack-tags 
    label="Technologies" 
    name="technologies"
/>
```

### Tags with Default Values

```php
<x-artisanpack-tags 
    label="Skills" 
    :value="['PHP', 'Laravel', 'Livewire']"
/>
```

### Required Tags

```php
<x-artisanpack-tags 
    label="Categories" 
    required
/>
```

### Disabled Tags

```php
<x-artisanpack-tags 
    label="Tags" 
    :value="['Important', 'Urgent', 'Review']" 
    disabled
/>
```

### Tags with Helper Text

```php
<x-artisanpack-tags 
    label="Keywords" 
    helper="Add keywords to improve discoverability"
/>
```

### Tags with Error

```php
<x-artisanpack-tags 
    label="Categories" 
    error="Please add at least one category"
/>
```

### Tags with Livewire Binding

```php
<x-artisanpack-tags 
    label="Skills" 
    wire:model="skills"
/>
```

### Tags with Suggestions

```php
<x-artisanpack-tags 
    label="Programming Languages" 
    :suggestions="['PHP', 'JavaScript', 'Python', 'Ruby', 'Java', 'C#', 'Go', 'Rust', 'Swift']"
/>
```

### Tags with Maximum Limit

```php
<x-artisanpack-tags 
    label="Categories (max 5)" 
    :max-tags="5"
/>
```

### Tags with Minimum Requirement

```php
<x-artisanpack-tags 
    label="Keywords (min 3)" 
    :min-tags="3"
    error="Please add at least 3 keywords"
/>
```

### Tags with Validation

```php
<x-artisanpack-tags 
    label="Email Addresses" 
    :validate="true"
    validation-pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
    validation-message="Please enter a valid email address"
/>
```

### Tags with Custom Delimiter

```php
<x-artisanpack-tags 
    label="Tags" 
    delimiter=","
    placeholder="Type and press comma to add"
/>
```

### Tags with Custom Colors

```php
<x-artisanpack-tags 
    label="Categories" 
    tag-color="primary"
/>

<x-artisanpack-tags 
    label="Priorities" 
    tag-color="secondary"
/>

<x-artisanpack-tags 
    label="Labels" 
    tag-color="accent"
/>
```

### Tags with Custom Tag Template

```php
<x-artisanpack-tags 
    label="Team Members" 
    :value="$teamMembers"
>
    <x-slot:tag-template>
        <div class="flex items-center bg-primary text-primary-content rounded-full px-3 py-1">
            <img src="{{ $tag.avatar }}" class="w-5 h-5 rounded-full mr-2">
            <span>{{ $tag.name }}</span>
            <button type="button" class="ml-2" @click="removeTag(index)">
                <x-artisanpack-icon name="heroicon-o-x-mark" class="w-4 h-4" />
            </button>
        </div>
    </x-slot:tag-template>
</x-artisanpack-tags>
```

### Tags with Custom Suggestion Template

```php
<x-artisanpack-tags 
    label="Users" 
    :suggestions="$users"
>
    <x-slot:suggestion-template>
        <div class="flex items-center p-2">
            <img src="{{ $suggestion.avatar }}" class="w-6 h-6 rounded-full mr-2">
            <div>
                <div class="font-medium">{{ $suggestion.name }}</div>
                <div class="text-sm text-gray-500">{{ $suggestion.email }}</div>
            </div>
        </div>
    </x-slot:suggestion-template>
</x-artisanpack-tags>
```

### Tags with Case Sensitivity

```php
<x-artisanpack-tags 
    label="Tags" 
    :case-sensitive="true"
/>
```

### Tags with Placeholder

```php
<x-artisanpack-tags 
    label="Keywords" 
    placeholder="Type and press Enter to add"
/>
```

### Tags with Prefix and Suffix

```php
<x-artisanpack-tags 
    label="Categories"
>
    <x-slot:prefix>
        <x-artisanpack-icon name="heroicon-o-tag" class="w-5 h-5" />
    </x-slot:prefix>
    
    <x-slot:suffix>
        <span class="text-gray-500">Tags</span>
    </x-slot:suffix>
</x-artisanpack-tags>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the tags input |
| `name` | string | `null` | The name attribute for the tags input |
| `id` | string | `null` | The ID attribute for the tags input (auto-generated if not provided) |
| `value` | array | `[]` | The default tags |
| `placeholder` | string | `'Add tag...'` | Placeholder text for the input |
| `suggestions` | array | `[]` | Array of suggested tags |
| `max-tags` | integer | `null` | Maximum number of tags allowed |
| `min-tags` | integer | `null` | Minimum number of tags required |
| `validate` | boolean | `false` | Whether to validate tags |
| `validation-pattern` | string | `null` | Regular expression pattern for tag validation |
| `validation-message` | string | `'Invalid tag'` | Message to display for invalid tags |
| `delimiter` | string | `'Enter'` | Key to press to add a tag (Enter, comma, space, etc.) |
| `tag-color` | string | `'neutral'` | Color of the tags (`primary`, `secondary`, `accent`, etc.) |
| `case-sensitive` | boolean | `false` | Whether tags are case-sensitive (affects duplicates) |
| `allow-duplicates` | boolean | `false` | Whether to allow duplicate tags |
| `required` | boolean | `false` | Whether the tags input is required |
| `disabled` | boolean | `false` | Whether the tags input is disabled |
| `helper` | string | `null` | Helper text displayed below the tags input |
| `error` | string | `null` | Error message to display |

## Slots

| Slot | Description |
|------|-------------|
| `prefix` | Content to display before the tags input |
| `suffix` | Content to display after the tags input |
| `tag-template` | Custom template for rendering tags |
| `suggestion-template` | Custom template for rendering suggestions |

## Events

The Tags component supports the following events:

- `add` - Triggered when a tag is added
- `remove` - Triggered when a tag is removed
- `change` - Triggered when the tags collection changes
- `focus` - Triggered when the input is focused
- `blur` - Triggered when the input loses focus

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`

## Styling

The Tags component provides a customizable tags interface. You can customize the appearance by:

1. Using the provided props (`tag-color`, etc.)
2. Adding custom classes via the `class` attribute
3. Using custom templates for tags and suggestions

### Custom Styling Example

```php
<x-artisanpack-tags 
    label="Custom styled tags" 
    class="border-2 border-purple-500 focus:border-purple-700"
    tag-color="accent"
/>
```

## Accessibility

The Tags component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Includes appropriate ARIA attributes
- Supports keyboard navigation
- Maintains focus management
- Ensures adequate color contrast
- Provides clear feedback on tag addition and removal

## Related Components

- [Form](form.md) - Container for form elements
- [Input](input.md) - Standard text input
- [Choices](choices.md) - Multi-select dropdown with search
- [ChoicesOffline](choices-offline.md) - Offline version of the Choices component
