---
title: SelectGroup Component
---

# SelectGroup Component

The SelectGroup component is used to group related select options together with a label, providing better organization and visual hierarchy in select dropdowns.

## Basic Usage

```php
<x-artisanpack-select>
    <x-artisanpack-select-group label="Fruits">
        <option value="apple">Apple</option>
        <option value="banana">Banana</option>
        <option value="orange">Orange</option>
    </x-artisanpack-select-group>
    
    <x-artisanpack-select-group label="Vegetables">
        <option value="carrot">Carrot</option>
        <option value="broccoli">Broccoli</option>
        <option value="spinach">Spinach</option>
    </x-artisanpack-select-group>
</x-artisanpack-select>
```

## Examples

### Basic Select Group

```php
<x-artisanpack-select>
    <x-artisanpack-select-group label="Fruits">
        <option value="apple">Apple</option>
        <option value="banana">Banana</option>
        <option value="orange">Orange</option>
    </x-artisanpack-select-group>
    
    <x-artisanpack-select-group label="Vegetables">
        <option value="carrot">Carrot</option>
        <option value="broccoli">Broccoli</option>
        <option value="spinach">Spinach</option>
    </x-artisanpack-select-group>
</x-artisanpack-select>
```

### Multiple Select Groups

```php
<x-artisanpack-select>
    <x-artisanpack-select-group label="Frontend">
        <option value="html">HTML</option>
        <option value="css">CSS</option>
        <option value="javascript">JavaScript</option>
    </x-artisanpack-select-group>
    
    <x-artisanpack-select-group label="Backend">
        <option value="php">PHP</option>
        <option value="python">Python</option>
        <option value="ruby">Ruby</option>
    </x-artisanpack-select-group>
    
    <x-artisanpack-select-group label="Database">
        <option value="mysql">MySQL</option>
        <option value="postgresql">PostgreSQL</option>
        <option value="mongodb">MongoDB</option>
    </x-artisanpack-select-group>
</x-artisanpack-select>
```

### Disabled Group

```php
<x-artisanpack-select>
    <x-artisanpack-select-group label="Available">
        <option value="item1">Item 1</option>
        <option value="item2">Item 2</option>
    </x-artisanpack-select-group>
    
    <x-artisanpack-select-group label="Out of Stock" disabled>
        <option value="item3">Item 3</option>
        <option value="item4">Item 4</option>
    </x-artisanpack-select-group>
</x-artisanpack-select>
```

### With Custom Styling

```php
<x-artisanpack-select>
    <x-artisanpack-select-group label="Priority Tasks" class="font-bold text-error">
        <option value="task1">Complete project proposal</option>
        <option value="task2">Fix critical bug</option>
    </x-artisanpack-select-group>
    
    <x-artisanpack-select-group label="Regular Tasks" class="text-info">
        <option value="task3">Update documentation</option>
        <option value="task4">Review pull requests</option>
    </x-artisanpack-select-group>
</x-artisanpack-select>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the option group |
| `disabled` | boolean | `false` | Whether the entire group of options is disabled |
| `id` | string | `null` | Optional ID for the optgroup element |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The content of the select group, typically option elements |

## Behavior

The SelectGroup component:

1. Wraps options in an HTML `<optgroup>` element
2. Provides a label for the group of options
3. Can be disabled to prevent selection of any options in the group
4. Works within the Select component to organize options

## Styling

The SelectGroup component uses the native HTML `<optgroup>` element, which has limited styling options. However, you can apply some basic styling:

- The `label` attribute sets the visible label for the group
- The `disabled` attribute applies the browser's default disabled styling
- Custom classes can be applied but may have limited effect depending on the browser

## Accessibility

The SelectGroup component follows accessibility best practices:

- Uses the semantic `<optgroup>` HTML element
- Provides proper labeling for groups of options
- Supports keyboard navigation within the select dropdown
- Maintains proper focus management

For better accessibility:

1. Use clear, descriptive labels for option groups
2. Avoid nesting too many groups in a single select
3. Consider using a more advanced component for complex selection needs

## Related Components

- [Select](select) - The parent component for select inputs
- [Choices](choices) - Enhanced select with search and multiple selection
- [ChoicesOffline](choices-offline) - Client-side version of the Choices component