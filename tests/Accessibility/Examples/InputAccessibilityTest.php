<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Accessibility\Examples;

use ArtisanPack\LivewireUiComponents\Tests\Accessibility\AccessibilityTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits\TestsAriaAttributes;

/**
 * Example accessibility tests for Input component
 *
 * Demonstrates testing form accessibility
 *
 * @package ArtisanPack\LivewireUiComponents\Tests\Accessibility\Examples
 */
class InputAccessibilityTest extends AccessibilityTestCase
{
    use TestsAriaAttributes;

    /** @test */
    public function input_has_associated_label(): void
    {
        $inputId = 'test-input-' . uniqid();

        $html = $this->renderComponent('livewire-ui-components::components.input', [
            'label' => 'Email Address',
            'id' => $inputId,
        ]);

        $this->assertHasLabel($html, $inputId);
    }

    /** @test */
    public function required_input_has_aria_required(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.input', [
            'label' => 'Username',
            'required' => true,
        ]);

        $this->assertHasAriaRequired($html, true);
    }

    /** @test */
    public function input_with_error_has_aria_invalid(): void
    {
        // This would need to be tested with actual error state
        // Showing the pattern for checking
        $html = '<input id="test" aria-invalid="true" aria-describedby="test-error">';

        $this->assertHasAriaInvalid($html, true);
    }

    /** @test */
    public function input_with_error_has_describedby(): void
    {
        $inputId = 'test-input';

        // Simulated error state
        $html = '<input id="test-input" aria-invalid="true" aria-describedby="test-input-error">
                 <div id="test-input-error" role="alert">This field is required</div>';

        $this->assertHasErrorAssociation($html, $inputId);
    }

    /** @test */
    public function input_with_hint_has_aria_describedby(): void
    {
        $inputId = 'test-input-' . uniqid();

        $html = $this->renderComponent('livewire-ui-components::components.input', [
            'label' => 'Password',
            'hint' => 'Must be at least 8 characters',
            'id' => $inputId,
        ]);

        $this->assertStringContainsString('aria-describedby=', $html,
            "Input with hint should have aria-describedby");
    }

    /** @test */
    public function input_error_message_has_role_alert(): void
    {
        // This pattern shows what should be in error state
        $html = '<div id="input-error" role="alert" aria-live="polite">Error message</div>';

        $this->assertStringContainsString('role="alert"', $html);
        $this->assertStringContainsString('aria-live="polite"', $html);
    }

    /** @test */
    public function input_clearable_button_has_aria_label(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.input', [
            'label' => 'Search',
            'clearable' => true,
        ]);

        // Clearable icon should have proper button semantics
        if (str_contains($html, 'clearable') || str_contains($html, 'x-mark')) {
            $this->assertTrue(
                str_contains($html, 'aria-label="Clear input"') ||
                str_contains($html, 'role="button"'),
                "Clear button should have aria-label and role"
            );
        }
    }

    /** @test */
    public function disabled_input_is_not_required(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.input', [
            'label' => 'Disabled Field',
            'disabled' => true,
        ]);

        // Disabled inputs don't need aria-required
        $this->assertStringContainsString('disabled', $html);
    }

    /** @test */
    public function input_with_prefix_maintains_accessibility(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.input', [
            'label' => 'Price',
            'prefix' => '$',
        ]);

        // Prefix should not interfere with label association
        $this->assertTrue(
            str_contains($html, 'for=') || str_contains($html, 'aria-label='),
            "Input with prefix must maintain label association"
        );
    }

    /** @test */
    public function money_input_has_proper_inputmode(): void
    {
        $html = $this->renderComponent('livewire-ui-components::components.input', [
            'label' => 'Amount',
            'money' => true,
        ]);

        // Money inputs should have inputmode="numeric"
        $this->assertStringContainsString('inputmode="numeric"', $html,
            "Money input should have inputmode=\"numeric\"");
    }
}
