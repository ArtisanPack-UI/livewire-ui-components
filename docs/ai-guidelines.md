---
title: AI Guidelines
menu_order: 9
---

# AI Guidelines

Guidance for AI coding assistants that write Blade and Livewire code with Livewire UI Components 3.0. If you use [Laravel Boost](https://github.com/laravel/boost), you don't need to copy anything: the package ships this guidance as Boost guidelines (`resources/boost/guidelines/core.blade.php`), which `php artisan boost:install` adds to your assistant's instructions. Otherwise, paste the rules below into your assistant's project instructions, or point the assistant at this page.

## Rules

1. **Use the components, not raw markup.** Write `<x-artisanpack-button>`, `<x-artisanpack-input>`, `<x-artisanpack-dialog>` and so on, rather than hand-built HTML. Check the [component list](Components) first.
2. **Don't use DaisyUI.** 3.0 has no DaisyUI: classes such as `btn`, `card`, `badge`, `modal` and `bg-base-200` style nothing. Style with component props (`color`, `variant`, `size`, `surface`), the `classes` prop and the design tokens (`var(--apui-surface-sunken)`, or `bg-surface-sunken` with the Tailwind preset).
3. **Use 3.0 names.** `dialog` not `modal`, `dropdown-menu` not `dropdown`, `combobox` not `choices` or `tags`, `page-header` not `header`, `app-shell` not `main`. The old names are [deprecated aliases](Components-Deprecated-Aliases), and `php artisan artisanpack:upgrade-ui` rewrites 2.x Blade for you.
4. **Use 3.0 props.** On Button, `color="primary"` and `variant="ghost"`; `leading-icon` and `trailing-icon` instead of `icon` and `icon-right`; the label in the slot, not a `label` attribute.
5. **Bind with `wire:model`.** Fields bind their value with `wire:model` (add `.live` only when each change must reach the server). Overlays bind their open state with `wire:model` to a boolean property.
6. **Give list items a `wire:key`** in every `@foreach`.
7. **Use registry icon names**: Lucide names in kebab-case, such as `plus`, `search`, `circle-alert`. A name without a dot falls back to Heroicons (`o-user` renders `heroicon-o-user`). Any other Blade Icons set needs a dot after its prefix: Font Awesome is `fas.user`, not `fa-user`.
8. **Show toasts from PHP** with the `Toast` trait (`$this->success( __( 'Saved' ) )`) or `$this->dispatch( 'toast', title: …, color: … )`, and keep one `<x-artisanpack-toaster />` in the layout.
9. **Translate every visible string** with `__()`, including attributes: `:label="__( 'Email' )"`.
10. **Keep accessibility intact.** Give icon-only buttons an accessible name (`aria-label`), keep visible labels on fields, and don't make a toast the only place an error appears.

## References

- [Components](Components) and the design system's [component pages](https://docs.artisanpackui.dev/documentation/design-system/components)
- [Livewire Integration](Livewire)
- [Upgrading from 2.x](Upgrading)
