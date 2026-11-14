# ArtisanPack UI Livewire UI Components

Blade and Livewire components for Laravel, built on the [ArtisanPack UI design system](https://docs.artisanpackui.dev/documentation/design-system/home). Version 3.0 renders the design system's components, with the same markup, accessibility and behavior as its React and Vue packages, and binds them to Livewire with `wire:model`.

## Requirements

- PHP 8.2 or later
- Laravel 12 or 13
- Livewire 3.6 or later, or Livewire 4

## Installation

```bash
composer require artisanpack-ui/livewire-ui-components
php artisan livewire-ui-components:install
```

The installer sets up the design system's CSS and JavaScript, either through npm and Vite or as prebuilt files for apps without a build step, and copies a starter layout into a fresh app. Put the theme script and the toaster in your layout:

```blade
<head>
    @apuiThemeScript
    @artisanpackStyles
    @artisanpackScripts
</head>
<body>
    {{ $slot }}

    <x-artisanpack-toaster />
</body>
```

See [Installation](https://docs.artisanpackui.dev/documentation/livewire-ui-components/getting-started/installation) for both asset paths and the installer's options.

## Usage

```blade
<x-artisanpack-input wire:model="email" type="email" :label="__( 'Email' )" />

<x-artisanpack-button color="primary" leading-icon="plus" wire:click="save">
    {{ __( 'Save' ) }}
</x-artisanpack-button>

<x-artisanpack-dialog wire:model="confirming" role="alertdialog" :title="__( 'Delete listing?' )">
    <x-slot:footer>
        <x-artisanpack-button color="error" wire:click="delete">{{ __( 'Delete listing' ) }}</x-artisanpack-button>
    </x-slot:footer>
</x-artisanpack-dialog>
```

Style components with their props (`color`, `variant`, `size`, `surface`) and per-part `:classes`. Theme them with the design tokens, and generate a brand preset with `php artisan artisanpack:generate-preset`.

## Documentation

- [Getting Started](https://docs.artisanpackui.dev/documentation/livewire-ui-components/getting-started)
- [Configuration](https://docs.artisanpackui.dev/documentation/livewire-ui-components/getting-started/configuration)
- [Components](https://docs.artisanpackui.dev/documentation/livewire-ui-components/components)
- [Livewire Integration](https://docs.artisanpackui.dev/documentation/livewire-ui-components/livewire)
- [Theming](https://docs.artisanpackui.dev/documentation/livewire-ui-components/theming)
- [Icons](https://docs.artisanpackui.dev/documentation/livewire-ui-components/icons)
- [Extension Hooks](https://docs.artisanpackui.dev/documentation/livewire-ui-components/advanced/extension-hooks)
- [Upgrading from 2.x](https://docs.artisanpackui.dev/documentation/livewire-ui-components/upgrading): `php artisan artisanpack:upgrade-ui` rewrites 2.x Blade for you.

## Acknowledgements

Livewire UI Components began as a fork of [maryUI](https://github.com/robsontenorio/mary) by Robson Tenorio and contributors. Thank you to the maryUI team for their work.

## Contributing

Contributions are welcome. Fork the repository, create a branch, and open a pull request. See [CONTRIBUTING.md](CONTRIBUTING.md).

## License

Livewire UI Components is open-source software licensed under the [MIT license](license.md).
