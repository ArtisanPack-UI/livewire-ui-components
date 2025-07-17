# Markdown Component

The Markdown component provides a full-featured Markdown editor with live preview, image uploads, and formatting tools. It's built on top of EasyMDE and integrates with Livewire for two-way data binding.

## Basic Usage

```php
<x-artisanpack-markdown wire:model="content" />
```

## Examples

### Basic Markdown Editor

```php
<x-artisanpack-markdown 
    wire:model="content"
    label="Content"
    hint="Use Markdown to format your content"
/>
```

### Markdown Editor with Custom Storage Location

```php
<x-artisanpack-markdown 
    wire:model="content"
    disk="s3"
    folder="blog-posts/images"
/>
```

### Markdown Editor with Custom Toolbar

```php
@php
$config = [
    'toolbar' => [
        'heading',
        'bold',
        'italic',
        '|',
        'unordered-list',
        'ordered-list',
        '|',
        'link',
        'upload-image',
        '|',
        'preview'
    ]
];
@endphp

<x-artisanpack-markdown 
    wire:model="content"
    :config="$config"
/>
```

### Markdown Editor with Validation

```php
<x-artisanpack-markdown 
    wire:model="content"
    label="Content"
    required
/>

<!-- In your Livewire component -->
protected $rules = [
    'content' => 'required|min:10',
];
```

### Markdown Editor with Custom Error Handling

```php
<x-artisanpack-markdown 
    wire:model="content"
    errorField="post_content"
    errorClass="text-red-500 text-sm mt-1"
    :firstErrorOnly="true"
/>
```

### Markdown Editor with Custom Configuration

```php
@php
$config = [
    'spellChecker' => true,
    'autofocus' => true,
    'placeholder' => 'Write your content here...',
    'status' => ['lines', 'words', 'cursor'],
    'lineWrapping' => false,
    'tabSize' => 4
];
@endphp

<x-artisanpack-markdown 
    wire:model="content"
    :config="$config"
/>
```

### Markdown Editor in a Form

```php
<form wire:submit.prevent="savePost">
    <x-artisanpack-input wire:model="title" label="Title" />
    
    <x-artisanpack-markdown 
        wire:model="content"
        label="Content"
        hint="Use Markdown to format your post"
    />
    
    <div class="mt-4">
        <x-artisanpack-button type="submit">Save Post</x-artisanpack-button>
    </div>
</form>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the markdown editor element |
| `label` | string\|null | `null` | Optional label for the markdown editor |
| `hint` | string\|null | `null` | Optional hint text displayed below the editor |
| `hintClass` | string\|null | `'fieldset-label'` | CSS class for the hint text |
| `disk` | string\|null | `'public'` | Storage disk for image uploads |
| `folder` | string\|null | `'markdown'` | Folder within the disk for image uploads |
| `config` | array\|null | `[]` | Additional configuration options for EasyMDE |
| `errorField` | string\|null | `null` | Custom field name for validation errors |
| `errorClass` | string\|null | `'text-error'` | CSS class for error messages |
| `omitError` | boolean\|null | `false` | Whether to hide error messages |
| `firstErrorOnly` | boolean\|null | `false` | Whether to show only the first error message |

## Configuration Options

The `config` prop accepts an array of options that control how the editor is displayed and behaves. Here are some common options:

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `spellChecker` | boolean | `false` | Whether to enable spell checking |
| `autoSave` | boolean | `false` | Whether to automatically save content to localStorage |
| `uploadImage` | boolean | `true` | Whether to enable image uploads |
| `imageAccept` | string | `'image/png, image/jpeg, image/gif, image/avif'` | Accepted image MIME types |
| `placeholder` | string | - | Placeholder text when the editor is empty |
| `autofocus` | boolean | - | Whether to focus the editor on load |
| `lineWrapping` | boolean | - | Whether to wrap long lines |
| `tabSize` | number | - | Number of spaces per tab |
| `status` | array | - | Status bar items to display |
| `toolbar` | array | See below | Toolbar buttons to display |

### Default Toolbar

The default toolbar includes the following buttons:

```php
[
    'heading',
    'bold',
    'italic',
    'strikethrough',
    '|',
    'code',
    'quote',
    'unordered-list',
    'ordered-list',
    'horizontal-rule',
    '|',
    'link',
    'upload-image',
    'table',
    '|',
    'preview',
    'side-by-side'
]
```

## Image Uploads

The Markdown component supports image uploads out of the box. When a user drags and drops an image or uses the upload button, the image will be uploaded to the specified disk and folder. By default, images are stored in the `public` disk in the `markdown` folder.

You can customize the upload location using the `disk` and `folder` props:

```php
<x-artisanpack-markdown 
    wire:model="content"
    disk="s3"
    folder="uploads/blog-posts"
/>
```

## Styling

The Markdown component uses EasyMDE with custom styling to match your application's theme. The editor is wrapped in a fieldset for structure and styling.

### Default Classes

- `fieldset` - Container for the editor
- `fieldset-legend` - Applied to the label
- `fieldset-label` - Applied to the hint text
- `text-error` - Applied to error messages

## Accessibility

The Markdown component follows accessibility best practices:

- Uses semantic HTML with proper labeling
- Supports keyboard navigation and shortcuts
- Provides error messages for validation
- Includes proper ARIA attributes
- Maintains focus management

## Related Components

- [Textarea](textarea.md) - Simple text area input
- [Editor](editor.md) - Rich text editor
- [Code](code.md) - Code editor with syntax highlighting