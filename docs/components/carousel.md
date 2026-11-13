---
title: Carousel Component
---

# Carousel Component

Scroll-snapped slides with previous and next buttons and position dots, built on the ArtisanPack UI design system (`.apui-carousel`) and the behavior engine's carousel machine (`apuiCarousel`), the same one ImageSlider runs on. Several slides can show at once, with the next one peeking so people know there's more. The root is a region with `aria-roledescription="carousel"`.

## Basic Usage

```blade
<x-artisanpack-carousel
	:title="__( 'Featured properties' )"
	:per-view="3"
	:slides="[
		[ 'image' => asset( 'photos/cedarwood.jpg' ), 'alt' => __( 'Cedarwood Estates from the street' ), 'title' => 'Cedarwood Estates', 'description' => __( 'United States, New York' ) ],
		[ 'image' => asset( 'photos/lakeview.jpg' ), 'alt' => __( 'Lakeview Lofts from the lake' ), 'title' => 'Lakeview Lofts', 'description' => __( 'United States, Chicago' ) ],
		[ 'image' => asset( 'photos/oaks.jpg' ), 'alt' => __( 'Tranquil Oaks garden' ), 'title' => 'Tranquil Oaks', 'description' => __( 'Canada, Toronto' ) ],
		[ 'image' => asset( 'photos/golden.jpg' ), 'alt' => __( 'Golden Hour at sunset' ), 'title' => 'Golden Hour', 'description' => __( 'United States, Denver' ) ],
	]"
/>
```

Each slide is a Card built from its `image`, `alt`, `title` and `description` (and `href`, which links the card). `title` puts a heading in a header with the previous and next buttons beside it; without a title, the buttons overlay the slides' edges. `per-view` shows that many slides at once, and each move shows one more; the dots mark the positions.

## Custom slides, autoplay and binding

Render each slide yourself with `@scope('slide', $slide)`; `$loop->index` is its index. Give each slide a unique `id` when the list can be reordered: it keys the slide, so a slide's own state (an input, a nested component) moves with it; without unique ids slides are keyed by position. When a Livewire update changes the number of slides (or `per-view`, `loop`, `autoplay`, `interval` or a label), the carousel remounts so it counts the new slides. `autoplay` advances every `interval` ms and always shows a pause button; it pauses while the pointer or focus is on the carousel and starts paused when the person prefers reduced motion. `wire:model` binds the position shown (0-based), and the widget dispatches `index-change`.

```blade
<x-artisanpack-carousel wire:model.live="note" :label="__( 'Customer notes' )" :slides="$quotes" autoplay loop>
	@scope( 'slide', $quote )
		<x-artisanpack-card>
			<x-artisanpack-text size="lg">{{ $quote['text'] }}</x-artisanpack-text>
			<x-artisanpack-text size="sm" tone="muted">{{ $quote['author'] }}</x-artisanpack-text>
		</x-artisanpack-card>
	@endscope
</x-artisanpack-carousel>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `slides` | array | `[]` | The slides, in order. A unique `id` on every slide keys it. |
| `per-view` | int | `1` | Slides in view at once. Above 1 the next slide peeks. |
| `index` | int | `0` | The position shown; `wire:model` binds it. |
| `loop` | bool | `false` | Wraps from the last position to the first. |
| `autoplay` | bool | `false` | Advances on its own, with a pause button. |
| `interval` | int | `5000` | Milliseconds between slides while autoplay runs (at least 1000). |
| `title` | string | `null` | The header's heading. It names the region unless `label` is set. |
| `heading-level` | int | `3` | The title's heading level, 2 to 6. |
| `label` | string | the title, else `__( 'Carousel' )` | Names the region. |
| `previous-label` | string | `__( 'Previous slide' )` | The previous button's name. |
| `next-label` | string | `__( 'Next slide' )` | The next button's name. |
| `indicator-label` | string | `__( 'Go to slide :index' )` | A dot's name; `{index}` is replaced. |
| `slide-label` | string | `__( ':index of :count' )` | A slide's name. |
| `play-label` | string | `__( 'Start slide rotation' )` | The autoplay button while paused. |
| `pause-label` | string | `__( 'Stop slide rotation' )` | The autoplay button while playing. |
| `classes` | array | `[]` | Extra classes per part: `root`, `header`, `title`, `viewport`, `itemGroup`, `item`, `prevTrigger`, `nextTrigger`, `indicatorGroup`, `indicator`, `autoplayTrigger`. |

Every other attribute lands on the root.

## Upgrading from 2.x

- Slides keep their `image`, `alt`, `title` and `description` keys and now render as Cards. `url` links the card; `urlText` is ignored.
- The `content` scoped slot is now `@scope('slide', $slide)`.
- `aria-label` still works; prefer `label`.
- `without-arrows`, `without-indicators`, `next-arrow`, `previous-arrow`, `dots`, `aria-labelled-by` and `respects-reduced-motion` are accepted and ignored: the buttons and dots always show, and autoplay always respects reduced motion.
- `interval` now defaults to 5000ms (was 2000ms), and the carousel no longer wraps unless you set `loop`.
- Slides scroll rather than fade.

## Accessibility

- A region with `aria-roledescription="carousel"` and a translatable name; each slide is a group with `aria-roledescription="slide"`, named "2 of 6", and slides out of view are hidden from assistive technology.
- The track is a polite live region (off while autoplay runs), so moves are announced.
- The autoplay button comes first in the Tab order, before the slides and their links, so rotation can be stopped before reaching the moving content; it still sits over the viewport's top start.
- The previous and next buttons and the dots have translatable names; the dot shown carries `aria-current`. ArrowLeft and ArrowRight on the dots follow the text direction; Home and End go to the first and last position.
- Autoplay always has a visible pause button, stops while the pointer or focus is on the carousel, and never starts on its own under reduced motion (WCAG 2.2.2).
- In right-to-left pages the buttons' chevrons mirror and the next slide peeks on the left.
