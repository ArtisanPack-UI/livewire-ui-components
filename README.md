# ArtisanPack UI Livewire UI Components

ArtisanPack UI Livewire UI Components is a comprehensive set of UI components for Livewire powered by daisyUI and Tailwind CSS. This package provides a collection of beautiful, responsive, and customizable components to accelerate your Laravel application development.

## v2.0 Beta Available

Version 2.0 is now in beta with exciting new features:

- **Dashboard Components** - KpiCard with sparklines and trend indicators, WidgetGrid for responsive layouts
- **Table Data Export** - Export table data to CSV, XLSX, and PDF formats
- **Streaming Content** - Real-time streaming content component for AI chat interfaces (Livewire 4+)
- **Glass Effects** - Frosted glass UI effects with six pre-built presets (frosted-light, frosted-dark, liquid-light, liquid-dark, minimal, bold)
- **Accessibility Themes** - WCAG AAA high-contrast presets with automatic system preference detection
- **Theme Preview Tool** - Browser-based theme builder with real-time preview, component gallery, and export functionality
- **Livewire 4 Features** - Support for `wire:sort`, `wire:intersect`, and `data-loading` CSS variants

Install the beta:
```bash
composer require artisanpack-ui/livewire-ui-components:^2.0@beta
```

See the [v1.x to v2.0 Migration Guide](docs/migration/v1-to-v2.md) for details. **No breaking changes** - all existing code continues to work.

## 🚀 Quick Start

### Installation

```bash
# Install the package
composer require artisanpack-ui/livewire-ui-components

# Run the interactive installer
php artisan livewire-ui-components:install

# Compile your assets
npm run dev
```

### Basic Usage

```blade
<!-- Simple button -->
<x-artisanpack-button>Click Me</x-artisanpack-button>

<!-- Card with header and footer -->
<x-artisanpack-card>
    <x-slot:header>
        <h3 class="text-lg font-bold">Card Title</h3>
    </x-slot:header>
    
    <p>Card content goes here.</p>
    
    <x-slot:footer>
        <x-artisanpack-button color="primary">Action</x-artisanpack-button>
    </x-slot:footer>
</x-artisanpack-card>
```

## ✨ Key Features

- **🎯 70+ Pre-built Components**: From simple inputs to complex data tables and charts
- **⚡ TALL Stack Integration**: Built specifically for Tailwind CSS, Alpine.js, Laravel, and Livewire
- **🎨 DaisyUI Powered**: Leverages the beautiful daisyUI component library for consistent styling
- **🔧 Livewire 3 & 4 Compatible**: Fully compatible with Livewire 3 and 4
- **🎨 Customizable Theming**: Generate custom color themes with a simple Artisan command
- **📱 Responsive Design**: All components are fully responsive out of the box
- **♿ Accessibility Focused**: Components designed with accessibility best practices
- **📚 Comprehensive Documentation**: Detailed documentation with examples for every component

## 🧩 Component Categories

### 📝 Form Components
Input, Button, Checkbox, Select, DatePicker, File Upload, Rich Text Editor, and more.

### 🏗️ Layout Components  
Card, Modal, Tabs, Accordion, Drawer, Dropdown, and structural elements.

### 🧭 Navigation Components
Menu, Breadcrumbs, Pagination, Spotlight Search, and navigation helpers.

### 📊 Data Display Components
Table, Chart, Calendar, Avatar, Badge, Progress indicators, and data visualization.

### 💬 Feedback Components
Alert, Toast, Loading states, and user feedback elements.

### 🛠️ Utility Components
Icon, Theme Toggle, Carousel, and various utility components.

## 📖 Documentation

Comprehensive documentation is available in our [Documentation Wiki](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/wikis/home):

- **[Installation Guide](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/wikis/installation)** - Detailed setup instructions
- **[Components Overview](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/wikis/components)** - Complete component reference
- **[Customization Guide](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/wikis/customization)** - Theming and customization options
- **[Advanced Topics](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/wikis/advanced)** - Color system, custom components, and more

## 🎨 Theming

Generate custom themes to match your brand:

```bash
php artisan artisanpack:generate-theme
```

This interactive command helps you create custom color schemes that work across all components.

## 📦 Optional Dependencies

Some features require optional packages:

```bash
# For Excel (XLSX) export
composer require phpoffice/phpspreadsheet

# For PDF export
composer require barryvdh/laravel-dompdf
```

CSV export works without any additional dependencies.

## 🚀 Migration Guides

### Upgrading to v2.0

Version 2.0 is **fully backwards compatible** with v1.x. All your existing code will continue to work without any modifications. Simply update the package:

```bash
composer require artisanpack-ui/livewire-ui-components:^2.0
```

See the complete [v1.x to v2.0 Migration Guide](docs/migration/v1-to-v2.md) for details on new features and optional enhancements.

### Upgrading to v1.0

Version 1.0.0 introduced standardized component naming. The duplicated prefix was removed:

**Before (deprecated):**
```blade
<x-artisanpack-artisanpack-button>Click Me</x-artisanpack-artisanpack-button>
```

**After (v1.0.0+):**
```blade
<x-artisanpack-button>Click Me</x-artisanpack-button>
```

Search and replace `artisanpack-artisanpack-` with `artisanpack-` in your Blade files.

## Acknowledgements

ArtisanPack UI Livewire UI Components is a fork of the excellent [MaryUI](https://github.com/robsontenorio/mary) library, created by Robson Tenorio and contributors.

We extend our sincere gratitude to the MaryUI team for their incredible work and for making it available to the open-source community. This fork aims to adapt MaryUI to the specific coding standards and architectural patterns of the ArtisanPack UI ecosystem while adding new features.

## Contributing

Contributions are welcome! To contribute:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

<a name="license"></a>

ArtisanPack UI Livewire UI Components is open-sourced software licensed under the [MIT license](/license.md).
