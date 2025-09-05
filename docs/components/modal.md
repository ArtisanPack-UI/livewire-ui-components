---
title: Modal Component
---

# Modal Component

The Modal component is a layout element that displays content in a dialog box that overlays the page. It's commonly used for alerts, confirmations, forms, and other content that requires user attention or interaction without navigating away from the current page.

## Basic Usage

```php
<x-artisanpack-modal title="Modal Title">
    Modal content goes here
</x-artisanpack-modal>
```

## Examples

### Simple Modal

```php
<x-artisanpack-button @click="$refs.myModal.showModal()">
    Open Modal
</x-artisanpack-button>

<x-artisanpack-modal title="Welcome" id="myModal">
    <p>Welcome to ArtisanPack UI! This is a simple modal dialog.</p>
    
    <x-slot:footer>
        <x-artisanpack-button @click="$refs.myModal.close()">
            Close
        </x-artisanpack-button>
    </x-slot:footer>
</x-artisanpack-modal>
```

### Modal with Custom Header

```php
<x-artisanpack-modal id="customHeaderModal">
    <x-slot:header>
        <div class="flex items-center">
            <x-artisanpack-icon name="heroicon-o-information-circle" class="w-6 h-6 mr-2 text-info" />
            <h3 class="text-lg font-bold">Important Information</h3>
        </div>
    </x-slot:header>
    
    <p>This modal has a custom header with an icon.</p>
    
    <x-slot:footer>
        <x-artisanpack-button @click="$refs.customHeaderModal.close()">
            Got it
        </x-artisanpack-button>
    </x-slot:footer>
</x-artisanpack-modal>
```

### Modal with Form

```php
<x-artisanpack-modal title="Create User" id="formModal">
    <x-artisanpack-form wire:submit="createUser">
        <x-artisanpack-input label="Name" wire:model="name" required />
        <x-artisanpack-input label="Email" wire:model="email" type="email" required />
        <x-artisanpack-password label="Password" wire:model="password" required />
        
        <x-slot:footer>
            <div class="flex justify-end space-x-2">
                <x-artisanpack-button color="ghost" @click="$refs.formModal.close()">
                    Cancel
                </x-artisanpack-button>
                <x-artisanpack-button type="submit" color="primary">
                    Create
                </x-artisanpack-button>
            </div>
        </x-slot:footer>
    </x-artisanpack-form>
</x-artisanpack-modal>
```

### Modal with Confirmation

```php
<x-artisanpack-modal title="Confirm Deletion" id="confirmModal">
    <p>Are you sure you want to delete this item? This action cannot be undone.</p>
    
    <x-slot:footer>
        <div class="flex justify-end space-x-2">
            <x-artisanpack-button color="ghost" @click="$refs.confirmModal.close()">
                Cancel
            </x-artisanpack-button>
            <x-artisanpack-button color="error" wire:click="deleteItem">
                Delete
            </x-artisanpack-button>
        </div>
    </x-slot:footer>
</x-artisanpack-modal>
```

### Modal with Close Button

```php
<x-artisanpack-modal title="Information" id="closeButtonModal" with-close-button>
    <p>This modal has a close button in the top-right corner.</p>
</x-artisanpack-modal>
```

### Modal with Custom Close Button

```php
<x-artisanpack-modal title="Information" id="customCloseModal">
    <x-slot:close-button>
        <button @click="$refs.customCloseModal.close()" class="absolute top-2 right-2 p-2 rounded-full hover:bg-base-200">
            <x-artisanpack-icon name="heroicon-o-x-mark" class="w-5 h-5" />
        </button>
    </x-slot:close-button>
    
    <p>This modal has a custom close button.</p>
</x-artisanpack-modal>
```

### Modal with Custom Width

```php
<x-artisanpack-modal title="Wide Modal" id="wideModal" width="max-w-4xl">
    <p>This modal is wider than the default modal.</p>
</x-artisanpack-modal>
```

### Modal with Custom Position

```php
<x-artisanpack-modal title="Top Modal" id="topModal" position="items-start">
    <p>This modal appears at the top of the screen.</p>
</x-artisanpack-modal>
```

### Modal with Backdrop Click to Close

```php
<x-artisanpack-modal title="Click Outside" id="backdropModal" backdrop-close>
    <p>Click outside this modal to close it.</p>
</x-artisanpack-modal>
```

### Modal with Escape Key to Close

```php
<x-artisanpack-modal title="Press Escape" id="escapeModal" escape-close>
    <p>Press the Escape key to close this modal.</p>
</x-artisanpack-modal>
```

### Modal with No Close Options

```php
<x-artisanpack-modal title="No Close" id="noCloseModal" :backdrop-close="false" :escape-close="false">
    <p>This modal can only be closed programmatically.</p>
    
    <x-slot:footer>
        <x-artisanpack-button @click="$refs.noCloseModal.close()">
            Close
        </x-artisanpack-button>
    </x-slot:footer>
</x-artisanpack-modal>
```

### Modal with Custom Animation

```php
<x-artisanpack-modal title="Custom Animation" id="animationModal" animation="fade-in-scale">
    <p>This modal has a custom animation.</p>
</x-artisanpack-modal>
```

### Modal with Livewire Integration

```php
<div x-data="{ open: @entangle('showModal') }">
    <x-artisanpack-modal title="Livewire Modal" x-show="open" @close="open = false">
        <p>This modal is controlled by a Livewire property.</p>
        
        <x-slot:footer>
            <x-artisanpack-button wire:click="closeModal">
                Close
            </x-artisanpack-button>
        </x-slot:footer>
    </x-artisanpack-modal>
</div>
```

### Modal with Alpine.js Integration

```php
<div x-data="{ open: false, message: '' }">
    <x-artisanpack-button @click="open = true; message = 'Hello from Alpine.js!'">
        Open Alpine Modal
    </x-artisanpack-button>
    
    <x-artisanpack-modal title="Alpine.js Modal" x-show="open" @close="open = false">
        <p x-text="message"></p>
        
        <x-slot:footer>
            <x-artisanpack-button @click="open = false">
                Close
            </x-artisanpack-button>
        </x-slot:footer>
    </x-artisanpack-modal>
</div>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `id` | string | `null` | The ID of the modal (auto-generated if not provided) |
| `title` | string | `null` | The title of the modal |
| `width` | string | `'max-w-md'` | The maximum width of the modal |
| `position` | string | `'items-center'` | The vertical position of the modal (`items-start`, `items-center`, `items-end`) |
| `with-close-button` | boolean | `false` | Whether to display a close button in the top-right corner |
| `backdrop-close` | boolean | `true` | Whether clicking the backdrop closes the modal |
| `escape-close` | boolean | `true` | Whether pressing the Escape key closes the modal |
| `animation` | string | `'fade'` | The animation style for the modal (`fade`, `slide`, `scale`, `fade-in-scale`) |
| `z-index` | string | `'z-50'` | The z-index of the modal |
| `backdrop-class` | string | `'bg-black/50'` | Additional classes for the backdrop |
| `modal-class` | string | `null` | Additional classes for the modal dialog |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The main content of the modal |
| `header` | Custom header content (overrides the `title` prop) |
| `footer` | Footer content for the modal |
| `close-button` | Custom close button content (only used if `with-close-button` is `true` or this slot is provided) |

## Events

The Modal component supports the following events:

- `show` - Triggered when the modal is shown
- `shown` - Triggered after the modal has been shown and animations are complete
- `hide` - Triggered when the modal is hidden
- `hidden` - Triggered after the modal has been hidden and animations are complete

## Methods

The Modal component exposes the following methods that can be accessed via JavaScript:

| Method | Description |
|--------|-------------|
| `showModal()` | Opens the modal |
| `close()` | Closes the modal |
| `toggle()` | Toggles the modal between open and closed states |

## Styling

The Modal component uses DaisyUI's modal component under the hood, which provides a consistent styling with other components. You can customize the appearance by:

1. Using the provided props (`width`, `position`, etc.)
2. Adding custom classes via the `modal-class` and `backdrop-class` attributes
3. Using custom slots for the header, footer, and close button

### Custom Styling Example

```php
<x-artisanpack-modal 
    title="Custom Styled Modal" 
    id="styledModal"
    modal-class="bg-base-200 border-2 border-primary rounded-lg shadow-xl"
    backdrop-class="bg-primary/20 backdrop-blur-sm"
>
    <p>This modal has custom styling.</p>
</x-artisanpack-modal>
```

## Accessibility

The Modal component follows accessibility best practices:

- Uses the HTML `<dialog>` element for native modal behavior
- Sets appropriate ARIA attributes (`aria-labelledby`, `aria-modal`, etc.)
- Traps focus within the modal when open
- Returns focus to the triggering element when closed
- Supports keyboard navigation and interaction
- Provides multiple ways to close the modal (close button, Escape key, backdrop click)

## Related Components

- [Drawer](drawer) - Side panel that slides in from the edge of the screen
- [Dropdown](dropdown) - Dropdown menu for navigation or actions
- [Popover](popover) - Small overlay for additional information or controls
