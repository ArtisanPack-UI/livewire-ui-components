---
title: Getting Started
menu_order: 2
---

# Getting Started

```bash
composer require artisanpack-ui/livewire-ui-components
php artisan livewire-ui-components:install
```

Then add the design system's assets and the theme script to your layout, and use the components:

```blade
<x-artisanpack-input :label="__( 'Email' )" type="email" wire:model="email" />

<x-artisanpack-button color="primary" wire:click="save">
    {{ __( 'Save changes' ) }}
</x-artisanpack-button>
```

- [Installation](Getting-Started-Installation): the installer, loading the CSS and JavaScript with or without a bundler, and the Blade directives.
- [Configuration](Getting-Started-Configuration): the package's config file.

For the design system's concepts (tokens, themes, presets, cascade layers), see [Getting Started with Livewire](https://docs.artisanpackui.dev/documentation/design-system/getting-started/livewire) in the design-system documentation.
