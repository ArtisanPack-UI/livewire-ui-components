# ArtisanPack UI Livewire UI Components

ArtisanPack UI Livewire UI Components is a comprehensive set of UI components for Livewire powered by daisyUI and Tailwind CSS. This package provides a collection of beautiful, responsive, and customizable components to accelerate your Laravel application development.

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
- **🔧 Livewire 3 Compatible**: Fully compatible with the latest version of Livewire
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
