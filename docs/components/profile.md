---
title: Profile Component
---

# Profile Component

**Deprecated.** `<x-artisanpack-profile>` renders [UserMenu](user-menu): it is an avatar dropdown, not a profile page.

```blade
{{-- Before --}}
<x-artisanpack-profile title="Olivia Rhye" subtitle="Admin" image="/avatars/olivia.jpg" right>
	…
</x-artisanpack-profile>

{{-- After --}}
<x-artisanpack-user-menu :user="[ 'name' => 'Olivia Rhye', 'role' => 'Admin', 'avatar' => '/avatars/olivia.jpg' ]" align="end" :logout-action="route( 'logout' )">
	<x-artisanpack-dropdown-menu-item value="settings" :label="__( 'Settings' )" icon="settings" />
</x-artisanpack-user-menu>
```

The alias maps `title` to the name, `subtitle` to the role line, `image` to the avatar and `right` to `align="end"`, and hides the theme control. `placeholder`, `color`, `color-adjustment`, `top` and `no-x-anchor` are gone.
