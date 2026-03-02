---
title: Form Component
---

# Form Component

The Form component is a container for form elements that provides a consistent layout, validation handling, and submission functionality. It works seamlessly with Livewire and other ArtisanPack UI form components.

## Basic Usage

```php
<x-artisanpack-form wire:submit="save">
    <x-artisanpack-input label="Name" wire:model="name" required />
    <x-artisanpack-input label="Email" wire:model="email" type="email" required />
    
    <x-slot:actions>
        <x-artisanpack-button type="submit">Submit</x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-form>
```

## Examples

### Simple Form

```php
<x-artisanpack-form action="/submit" method="POST">
    @csrf
    <x-artisanpack-input label="Name" name="name" required />
    <x-artisanpack-input label="Email" name="email" type="email" required />
    <x-artisanpack-textarea label="Message" name="message" required />
    
    <x-slot:actions>
        <x-artisanpack-button type="submit">Send Message</x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-form>
```

### Form with Livewire

```php
<x-artisanpack-form wire:submit="register">
    <x-artisanpack-input label="Name" wire:model="name" required />
    <x-artisanpack-input label="Email" wire:model="email" type="email" required />
    <x-artisanpack-password label="Password" wire:model="password" required />
    <x-artisanpack-password label="Confirm Password" wire:model="passwordConfirmation" required />
    
    <x-slot:actions>
        <x-artisanpack-button type="submit" color="primary">Register</x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-form>
```

### Form with Validation Errors

```php
<x-artisanpack-form wire:submit="save">
    <x-artisanpack-input 
        label="Name" 
        wire:model="name" 
        required 
        error="{{ $errors->first('name') }}"
    />
    <x-artisanpack-input 
        label="Email" 
        wire:model="email" 
        type="email" 
        required 
        error="{{ $errors->first('email') }}"
    />
    <x-artisanpack-textarea 
        label="Message" 
        wire:model="message" 
        required 
        error="{{ $errors->first('message') }}"
    />
    
    <x-slot:actions>
        <x-artisanpack-button type="submit">Submit</x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-form>
```

### Form with Multiple Columns

```php
<x-artisanpack-form wire:submit="saveAddress">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-artisanpack-input label="First Name" wire:model="firstName" required />
        <x-artisanpack-input label="Last Name" wire:model="lastName" required />
        <x-artisanpack-input label="Street Address" wire:model="street" required />
        <x-artisanpack-input label="Apartment/Suite" wire:model="apartment" />
        <x-artisanpack-input label="City" wire:model="city" required />
        <x-artisanpack-input label="State/Province" wire:model="state" required />
        <x-artisanpack-input label="Postal Code" wire:model="postalCode" required />
        <x-artisanpack-select label="Country" wire:model="country" :options="$countries" required />
    </div>
    
    <x-slot:actions>
        <x-artisanpack-button type="button" color="ghost" wire:click="cancel">
            Cancel
        </x-artisanpack-button>
        <x-artisanpack-button type="submit" color="primary">
            Save Address
        </x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-form>
```

### Form with Sections

```php
<x-artisanpack-form wire:submit="saveProfile">
    <div class="space-y-8">
        <div>
            <h3 class="text-lg font-medium">Personal Information</h3>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-artisanpack-input label="First Name" wire:model="firstName" required />
                <x-artisanpack-input label="Last Name" wire:model="lastName" required />
                <x-artisanpack-input label="Email" wire:model="email" type="email" required />
                <x-artisanpack-input label="Phone" wire:model="phone" type="tel" />
            </div>
        </div>
        
        <div>
            <h3 class="text-lg font-medium">Account Preferences</h3>
            <div class="mt-4 space-y-4">
                <x-artisanpack-select label="Language" wire:model="language" :options="$languages" />
                <x-artisanpack-select label="Timezone" wire:model="timezone" :options="$timezones" />
                <x-artisanpack-checkbox label="Receive email notifications" wire:model="emailNotifications" />
            </div>
        </div>
    </div>
    
    <x-slot:actions>
        <x-artisanpack-button type="submit" color="primary">Save Profile</x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-form>
```

### Form with File Upload

```php
<x-artisanpack-form wire:submit="uploadDocument" enctype="multipart/form-data">
    <x-artisanpack-input label="Document Title" wire:model="title" required />
    <x-artisanpack-file label="Document File" wire:model="document" required />
    <x-artisanpack-textarea label="Description" wire:model="description" />
    
    <x-slot:actions>
        <x-artisanpack-button type="submit" color="primary">
            Upload Document
        </x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-form>
```

### Form with Custom Spacing

```php
<x-artisanpack-form wire:submit="save" spacing="8">
    <x-artisanpack-input label="Name" wire:model="name" required />
    <x-artisanpack-input label="Email" wire:model="email" type="email" required />
    <x-artisanpack-textarea label="Message" wire:model="message" required />
    
    <x-slot:actions>
        <x-artisanpack-button type="submit">Submit</x-artisanpack-button>
    </x-slot:actions>
</x-artisanpack-form>
```

### Form with Loading State

```php
<x-artisanpack-form wire:submit="save" wire:loading.class="opacity-50">
    <x-artisanpack-input label="Name" wire:model="name" required />
    <x-artisanpack-input label="Email" wire:model="email" type="email" required />
    <x-artisanpack-textarea label="Message" wire:model="message" required />
    
    <x-slot:actions>
        <div class="flex items-center space-x-4">
            <x-artisanpack-button type="submit" wire:loading.attr="disabled">
                Submit
            </x-artisanpack-button>
            <div wire:loading>
                <x-artisanpack-loading size="sm" />
            </div>
        </div>
    </x-slot:actions>
</x-artisanpack-form>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `no-separator` | boolean | `false` | Whether to hide the separator line between form content and actions |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The main content of the form (form elements) |
| `actions` | Form actions (submit button, cancel button, etc.) |

## Events

The Form component supports all standard HTML form events:

- `submit`
- `reset`

It also supports all Livewire form submission directives:

- `wire:submit`
- `wire:submit.prevent`

## Styling

The Form component provides a consistent layout for form elements. You can customize the appearance by:

1. Using the provided props (`spacing`, etc.)
2. Adding custom classes via the `class` attribute
3. Customizing the layout within the form

### Custom Styling Example

```php
<x-artisanpack-form 
    wire:submit="save" 
    class="bg-gray-50 p-6 rounded-lg shadow-md"
>
    <x-artisanpack-input label="Name" wire:model="name" required />
    <x-artisanpack-input label="Email" wire:model="email" type="email" required />
    
    <x-slot:actions>
        <div class="flex justify-end space-x-4">
            <x-artisanpack-button type="button" color="ghost">
                Cancel
            </x-artisanpack-button>
            <x-artisanpack-button type="submit" color="primary">
                Submit
            </x-artisanpack-button>
        </div>
    </x-slot:actions>
</x-artisanpack-form>
```

## Accessibility

The Form component follows accessibility best practices:

- Uses semantic HTML form elements
- Maintains proper focus management
- Ensures form controls have associated labels
- Provides clear error messages
- Supports keyboard navigation

## Related Components

- [Input](Input) - Text input field
- [Textarea](Textarea) - Multi-line text input
- [Select](Select) - Dropdown select input
- [Checkbox](Checkbox) - Checkbox input
- [Radio](Radio) - Radio button input
- [Button](Button) - Button element
- [Errors](Errors) - Form validation errors display
