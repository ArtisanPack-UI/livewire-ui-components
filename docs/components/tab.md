---
title: Tab Component
---

# Tab Component

One tab of [Tabs](tabs): it renders its panel from its slot and registers its trigger with the parent, so the tablist renders before the panels. See [Tabs](tabs) for its props, examples and accessibility notes.

```blade
<x-artisanpack-tabs wire:model.live="section">
	<x-artisanpack-tab value="profile" :label="__( 'Profile' )" icon="user">…</x-artisanpack-tab>
	<x-artisanpack-tab value="billing" :label="__( 'Billing' )" :count="2">…</x-artisanpack-tab>
</x-artisanpack-tabs>
```

`name` still works for `value` and is deprecated.
