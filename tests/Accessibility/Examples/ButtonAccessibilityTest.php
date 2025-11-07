<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Accessibility\Examples;

use ArtisanPack\LivewireUiComponents\Tests\Accessibility\AccessibilityTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits\TestsAriaAttributes;
use ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits\TestsKeyboardNavigation;
use ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits\TestsFocusManagement;

/**
 * Example accessibility tests for Button component
 *
 * This demonstrates how to test button accessibility
 *
 * @package ArtisanPack\LivewireUiComponents\Tests\Accessibility\Examples
 */
class ButtonAccessibilityTest extends AccessibilityTestCase
{
    use TestsAriaAttributes;
    use TestsKeyboardNavigation;
    use TestsFocusManagement;

    /** @test */
    public function button_with_text_label_is_accessible(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.button', [
            'label' => 'Click me',
        ]);

        // Should have accessible name from text content
        $this->assertHasAccessibleName($html, 'button');

        // Should be keyboard focusable
        $this->assertIsKeyboardFocusable($html, 'button');

        // Should have visible focus indicators
        $this->assertHasVisibleFocus($html);
    }

    /** @test */
    public function icon_only_button_has_aria_label(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.button', [
            'icon' => 'o-trash',
            'aria-label' => 'Delete item',
        ]);

        // Must have aria-label for icon-only buttons
        $this->assertHasAriaLabel($html);
        $this->assertStringContainsString('aria-label="Delete item"', $html);
    }

    /** @test */
    public function disabled_button_has_proper_aria(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.button', [
            'label' => 'Submit',
            'disabled' => true,
        ]);

        // Should have disabled attribute or aria-disabled
        $this->assertTrue(
            str_contains($html, 'disabled') || str_contains($html, 'aria-disabled="true"'),
            "Disabled button must have disabled or aria-disabled attribute"
        );
    }

    /** @test */
    public function loading_button_has_aria_busy(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.button', [
            'label' => 'Save',
            'spinner' => true,
        ]);

        // Should have aria-busy when loading
        $this->assertTrue(
            str_contains($html, 'aria-busy'),
            "Loading button should have aria-busy attribute"
        );

        // Loading spinner should be hidden from screen readers
        $this->assertTrue(
            str_contains($html, 'aria-hidden="true"'),
            "Spinner icon should be aria-hidden"
        );

        // Should have screen reader text for loading state
        $this->assertTrue(
            str_contains($html, 'sr-only') && str_contains($html, 'Processing'),
            "Should have sr-only text announcing loading state"
        );
    }

    /** @test */
    public function button_link_has_role_button(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.button', [
            'label' => 'Go to page',
            'link' => '/some-page',
        ]);

        // Links styled as buttons should have role="button"
        $this->assertHasRole($html, 'button');

        // Should have proper tabindex
        $this->assertTrue(
            str_contains($html, 'tabindex="0"') || !str_contains($html, 'tabindex='),
            "Button link should be keyboard accessible"
        );
    }

    /** @test */
    public function button_supports_keyboard_activation(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.button', [
            'label' => 'Click me',
        ]);

        // Native button elements are keyboard accessible by default
        $this->assertStringContainsString('<button', $html);
    }
}
