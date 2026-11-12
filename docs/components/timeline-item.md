---
title: TimelineItem Component
---

# TimelineItem Component

One event of a [Timeline](timeline.md). Place it inside `<x-artisanpack-timeline>`; see the Timeline page for its props and examples.

```blade
<x-artisanpack-timeline>
	<x-artisanpack-timeline-item :title="__( 'Listing published' )" :time="__( 'Dec 13' )" datetime="2026-12-13" icon="house" color="primary" />
</x-artisanpack-timeline>
```

## Upgrading from 2.x

- An item now renders an `<li>`: wrap items in `<x-artisanpack-timeline>`.
- `subtitle` still works, as `time`. `first`, `last` and `pending` are ignored.
