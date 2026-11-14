---
title: Livewire Integration
menu_order: 4
---

# Livewire Integration

The components work in plain Blade views, but they're built for Livewire: values and open state bind with `wire:model`, every part keeps its identity through Livewire's DOM morphing, and Livewire actions can show toasts. The design system's [Alpine and Livewire](https://docs.artisanpackui.dev/documentation/design-system/behavior/alpine-and-livewire) page explains how the behavior engine works underneath; this page covers using the components.

## Binding values with `wire:model`

Form fields take `wire:model` like native inputs, modifiers included:

```blade
<x-artisanpack-input :label="__( 'Name' )" wire:model.blur="name" />
<x-artisanpack-select :label="__( 'Country' )" :options="$countries" wire:model.live="country" />
<x-artisanpack-toggle :label="__( 'Email me updates' )" wire:model="subscribed" />
```

Fields show their validation error on their own. They read it from `$errors` under the `wire:model` name, or under `error-field` when the rule's key is different. Pass `error` to set a message yourself:

```blade
<x-artisanpack-input :label="__( 'Email' )" wire:model="form.email" error-field="email" />
```

Components with richer values, such as Combobox, DatePicker, ColorPicker, RichTextEditor, Signature, Tabs, Accordion and Table's row selection, bind through `$wire.entangle()`. `wire:model` is deferred until the next request, as usual; add `.live` to send each change at once.

## Binding open state

Overlays bind whether they're open the same way. Set the property to open or close them from PHP:

```php
public bool $confirmingDelete = false;

public function confirmDelete(): void
{
    $this->confirmingDelete = true;
}
```

```blade
<x-artisanpack-dialog wire:model="confirmingDelete" :title="__( 'Delete this order?' )">
    {{ __( 'This cannot be undone.' ) }}

    <x-slot:footer>
        <x-artisanpack-button variant="ghost" wire:click="$set( 'confirmingDelete', false )">{{ __( 'Cancel' ) }}</x-artisanpack-button>
        <x-artisanpack-button color="error" wire:click="delete">{{ __( 'Delete' ) }}</x-artisanpack-button>
    </x-slot:footer>
</x-artisanpack-dialog>
```

This works on Dialog, Drawer, Popover, DropdownMenu, Spotlight and Collapse. Closing an overlay on the client (Escape, the close button, the backdrop) writes `false` back to the property. Without `wire:model`, the `open` prop sets the initial state and the overlay keeps its own state in Alpine.

## Lists and `wire:key`

Give each item you render in a loop a `wire:key`, as any Livewire list needs:

```blade
@foreach ( $orders as $order )
    <x-artisanpack-list-item wire:key="order-{{ $order->id }}" :title="$order->number" />
@endforeach
```

## Stable ids

Livewire's morph matches elements by `wire:key`, then by `id`. A widget part without a stable id would be replaced on every re-render, dropping its focus trap and listeners, so every part the components render has a server-rendered id:

- It's derived from the `wire:model` name, or from the props that don't change (never from text or open state), plus the Livewire component's id, so it's unique on the page and the same on every render.
- A repeat in the same render, such as a component in a `@foreach`, gets `-2`, `-3` in render order.
- An `id` attribute replaces the derived base id, and the parts are named after it.

Set an `id` yourself when a component's position among identical siblings can change between renders.

## Events

Widgets report changes as DOM events, such as `open-change`, `value-change` and `select`. Each component's page lists its events. Listen with Alpine's `x-on` and call your component through `$wire`:

```blade
<x-artisanpack-dialog wire:model="editing" x-on:open-change="if ( ! $event.detail.open ) $wire.resetDraft()">
    …
</x-artisanpack-dialog>
```

Livewire's `wire:` syntax works for these events too, for example `wire:stop="cancel"` on StreamableContent.

## `wire:navigate`

Internal links in MenuItem, NavbarItem, Breadcrumbs and Spotlight get `wire:navigate`; set `:navigate="false"` on the component to turn it off. When you leave a page, every widget stops and cleans up its focus traps, scroll locks and listeners. The toaster is persisted, so toasts survive the navigation.

## Toasts from PHP

```php
$this->dispatch( 'toast', title: __( 'Saved' ), color: 'success' );
```

Or use the `Toast` trait for `$this->success( __( 'Saved' ) )` and toasts that survive a redirect. See [Toasts](Livewire-Toasts).

## Streaming content

[StreamableContent](https://docs.artisanpackui.dev/documentation/design-system/components/streamable-content) shows content that arrives over time, such as a generated answer. `target` names the `wire:stream` target, and `state` is `waiting`, `streaming`, `done`, `error` or `stopped`:

```blade
<x-artisanpack-streamable-content target="answer" :state="$state" wire:stop="cancel">
    {{ $answer }}
</x-artisanpack-streamable-content>
```

```php
$this->stream( to: 'answer', content: $chunk );
```

While it's streaming, its Stop button dispatches `stop`. The component adds `wire:stream` on Livewire 4; on Livewire 3 the content renders when the request finishes.

## Livewire-only components

ImageLibrary and StreamableContent exist only in Livewire UI Components, and Calendar has a full Livewire component. See:

- [Toasts](Livewire-Toasts)
- [Spotlight](Livewire-Spotlight)
- [Calendar](Livewire-Calendar)
- [Tables](Livewire-Tables)
- [Media and Uploads](Livewire-Media-And-Uploads)
