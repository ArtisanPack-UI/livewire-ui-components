---
title: RichTextEditor Component
---

# RichTextEditor Component

Formatted text editing with a toolbar over the shared prose styles, built on the ArtisanPack UI design system. The frame, toolbar and content look the same in Livewire, React and Vue, but the engines differ (D-003): Livewire edits with **TinyMCE** when the page loads it, under the design-system toolbar, and falls back to the built-in contentEditable engine React and Vue use.

## Basic Usage

```blade
<x-artisanpack-rich-text-editor
	wire:model="description"
	:label="__( 'Description' )"
	:hint="__( 'Shown on the listing page.' )"
	counter
/>
```

## Compact

Keep the toolbar to the formats your content needs.

```blade
<x-artisanpack-rich-text-editor
	wire:model="note"
	toolbar="compact"
	:label="__( 'Note to buyer' )"
	:placeholder="__( 'Write a note for the buyer…' )"
/>
```

## Markdown output

`output="markdown"` reads and writes Markdown and leaves underline off the toolbar. `max-length` stops typing at the limit and counts against it in the footer.

```blade
<x-artisanpack-rich-text-editor
	wire:model="summary"
	output="markdown"
	:max-length="500"
	:label="__( 'Summary' )"
/>
```

## TinyMCE

Load TinyMCE in `<head>` before `@artisanpackScripts` and Livewire:

```blade
<script src="{{ asset( 'vendor/artisanpack-ui/js/tinymce/tinymce.min.js' ) }}" referrerpolicy="origin"></script>
```

The component runs it inline on the content, with TinyMCE's own toolbar and menus turned off: the design-system toolbar drives it. Link and Image open TinyMCE's dialogs, and images upload to the package's `artisanpack.upload` route on `disk` and `folder` (signed-in users only). Pass extra settings with `config`, or `engine="native"` to always use the built-in engine. Without TinyMCE on the page, the built-in engine runs, with Markdown shortcuts.

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `toolbar` | string or array | `default` | A preset (`default`, `compact`, `minimal`) or a list of `bold`, `italic`, `underline`, `strike`, `heading2`, `heading3`, `bulletList`, `orderedList`, `quote`, `code`, `link`, `image`, `undo`, `redo` and `|` dividers. |
| `output` | string | `html` | `html` or `markdown`. Emitted as `data-output`. |
| `max-length` | int | none | The most characters allowed. Shows the footer. |
| `counter` | bool | `false` | Shows the footer with the word and character count. |
| `label` | string or slot | none | The visible label. It names the content. |
| `hint` | string or slot | none | Help text, linked with `aria-describedby`. |
| `error` | string | first `$errors` message | Validation message. Marks the field invalid. |
| `invalid` · `disabled` · `readonly` · `required` | bool | `false` | States; `required` also shows the required mark. |
| `placeholder` | string | none | Shown while the content is empty. |
| `value` | string | none | The value in the output format. `wire:model` binds it instead. Sanitized before it renders. |
| `toolbar-label` | string | `__( 'Formatting' )` | The toolbar's accessible name. |
| `bold-label` | string | `__( 'Bold' )` | The Bold button's accessible name. |
| `italic-label` | string | `__( 'Italic' )` | The Italic button's accessible name. |
| `underline-label` | string | `__( 'Underline' )` | The Underline button's accessible name. |
| `strike-label` | string | `__( 'Strikethrough' )` | The Strikethrough button's accessible name. |
| `heading2-label` | string | `__( 'Heading 2' )` | The Heading 2 button's accessible name. |
| `heading3-label` | string | `__( 'Heading 3' )` | The Heading 3 button's accessible name. |
| `bullet-list-label` | string | `__( 'Bulleted list' )` | The bulleted list button's accessible name. |
| `ordered-list-label` | string | `__( 'Numbered list' )` | The numbered list button's accessible name. |
| `quote-label` | string | `__( 'Quote' )` | The Quote button's accessible name. |
| `code-label` | string | `__( 'Code' )` | The Code button's accessible name. |
| `link-label` | string | `__( 'Link' )` | The Link button's accessible name. |
| `image-label` | string | `__( 'Image' )` | The Image button's accessible name. |
| `undo-label` | string | `__( 'Undo' )` | The Undo button's accessible name. |
| `redo-label` | string | `__( 'Redo' )` | The Redo button's accessible name. |
| `link-prompt-label` | string | `__( 'Link URL' )` | The prompt that asks for a link URL (built-in engine). |
| `image-prompt-label` | string | `__( 'Image URL' )` | The prompt that asks for an image URL (built-in engine). |
| `image-alt-prompt-label` | string | `__( 'Describe the image' )` | The prompt that asks for an image description (built-in engine). |
| `shortcuts-label` | string | `__( 'Markdown shortcuts on' )` | The footer note about Markdown shortcuts. |
| `word-label` | string | `__( ':count word' )` | The footer's word count for one word; :count is the number. |
| `words-label` | string | `__( ':count words' )` | The footer's word count; :count is the number. |
| `character-label` | string | `__( ':count character' )` | The footer's character count for one character. |
| `characters-label` | string | `__( ':count characters' )` | The footer's character count; :count is the number. |
| `limit-label` | string | `__( ':count / :max characters' )` | The footer's count with maxLength; :count and :max are numbers. |
| `engine` | string | `tinymce` | `tinymce` (when loaded) or `native`. Livewire only. |
| `config` | array | `[]` | Extra TinyMCE settings. Livewire only. |
| `disk` · `folder` | string | the first `uploads.disks` entry · `editor` | Where TinyMCE image uploads go. Leave `disk` unset to use the first allowed disk; a set disk must be in the `uploads.disks` config, and only images are accepted (see [Markdown](markdown#image-uploads)). Livewire only. |
| `error-field` | string | the `wire:model` name | The `$errors` key to read. |
| `classes` | array | `[]` | Extra classes per part: `root`, `label`, `required`, `box`, `toolbar`, `button`, `divider`, `content`, `placeholder`, `footer`, `count`, `hint`, `error`. |

`wire:model` binds the value through `$wire.entangle()`. `class` lands on the root (`.apui-rich-text-editor`), `name` on a hidden input, every other attribute on the root. The box is `wire:ignore`, so a re-render never resets what is being edited.

The value is sanitized to the editor's formats when it renders (`ArtisanPack\LivewireUiComponents\Support\RichTextHtml`): paragraphs, headings, lists, quotes, code, links and images with http, https (and mailto, tel for links) or relative URLs. Sanitize again on the server before you store or show it elsewhere.

## Keyboard

| Keys | Action |
|---|---|
| Tab | Into the toolbar (one stop), then the content |
| ArrowLeft / ArrowRight, Home / End | Between toolbar buttons, following the text direction |
| Mod+B / Mod+I / Mod+U | Bold, italic, underline (Mod is Ctrl, or ⌘ on Apple platforms) |
| Mod+Shift+X / Mod+Shift+7 / Mod+Shift+8 | Strikethrough, numbered list, bulleted list |
| Mod+K | Add or remove a link |
| `##`, `###`, `-`, `1.`, `>` then Space | Markdown shortcuts at the start of a line (built-in engine) |

## Accessibility

- The toolbar is `role="toolbar"` with a name and roving focus; toggles use `aria-pressed`, and every button has a name.
- The content is `role="textbox"` with `aria-multiline`, named by the label and described by the hint and error.
- Undo and redo icons mirror in RTL; code blocks stay left to right.

## Upgrading from `<x-artisanpack-editor>`

`<x-artisanpack-editor>` is now a deprecated alias of `<x-artisanpack-rich-text-editor>` and takes its props. You no longer copy `tinymce-editor.js` into your app: the behavior engine (`@artisanpackScripts`) runs TinyMCE. `gpl-license`, `hint-class`, `error-class`, `omit-error` and `first-error-only` are gone. The alias is removed in a later major release.
