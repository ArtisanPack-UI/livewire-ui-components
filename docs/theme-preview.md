---
title: Theme Preview Tool
---

# Theme Preview Tool

The browser-based theme preview tool (`<livewire:theme-preview>` and the `/artisanpack/theme-preview` route) was removed in 3.0, with the daisyUI theme it previewed.

- To build a brand preset on the design tokens, run `php artisan artisanpack:generate-preset`.
- To let people pick a theme or preset with live miniatures, use the [ThemePreview component](components/theme-preview).
- To switch light, dark, high contrast or system, use the [ThemeToggle component](components/theme-toggle).
