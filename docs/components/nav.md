# Nav Component

The Nav component provides a navigation bar for your application with support for brand and action elements.

## Basic Usage

```blade
<x-artisanpack-nav>
    <x-slot:brand>
        <a href="/" class="text-xl font-bold">My App</a>
    </x-slot:brand>
    
    <x-slot:actions>
        <x-artisanpack-button label="Login" link="/login" />
        <x-artisanpack-button label="Register" link="/register" class="btn-primary" />
    </x-slot:actions>
</x-artisanpack-nav>
```

## With Logo

```blade
<x-artisanpack-nav>
    <x-slot:brand>
        <a href="/" class="flex items-center gap-2">
            <img src="/logo.svg" alt="Logo" class="w-8 h-8" />
            <span class="text-xl font-bold">My App</span>
        </a>
    </x-slot:brand>
    
    <x-slot:actions>
        <x-artisanpack-button label="Login" link="/login" />
        <x-artisanpack-button label="Register" link="/register" class="btn-primary" />
    </x-slot:actions>
</x-artisanpack-nav>
```

## Sticky Navigation

```blade
<x-artisanpack-nav sticky>
    <x-slot:brand>
        <a href="/" class="text-xl font-bold">My App</a>
    </x-slot:brand>
    
    <x-slot:actions>
        <x-artisanpack-button label="Login" link="/login" />
        <x-artisanpack-button label="Register" link="/register" class="btn-primary" />
    </x-slot:actions>
</x-artisanpack-nav>
```

## Full Width Navigation

```blade
<x-artisanpack-nav full-width>
    <x-slot:brand>
        <a href="/" class="text-xl font-bold">My App</a>
    </x-slot:brand>
    
    <x-slot:actions>
        <x-artisanpack-button label="Login" link="/login" />
        <x-artisanpack-button label="Register" link="/register" class="btn-primary" />
    </x-slot:actions>
</x-artisanpack-nav>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| sticky | boolean | false | Whether the navigation bar should stick to the top of the viewport |
| fullWidth | boolean | false | Whether the navigation bar should span the full width of the viewport |

## Slots

| Name | Description |
|------|-------------|
| brand | Content for the brand/logo area on the left side of the navigation bar |
| actions | Content for the actions area on the right side of the navigation bar |
