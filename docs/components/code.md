---
title: Code Component
---

# Code Component

The Code component provides a powerful code editor with syntax highlighting, autocompletion, and theme support. It's built on top of the Ace editor and integrates with Livewire for two-way data binding.

## Basic Usage

```php
<x-artisanpack-code wire:model="code" />
```

## Examples

### Basic Code Editor

```php
<x-artisanpack-code 
    wire:model="javascriptCode"
    label="JavaScript Code"
    hint="Write your JavaScript code here"
/>
```

### Different Programming Languages

```php
<!-- PHP Code Editor -->
<x-artisanpack-code 
    wire:model="phpCode"
    language="php"
    label="PHP Code"
/>

<!-- HTML Code Editor -->
<x-artisanpack-code 
    wire:model="htmlCode"
    language="html"
    label="HTML Code"
/>

<!-- CSS Code Editor -->
<x-artisanpack-code 
    wire:model="cssCode"
    language="css"
    label="CSS Code"
/>
```

### Custom Height and Line Height

```php
<x-artisanpack-code 
    wire:model="code"
    height="400px"
    lineHeight="1.8"
/>
```

### Custom Themes

```php
<x-artisanpack-code 
    wire:model="code"
    lightTheme="xcode"
    darkTheme="monokai"
/>
```

### With Print Margin

```php
<x-artisanpack-code 
    wire:model="code"
    :printMargin="true"
/>
```

### In a Form

```php
<form wire:submit.prevent="saveCode">
    <x-artisanpack-code 
        wire:model="code"
        label="Code"
        hint="Enter your code here"
    />
    
    <div class="mt-4">
        <x-artisanpack-button type="submit">Save Code</x-artisanpack-button>
    </div>
</form>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the code editor element |
| `label` | string\|null | `null` | Optional label for the code editor |
| `hint` | string\|null | `''` | Optional hint text displayed below the editor |
| `language` | string | `'javascript'` | Programming language for syntax highlighting |
| `lightTheme` | string\|null | `'github_light_default'` | Theme to use in light mode |
| `darkTheme` | string\|null | `'github_dark'` | Theme to use in dark mode |
| `lightClass` | string\|null | `'light'` | CSS class for light mode |
| `darkClass` | string\|null | `'dark'` | CSS class for dark mode |
| `height` | string | `'200px'` | Height of the code editor |
| `lineHeight` | string | `'2'` | Line height for the code editor |
| `printMargin` | boolean | `false` | Whether to show the print margin |

## Supported Languages

The Code component supports a wide range of programming languages through Ace editor, including:

- JavaScript
- PHP
- HTML
- CSS
- Python
- Ruby
- Java
- C/C++
- C#
- TypeScript
- SQL
- JSON
- XML
- Markdown
- YAML
- And many more

To specify a language, use the `language` prop with the appropriate language identifier.

## Supported Themes

The Code component supports various themes through Ace editor. Some popular themes include:

- github_light_default
- github_dark
- monokai
- twilight
- solarized_dark
- solarized_light
- tomorrow
- tomorrow_night
- xcode
- textmate
- chrome

To specify themes, use the `lightTheme` and `darkTheme` props with the appropriate theme identifiers.

## Styling

The Code component uses the Ace editor with custom styling to match your application's theme. You can customize the appearance by:

1. Using the provided props (`height`, `lineHeight`, etc.)
2. Specifying custom themes via the `lightTheme` and `darkTheme` props
3. Adding custom classes via the `class` attribute

### Default Classes

- `textarea` - Base textarea class from DaisyUI
- `w-full` - Full width
- `p-0` - No padding

## Accessibility

The Code component follows accessibility best practices:

- Provides proper labeling when a label is specified
- Includes error messages for form validation
- Supports keyboard navigation and shortcuts
- Maintains appropriate color contrast in both light and dark themes

## Related Components

- [Textarea](textarea.md) - Simple text area input
- [Markdown](markdown.md) - Markdown editor
- [Editor](editor.md) - Rich text editor