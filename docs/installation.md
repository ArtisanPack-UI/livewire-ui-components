# Installation Guide

This guide will walk you through the process of installing and configuring ArtisanPack UI Livewire Components in your Laravel application.

## Requirements

Before installing ArtisanPack UI Livewire Components, ensure your environment meets the following requirements:

- **Laravel 12+**: The package is designed for Laravel 12 or newer
- **PHP 8.1+**: PHP 8.1 or newer is required
- **Node.js and NPM/Yarn/Bun/PNPM**: Required for compiling assets
- **Composer**: Required for installing the package

## Installation Steps

### 1. Install via Composer

Begin by installing the package through Composer:

```bash
composer require artisanpack-ui/livewire-ui-components
```

### 2. Run the Installation Command

After installing the package, run the installation command:

```bash
php artisan livewire-ui-components:install
```

This interactive installer will:

1. Check your Laravel version
2. Ask if you want to install Livewire/Volt
3. Detect and use your preferred package manager (npm, yarn, bun, or pnpm)
4. Install Livewire and optionally Volt
5. Set up Tailwind CSS and DaisyUI
6. Copy example components if you're starting a new project
7. Configure component prefixing if needed

### 3. Installation Options

During installation, you'll be prompted with several options:

#### Volt Installation

```
Also install `livewire/volt` ?
> Yes
  No
```

Choose whether to install Livewire/Volt alongside Livewire. Volt provides a simplified syntax for creating Livewire components.

#### Package Manager Selection

```
Install with ...
> npm install --save-dev
  yarn add -D
  bun i -D
  pnpm i -D
```

Select your preferred package manager for installing JavaScript dependencies.

### 4. Post-Installation Configuration

After installation:

1. **Compile your assets**:
   ```bash
   npm run dev
   # or
   yarn dev
   # or
   bun run dev
   # or
   pnpm run dev
   ```

2. **Clear view cache**:
   ```bash
   php artisan view:clear
   ```

3. **Generate a theme** (optional):
   ```bash
   php artisan artisanpack:generate-theme
   ```

## Manual Installation

If you prefer to install the package manually, follow these steps:

### 1. Install Required Packages

```bash
# Install the package
composer require artisanpack-ui/livewire-ui-components

# Install Livewire
composer require livewire/livewire

# Optional: Install Volt
composer require livewire/volt
php artisan volt:install
```

### 2. Install Frontend Dependencies

```bash
# Using npm
npm install --save-dev daisyui tailwindcss @tailwindcss/vite

# Or using yarn
yarn add -D daisyui tailwindcss @tailwindcss/vite

# Or using bun
bun i -D daisyui tailwindcss @tailwindcss/vite

# Or using pnpm
pnpm i -D daisyui tailwindcss @tailwindcss/vite
```

### 3. Configure Tailwind CSS

Update your `resources/css/app.css` file to include:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/** daisyUI */
@plugin "daisyui" {
    themes: light --default, dark --prefersdark;
}

/* Livewire UI Components */
@source "../../vendor/artisanpack-ui/livewire-ui-components/resources/views/**/*.php";
@source "../../vendor/artisanpack-ui/livewire-ui-components/src/View/Components/**/*.php";

/* Theme toggle */
@custom-variant dark (&:where(.dark, .dark *));

/**
* Paginator - Traditional style
* Because Laravel defaults does not match well the design of daisyUI.
*/

.artisanpack-table-pagination span[aria-current="page"] > span {
    @apply bg-primary text-base-100
}

.artisanpack-table-pagination button {
    @apply cursor-pointer
}
```

### 4. Publish Configuration File

```bash
php artisan vendor:publish --tag=livewire-ui-components.config
```

## Troubleshooting

### Common Installation Issues

#### Component Name Collisions

If you're using Jetstream, Breeze, or another UI package that might have components with the same names, the installer will automatically add a prefix to ArtisanPack UI components.

You'll see a message like:

```
---------------------------------------------
🚨`jetstream` was detected.🚨
---------------------------------------------
A global prefix on Livewire UI Components components was added to avoid name collision.

 * Example: x-artisanpack-button, x-artisanpack-card ...
 * See config/livewire-ui-components.php
---------------------------------------------
```

You can customize this prefix in the `config/livewire-ui-components.php` file.

#### Asset Compilation Issues

If you encounter issues with asset compilation:

1. Make sure you have the latest version of your package manager
2. Clear your Node.js cache:
   ```bash
   npm cache clean --force
   # or
   yarn cache clean
   ```
3. Delete `node_modules` and reinstall:
   ```bash
   rm -rf node_modules
   npm install
   ```

#### View Cache Issues

If components aren't displaying correctly:

```bash
php artisan view:clear
```

### Compatibility with Starter Kits

#### Laravel Jetstream

ArtisanPack UI Livewire Components is compatible with Jetstream. The installer will detect Jetstream and add a prefix to ArtisanPack UI components to avoid name collisions.

#### Laravel Breeze

ArtisanPack UI Livewire Components is compatible with Breeze. The installer will detect Breeze and add a prefix to ArtisanPack UI components to avoid name collisions.

#### Livewire Flux

If you're using Livewire Flux, the installer will detect it and add a prefix to ArtisanPack UI components to avoid name collisions.
