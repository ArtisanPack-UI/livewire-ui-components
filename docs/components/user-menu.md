---
title: UserMenu Component
---

# UserMenu Component

The account menu, built on the ArtisanPack UI design system from Avatar and DropdownMenu (`.apui-dropdown-menu.apui-user-menu`): an avatar button that opens the person's identity, a workspace switcher, a theme control, your items and Log out, which always comes last.

## Basic Usage

```blade
<x-artisanpack-user-menu
	:user="[ 'name' => $user->name, 'email' => $user->email, 'avatar' => $user->avatar_url, 'role' => $user->role_label ]"
	:workspaces="$workspaces"
	:workspace="$currentWorkspace"
	:items="[ [ 'value' => 'profile', 'label' => __( 'Profile' ), 'icon' => 'user' ], [ 'value' => 'settings', 'label' => __( 'Settings' ), 'icon' => 'settings' ] ]"
	:logout-action="route( 'logout' )"
	wire:workspace-change="switchWorkspace( $event.detail.value )"
	wire:theme-change="setTheme( $event.detail.value )"
	wire:select="go( $event.detail.value )"
/>
```

- `workspaces` takes `{ id, name }`; two or more render a Workspace group of radio items, and choosing one dispatches `workspace-change` with its id. Checking it stays yours, through `workspace`.
- The theme control dispatches `theme-change` with `light`, `dark` or `system`; applying the theme stays yours. `hide-theme` leaves it out.
- `items` takes DropdownMenu items data; `dropdown-menu-item` children render after them. Choosing an item dispatches `select` with its value.
- With `logout-action`, Log out posts to that URL with the CSRF token. Without it, Log out dispatches `select` with `logout`.

## Compact trigger

```blade
<x-artisanpack-user-menu :user="[ 'name' => $user->name, 'email' => $user->email ]" compact align="end" :logout-action="route( 'logout' )" />
```

`compact` shows only the avatar, for a top bar; the name stays the trigger's accessible name.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `user` | array | `[]` | `{ name, email, avatar, role, status }`. |
| `workspaces` | array | `[]` | `{ id, name }` pairs. |
| `workspace` | string | none | The current workspace's id. |
| `hide-theme` | bool | `false` | Hides the theme control. |
| `theme` | `light` · `dark` · `system` | `system` | The checked theme. |
| `items` | array | `[]` | DropdownMenu items data. |
| `compact` | bool | `false` | Shows only the avatar in the trigger. |
| `open` | bool | `false` | Whether it's open; `wire:model` binds it. |
| `align` | `start` · `center` · `end` | `start` | Which of the menu's edges lines up with the trigger's. |
| `surface` | `overlay` · `glass` | `overlay` | The menu's surface. |
| `portal` | bool | `true` | Renders the menu at the end of the body. |
| `workspace-label` | string | `__( 'Workspace' )` | The workspace group's label. |
| `theme-label` | string | `__( 'Theme' )` | The theme group's label. |
| `theme-labels` | array | `__( 'Light' )`, `__( 'Dark' )`, `__( 'System' )` | The theme options' names. |
| `logout-label` | string | `__( 'Log out' )` | The last item's label. |
| `logout-action` | string | none | A URL Log out posts to, with the CSRF token. |
| `classes` | array | `[]` | Extra classes per part, the menu's included. |

The `trigger` slot replaces the trigger's content. `class` lands on the panel; every other attribute, `wire:select` included, on the element that dispatches the events.

## Upgrading from `profile`

`<x-artisanpack-profile>` is a deprecated alias: `title` becomes the name, `subtitle` the role line, `image` the avatar and `right` aligns the menu to the end. The theme control is hidden, as 2.x had none. Move to `<x-artisanpack-user-menu>` and pass menu items as `items` or `dropdown-menu-item` children.

## Accessibility

- The trigger is a button with `aria-haspopup="menu"` and the person's name.
- Workspace and theme options are `menuitemradio` in labelled groups; the theme options show icons and keep their names for screen readers.
- Arrow keys, Home, End, typeahead and Escape work as in DropdownMenu.
