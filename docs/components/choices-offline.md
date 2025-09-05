---
title: ChoicesOffline Component
---

# ChoicesOffline Component

The ChoicesOffline component is a variant of the Choices component that works entirely client-side without making network requests. It provides a searchable, multi-select dropdown interface with all the functionality of the standard Choices component but optimized for offline use or when all options are known in advance.

## Basic Usage

```php
<x-artisanpack-choices-offline 
    label="Select Countries" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
/>
```

## Examples

### Simple ChoicesOffline Dropdown

```php
<x-artisanpack-choices-offline 
    label="Select Fruits" 
    name="fruits"
    :options="['Apple', 'Banana', 'Orange', 'Strawberry', 'Mango', 'Pineapple']"
/>
```

### ChoicesOffline with Key-Value Options

```php
<x-artisanpack-choices-offline 
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
<x-artisanpack-choices-offline 
    label="Select Countries" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'Japan']"
    multiple
/>
```

### ChoicesOffline with Default Value

```php
<x-artisanpack-choices-offline 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    value="Canada"
/>

<!-- Multiple Default Values -->
<x-artisanpack-choices-offline 
    label="Select Countries" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    :value="['Canada', 'United Kingdom']"
    multiple
/>
```

### Required ChoicesOffline

```php
<x-artisanpack-choices-offline 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    required
/>
```

### Disabled ChoicesOffline

```php
<x-artisanpack-choices-offline 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    disabled
/>
```

### ChoicesOffline with Placeholder

```php
<x-artisanpack-choices-offline 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    placeholder="Choose a country..."
/>
```

### ChoicesOffline with Helper Text

```php
<x-artisanpack-choices-offline 
    label="Select Languages" 
    :options="['English', 'Spanish', 'French', 'German', 'Chinese', 'Japanese']"
    helper="Select all languages you speak fluently"
/>
```

### ChoicesOffline with Error

```php
<x-artisanpack-choices-offline 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    error="Please select a country"
/>
```

### ChoicesOffline with Livewire Binding

```php
<x-artisanpack-choices-offline 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    wire:model="country"
/>

<!-- Multiple Selection with Livewire -->
<x-artisanpack-choices-offline 
    label="Select Countries" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    wire:model="countries"
    multiple
/>
```

### ChoicesOffline with Search

```php
<x-artisanpack-choices-offline 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'Japan', 'China', 'India', 'Brazil', 'Mexico', 'Italy', 'Spain', 'Russia']"
    searchable
/>
```

### ChoicesOffline with Tagging

```php
<x-artisanpack-choices-offline 
    label="Skills" 
    :options="['PHP', 'JavaScript', 'Laravel', 'Vue.js', 'React', 'CSS', 'HTML']"
    taggable
    multiple
/>
```

### ChoicesOffline with Option Groups

```php
<x-artisanpack-choices-offline 
    label="Select Destination" 
    :option-groups="[
        'North America' => ['United States', 'Canada', 'Mexico'],
        'Europe' => ['United Kingdom', 'France', 'Germany', 'Italy', 'Spain'],
        'Asia' => ['Japan', 'China', 'India', 'Thailand', 'Vietnam']
    ]"
/>
```

### ChoicesOffline with Custom Option Template

```php
<x-artisanpack-choices-offline 
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
</x-artisanpack-choices-offline>
```

### ChoicesOffline with Custom Selected Template

```php
<x-artisanpack-choices-offline 
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
</x-artisanpack-choices-offline>
```

### ChoicesOffline with Maximum Selection Limit

```php
<x-artisanpack-choices-offline 
    label="Select Countries (max 3)" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia', 'Germany', 'France', 'Japan']"
    multiple
    :max-items="3"
/>
```

### ChoicesOffline with Custom No Results Text

```php
<x-artisanpack-choices-offline 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
    searchable
    no-results-text="No countries found matching your search"
/>
```

### ChoicesOffline with Prefix and Suffix

```php
<x-artisanpack-choices-offline 
    label="Select Country" 
    :options="['United States', 'Canada', 'United Kingdom', 'Australia']"
>
    <x-slot:prefix>
        <x-artisanpack-icon name="heroicon-o-globe-alt" class="w-5 h-5" />
    </x-slot:prefix>
    
    <x-slot:suffix>
        <span class="text-gray-500">Country</span>
    </x-slot:suffix>
</x-artisanpack-choices-offline>
```

### ChoicesOffline with Custom Search Function

```php
<x-artisanpack-choices-offline 
    label="Select Country" 
    :options="$countries"
    searchable
    :search-keys="['name', 'code', 'region']"
/>
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
| `search-keys` | array | `[]` | Array of keys to search when options are objects (if empty, searches all properties) |
| `search-placeholder` | string | `'Type to search...'` | Placeholder text for the search input |
| `multiple` | boolean | `false` | Whether multiple options can be selected |
| `taggable` | boolean | `false` | Whether to allow creating new options (tags) |
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

The ChoicesOffline component supports the following events:

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

The ChoicesOffline component provides a customizable dropdown interface. You can customize the appearance by:

1. Using the provided props and slots
2. Adding custom classes via the `class` attribute
3. Using custom templates for options and selected items

### Custom Styling Example

```php
<x-artisanpack-choices-offline 
    label="Custom styled choices" 
    :options="['Option 1', 'Option 2', 'Option 3']"
    class="border-2 border-purple-500 focus:border-purple-700"
/>
```

## Accessibility

The ChoicesOffline component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Includes appropriate ARIA attributes
- Supports keyboard navigation
- Maintains focus management
- Ensures adequate color contrast
- Provides clear feedback on selection state

## Related Components

- [Form](form) - Container for form elements
- [Select](select) - Standard select dropdown
- [Choices](choices) - Standard choices component with remote data capabilities
- [Tags](tags) - Tags input component
