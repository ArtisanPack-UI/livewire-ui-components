---
title: Choices Component
---

# Choices Component

The Choices component is an advanced form element that provides a searchable, multi-select dropdown interface. It offers enhanced functionality over the standard select component, including search capabilities, tagging, and remote data loading.

## Basic Usage

```php
<x-artisanpack-choices 
    label="Select Countries" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
/>
```

## Examples

### Simple Choices Dropdown

```php
<x-artisanpack-choices 
    label="Select Fruits" 
    name="fruits"
    :options="['Apple', 'Banana', 'Orange', 'Strawberry', 'Mango', 'Pineapple']"
/>
```

### Choices with Key-Value Options

```php
<x-artisanpack-choices 
    label="Select Country" 
    :options="[
        'us' => 'United States',
        'ca' => 'Canada',
        'uk' => 'United Kingdom',
        'au' => 'Australia',
        'de' => 'Germany',
        'fr' => 'France',
        'jp' => 'Japan'
    ]"
/>
```

### Multiple Selection

```php
<x-artisanpack-choices 
    label="Select Countries" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'Japan']"
    multiple
/>
```

### Choices with Default Value

```php
<x-artisanpack-choices 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    value="Canada"
/>

<!-- Multiple Default Values -->
<x-artisanpack-choices 
    label="Select Countries" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    :value="['Canada', 'United Kingdom']"
    multiple
/>
```

### Required Choices

```php
<x-artisanpack-choices 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    required
/>
```

### Disabled Choices

```php
<x-artisanpack-choices 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    disabled
/>
```

### Choices with Placeholder

```php
<x-artisanpack-choices 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    placeholder="Choose a country..."
/>
```

### Choices with Helper Text

```php
<x-artisanpack-choices 
    label="Select Languages" 
    :options="['English', 'Spanish', 'French', 'German', 'Chinese', 'Japanese']"
    helper="Select all languages you speak fluently"
/>
```

### Choices with Error

```php
<x-artisanpack-choices 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    error="Please select a country"
/>
```

### Choices with Livewire Binding

```php
<x-artisanpack-choices 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    wire:model="country"
/>

<!-- Multiple Selection with Livewire -->
<x-artisanpack-choices 
    label="Select Countries" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    wire:model="countries"
    multiple
/>
```

### Choices with Search

```php
<x-artisanpack-choices 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'Japan', 'China', 'India', 'Brazil', 'Mexico', 'Italy', 'Spain', 'Russia']"
    searchable
/>
```

### Choices with Remote Data

```php
<x-artisanpack-choices 
    label="Select User" 
    remote-url="/api/users"
    option-label="name"
    option-value="id"
    searchable
/>
```

### Choices with Tagging

```php
<x-artisanpack-choices 
    label="Skills" 
    :options="['PHP', 'JavaScript', 'Laravel', 'Vue.js', 'React', 'CSS', 'HTML']"
    taggable
    multiple
/>
```

### Choices with Option Groups

```php
<x-artisanpack-choices 
    label="Select Destination" 
    :option-groups="[
        'North America' => ['United States', 'Canada', 'Mexico'],
        'Europe' => ['United Kingdom', 'France', 'Germany', 'Italy', 'Spain'],
        'Asia' => ['Japan', 'China', 'India', 'Thailand', 'Vietnam']
    ]"
/>
```

### Choices with Custom Option Template

```php
<x-artisanpack-choices 
    label="Select User" 
    :options="$users"
    option-label="name"
    option-value="id"
>
    <x-slot:option-template>
        <div class="flex items-center">
            <img src="{{ $option['avatar'] }}" class="w-8 h-8 rounded-full mr-2">
            <div>
                <div class="font-medium">{{ $option['name'] }}</div>
                <div class="text-sm text-gray-500">{{ $option['email'] }}</div>
            </div>
        </div>
    </x-slot:option-template>
</x-artisanpack-choices>
```

### Choices with Custom Selected Template

```php
<x-artisanpack-choices 
    label="Select User" 
    :options="$users"
    option-label="name"
    option-value="id"
    multiple
>
    <x-slot:selected-template>
        <div class="flex items-center">
            <img src="{{ $option['avatar'] }}" class="w-6 h-6 rounded-full mr-1">
            <span>{{ $option['name'] }}</span>
        </div>
    </x-slot:selected-template>
</x-artisanpack-choices>
```

### Choices with Maximum Selection Limit

```php
<x-artisanpack-choices 
    label="Select Countries (max 3)" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'Japan']"
    multiple
    :max-items="3"
/>
```

### Choices with Custom No Results Text

```php
<x-artisanpack-choices 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    searchable
    no-results-text="No countries found matching your search"
/>
```

### Choices with Prefix and Suffix

```php
<x-artisanpack-choices 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
>
    <x-slot:prefix>
        <x-artisanpack-icon name="heroicon-o-globe-alt" class="w-5 h-5" />
    </x-slot:prefix>
    
    <x-slot:suffix>
        <span class="text-gray-500">Country</span>
    </x-slot:suffix>
</x-artisanpack-choices>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the choices dropdown |
| `name` | string | `null` | The name attribute for the choices dropdown |
| `id` | string | `null` | The ID attribute for the choices dropdown (auto-generated if not provided) |
| `options` | array | `[]` | Array of options for the dropdown (can be simple array or associative array) |
| `option-groups` | array | `[]` | Array of option groups with nested options |
| `value` | string/array | `null` | The selected value(s) |
| `placeholder` | string | `'Select an option'` | Placeholder text |
| `searchable` | boolean | `false` | Whether to enable search functionality |
| `search-placeholder` | string | `'Type to search...'` | Placeholder text for the search input |
| `multiple` | boolean | `false` | Whether multiple options can be selected |
| `taggable` | boolean | `false` | Whether to allow creating new options (tags) |
| `remote-url` | string | `null` | URL for loading options from a remote source |
| `option-label` | string | `'label'` | The property to use as the option label when using objects |
| `option-value` | string | `'value'` | The property to use as the option value when using objects |
| `max-items` | integer | `null` | Maximum number of items that can be selected (for multiple mode) |
| `no-results-text` | string | `'No results found'` | Text to display when no search results are found |
| `required` | boolean | `false` | Whether the choices dropdown is required |
| `disabled` | boolean | `false` | Whether the choices dropdown is disabled |
| `helper` | string | `null` | Helper text displayed below the choices dropdown |
| `error` | string | `null` | Error message to display |

## Slots

| Slot | Description |
|------|-------------|
| `prefix` | Content to display before the choices dropdown |
| `suffix` | Content to display after the choices dropdown |
| `option-template` | Custom template for rendering options |
| `selected-template` | Custom template for rendering selected options |
| `no-results` | Custom template for when no results are found |

## Events

The Choices component supports the following events:

- `change` - Triggered when the selection changes
- `search` - Triggered when the search query changes
- `open` - Triggered when the dropdown is opened
- `close` - Triggered when the dropdown is closed
- `add` - Triggered when an item is added (in multiple mode)
- `remove` - Triggered when an item is removed (in multiple mode)

It also supports all Livewire model binding directives:

- `wire:model`
- `wire:model.defer`
- `wire:model.lazy`

## Styling

The Choices component provides a customizable dropdown interface. You can customize the appearance by:

1. Using the provided props and slots
2. Adding custom classes via the `class` attribute
3. Using custom templates for options and selected items

### Custom Styling Example

```php
<x-artisanpack-choices 
    label="Custom styled choices" 
    :options="['Option 1', 'Option 2', 'Option 3']"
    class="border-2 border-purple-500 focus:border-purple-700"
/>
```

## Accessibility

The Choices component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Includes appropriate ARIA attributes
- Supports keyboard navigation
- Maintains focus management
- Ensures adequate color contrast
- Provides clear feedback on selection state

## Related Components

- [Form](form.md) - Container for form elements
- [Select](select.md) - Standard select dropdown
- [ChoicesOffline](choices-offline.md) - Offline version of the Choices component
- [Tags](tags.md) - Tags input component
