<?php

namespace ArtisanPack\LivewireUiComponents\Traits;

use Illuminate\Support\Str;

/**
 * Trait HasAccessibility
 *
 * Provides accessibility features and ARIA attributes management for components.
 * Implements WCAG 2.1 AA compliance patterns.
 *
 * @package ArtisanPack\LivewireUiComponents\Traits
 */
trait HasAccessibility
{
    /**
     * ARIA label for the component
     */
    public ?string $ariaLabel = null;

    /**
     * ID(s) of element(s) that describe this component
     */
    public ?string $ariaDescribedBy = null;

    /**
     * ID(s) of element(s) that label this component
     */
    public ?string $ariaLabelledBy = null;

    /**
     * Whether the component is hidden from assistive technologies
     */
    public bool $ariaHidden = false;

    /**
     * ARIA role for the component
     */
    public ?string $role = null;

    /**
     * Tab index for keyboard navigation
     */
    public ?int $tabindex = null;

    /**
     * Whether the component is disabled
     */
    public bool $disabled = false;

    /**
     * Whether the component is required (for form elements)
     */
    public bool $required = false;

    /**
     * Whether the component is invalid (for form elements)
     */
    public bool $invalid = false;

    /**
     * Build accessibility attributes array
     *
     * @return array<string, string|bool>
     */
    protected function buildAccessibilityAttributes(): array
    {
        $attributes = [];

        if ($this->ariaLabel) {
            $attributes['aria-label'] = $this->ariaLabel;
        }

        if ($this->ariaDescribedBy) {
            $attributes['aria-describedby'] = $this->ariaDescribedBy;
        }

        if ($this->ariaLabelledBy) {
            $attributes['aria-labelledby'] = $this->ariaLabelledBy;
        }

        if ($this->ariaHidden) {
            $attributes['aria-hidden'] = 'true';
        }

        if ($this->role) {
            $attributes['role'] = $this->role;
        }

        if ($this->tabindex !== null) {
            $attributes['tabindex'] = (string) $this->tabindex;
        }

        if ($this->disabled) {
            $attributes['aria-disabled'] = 'true';
        }

        if ($this->required) {
            $attributes['aria-required'] = 'true';
        }

        if ($this->invalid) {
            $attributes['aria-invalid'] = 'true';
        }

        return $attributes;
    }

    /**
     * Get accessibility attributes as a string for blade templates
     *
     * @return string
     */
    protected function getAccessibilityAttributeString(): string
    {
        $attrs = $this->buildAccessibilityAttributes();

        return collect($attrs)
            ->map(fn($value, $key) => sprintf('%s="%s"', $key, e($value)))
            ->implode(' ');
    }

    /**
     * Merge accessibility attributes with existing attributes
     *
     * @param array $existingAttributes
     * @return array
     */
    protected function mergeAccessibilityAttributes(array $existingAttributes = []): array
    {
        return array_merge($existingAttributes, $this->buildAccessibilityAttributes());
    }

    /**
     * Generate a unique ID for ARIA relationships if not provided
     *
     * @param string|null $prefix
     * @return string
     */
    protected function generateAriaId(?string $prefix = null): string
    {
        $prefix = $prefix ?: 'aria';
        return $prefix . '-' . Str::random(8);
    }

    /**
     * Check if component has accessibility attributes
     *
     * @return bool
     */
    protected function hasAccessibilityAttributes(): bool
    {
        return !empty($this->buildAccessibilityAttributes());
    }

    /**
     * Get screen reader only text helper
     *
     * @param string $text
     * @return string
     */
    protected function screenReaderOnly(string $text): string
    {
        return sprintf('<span class="sr-only">%s</span>', e($text));
    }
}
