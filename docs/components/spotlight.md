---
title: Spotlight Component
---

# Spotlight Component

A command palette for searching across pages, records and actions, built on the ArtisanPack UI design system. It's a modal dialog holding a search combobox whose listbox of grouped results is always open. ⌘K (Ctrl+K outside Apple platforms) opens it from anywhere on the page; the arrow keys move through the results, Enter opens one and Escape closes it.

The interaction comes from the design system's behavior engine (`apuiSpotlight`, which composes the dialog and combobox machines), so load the Alpine adapter before Livewire's script: `@artisanpackScripts` in the layout, which the installer adds (see [Installation](../installation)).

> **Changed in 3.0.** Spotlight was rebuilt on the design system. Results come from `groups` (rendered on the server and filtered as you type) or from `url` (the 2.x endpoint, still the default without `groups`). The 2.x names `search-text`, `no-results-text` (and the pre-release `empty-text`, `loading-text`), the `append` slot and the `meta.g` shortcut spelling still work but are deprecated; endpoint icons are now icon names from the registry, not HTML. See [Upgrading from 2.x](#upgrading-from-2x).

## Basic Usage

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
		[
			'label' => __( 'Actions' ),
			'items' => [
				[ 'value' => 'add-property', 'label' => __( 'Add property' ), 'icon' => 'plus', 'color' => 'success', 'shortcut' => '⌘ N' ],
			],
		],
	]"
	wire:select="run( $event.detail.value )"
/>
```

Each item takes `label`, and optionally `value` (defaults to `href`, then `label`), `description`, `href`, `icon` (a registry name, drawn in a tile), `color` (tints the tile), `shortcut` (a display hint), `keywords` (more words to match) and `disabled`. Groups take `label`, `items` and an optional stable `id`.

As the user types, items are filtered by every word of the query against their label, description and keywords, ignoring case and accents; the matched words are marked in each label with `<mark>`, empty groups hide, and the `empty` state shows when nothing matches. The active result shows an Enter glyph at its end (mirrored in RTL).

With `recent`, the most recent choices come first in a Recent group while the query is empty. It's off by default because it keeps them in the browser's `localStorage` (per palette id); turn it on only where storing recent choices on the device is acceptable.

Choosing an item (click or Enter) dispatches `select` with `{ value, item }` and closes the palette. A link item (`href`) is followed; internal links load through `wire:navigate` unless `:navigate="false"`.

## Server search

### From an endpoint

`url` points at an endpoint that returns results as JSON for `?search=…`. The palette fetches it as the user types (after a 250ms pause, cancelling the previous request), shows the loading row meanwhile, and lists the results after `groups`.

```blade
<x-artisanpack-spotlight :url="route( 'spotlight' )" :placeholder="__( 'Search listings…' )" />
```

```php
Route::get( '/spotlight', function ( Request $request ) {
	return [
		[
			'label' => __( 'Listings' ),
			'items' => Listing::search( $request->string( 'search' ) )->take( 8 )->get()->map( fn ( Listing $listing ) => [
				'label'       => $listing->title,
				'description' => $listing->city,
				'href'        => route( 'listings.show', $listing ),
				'icon'        => 'house',
			] ),
		],
	];
} )->name( 'spotlight' );
```

The endpoint returns groups (`{ label, items }`) or a flat list of items. Without `groups` and `url`, the palette uses the package's `artisanpack.spotlight` route, as in 2.x: it calls `search( Request $request )` on the class in `artisanpack.livewire-ui-components.components.spotlight.class` and runs the results through the `ap.livewireUiComponents.spotlightCommands` filter (see [Extension hooks](../advanced/extension-hooks)). The 2.x item shape (`name`, `link`) still reads as `label` and `href`.

### From the Livewire component

Inside a Livewire component, render the results yourself and pass `remote`, so the palette doesn't filter them again. The query arrives with every keystroke as `search-change`:

```blade
<x-artisanpack-spotlight :groups="$this->results" remote :loading="$searching" wire:search-change.debounce.250ms="$set( 'query', $event.detail.search )" />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `open` | bool | `false` | Whether it's open. `wire:model` binds it instead. Emitted as `data-state` on every part. |
| `groups` | array | `[]` | The results, in groups (see above). |
| `remote` | bool | `false` | The page searches: `groups` are the results and aren't filtered here. |
| `url` | string | the `artisanpack.spotlight` route without `groups` | An endpoint returning results as JSON for `?search=…`. |
| `navigate` | bool | `true` | Adds `wire:navigate` to internal link items. Off-site and `#` links load normally. |
| `loading` | bool | `false` | Shows the loading row and sets `aria-busy` on the results. |
| `shortcut` | string | `mod+k` | The global shortcut: modifiers and a key joined with `+`. `mod` is ⌘ on Apple platforms and Ctrl elsewhere; `ctrl`, `meta`, `alt` and `shift` work too. Empty turns it off. |
| `recent` | bool | `false` | Lists recent choices first while the query is empty, kept in `localStorage`. |
| `label` | string | `__( 'Search' )` | The palette's accessible name (panel, query and results). |
| `placeholder` | string | `__( 'Search…' )` | The query's placeholder. |
| `search-text` | string | none | Deprecated: use `placeholder`. |
| `empty-label` | string | `__( 'No results.' )` | Shown when nothing matches. |
| `empty-text`, `no-results-text` | string | none | Deprecated: use `empty-label`. |
| `loading-label` | string | `__( 'Searching…' )` | Shown while loading. |
| `loading-text` | string | none | Deprecated: use `loading-label`. |
| `recent-label` | string | `__( 'Recent' )` | The Recent group's label. |
| `close-label` | string | `__( 'Close' )` | The Esc button's accessible name, after its visible key: `__( 'Esc, :label' )` ("Esc, Close"). |
| `navigate-label` | string | `__( 'Navigate' )` | The footer hint beside the arrow keys. |
| `select-label` | string | `__( 'Open' )` | The footer hint beside the Enter key. |
| `trigger` | string or slot | none | Content for an outline Button that opens the palette, shown with the shortcut's keys. |
| `footer` | string or slot | none | Replaces the footer's key hints. |
| `modal` | bool | `true` | Traps focus, locks scroll and sets `aria-modal`. |
| `portal` | bool | `true` | Renders the palette at the end of the body (`x-teleport`). Off renders it in place. |
| `classes` | array | `[]` | Extra classes per part: `root`, `trigger`, `backdrop`, `positioner`, `search`, `icon`, `query`, `closeTrigger`, `results`, `itemGroup`, `itemGroupLabel`, `item`, `tile`, `itemText`, `itemDescription`, `shortcut`, `itemIndicator`, `empty`, `loading`, `footer`. |

The `trigger` and `footer` slots override the props of the same name, and the `empty` slot replaces `empty-label`. The deprecated `append` slot fills the footer when there's no `footer`. The results count ("3 results", "No results") is announced from `__( ':count result|:count results' )`.

## Attributes and events

`class` lands on the panel (`.apui-spotlight`). Every other attribute lands on the element that holds the Alpine component, which is where the palette dispatches:

| Event | Detail | When |
|---|---|---|
| `open-change` | `{ open }` | It opened or closed itself: the trigger, the shortcut, Escape, the backdrop, the Esc button or a choice. |
| `select` | `{ value, item }` | An item was chosen. |
| `search-change` | `{ search }` | The user typed in the query. |

Setting `open` (or the bound property) doesn't fire `open-change`. To open it from your own button, bind `wire:model`, or give the palette an `id` and call `Alpine.$data( document.getElementById( 'spotlight' ) ).show()`.

## Ids

Every part has a stable, server-rendered id and every item a `wire:key`, so Livewire's morph keeps the open palette, its focus trap and the active result. The base id is the `id` attribute, else one derived from the `wire:model` name, else a hash of the props that aren't text, data or open state. Pass an `id` to keep recent choices apart between palettes.

## Markup

```html
<div id="spotlight" x-data="apuiSpotlight({ … })" style="display: contents">
	<button class="apui-button" data-scope="spotlight" data-part="trigger" aria-keyshortcuts="Meta+K Control+K">Search <span data-part="shortcut"><kbd>Ctrl</kbd><kbd>K</kbd></span></button>
	<div data-scope="spotlight" data-part="backdrop"></div>
	<div data-scope="spotlight" data-part="positioner">
		<div class="apui-spotlight" data-scope="spotlight" data-part="content" role="dialog" aria-label="Search">
			<div data-part="search">icon · <input data-part="query" role="combobox"> · <button data-part="close-trigger">Esc</button></div>
			<div data-part="results" role="listbox">
				<div data-part="item-group" role="group"><div data-part="item-group-label">Pages</div><a data-part="item" role="option">tile · item-text (label with &lt;mark&gt;, item-description) · shortcut · item-indicator</a></div>
				<div data-part="empty">No results.</div>
				<div data-part="loading" hidden>Searching…</div>
			</div>
			<div data-part="footer">↑↓ Navigate · ↵ Open</div>
		</div>
	</div>
</div>
```

## Accessibility

- A dialog named by `label`, holding a combobox (the query) that controls an always-open listbox; the active result is `aria-activedescendant` and `aria-selected`.
- Focus moves to the query on open and returns to where it was on close. A modal palette hides the rest of the page from assistive technology and locks scroll.
- Groups are labelled groups; the results count is announced politely inside the panel.
- The shortcut is exposed with `aria-keyshortcuts`; a single-key shortcut (`/`) never fires while typing in a field.
- Don't put destructive actions in the palette without a confirmation.

## Upgrading from 2.x

The 2.x names below still work in 3.x and are deprecated: they'll be removed in a later major release.

| 2.x | 3.0 | Status |
|---|---|---|
| `shortcut="meta.g"` (Alpine modifiers joined with dots) | `shortcut="mod+g"` (`mod` follows the platform) | Deprecated: still read, `meta` and `cmd` as `mod` |
| `search-text` | `placeholder` | Deprecated alias |
| `no-results-text` | `empty-label` | Deprecated alias |
| `append` slot | `footer` slot | Deprecated alias |
| `url` endpoint returning `name`, `description`, `link` | `label`, `description`, `href` | Still read |
| Endpoint `icon` (HTML) and `avatar` | `icon` is a registry name; `avatar` is dropped | Changed |
| Default slot (custom content) | `groups`, or the `empty` and `footer` slots | Removed |
| `mary-search-open` window event | `Alpine.$data( el ).show()` or `wire:model` | Removed |
