---
title: Pagination Component
---

# Pagination Component

Moves between the pages of a long list, built on the ArtisanPack UI design system (`.apui-pagination`): previous, the first and last pages, the pages around the current one, next, and an optional summary, in a navigation landmark. The control keeps one width as the page changes, collapsing gaps into an ellipsis.

## With a Laravel paginator

Pass the paginator as `rows`. Pages become links to its URLs; inside a Livewire component using `WithPagination` they call `gotoPage()` instead of reloading the page, and keep their `href` for opening in a new tab. A simple or cursor paginator renders previous and next only.

```blade
{{-- $listings = Listing::paginate( 10 ) --}}
<x-artisanpack-pagination :rows="$listings" summary />
```

## With numbers

Without a paginator, give `page` and either `total` (with `per-page`) or `page-count`. Every control dispatches a `page-change` browser event with `{ page }`:

```blade
<x-artisanpack-pagination
	:page="$page"
	:total="318"
	:per-page="10"
	summary
	:summary-label="__( 'Showing {from}–{to} of {total} properties' )"
	wire:page-change="$set( 'page', $event.detail.page )"
/>
```

`x-on:page-change` works the same way in Alpine.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `rows` | paginator | none | A `LengthAwarePaginator`, `Paginator` or `CursorPaginator`. It supplies the numbers and URLs. |
| `page` | int | `1` | The current page, from 1. |
| `page-count` | int | none | How many pages there are. Without it, `total / per-page`. |
| `total` | int | none | How many items the list has. |
| `per-page` | int | `10` | How many items a page holds. |
| `siblings` | int | `1` | Pages shown on each side of the current one. |
| `summary` | bool | `false` | Shows the summary before the pages. |
| `label` | string | `__( 'Pagination' )` | The navigation landmark's name. Give two paginations on one page different labels. |
| `previous-label` | string | `__( 'Previous page' )` | The previous button's name. |
| `next-label` | string | `__( 'Next page' )` | The next button's name. |
| `page-label` | string | `__( 'Page {page}' )` | Each page's name. |
| `summary-label` | string | `__( 'Showing {from}–{to} of {total}' )` | The summary, with `{from}`, `{to}`, `{total}`, `{page}` and `{pageCount}`. Without a total it defaults to `__( 'Page {page} of {pageCount}' )`. |
| `classes` | array | `[]` | Extra classes per part: `root`, `summary`, `previous`, `page`, `ellipsis`, `next`. |

## Upgrading from 2.x

- The `simple`, `compact`, `advanced` and `minimal` variants, `size`, `show-first-last` and `show-jump-to` are gone: there is one design.
- The per-page select (`per-page-values`, `hide-per-page`, `wire:model` on the pagination) is gone. Pair Pagination with a Select for "Rows per page".
- `show-page-info`, `hide-page-info` and `page-info-template` are `summary` and `summary-label`; the `{from}`, `{to}` and `{total}` placeholders are unchanged. The summary is off by default.

## Accessibility

- A `nav` landmark named "Pagination"; the current page has `aria-current="page"`.
- Previous and next are icon-only with translatable names. On the first or last page they stay focusable with `aria-disabled="true"` and do nothing.
- Each page is named "Page n"; ellipses are `aria-hidden`.
- The chevrons mirror in right-to-left layouts.
