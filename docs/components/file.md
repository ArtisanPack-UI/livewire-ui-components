# File Component

The File component is a form element that provides an enhanced file upload interface. It supports single and multiple file uploads, preview capabilities, drag-and-drop functionality, and integrates seamlessly with Livewire for handling file uploads.

## Basic Usage

```php
<x-artisanpack-file label="Upload File" />
```

## Examples

### Simple File Upload

```php
<x-artisanpack-file 
    label="Document" 
    name="document"
/>
```

### Multiple File Upload

```php
<x-artisanpack-file 
    label="Photos" 
    multiple
/>
```

### Required File Upload

```php
<x-artisanpack-file 
    label="Resume" 
    required
/>
```

### File Upload with Helper Text

```php
<x-artisanpack-file 
    label="Profile Picture" 
    helper="Maximum file size: 2MB. Supported formats: JPG, PNG"
/>
```

### File Upload with Error

```php
<x-artisanpack-file 
    label="Document" 
    error="Please upload a valid document"
/>
```

### File Upload with Accept Attribute

```php
<x-artisanpack-file 
    label="Image" 
    accept="image/*"
/>

<x-artisanpack-file 
    label="PDF Document" 
    accept=".pdf"
/>

<x-artisanpack-file 
    label="Excel File" 
    accept=".xlsx,.xls"
/>
```

### File Upload with Livewire Binding

```php
<x-artisanpack-file 
    label="Document" 
    wire:model="document"
/>

<x-artisanpack-file 
    label="Photos" 
    wire:model="photos" 
    multiple
/>
```

### File Upload with Custom Button Text

```php
<x-artisanpack-file 
    label="Document" 
    button-text="Select Document"
/>
```

### File Upload with Preview

```php
<x-artisanpack-file 
    label="Image" 
    with-preview
    accept="image/*"
/>
```

### File Upload with Drag and Drop

```php
<x-artisanpack-file 
    label="Drop Files Here" 
    with-drag-drop
/>
```

### File Upload with Progress Indicator

```php
<x-artisanpack-file 
    label="Document" 
    wire:model="document"
    with-progress
/>
```

### File Upload with Size Limit

```php
<x-artisanpack-file 
    label="Image" 
    :max-file-size="2048" 
    helper="Maximum file size: 2MB"
/>
```

### File Upload with Custom Validation

```php
<x-artisanpack-file 
    label="Document" 
    wire:model="document"
    :validate="true"
    :rules="['mimes:pdf,doc,docx', 'max:2048']"
    error="{{ $errors->first('document') }}"
/>
```

### File Upload with Custom Styles

```php
<x-artisanpack-file 
    label="Document" 
    class="border-2 border-dashed border-primary p-6 rounded-lg"
    button-class="bg-primary hover:bg-primary-focus"
/>
```

### File Upload with Disabled State

```php
<x-artisanpack-file 
    label="Document" 
    disabled
/>
```

### File Upload with Custom Icons

```php
<x-artisanpack-file 
    label="Document" 
    upload-icon="heroicon-o-paper-clip"
    success-icon="heroicon-o-check-circle"
    error-icon="heroicon-o-exclamation-circle"
/>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | The label text for the file input |
| `name` | string | `null` | The name attribute for the file input |
| `id` | string | `null` | The ID attribute for the file input (auto-generated if not provided) |
| `multiple` | boolean | `false` | Whether multiple files can be selected |
| `accept` | string | `null` | File types that the server accepts (e.g., '.jpg,.png' or 'image/*') |
| `required` | boolean | `false` | Whether the file input is required |
| `disabled` | boolean | `false` | Whether the file input is disabled |
| `helper` | string | `null` | Helper text displayed below the file input |
| `error` | string | `null` | Error message to display |
| `button-text` | string | `'Choose File'` | Text displayed on the file selection button |
| `button-class` | string | `null` | Additional classes for the button |
| `with-preview` | boolean | `false` | Whether to show a preview of the selected file(s) |
| `with-drag-drop` | boolean | `false` | Whether to enable drag and drop functionality |
| `with-progress` | boolean | `false` | Whether to show a progress indicator during upload (requires Livewire) |
| `max-file-size` | integer | `null` | Maximum file size in kilobytes |
| `validate` | boolean | `false` | Whether to enable client-side validation |
| `rules` | array | `[]` | Validation rules for the file (when using Livewire) |
| `upload-icon` | string | `'heroicon-o-arrow-up-tray'` | Icon for the upload button |
| `success-icon` | string | `'heroicon-o-check'` | Icon displayed on successful upload |
| `error-icon` | string | `'heroicon-o-x-mark'` | Icon displayed on failed upload |

## Events

The File component supports all standard HTML file input events:

- `change`
- `focus`
- `blur`

It also supports all Livewire model binding directives for file uploads:

- `wire:model`
- `wire:model.defer`
- `wire:model.live`

## Styling

The File component provides a customizable file upload interface. You can customize the appearance by:

1. Using the provided props (`button-text`, `button-class`, etc.)
2. Adding custom classes via the `class` attribute
3. Customizing the icons used in the component

### Custom Styling Example

```php
<x-artisanpack-file 
    label="Upload Document" 
    class="border-2 border-dashed border-primary p-6 rounded-lg hover:bg-primary/5 transition-colors"
    button-class="bg-primary text-white hover:bg-primary-focus"
/>
```

## Accessibility

The File component follows accessibility best practices:

- Associates labels with inputs using proper HTML markup
- Provides clear feedback on file selection and upload status
- Includes appropriate ARIA attributes
- Maintains focus management
- Ensures adequate color contrast
- Provides keyboard navigation support

## Related Components

- [Form](form.md) - Container for form elements
- [Input](input.md) - Standard text input
- [ImageGallery](image-gallery.md) - Display a gallery of images
- [ImageLibrary](image-library.md) - Image library/picker component
