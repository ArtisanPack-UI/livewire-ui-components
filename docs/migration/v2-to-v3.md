---
title: Upgrading from v2.x to v3.0
---

# Upgrading from v2.x to v3.0

Version 3.0 is rebuilt on the ArtisanPack UI design system. DaisyUI is gone, some components and attributes were renamed to match React and Vue, and theming moved to design tokens. Most 2.x tags still render in 3.0 as deprecated aliases, so an app keeps working while you migrate.

The full guide, with every renamed component and every attribute that changed, is the design system's [Livewire upgrade guide](https://github.com/ArtisanPack-UI/design-system/blob/main/docs/upgrade/livewire.md). This page covers the command that does the mechanical part.

## The upgrade command

Commit your work, update the package, then preview the changes:

```bash
composer require artisanpack-ui/livewire-ui-components:^3.0
php artisan artisanpack:upgrade-ui --dry-run
```

The dry run prints a diff of every file it would change and writes nothing. Run it without `--dry-run` to apply the changes:

```bash
php artisan artisanpack:upgrade-ui
```

| Option | What it does |
|---|---|
| `paths` | Files or directories to upgrade. Default: `resources/views` |
| `--dry-run` | Show the changes as a diff without writing |
| `--no-classes` | Leave raw DaisyUI classes out of the report |
| `--json` | Print the report as JSON. It writes without asking, so pair it with `--dry-run` to only report |
| `--force` | Write without asking |

## What it rewrites

```blade
{{-- Before --}}
<x-artisanpack-modal wire:model="confirming" title="Delete order" subtitle="This cannot be undone." separator>
    <x-slot:actions>
        <x-artisanpack-button variant="error" icon="o-trash" label="Delete" tooltip="Delete for good" />
    </x-slot:actions>
</x-artisanpack-modal>

{{-- After --}}
<x-artisanpack-dialog wire:model="confirming" title="Delete order" description="This cannot be undone.">
    <x-slot:footer>
        <x-artisanpack-tooltip content="Delete for good"><x-artisanpack-button color="error" leading-icon="o-trash">Delete</x-artisanpack-button></x-artisanpack-tooltip>
    </x-slot:footer>
</x-artisanpack-dialog>
```

- Renamed components, with their closing tags: `modal` to `dialog`, `header` to `page-header`, `choices` to `combobox`, and the rest.
- Renamed attributes and slots, bound or not, in kebab-case or camelCase.
- Flags that became values (`right` to `side="end"`) and per-part class attributes (merged into `:classes`).
- Button `variant`: a color (`variant="primary"`) becomes `color`; a variant (`variant="ghost"`) stays.
- `label` on a self-closing button moves into the slot.
- Tooltip attributes become a `<x-artisanpack-tooltip>` around the element.

## What it reports

Each line of the report has the file, line and column:

| Label | Meaning |
|---|---|
| `manual` | An attribute, slot or component with no automatic replacement. The message says what to do |
| `review` | A change that was made and deserves a look |
| `removed` | An attribute that was deleted because 3.0 has no equivalent |
| `class` | A raw DaisyUI class (`btn`, `card`, `bg-base-200`), theme variable or DaisyUI configuration |

Raw DaisyUI classes are never rewritten: the right replacement depends on the element. The guide's [class table](https://github.com/ArtisanPack-UI/design-system/blob/main/docs/upgrade/livewire.md#replace-raw-daisyui-classes) says what to use for each.

## What it leaves alone

- Tags that other packages register under the same prefix, such as `<x-artisanpack-ec-price>`.
- Blade comments, `@verbatim` blocks and PHP.
- Attributes built in PHP or passed through `$attributes`. The command flags tags that build their attributes with Blade, so you can check them.

The command is safe to run again after you fix things by hand.

## Where the rules come from

The command applies `resources/design-system/upgrade-rules.php`, which the design system generates from its component manifests and the catalogue of the 2.x API. A test checks every rule against the tags this package registers and the attributes their classes take, so the command can't write markup 3.0 doesn't understand.
