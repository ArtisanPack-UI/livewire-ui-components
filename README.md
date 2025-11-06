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

## 🚀 Migration Guide: Upgrading to Version 1.0.0

Version 1.0.0 introduces standardized naming for all Blade components to improve consistency and developer experience. The previously duplicated prefix (artisanpack-artisanpack-) has been removed.

What has changed?

All components have been migrated from the <x-artisanpack-artisanpack-{name}> syntax to the correct <x-artisanpack-{name}> syntax.

Before (v0.7.0 and earlier):

HTML
<x-artisanpack-artisanpack-button>Click Me</x-artisanpack-artisanpack-button>

<x-artisanpack-artisanpack-input wire:model="name" />
After (v1.0.0+):

HTML
<x-artisanpack-button>Click Me</x-artisanpack-button>

<x-artisanpack-input wire:model="name" />
How to Upgrade

Update Your composer.json: Update the artisanpack/livewire-ui-components version constraint to ^1.0.

Run composer update.

Update Your Blade Files: We highly recommend performing a project-wide search-and-replace for artisanpack-artisanpack- and replacing it with artisanpack-.

Backwards compatibility has been maintained for this version. Your application will not break immediately, but the old artisanpack-artisanpack- component names are considered deprecated and will be removed in a future major release (e.g., v2.0). We strongly advise updating your templates to the new, cleaner syntax.


***

### ✅ Implement Automated Testing

This is the unit test file we discussed previously. It ensures that both the new, correct names and the old, deprecated names are registered, preventing future regressions.

#### File: `tests/Unit/ComponentRegistrationTest.php`

```php
<?php
/**
 * Unit test for component registration.
 *
 * This test ensures that all UI components are registered with their correct
 * standardized names and that deprecated aliases remain for backwards
 * compatibility.
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 *
 * @package    ArtisanPack\LivewireUiComponents\Tests\Unit
 * @since      1.0.0
 */

namespace Tests\Unit;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

/**
 * Verifies component registration and naming conventions.
 *
 * @since 1.0.0
 */
class ComponentRegistrationTest extends TestCase
{
	/**
	 * Test that new, standardized component names are registered correctly.
	 *
	 * This test iterates through all expected components and asserts that
	 * the <x-artisanpack-{name}> syntax is registered.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function test_new_component_names_are_registered(): void
	{
		$registeredComponents = array_keys( Blade::getClassComponentAliases() );
		$components           = config( 'artisanpack.livewire-ui-components.components', [] );
		$prefix               = config( 'artisanpack.livewire-ui-components.prefix', 'artisanpack' );

		foreach ( array_keys( $components ) as $alias ) {
			$this->assertContains(
				$prefix . '-' . $alias,
				$registeredComponents,
				"Component [{$prefix}-{$alias}] is not registered."
			);
		}
	}

	/**
	 * Test that deprecated component aliases are registered for backwards compatibility.
	 *
	 * This test ensures that the old <x-artisanpack-artisanpack-{name}> syntax
	 * is still available to prevent breaking changes for existing users.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function test_deprecated_aliases_are_registered(): void
	{
		$registeredComponents = array_keys( Blade::getClassComponentAliases() );
		$components           = config( 'artisanpack.livewire-ui-components.components', [] );

		foreach ( array_keys( $components ) as $alias ) {
			$this->assertContains(
				'artisanpack-artisanpack-' . $alias,
				$registeredComponents,
				"Deprecated alias [artisanpack-artisanpack-{$alias}] is not registered."
			);
		}
	}
}
```

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
