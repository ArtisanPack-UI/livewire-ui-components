---
title: ImageGallery Component
---

# ImageGallery Component

A grid of images that opens a lightbox, built on the ArtisanPack UI design system (`.apui-image-gallery`). Each image is a button that opens the lightbox on it: a dark dialog (`.apui-lightbox`) with the image, a counter and caption, previous and next buttons, thumbnails, and arrow keys that follow the text direction. The lightbox runs on the behavior engine's dialog machine (`apuiLightbox`), so it traps focus, closes on Escape and returns focus to the image that opened it.

## Basic Usage

```blade
<x-artisanpack-image-gallery :images="$property->photos->map( fn ( $photo ) => [
	'src'     => $photo->url,
	'alt'     => $photo->alt,
	'caption' => $photo->caption,
] )->all()" />
```

Every image needs `alt` text that describes it; the grid button is named by it, and the lightbox shows the same alt. `caption` is visible text over the image's foot, shown on hover and focus (always on touch screens), and in the lightbox's title.

## Featured, columns and download

`featured` makes the first image span two columns and two rows. `columns` sets the grid (4 by default). `downloadable` adds a download link for the image shown to the lightbox's bar.

```blade
<x-artisanpack-image-gallery :images="$photos" :columns="3" featured downloadable />
```

## Binding the lightbox

`wire:model` binds the lightbox's open state, and `index` picks the image it opens on. The widget dispatches `open-change` and `index-change` (`$event.detail.open`, `$event.detail.index`).

```blade
<x-artisanpack-image-gallery
	wire:model="showPhotos"
	:images="$photos"
	:index="$startAt"
	x-on:index-change="$wire.set( 'startAt', $event.detail.index )"
/>
```

`:lightbox="false"` renders a plain grid of figures with no buttons and no widget. `:portal="false"` renders the lightbox in place instead of at the end of the body, and `:modal="false"` leaves the page usable behind it.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `images` | array | `[]` | The images: `[ 'src' => …, 'alt' => …, 'caption' => … ]`. The 2.x `url` key and plain URL strings still work. |
| `columns` | int | `4` | The grid's columns. A 2.x breakpoint array uses its largest value. |
| `featured` | bool | `false` | The first image spans two columns and rows. |
| `lightbox` | bool | `true` | Each image opens the lightbox. |
| `open` | bool | `false` | Whether the lightbox is open; `wire:model` binds it. |
| `index` | int | `0` | The image the lightbox shows, 0-based. |
| `downloadable` | bool | `false` | Adds a download link to the lightbox. |
| `modal` | bool | `true` | Traps focus, locks scroll and hides the page behind the lightbox. |
| `portal` | bool | `true` | Renders the lightbox at the end of the body (`x-teleport`). |
| `close-label` | string | `__( 'Close' )` | The close button's name. |
| `previous-label` | string | `__( 'Previous photo' )` | The previous button's name. |
| `next-label` | string | `__( 'Next photo' )` | The next button's name. |
| `download-label` | string | `__( 'Download' )` | The download link's name. |
| `counter-label` | string | `__( ':index / :count' )` | The counter before the caption; `{index}` and `{count}` are replaced. |
| `thumbs-label` | string | `__( 'Photos' )` | Names the thumbnail strip. |
| `classes` | array | `[]` | Extra classes per part: `root`, `grid`, `item`, `trigger`, `image`, `caption`, `backdrop`, `positioner`, `content`, `bar`, `title`, `download`, `closeTrigger`, `stage`, `prevTrigger`, `nextTrigger`, `thumbs`, `thumb`. |

Every other attribute lands on the root.

## Upgrading from 2.x

- The PhotoSwipe integration is gone: the lightbox is built in, and you no longer need to load PhotoSwipe. `enable-lightbox` still turns it off but is deprecated: use `:lightbox="false"`.
- `aspect-ratio`, `gap`, `layout`, `filters`, `items-per-page`, `loading-style`, `show-captions` and `lazy-load` are accepted and ignored. Thumbnails are 4:3 and always lazy-loaded; captions show whenever an image has one.
- Responsive `columns` arrays collapse to their largest value; set `columns` per breakpoint with your own CSS on `--apui-_image-gallery-columns` if you need more.

## Accessibility

- Every image has alt text; each grid image is a button named by it, with `aria-haspopup="dialog"`.
- The lightbox is a dialog named by its title (the counter and caption), which is a polite live region, so moving between images is announced.
- Focus moves into the lightbox, is trapped there while it's modal, and returns to the image that opened it.
- ArrowLeft and ArrowRight move between images and follow the text direction (in RTL, ArrowLeft shows the next image); Home and End show the first and last; Escape closes.
- The previous, next, close and download buttons have translatable names; the thumbnail shown carries `aria-current`.
