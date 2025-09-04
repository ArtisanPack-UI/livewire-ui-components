---
title: AI Guidelines
---

# AI Guidelines

## Livewire UI Components (livewire-ui-components)

**Primary Goal**: To guide the creation of reusable, performant, and accessible Livewire components.

### Core Principles for the AI:

**Reusability**: Components should be designed to be self-contained and easily reusable throughout the application.

**Performance**: Prioritize performance by using Livewire's optimization features, such as wire:model.defer and lazy loading.

**Interactivity**: Use Alpine.js for client-side interactions that do not require a server round-trip.

### Specific Instructions for the AI:

1. **Loading States**: When generating Livewire components, include loading states using `wire:loading` to provide visual feedback to the user during asynchronous operations.

2. **Form Input Optimization**: For all form inputs, use `wire:model.defer` to batch updates and reduce the number of server requests.

3. **Client-Side Interactions**: For complex client-side interactions, such as a dropdown menu, generate Alpine.js code within the Blade view and use the `$wire` object to communicate with the Livewire component.

4. **Accessibility**: Ensure that all interactive elements within Livewire components are fully accessible, with proper focus management and ARIA attributes.