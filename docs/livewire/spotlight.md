---
title: Spotlight
menu_order: 2
---

# Spotlight

[Spotlight](https://docs.artisanpackui.dev/documentation/design-system/components/spotlight) is a command palette, opened with a button or a keyboard shortcut (`mod+k` by default). The component page covers its props, items and keyboard behavior. This page covers where its results come from in a Laravel app. There are three sources.

## Results from `groups`

Pass the results as groups. They render on the server and the palette filters them as the user types:

```blade
<x-artisanpack-spotlight
    :trigger="__( 'Search' )"
    :groups="[
        [
            'label' => __( 'Pages' ),
            'items' => [
                [ 'label' => __( 'Dashboard' ), 'href' => route( 'dashboard' ), 'icon' => 'house' ],
                [ 'label' => __( 'Reports' ), 'href' => route( 'reports' ), 'icon' => 'file-text', 'keywords' => [ 'analytics' ] ],
            ],
        ],
    ]"
/>
```

Each item takes a `label`, and optionally `value`, `description`, `href`, `icon`, `color`, `shortcut`, `keywords` and `disabled`. `href` only accepts relative, `http(s)`, `mailto`, `tel` and `sms` links.

## Results from your Livewire component

To search on the server yourself, set `remote`: `groups` are then the results for the current query and aren't filtered again. Update them when the palette reports a new query:

```blade
<x-artisanpack-spotlight
    remote
    :groups="$this->results"
    :loading="$searching"
    wire:search-change.debounce.250ms="$set( 'query', $event.detail.search )"
/>
```

## Results from an endpoint

`url` names an endpoint that returns results as JSON for `?search=…`. The palette fetches it as the user types and lists the results after any `groups`. The endpoint can return groups (`{ label, items }`) or a flat list of items.

```blade
<x-artisanpack-spotlight :url="route( 'spotlight' )" />
```

```php
Route::get( '/spotlight', function ( Request $request ) {
    return [
        [
            'label' => __( 'Listings' ),
            'items' => Listing::search( $request->string( 'search' ) )->take( 8 )->get()->map( fn ( Listing $listing ) => [
                'label' => $listing->title,
                'href'  => route( 'listings.show', $listing ),
            ] ),
        ],
    ];
} )->middleware( 'auth' )->name( 'spotlight' );
```

### The package's Spotlight route

With no `groups`, no `remote` and no `url`, Spotlight uses the package's `artisanpack.spotlight` route (`/artisanpack/spotlight`), as in 2.x. The route resolves the class named in the `components.spotlight.class` config key (`App\Support\Spotlight` by default), calls its `search()` method with the request and returns the results:

```php
namespace App\Support;

use Illuminate\Http\Request;

class Spotlight
{
    public function search( Request $request ): array
    {
        $query = $request->string( 'search' );

        return User::query()
            ->where( 'name', 'like', "%{$query}%" )
            ->take( 5 )
            ->get()
            ->map( fn ( User $user ) => [
                'label'       => $user->name,
                'description' => $user->email,
                'href'        => route( 'users.show', $user ),
                'icon'        => 'user',
            ] )
            ->all();
    }
}
```

The results pass through the `ap.livewireUiComponents.spotlightCommands` filter, with the current user, so other packages can add their own commands. See [Extension Hooks](Advanced-Extension-Hooks).

The package's route only has the `web` middleware. Check the user's permissions in `search()`, as you would in any controller, before returning anything private.

The 2.x item keys `name` and `link` are still read as `label` and `href`.
