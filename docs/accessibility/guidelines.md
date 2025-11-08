# Accessibility Guidelines

## Introduction

This document outlines the accessibility standards and best practices for ArtisanPack UI Livewire UI Components. All components in this library aim to meet WCAG 2.1 Level AA compliance.

## Core Principles

### 1. Perceivable
Information and user interface components must be presentable to users in ways they can perceive.

- **1.1 Text Alternatives**: Provide text alternatives for non-text content
- **1.2 Time-based Media**: Provide alternatives for time-based media
- **1.3 Adaptable**: Create content that can be presented in different ways
- **1.4 Distinguishable**: Make it easier for users to see and hear content

### 2. Operable
User interface components and navigation must be operable.

- **2.1 Keyboard Accessible**: Make all functionality available from a keyboard
- **2.2 Enough Time**: Provide users enough time to read and use content
- **2.3 Seizures and Physical Reactions**: Do not design content in a way that is known to cause seizures
- **2.4 Navigable**: Provide ways to help users navigate and find content
- **2.5 Input Modalities**: Make it easier for users to operate functionality through various inputs

### 3. Understandable
Information and the operation of user interface must be understandable.

- **3.1 Readable**: Make text content readable and understandable
- **3.2 Predictable**: Make web pages appear and operate in predictable ways
- **3.3 Input Assistance**: Help users avoid and correct mistakes

### 4. Robust
Content must be robust enough that it can be interpreted by a wide variety of user agents.

- **4.1 Compatible**: Maximize compatibility with current and future user agents

## Component-Specific Guidelines

### Form Components

#### Required Patterns
- All form inputs must have associated labels
- Error messages must be announced to screen readers
- Required fields must be indicated with `aria-required="true"`
- Invalid fields must be indicated with `aria-invalid="true"`
- Help text must be associated using `aria-describedby`

#### Example
```blade
<x-artisanpack-input
    label="Email Address"
    hint="We'll never share your email"
    wire:model="email"
    required
    aria-describedby="email-help"
/>
```

### Interactive Components

#### Modal/Dialog Pattern
- Must use `<dialog>` element or `role="dialog"`
- Must have `aria-modal="true"`
- Must have accessible label via `aria-labelledby` or `aria-label`
- Must trap focus within the modal
- Must return focus to trigger element on close
- Must respond to Escape key

#### Dropdown/Menu Pattern
- Must use proper ARIA roles (`menu`, `menuitem`)
- Must support keyboard navigation (Arrow keys, Home, End, Escape)
- Must indicate expanded state with `aria-expanded`
- Must associate trigger with menu using `aria-controls`

### Navigation Components

#### Tab Pattern
- Must use `role="tablist"`, `role="tab"`, `role="tabpanel"`
- Must indicate selected tab with `aria-selected="true"`
- Must support keyboard navigation (Arrow keys, Home, End)
- Must associate tabs with panels using `aria-controls` and `aria-labelledby`

## Keyboard Navigation Standards

### Standard Key Bindings

| Key | Action |
|-----|--------|
| Tab | Move focus to next focusable element |
| Shift+Tab | Move focus to previous focusable element |
| Enter | Activate button/link |
| Space | Activate button, toggle checkbox |
| Escape | Close modal/dialog/dropdown |
| Arrow Keys | Navigate within component (tabs, menus, etc.) |
| Home | Jump to first item |
| End | Jump to last item |

### Focus Management

- All interactive elements must be keyboard accessible
- Focus order must be logical and intuitive
- Focus must be visible (2px outline, high contrast)
- Focus must not be trapped (except in modals)
- Custom keyboard shortcuts should not override browser shortcuts

## Color Contrast Requirements

### WCAG AA Standards

- **Normal text**: Minimum 4.5:1 contrast ratio
- **Large text** (18pt+): Minimum 3:1 contrast ratio
- **UI components**: Minimum 3:1 contrast ratio
- **Graphical objects**: Minimum 3:1 contrast ratio

### Testing Tools

- Use the color contrast checker in browser DevTools
- Test with high contrast mode enabled
- Verify with automated tools (aXe, WAVE)

## Screen Reader Support

### ARIA Live Regions

Use for dynamic content updates:

```html
<div aria-live="polite" aria-atomic="true">
    <!-- Dynamic content -->
</div>
```

- **polite**: Announces when user is idle
- **assertive**: Announces immediately

### Hiding Content

```html
<!-- Visually hidden but announced -->
<span class="sr-only">For screen readers only</span>

<!-- Hidden from screen readers -->
<div aria-hidden="true">Decorative content</div>
```

## Testing Checklist

For each component, verify:

- [ ] Keyboard navigation works without mouse
- [ ] Screen reader announces all important information
- [ ] Focus indicators are clearly visible
- [ ] Color contrast meets WCAG AA standards
- [ ] Component works in high contrast mode
- [ ] Animations respect `prefers-reduced-motion`
- [ ] All interactive elements have accessible names
- [ ] Error states are properly announced
- [ ] No accessibility violations in aXe DevTools

## Resources

- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [WAI-ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/)
- [WebAIM Resources](https://webaim.org/resources/)
- [The A11Y Project](https://www.a11yproject.com/)
