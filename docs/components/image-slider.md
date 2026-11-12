---
title: ImageSlider Component
---

# ImageSlider Component

One large image per view, built on the ArtisanPack UI design system (`.apui-image-slider`) and the behavior engine's carousel machine (`apuiCarousel`): a scroll-snapping track with a glass caption, a counter, previous and next buttons, dots and optional thumbnails. The root is a region with `aria-roledescription="carousel"`.

## Basic Usage

```blade
<x-artisanpack-image-slider
	:label="__( 'Lakeview Lofts photos' )"
	:images="[
		[ 'src' => asset( 'photos/lake.jpg' ), 'alt' => __( 'Lake view from unit 4A' ), 'caption' => __( 'Lake view from 4A' ), 'description' => __( 'South-facing, 11th floor' ) ],
		[ 'src' => asset( 'photos/ridge.jpg' ), 'alt' => __( 'Sunrise over the ridge' ) ],
	]"
/>
```

Every image needs `alt` text. `caption` and `description` render as a glass card over the image's foot; keep them text, never baked into the image, and use the glass overlays only over photos.

## Thumbnails, autoplay and binding

`thumbnails` adds a strip under the frame. `autoplay` advances every `interval` ms and always shows a pause button; it pauses while the pointer or focus is on the slider. `wire:model` binds the slide shown (0-based), and the widget dispatches `index-change`.

```blade
<x-artisanpack-image-slider wire:model.live="photo" :images="$photos" aspect-ratio="16 / 9" thumbnails autoplay :interval="6000" />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `images` | array | `[]` | The slides: `[ 'src' => …, 'alt' => …, 'caption' => …, 'description' => … ]`. The 2.x `url` and `title` keys and plain URL strings still work. |
| `aspect-ratio` | string | `21 / 9` | The frame's ratio. The 2.x `16:9` form and `square` still work. |
| `index` | int | `0` | The slide shown; `wire:model` binds it. |
| `loop` | bool | `true` | Wraps from the last slide to the first. |
| `autoplay` | bool | `false` | Advances on its own, with a pause button. |
| `interval` | int | `5000` | Milliseconds between slides while autoplay runs (at least 1000). |
| `thumbnails` | bool | `false` | Shows a strip of thumbnails. |
| `label` | string | `__( 'Photos' )` | Names the region. |
| `previous-label` | string | `__( 'Previous photo' )` | The previous button's name. |
| `next-label` | string | `__( 'Next photo' )` | The next button's name. |
| `indicator-label` | string | `__( 'Photo :index' )` | A dot's name; `{index}` is replaced. |
| `slide-label` | string | `__( ':index of :count' )` | A slide's name. |
| `counter-label` | string | `__( ':index / :count' )` | The counter text. |
| `play-label` | string | `__( 'Start slide rotation' )` | The autoplay button while paused. |
| `pause-label` | string | `__( 'Stop slide rotation' )` | The autoplay button while playing. |
| `classes` | array | `[]` | Extra classes per part: `root`, `viewport`, `itemGroup`, `item`, `image`, `caption`, `title`, `description`, `progressText`, `prevTrigger`, `nextTrigger`, `indicatorGroup`, `indicator`, `autoplayTrigger`, `thumbs`, `thumb`. |

Every other attribute lands on the root.

## Upgrading from 2.x

- `auto-play` and `auto-play-interval` still work but are deprecated: use `autoplay` and `interval`.
- `with-arrows`, `with-indicators`, `show-counter`, `pause-on-hover`, `transition` and `transition-duration` are accepted and ignored: the arrows, dots and counter always show, slides scroll, and autoplay always pauses on hover and focus.
- The PhotoSwipe lightbox is gone, and `enable-lightbox` is ignored. For a lightbox, use ImageGallery.

## Accessibility

- A region with `aria-roledescription="carousel"` and a translatable name; each slide is a group with `aria-roledescription="slide"`, named by its position, and slides out of view are hidden from assistive technology.
- The track is a polite live region (off while autoplay runs), so moves are announced.
- The previous and next buttons and the dots have translatable names; the dot shown carries `aria-current`. ArrowLeft and ArrowRight on the dots follow the text direction; Home and End go to the first and last slide.
- Autoplay always has a pause button and stops while the pointer or focus is on the slider (WCAG 2.2.2).
