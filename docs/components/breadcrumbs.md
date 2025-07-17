# Breadcrumbs Component

The Breadcrumbs component provides a navigation aid that helps users understand their current location within the application's hierarchy.

## Basic Usage

```blade
<x-artisanpack-breadcrumbs :items="[
    ['label' => 'Home', 'link' => '/'],
    ['label' => 'Products', 'link' => '/products'],
    ['label' => 'Product Name'],
]" />
```

## With Icons

```blade
<x-artisanpack-breadcrumbs :items="[
    ['label' => 'Home', 'link' => '/', 'icon' => 'o-home'],
    ['label' => 'Products', 'link' => '/products', 'icon' => 'o-shopping-bag'],
    ['label' => 'Product Name', 'icon' => 'o-tag'],
]" />
```

## With Custom Separator

```blade
<x-artisanpack-breadcrumbs 
    separator="o-chevron-double-right"
    :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Products', 'link' => '/products'],
        ['label' => 'Product Name'],
    ]" 
/>
```

## With Tooltips

```blade
<x-artisanpack-breadcrumbs :items="[
    ['label' => 'Home', 'link' => '/', 'tooltip' => 'Go to homepage'],
    ['label' => 'Products', 'link' => '/products', 'tooltip' => 'View all products'],
    ['label' => 'Product Name', 'tooltip' => 'You are here'],
]" />
```

## With Custom Classes

```blade
<x-artisanpack-breadcrumbs 
    link-item-class="text-primary hover:text-primary-focus"
    text-item-class="text-gray-500"
    icon-class="w-5 h-5"
    separator-class="h-4 w-4 mx-2 text-gray-400"
    :items="[
        ['label' => 'Home', 'link' => '/'],
        ['label' => 'Products', 'link' => '/products'],
        ['label' => 'Product Name'],
    ]" 
/>
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the breadcrumbs |
| items | array | [] | Array of breadcrumb items. Each item can have: 'label', 'link', 'icon', 'tooltip', 'tooltip-left', 'tooltip-right', 'tooltip-bottom', 'tooltip-top' |
| separator | string | 'o-chevron-right' | Icon name for the separator between breadcrumb items |
| linkItemClass | string | "hover:underline text-sm" | CSS classes for breadcrumb items with links |
| textItemClass | string | "text-sm" | CSS classes for breadcrumb items without links |
| iconClass | string | "h-4 w-4" | CSS classes for icons in breadcrumb items |
| separatorClass | string | "h-3 w-3 mx-1 text-base-content/40" | CSS classes for the separator |
| noWireNavigate | boolean | false | Whether to disable wire:navigate on links |

## Item Properties

Each item in the `items` array can have the following properties:

| Name | Type | Description |
|------|------|-------------|
| label | string | Text to display for the breadcrumb item |
| link | string | Optional URL for the breadcrumb item |
| icon | string | Optional icon name for the breadcrumb item |
| tooltip | string | Optional tooltip text for the breadcrumb item |
| tooltip-left | string | Optional tooltip text positioned to the left |
| tooltip-right | string | Optional tooltip text positioned to the right |
| tooltip-bottom | string | Optional tooltip text positioned to the bottom |
| tooltip-top | string | Optional tooltip text positioned to the top |
