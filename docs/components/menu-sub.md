---
title: MenuSub Component
---

# MenuSub Component

A collapsible sub-menu in a [Menu](menu): a button with `aria-expanded` that opens and closes its list. It renders open when `open` is set or it holds the current page. See [Menu](menu) for its props and accessibility notes.

```blade
<x-artisanpack-menu-sub :label="__( 'Properties' )" icon="settings">
	<x-artisanpack-menu-item :href="route( 'properties.index' )">{{ __( 'All properties' ) }}</x-artisanpack-menu-item>
</x-artisanpack-menu-sub>
```
