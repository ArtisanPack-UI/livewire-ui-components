---
title: CodeEditor Component
---

# CodeEditor Component

Editable code in a native textarea, built on the ArtisanPack UI design system: file tabs, a language select, line numbers, the active line, diagnostics listed in text and a status bar. The code stays left to right in RTL; the controls around it mirror.

## Basic Usage

Tab indents. To leave the editor, press Escape, then Tab.

```blade
<x-artisanpack-code-editor
	wire:model="css"
	language="CSS"
	:label="__( 'Custom CSS' )"
	:hint="__( 'Added after the theme styles.' )"
	:rows="8"
/>
```

## Files and languages

File tabs and a language select over the code. Swap the value when `tab-change` reports a new file.

```blade
<x-artisanpack-code-editor
	wire:model="source"
	:label="__( 'Source' )"
	:tabs="[ 'format-price.ts', 'listing.blade.php' ]"
	:tab="$file"
	:languages="[ [ 'value' => 'typescript', 'label' => 'TypeScript' ], [ 'value' => 'php', 'label' => 'PHP' ] ]"
	:language="$language"
	x-on:tab-change="$wire.openFile( $event.detail.value )"
	x-on:language-change="$wire.set( 'language', $event.detail.value )"
/>
```

## Diagnostics

Problems are marked in the gutter and listed in text under the code, with their line and severity.

```blade
<x-artisanpack-code-editor
	wire:model="source"
	language="TypeScript"
	:label="__( 'Source' )"
	:diagnostics="[ [ 'line' => 6, 'column' => 31, 'message' => __( 'Type \'string\' is not assignable to type \'number\'.' ), 'severity' => 'error' ] ]"
/>
```

## Props

| Prop | Type | Default | Description |
|---|---|---|---|
| `language` | string | none | Shown in the status bar and selected in the language select. |
| `languages` | array | `[]` | Options for the language select, as strings or `[ 'value', 'label' ]`. |
| `tabs` | array | `[]` | File tabs, as strings or `[ 'value', 'label' ]`. |
| `tab` | string | the first tab | The selected tab. |
| `diagnostics` | array | `[]` | `[ 'line', 'column'?, 'message', 'severity'? ]`, severity `error` (default), `warning` or `info`. |
| `tab-size` | int | `2` | Spaces Tab inserts. |
| `rows` | int | `12` | The fewest lines shown; the editor grows with its content. |
| `label` | string or slot | none | The visible label, linked to the control. |
| `hint` | string or slot | none | Help text, linked with `aria-describedby`. |
| `error` | string | first `$errors` message | Validation message. Marks the field invalid. |
| `invalid` · `disabled` · `readonly` · `required` | bool | `false` | Set on the control; `required` also shows the required mark. |
| `placeholder` | string | none | Never a replacement for the label. |
| `value` | string | none | The code. `wire:model` binds it instead. |
| `tabs-label` | string | `__( 'Open files' )` | The file tablist's accessible name. |
| `language-label` | string | `__( 'Language' )` | The language select's accessible name. |
| `position-label` | string | `__( 'Ln :line, Col :column' )` | The caret position in the status bar; :line and :column are numbers. |
| `spaces-label` | string | `__( 'Spaces: :count' )` | The indentation in the status bar; :count is tabSize. |
| `error-count-label` | string | `__( ':count error' )` | The status bar's count for one error. |
| `errors-count-label` | string | `__( ':count errors' )` | The status bar's error count; :count is the number. |
| `warning-count-label` | string | `__( ':count warning' )` | The status bar's count for one warning. |
| `warnings-count-label` | string | `__( ':count warnings' )` | The status bar's warning count; :count is the number. |
| `problems-label` | string | `__( 'Problems' )` | The diagnostics list's accessible name. |
| `line-label` | string | `__( 'Line :line' )` | A diagnostic's line; :line is the number. |
| `severity-error-label` | string | `__( 'Error' )` | The visually hidden severity name of an error. |
| `severity-warning-label` | string | `__( 'Warning' )` | The visually hidden severity name of a warning. |
| `severity-info-label` | string | `__( 'Info' )` | The visually hidden severity name of an info diagnostic. |
| `instructions-label` | string | `__( 'Tab inserts indentation. To leave the editor, press Escape, then Tab.' )` | The visually hidden keyboard instructions linked to the control. |
| `error-field` | string | the `wire:model` name | The `$errors` key to read. |
| `classes` | array | `[]` | Extra classes per part. |

The textarea is the native control: `wire:model` and its modifiers, `name` and every other attribute land on it. `class` and `x-on:` listeners land on the root (`.apui-code-editor`), where `tab-change` and `language-change` are dispatched with `{ value }`.

## Accessibility

- The label is linked with `for`/`id`; the hint, error and visually hidden keyboard instructions with `aria-describedby`.
- Tab inserts indentation; Escape, then Tab, moves focus out (WCAG 2.1.2).
- The file tabs are a tablist with one tab stop and arrow keys that follow the text direction; the code is their tab panel.
- Diagnostics are listed in text, never only marked in the gutter.
- Colors come from tokens, so the editor follows light, dark and high-contrast themes.
