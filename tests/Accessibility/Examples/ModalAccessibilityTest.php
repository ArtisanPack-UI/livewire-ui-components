<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Accessibility\Examples;

use ArtisanPack\LivewireUiComponents\Tests\Accessibility\AccessibilityTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits\TestsAriaAttributes;
use ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits\TestsKeyboardNavigation;
use ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits\TestsFocusManagement;

/**
 * Example accessibility tests for Modal component
 *
 * Demonstrates testing dialog pattern accessibility
 *
 * @package ArtisanPack\LivewireUiComponents\Tests\Accessibility\Examples
 */
class ModalAccessibilityTest extends AccessibilityTestCase
{
    use TestsAriaAttributes;
    use TestsKeyboardNavigation;
    use TestsFocusManagement;

    /** @test */
    public function modal_has_dialog_role_and_aria_modal(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.modal', [
            'title' => 'Confirm Action',
            'id' => 'test-modal',
        ]);

        $this->assertHasDialogAccessibility($html);
    }

    /** @test */
    public function modal_has_accessible_label(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.modal', [
            'title' => 'Delete Item',
            'id' => 'delete-modal',
        ]);

        // Should have aria-labelledby pointing to title
        $this->assertTrue(
            str_contains($html, 'aria-labelledby=') || str_contains($html, 'aria-label='),
            "Modal must have aria-labelledby or aria-label"
        );
    }

    /** @test */
    public function modal_has_aria_describedby(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.modal', [
            'title' => 'Warning',
            'id' => 'warning-modal',
        ]);

        // Should have aria-describedby pointing to content
        $this->assertStringContainsString('aria-describedby=', $html,
            "Modal should have aria-describedby linking to content");
    }

    /** @test */
    public function modal_has_focus_trap(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.modal', [
            'title' => 'Test Modal',
            'id' => 'test-modal',
        ]);

        $this->assertHasFocusTrap($html);
    }

    /** @test */
    public function modal_manages_focus_return(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.modal', [
            'title' => 'Test Modal',
            'id' => 'test-modal',
        ]);

        $this->assertReturnsFocus($html);
    }

    /** @test */
    public function modal_auto_focuses_first_element(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.modal', [
            'title' => 'Test Modal',
            'id' => 'test-modal',
        ]);

        $this->assertAutoFocuses($html);
    }

    /** @test */
    public function modal_close_button_has_aria_label(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.modal', [
            'title' => 'Test Modal',
            'id' => 'test-modal',
            'persistent' => false,
        ]);

        // Close button should have aria-label
        $this->assertStringContainsString('aria-label="Close modal"', $html,
            "Close button must have aria-label");
    }

    /** @test */
    public function modal_supports_escape_key(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.modal', [
            'title' => 'Test Modal',
            'id' => 'test-modal',
            'persistent' => false,
        ]);

        $this->assertHasEscapeKey($html);
    }

    /** @test */
    public function modal_backdrop_is_aria_hidden(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.modal', [
            'title' => 'Test Modal',
            'id' => 'test-modal',
        ]);

        // Backdrop should be decorative
        $this->assertTrue(
            str_contains($html, 'modal-backdrop') && str_contains($html, 'aria-hidden="true"'),
            "Modal backdrop should have aria-hidden=\"true\""
        );
    }

    /** @test */
    public function persistent_modal_cannot_be_closed_with_escape(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.modal', [
            'title' => 'Important Modal',
            'id' => 'important-modal',
            'persistent' => true,
        ]);

        // Should not have escape key handler when persistent
        $hasEscape = str_contains($html, '@keydown.escape');

        $this->assertFalse($hasEscape,
            "Persistent modal should not close on Escape key");
    }
}
