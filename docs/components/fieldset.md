---
title: Fieldset Component
---

# Fieldset Component

The Fieldset component provides a styled container for grouping related form fields, often including a header with title, subtitle, and actions.

## Basic Usage

```php
<x-artisanpack-fieldset title="Personal Information">
    <x-artisanpack-input label="First Name" wire:model="firstName" />
    <x-artisanpack-input label="Last Name" wire:model="lastName" />
    <x-artisanpack-input label="Email" type="email" wire:model="email" />
</x-artisanpack-fieldset>
```

## Examples

### With Title and Subtitle

```php
<x-artisanpack-fieldset
    title="Account Settings"
    subtitle="Manage your account preferences and security settings"
>
    <x-artisanpack-input label="Username" wire:model="username" />
    <x-artisanpack-password label="Current Password" wire:model="currentPassword" />
    <x-artisanpack-password label="New Password" wire:model="newPassword" />
</x-artisanpack-fieldset>
```

### With Icon

```php
<x-artisanpack-fieldset
    title="Billing Information"
    subtitle="Update your billing details"
    icon="o-credit-card"
>
    <x-artisanpack-input label="Card Number" wire:model="cardNumber" />
    <x-artisanpack-input label="Expiry Date" wire:model="expiryDate" />
    <x-artisanpack-input label="CVV" wire:model="cvv" />
</x-artisanpack-fieldset>
```

### Without Separator

```php
<x-artisanpack-fieldset
    title="Preferences"
    :separator="false"
>
    <x-artisanpack-toggle label="Email Notifications" wire:model="emailNotifications" />
    <x-artisanpack-toggle label="SMS Notifications" wire:model="smsNotifications" />
</x-artisanpack-fieldset>
```

### Custom Colors

```php
<x-artisanpack-fieldset
    title="Important Settings"
    subtitle="These settings affect your account security"
    bg-color="bg-warning/10"
    text-color="text-warning"
    border="border-2 border-warning"
>
    <x-artisanpack-toggle label="Two-Factor Authentication" wire:model="twoFactorEnabled" />
    <x-artisanpack-input label="Recovery Email" wire:model="recoveryEmail" />
</x-artisanpack-fieldset>
```

### With Actions Slot

```php
<x-artisanpack-fieldset
    title="Profile"
    subtitle="Your public profile information"
>
    <x-slot:actions>
        <x-artisanpack-button size="sm" color="primary">
            Edit Profile
        </x-artisanpack-button>
    </x-slot:actions>

    <x-artisanpack-input label="Display Name" wire:model="displayName" />
    <x-artisanpack-textarea label="Bio" wire:model="bio" />
</x-artisanpack-fieldset>
```

### With Middle Slot

```php
<x-artisanpack-fieldset
    title="Advanced Options"
>
    <x-slot:middle>
        <x-artisanpack-badge value="Beta" color="info" />
    </x-slot:middle>

    <x-slot:actions>
        <x-artisanpack-button size="sm" outline>
            Learn More
        </x-artisanpack-button>
    </x-slot:actions>

    <!-- Form fields here -->
</x-artisanpack-fieldset>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `title` | string | `null` | The title to display in the header |
| `subtitle` | string | `null` | The subtitle to display in the header |
| `icon` | string | `null` | The icon to display in the header |
| `separator` | boolean | `true` | Whether to show a separator in the header |
| `bgColor` | string | `'bg-base-100'` | The background color class |
| `textColor` | string | `null` | The text color class |
| `border` | string | `'border border-base-300'` | The border classes |

## Slots

| Slot | Description |
|------|-------------|
| `default` | The main content area for form fields |
| `middle` | Content to display in the middle section of the header (between title and actions) |
| `actions` | Content to display in the actions section of the header (typically buttons) |

## Styling

### Custom Background and Border

```php
<x-artisanpack-fieldset
    title="Dark Theme Section"
    bg-color="bg-base-200"
    border="border-2 border-primary"
>
    <!-- Form fields -->
</x-artisanpack-fieldset>
```

### Colored Header

```php
<x-artisanpack-fieldset
    title="Success Section"
    subtitle="Everything looks good"
    text-color="text-success"
    icon="o-check-circle"
>
    <!-- Form fields -->
</x-artisanpack-fieldset>
```

## Accessibility

The Fieldset component follows accessibility best practices:

- Uses semantic `<fieldset>` and `<legend>` HTML elements
- Proper heading hierarchy
- Screen reader friendly structure
- Keyboard navigation support

## Use Cases

The Fieldset component is ideal for:

1. **Form Sections**: Group related form fields together
2. **Settings Panels**: Organize settings into logical sections
3. **Multi-Step Forms**: Separate form steps visually
4. **Account Management**: Group account-related inputs
5. **Profile Editing**: Organize profile information sections

## Related Components

- [Form](form) - Main form container
- [Card](card) - General content container
- [Header](header) - Standalone header component
- [Group](group) - Group form elements horizontally
