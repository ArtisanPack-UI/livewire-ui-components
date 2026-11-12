---
title: Diff Component
---

# Diff Component

Shows the changes between two versions of a text, built on the ArtisanPack UI design system (`.apui-diff`): unified or side by side, with hunks of context around each change and the changed words marked. It's computed on the server, with the same rows React and Vue render: no diff library and no script.

## Basic Usage

```blade
<x-artisanpack-diff
	:old-content="$previous->body"
	:new-content="$listing->body"
	file-name="listings/grove-greenfield.json"
/>
```

## Split view and header controls

The `actions` slot sits at the end of the header, for a copy button or a view switch:

```blade
<x-artisanpack-diff :old-content="$v3" :new-content="$v4" :file-name="__( 'Version 3 → Version 4' )" :view="$view">
	<x-slot:actions>
		<x-artisanpack-button size="xs" variant="ghost" wire:click="toggleView">
			{{ 'split' === $view ? __( 'Unified' ) : __( 'Split' ) }}
		</x-artisanpack-button>
	</x-slot:actions>
</x-artisanpack-diff>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `old-content` | string | none | The earlier version. |
| `new-content` | string | none | The later version. |
| `file-name` | string | none | What changed, at the start of the header. |
| `view` | string | `unified` | `unified` or `split`. |
| `word-level` | bool | `true` | Marks the changed words inside a changed line. |
| `context` | int | `3` | Unchanged lines kept around each change. |
| `counts-label` | string | `__( '{additions} additions, {deletions} deletions' )` | The counts' text for screen readers. |
| `empty-label` | string | `__( 'No changes' )` | Shown when the versions are the same. |
| `actions` | string | none | Header controls. The `actions` slot replaces it. |
| `classes` | array | `[]` | Extra classes per part: `root`, `header`, `icon`, `file`, `counts`, `actions`, `table`, `hunk`, `line`, `lineNumber`, `sign`, `code`, `ins`, `del`. |

Every other attribute lands on the root.

## Upgrading from 2.x

- `old` and `new` still work, as `old-content` and `new-content`.
- The Diff no longer uses diff2html or `jfcherng/php-diff`: remove the diff2html script and styles from your layout. `config` is gone; use `view`, `word-level` and `context`.
- The 2.x default was side by side; the default is now `unified`.

## Accessibility

- Every changed line has a `+` or `−` sign, and changed words are `ins` and `del` elements: color is never the only signal.
- The counts are read as words; the visible `+3 −2` is hidden from screen readers.
- The header mirrors in right-to-left layouts; the code stays left to right.
