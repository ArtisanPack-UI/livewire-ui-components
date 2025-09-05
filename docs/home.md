---
title: ArtisanPack UI Livewire Components
---

# ArtisanPack UI Livewire Components

Welcome to the ArtisanPack UI Livewire Components documentation. This package provides a comprehensive set of UI components for Laravel applications using the TALL stack (Tailwind CSS, Alpine.js, Laravel, and Livewire).

## Introduction

ArtisanPack UI Livewire Components is a powerful UI component library for Laravel applications, forked from MaryUI and adapted for the ArtisanPack UI ecosystem. It provides a set of pre-built, customizable components that integrate seamlessly with Laravel, Livewire, Tailwind CSS, and DaisyUI.

### Key Features and Benefits

- **60+ Pre-built Components**: From simple buttons to complex data tables and charts
- **Livewire 3 Compatible**: Built specifically for the latest version of Livewire
- **DaisyUI Integration**: Leverages DaisyUI for beautiful, consistent styling
- **Customizable Theming**: Generate custom color themes with a simple Artisan command
- **Responsive Design**: All components are fully responsive out of the box
- **Accessibility**: Components are designed with accessibility in mind
- **Easy Installation**: Simple installation process with an interactive installer
- **Comprehensive Documentation**: Detailed documentation for all components and features

### Technology Stack

- **Laravel 12+**: Built for the latest version of Laravel
- **Livewire 3**: Utilizes Livewire for dynamic, reactive components
- **Tailwind CSS**: Uses Tailwind CSS for styling
- **DaisyUI**: Integrates with DaisyUI for additional styling and components
- **Alpine.js**: Uses Alpine.js for client-side interactivity

### Package Origin

ArtisanPack UI Livewire Components is forked from MaryUI, a popular Livewire component library. It has been adapted and enhanced for the ArtisanPack UI ecosystem, with additional features, components, and customization options.

## Getting Started

### Quick Installation

```bash
# Install the package
composer require artisanpack-ui/livewire-ui-components

# Run the installer
php artisan livewire-ui-components:install
```

For detailed installation instructions, see the [Installation Guide](installation).

### Basic Usage Example

```php
<x-artisanpack-card>
    <x-slot:header>
        <h3 class="text-lg font-bold">Card Title</h3>
    </x-slot:header>
    
    <p>This is a basic card component from ArtisanPack UI.</p>
    
    <x-slot:footer>
        <x-artisanpack-button>Click Me</x-artisanpack-button>
    </x-slot:footer>
</x-artisanpack-card>
```

## Component Showcase

ArtisanPack UI Livewire Components includes a wide range of components for various use cases:

### Form Components
- [Input](components/input) - Text input fields
- [Checkbox](components/checkbox) - Checkbox inputs
- [Select](components/select) - Dropdown select menus
- [Button](components/button) - Various button styles

### Layout Components
- [Card](components/card) - Content containers
- [Modal](components/modal) - Modal dialogs
- [Tabs](components/tabs) - Tabbed interfaces

### Data Display Components
- [Table](components/table) - Data tables
- [Chart](components/chart) - Data visualization
- [Pagination](components/pagination) - Page navigation

For a complete list of components, see the [Components Overview](components).

## Resources

- **Repository**: [GitLab Repository](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components)
- **Issues**: [GitLab Issues](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/issues)
- **Support**: For support, please open an issue on GitLab or contact the maintainer at [me@jacobmartella.com](mailto:me@jacobmartella.com)
