# Spotlight Component

The Spotlight component provides a searchable command palette for your application, similar to the command palette in popular applications like VS Code or Slack.

## Basic Usage

```blade
<x-artisanpack-spotlight />
```

## With Custom Shortcut

```blade
<x-artisanpack-spotlight shortcut="ctrl.k" />
```

## With Custom Text

```blade
<x-artisanpack-spotlight 
    search-text="Search commands..." 
    no-results-text="No commands found." 
/>
```

## With Custom URL

```blade
<x-artisanpack-spotlight :url="route('custom.spotlight')" />
```

## With Append Slot

```blade
<x-artisanpack-spotlight>
    <x-slot:append>
        <div class="flex items-center gap-2 px-3">
            <span class="text-xs opacity-50">Press</span>
            <x-artisanpack-kbd>↑</x-artisanpack-kbd>
            <x-artisanpack-kbd>↓</x-artisanpack-kbd>
            <span class="text-xs opacity-50">to navigate</span>
        </div>
    </x-slot:append>
</x-artisanpack-spotlight>
```

## With Custom Content

```blade
<x-artisanpack-spotlight>
    <div class="p-4 border-t border-base-300">
        <h3 class="font-bold">Recent Searches</h3>
        <ul class="mt-2">
            <li class="py-1 hover:bg-base-200 px-2 rounded cursor-pointer">Users</li>
            <li class="py-1 hover:bg-base-200 px-2 rounded cursor-pointer">Settings</li>
            <li class="py-1 hover:bg-base-200 px-2 rounded cursor-pointer">Dashboard</li>
        </ul>
    </div>
</x-artisanpack-spotlight>
```

## Server-Side Implementation

To provide search results, you need to implement a route that returns JSON data. By default, the component uses the `mary.spotlight` route.

```php
// In your routes file
Route::get('/spotlight', function (Request $request) {
    $search = $request->input('search');
    
    // Return empty array if search is empty
    if (empty($search)) {
        return [];
    }
    
    // Return search results
    return [
        [
            'name' => 'Dashboard',
            'description' => 'Go to dashboard',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>',
            'link' => '/dashboard',
        ],
        [
            'name' => 'Users',
            'description' => 'Manage users',
            'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
            'link' => '/users',
        ],
    ];
})->name('mary.spotlight');
```

## Props

| Name | Type | Default | Description |
|------|------|---------|-------------|
| id | string | null | Optional identifier for the spotlight component |
| shortcut | string | "meta.g" | Keyboard shortcut to open the spotlight (format: "modifier.key") |
| searchText | string | "Search ..." | Placeholder text for the search input |
| noResultsText | string | "Nothing found." | Text to display when no results are found |
| url | string | route('mary.spotlight') | URL to fetch search results from |

## Slots

| Name | Description |
|------|-------------|
| default | Custom content to display in the spotlight modal |
| append | Content to append to the search input |

## Search Result Format

Each search result should be an object with the following properties:

| Name | Type | Description |
|------|------|-------------|
| name | string | The name or title of the result |
| description | string | Optional description of the result |
| link | string | URL to navigate to when the result is clicked |
| icon | string | Optional HTML string for an icon |
| avatar | string | Optional URL to an avatar image (used if icon is not provided) |
