---
title: Editor Component
---

# Editor Component

The Editor component provides a rich text editor with formatting options, image uploads, and more. It's built on top of TinyMCE and integrates with Livewire for two-way data binding.

## Installation

### 1. Publish TinyMCE Resources

First, you need to publish the TinyMCE resources to your public directory:

```bash
php artisan vendor:publish --tag=artisanpack-assets
```

This will copy the TinyMCE files to `public/vendor/artisanpack-ui/js/tinymce`.

### 2. Include the Alpine.js Integration File

You need to include the TinyMCE Alpine.js integration file in your project. Copy the `tinymce-editor.js` file from the package to your project:

```bash
# Create the directory if it doesn't exist
mkdir -p resources/js

# Copy the file
cp vendor/artisanpack-ui/livewire-ui-components/resources/js/tinymce-editor.js resources/js/
```

Then, import it in your main JavaScript file (typically `resources/js/app.js`):

```javascript
// Import the TinyMCE Alpine.js integration
import './tinymce-editor.js';
```

### 3. Load TinyMCE in Your Layout

To prevent "Alpine Expression Error: Can't find variable: tinymce" errors, you must load the TinyMCE script in the `<head>` section of your main layout file, before any Alpine.js components are initialized:

```html
<head>
    <!-- Other head elements -->
    
    <!-- Add this line to load TinyMCE -->
    <script src="{{ asset('vendor/artisanpack-ui/js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    
    <!-- Your other scripts (app.js, etc.) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
```

> **Important:** Loading TinyMCE in the head section ensures it's available globally before Alpine.js initializes any components that depend on it. This prevents race conditions that can cause errors.

## Basic Usage

```php
<x-artisanpack-editor wire:model="content" />
```

## Examples

### Basic Editor

```php
<x-artisanpack-editor 
    wire:model="content"
    label="Content"
    hint="Enter your content here"
/>
```

### Editor with Custom Height

```php
<x-artisanpack-editor 
    wire:model="content"
    :config="['height' => 500]"
/>
```

### Editor with Custom Toolbar

```php
<x-artisanpack-editor 
    wire:model="content"
    :config="[
        'toolbar' => 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image'
    ]"
/>
```

### Editor with Additional Plugins

```php
<x-artisanpack-editor 
    wire:model="content"
    :config="[
        'plugins' => 'code searchreplace wordcount visualblocks visualchars fullscreen insertdatetime media table paste help'
    ]"
/>
```

### Editor with Custom Upload Location

```php
<x-artisanpack-editor 
    wire:model="content"
    disk="s3"
    folder="blog-posts"
/>
```

### Editor with Validation

```php
<x-artisanpack-editor 
    wire:model="content"
    label="Content"
    required
/>

<!-- In your Livewire component -->
protected $rules = [
    'content' => 'required|min:10',
];
```

### Disabled Editor

```php
<x-artisanpack-editor 
    wire:model="content"
    disabled
/>
```

### Editor with GPL License

```php
<x-artisanpack-editor 
    wire:model="content"
    :gplLicense="true"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string\|null | `null` | Optional ID for the editor element |
| `label` | string\|null | `null` | Optional label for the editor |
| `hint` | string\|null | `null` | Optional hint text displayed below the editor |
| `hintClass` | string\|null | `'fieldset-label'` | CSS class for the hint text |
| `disk` | string\|null | `'public'` | Storage disk for file uploads |
| `folder` | string\|null | `'editor'` | Folder within the disk for file uploads |
| `gplLicense` | boolean\|null | `false` | Whether to use the GPL license for TinyMCE |
| `config` | array\|null | `[]` | Additional configuration options for TinyMCE |
| `errorField` | string\|null | `null` | Custom field name for validation errors |
| `errorClass` | string\|null | `'text-error'` | CSS class for error messages |
| `omitError` | boolean\|null | `false` | Whether to hide error messages |
| `firstErrorOnly` | boolean\|null | `false` | Whether to show only the first error message |

## Configuration Options

The `config` prop accepts an array of options that control how the editor is displayed and behaves. Here are some common options:

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `height` | int | `300` | Height of the editor in pixels |
| `menubar` | boolean | `false` | Whether to show the menu bar |
| `toolbar` | string | `'undo redo \| align bullist numlist \| outdent indent \| quickimage quicktable'` | Toolbar buttons |
| `plugins` | string | `'advlist autolink lists link image table quickbars'` | Enabled plugins |
| `quickbars_selection_toolbar` | string | `'bold italic underline strikethrough \| forecolor backcolor \| link blockquote removeformat \| blocks'` | Toolbar that appears when text is selected |
| `automatic_uploads` | boolean | `true` | Whether to automatically upload images when inserted |
| `branding` | boolean | `false` | Whether to show TinyMCE branding |

For a complete list of configuration options, refer to the [TinyMCE documentation](https://www.tiny.cloud/docs/configure/).

## File Uploads

The Editor component supports file uploads out of the box. When a user inserts an image or file, it will be uploaded to the specified disk and folder. By default, files are stored in the `public` disk in the `editor` folder.

You can customize the upload location using the `disk` and `folder` props:

```php
<x-artisanpack-editor 
    wire:model="content"
    disk="s3"
    folder="uploads/blog-posts"
/>
```

## Dark Mode Support

The Editor component automatically detects and adapts to dark mode. When your application is in dark mode, the editor will use the dark theme for better visibility and reduced eye strain.

## Styling

The Editor component uses TinyMCE with custom styling to match your application's theme. The editor is wrapped in a fieldset for structure and styling.

### Default Classes

- `fieldset` - Container for the editor
- `fieldset-legend` - Applied to the label
- `fieldset-label` - Applied to the hint text
- `text-error` - Applied to error messages

## Accessibility

The Editor component follows accessibility best practices:

- Uses semantic HTML with proper labeling
- Supports keyboard navigation
- Provides error messages for validation
- Adapts to dark mode for better visibility
- Includes proper ARIA attributes

## Related Components

- [Textarea](textarea) - Simple text area input
- [Markdown](markdown) - Markdown editor
- [Code](code) - Code editor with syntax highlighting
