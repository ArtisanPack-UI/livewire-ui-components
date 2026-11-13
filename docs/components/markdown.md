---
title: Markdown Component
---

# Markdown Component

Renders GitHub-flavored Markdown (tables, task lists, strikethrough and autolinks) with the shared prose styles of the ArtisanPack UI design system (`.apui-markdown`, `.apui-prose`), and optionally edits it. It's rendered on the server with [league/commonmark](https://commonmark.thephpleague.com/), writing the same HTML React and Vue render, with no script in preview mode.

## Basic Usage

```blade
<x-artisanpack-markdown :source="$listing->notes" />
```

Headings keep their levels, task-list items are disabled checkboxes labelled by their text, tables keep their header cells (a column's `:---:` or `---:` alignment becomes `data-align`), and line length stops at the reading measure.

## Write and preview

`mode="write"` or `mode="split"` adds Write, Preview and Split tabs over a Textarea, opening on that tab; Split sets the source beside the preview. Bind the source with `wire:model`: it goes to the Textarea, and the source defaults to the bound property, so every Livewire update redraws the preview.

```blade
<x-artisanpack-markdown wire:model.live.debounce.500ms="notes" mode="split" />
```

With a deferred or debounced `wire:model`, the preview catches up when the Preview or Split tab is selected, which sends the latest edit first. Typing dispatches a `source-change` browser event with `{ value }`.

The panels render on the server, so the Write and Split panels each hold a Textarea bound to the same property. One is enabled at a time (Write's on the Write and Preview tabs, Split's on Split), so a form posts the source once, whichever tab is selected.

In a plain form, give it a `name` instead of `wire:model`. Both Textareas share one Alpine value, so an edit in Write shows in Split and the other way round, and the form posts the latest source whichever tab is selected. The Preview and Split panels render the draft in the browser with the behavior package's `renderMarkdown`, the same HTML the server writes (with the same `sanitize` setting), so they show what's being typed.

Native control attributes go to the Textarea, not the root: `name`, `required`, `disabled`, `readonly`, `placeholder`, `maxlength`, `minlength`, `rows` (default 12), `autofocus`, `autocomplete`, `spellcheck`, `form` and the like. A `required` source that's empty when the form is submitted from the Preview tab opens the Write tab.

```blade
<form method="POST" action="/notes">
    @csrf
    <x-artisanpack-markdown name="notes" :source="$note->body" mode="write" required />
</form>
```

## Raw HTML and links

Raw HTML in the source is escaped and shows as text, and links and images whose URL isn't safe are dropped, keeping their text: links take relative URLs, `http`, `https`, `mailto`, `tel` and `sms`; images relative URLs, `http`, `https`, `blob` and PNG, GIF, JPEG or WebP `data:` images.

`:sanitize="false"` keeps raw HTML that's formatting (`kbd`, `sup`, `sub`, `mark`, `details` and `summary`, `abbr`, tables, lists, headings, …) with a few safe attributes (`title`, `lang`, `dir`, a link's `href`, an image's `src`, `alt`, `width` and `height`, a cell's `colspan` and `rowspan`), and escapes every other tag. Event handlers, `style`, `class`, scripts, styles, frames, SVG and form controls never render, and unsafe URLs are still dropped: a raw `<img>` takes the same URLs as a Markdown image, so `data:` images are PNG, GIF, JPEG or WebP only (never `data:image/svg+xml`). To show HTML you fully trust, render it yourself.

Untrusted source can't make rendering unbounded: blocks nest at most 100 levels deep (a block quote is one level, a list two), deeper lines stay text, and a paragraph, heading or table cell keeps at most 500 emphasis or strikethrough delimiters, the rest stay literal.

```blade
<x-artisanpack-markdown :source="$release->notes" :sanitize="false" />
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `source` | string | the `wire:model` property | The Markdown. |
| `mode` | string | `preview` | `preview`, `write` or `split`. Emitted as `data-mode`. |
| `sanitize` | bool | `true` | Escape raw HTML. `false` keeps the allowlisted formatting tags. |
| `tabs-label` | string | `__( 'Markdown mode' )` | Names the mode tabs. |
| `write-label` | string | `__( 'Write' )` | The Write tab. |
| `preview-label` | string | `__( 'Preview' )` | The Preview tab. |
| `split-label` | string | `__( 'Split' )` | The Split tab. |
| `editor-label` | string | `__( 'Markdown source' )` | Names the source Textarea. |
| `output-label` | string | `__( 'Rendered preview' )` | Names the preview region in write and split modes. |
| `classes` | array | `[]` | Extra classes per part: `root`, `editor`, `preview`, `split`. |

`wire:model`, `name` and the native control attributes (`required`, `disabled`, `readonly`, `placeholder`, `maxlength`, `minlength`, `rows`, …) go to the Textarea; every other attribute lands on the root.

## Events

| Event | Detail | When |
|---|---|---|
| `source-change` | `{ value }` | The source was edited in the Textarea. |

## Upgrading from 2.x

- The component no longer embeds EasyMDE: remove the EasyMDE script and styles from your layout. It renders Markdown by default; add `mode="write"` or `mode="split"` where you edited with it, and keep your `wire:model`.
- `config`, `disk`, `folder`, `label`, `hint`, `hint-class`, `error-field`, `error-class`, `omit-error` and `first-error-only` are removed and ignored. Image uploads from the editor are gone; the `artisanpack.upload` route stays for the RichTextEditor. Put a label and validation messages around the component with a [Fieldset](fieldset).

## Accessibility

- Headings keep their levels and tables keep their header cells, so the structure reads as written.
- Task-list checkboxes are real, disabled checkboxes, named by their item's text.
- The mode tabs are design-system Tabs (the arrow keys follow the text direction); the Textarea and the preview region are named.
- Links are underlined, never marked by color alone; code blocks stay left to right in right-to-left pages.
