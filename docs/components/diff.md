# Diff Component

The Diff component provides a visual way to display differences between two text strings, similar to how Git shows file differences. It's useful for comparing code, configuration files, or any text-based content.

## Basic Usage

```php
<x-artisanpack-diff 
    old="This is the original text."
    new="This is the modified text."
/>
```

## Examples

### Basic Text Comparison

```php
<x-artisanpack-diff 
    old="This is the original text."
    new="This is the modified text with some changes."
/>
```

### Code Comparison

```php
@php
$oldCode = <<<'CODE'
function hello() {
    console.log("Hello, world!");
}
CODE;

$newCode = <<<'CODE'
function hello(name) {
    console.log(`Hello, ${name}!`);
    return name;
}
CODE;
@endphp

<x-artisanpack-diff 
    :old="$oldCode"
    :new="$newCode"
    fileName="hello.js"
/>
```

### JSON Comparison

```php
@php
$oldJson = <<<'JSON'
{
    "name": "John Doe",
    "age": 30,
    "email": "john@example.com"
}
JSON;

$newJson = <<<'JSON'
{
    "name": "John Doe",
    "age": 31,
    "email": "john.doe@example.com",
    "address": {
        "city": "New York",
        "country": "USA"
    }
}
JSON;
@endphp

<x-artisanpack-diff 
    :old="$oldJson"
    :new="$newJson"
    fileName="user.json"
/>
```

### Unified View

```php
<x-artisanpack-diff 
    old="This is the original text."
    new="This is the modified text."
    :config="['outputFormat' => 'line-by-line']"
/>
```

### With Custom Configuration

```php
<x-artisanpack-diff 
    old="This is the original text."
    new="This is the modified text."
    :config="[
        'outputFormat' => 'line-by-line',
        'matching' => 'words',
        'synchronisedScroll' => false,
        'highlight' => true,
        'renderNothingWhenEmpty' => false
    ]"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the diff element |
| `old` | string | `''` | The original text |
| `new` | string | `''` | The new text to compare against the original |
| `fileName` | string | `'payload.json'` | The name of the file being compared |
| `config` | array\|null | `[]` | Additional configuration options for the diff display |

## Configuration Options

The `config` prop accepts an array of options that control how the diff is displayed. Here are the available options:

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `drawFileList` | boolean | `false` | Whether to show a file list |
| `matching` | string | `'lines'` | The matching method (`'lines'` or `'words'`) |
| `outputFormat` | string | `'side-by-side'` | The output format (`'side-by-side'` or `'line-by-line'`) |
| `synchronisedScroll` | boolean | `true` | Whether to synchronize scrolling between old and new |
| `fileContentToggle` | boolean | `false` | Whether to show a toggle for file content |
| `highlight` | boolean | - | Whether to highlight changes |
| `renderNothingWhenEmpty` | boolean | - | Whether to render nothing when there are no differences |

## Styling

The Diff component uses the Diff2HtmlUI JavaScript library with custom styling to match your application's theme. The component applies several Tailwind CSS classes to customize the appearance of the diff display.

### Default Classes

The component applies the following CSS classes to the diff display:

- `!text-xs` - Sets the text size to extra small
- `!bg-base-100` - Sets the background color for headers
- `!border-dashed !border-[length:var(--border)]` - Adds a dashed border
- `!bg-red-50` - Background color for deleted lines
- `!bg-green-50` - Background color for inserted lines
- `!whitespace-pre-wrap` - Preserves whitespace and wraps text
- `!w-auto` - Sets the width to auto for code lines

## Accessibility

The Diff component follows accessibility best practices:

- Uses semantic HTML for the diff display
- Provides clear visual indicators for additions and deletions
- Maintains appropriate color contrast for readability
- Preserves text structure for screen readers

## Related Components

- [Code](code.md) - Code editor with syntax highlighting
- [Markdown](markdown.md) - Markdown editor and renderer
- [Editor](editor.md) - Rich text editor