<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Accessibility;

use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use Illuminate\Support\Facades\View;

/**
 * Base test case for accessibility testing
 *
 * This class provides utilities for testing WCAG 2.1 Level AA compliance
 *
 * @package ArtisanPack\LivewireUiComponents\Tests\Accessibility
 */
abstract class AccessibilityTestCase extends TestCase
{
    /**
     * WCAG 2.1 Level AA Rules to validate
     *
     * @var array
     */
    protected array $wcagRules = [
        // Level A - Required
        'area-alt',
        'aria-allowed-attr',
        'aria-required-attr',
        'aria-required-children',
        'aria-required-parent',
        'aria-roles',
        'aria-valid-attr',
        'aria-valid-attr-value',
        'button-name',
        'bypass',
        'color-contrast',
        'document-title',
        'duplicate-id',
        'form-field-multiple-labels',
        'frame-title',
        'html-has-lang',
        'html-lang-valid',
        'image-alt',
        'input-button-name',
        'input-image-alt',
        'label',
        'link-name',
        'list',
        'listitem',
        'meta-refresh',
        'object-alt',
        'tabindex',
        'td-headers-attr',
        'th-has-data-cells',
        'valid-lang',
        'video-caption',

        // Level AA - Required
        'color-contrast-enhanced',
        'label-title-only',
        'region',
        'skip-link',
    ];

    /**
     * Assert that rendered HTML has required ARIA attributes
     *
     * @param string $html
     * @param array $requiredAttributes
     * @return void
     */
    protected function assertHasAriaAttributes(string $html, array $requiredAttributes): void
    {
        foreach ($requiredAttributes as $attribute => $value) {
            if (is_int($attribute)) {
                // Just check for presence
                $this->assertStringContainsString($value, $html, "Missing ARIA attribute: {$value}");
            } else {
                // Check for specific value
                $this->assertStringContainsString("{$attribute}=\"{$value}\"", $html,
                    "Missing or incorrect ARIA attribute: {$attribute}=\"{$value}\"");
            }
        }
    }

    /**
     * Assert that element has proper role
     *
     * @param string $html
     * @param string $role
     * @return void
     */
    protected function assertHasRole(string $html, string $role): void
    {
        $this->assertStringContainsString("role=\"{$role}\"", $html,
            "Missing role=\"{$role}\" attribute");
    }

    /**
     * Assert that interactive element has accessible name
     *
     * @param string $html
     * @param string $selector (button, a, input, etc.)
     * @return void
     */
    protected function assertHasAccessibleName(string $html, string $selector = 'button'): void
    {
        $hasAriaLabel = str_contains($html, 'aria-label=');
        $hasAriaLabelledby = str_contains($html, 'aria-labelledby=');
        $hasTextContent = preg_match('/<' . $selector . '[^>]*>(.+?)<\/' . $selector . '>/s', $html, $matches) &&
                         !empty(trim(strip_tags($matches[1])));

        $this->assertTrue(
            $hasAriaLabel || $hasAriaLabelledby || $hasTextContent,
            "Element <{$selector}> must have an accessible name (aria-label, aria-labelledby, or text content)"
        );
    }

    /**
     * Assert that form field has associated label
     *
     * @param string $html
     * @param string $inputId
     * @return void
     */
    protected function assertHasLabel(string $html, string $inputId): void
    {
        $hasLabel = preg_match('/<label[^>]+for=["\']' . preg_quote($inputId, '/') . '["\']/', $html);
        $hasAriaLabel = str_contains($html, 'aria-label=');
        $hasAriaLabelledby = str_contains($html, 'aria-labelledby=');

        $this->assertTrue(
            $hasLabel || $hasAriaLabel || $hasAriaLabelledby,
            "Input with id '{$inputId}' must have an associated label"
        );
    }

    /**
     * Assert that element supports keyboard navigation
     *
     * @param string $html
     * @param string $element
     * @return void
     */
    protected function assertSupportsKeyboard(string $html, string $element = 'button'): void
    {
        $nativelyFocusable = ['button', 'a', 'input', 'select', 'textarea'];

        if (in_array($element, $nativelyFocusable)) {
            // Natively focusable elements don't need extra attributes
            $this->assertStringContainsString("<{$element}", $html);
        } else {
            // Custom interactive elements need tabindex and role
            $hasTabindex = str_contains($html, 'tabindex=');
            $hasRole = str_contains($html, 'role=');

            $this->assertTrue(
                $hasTabindex && $hasRole,
                "Custom interactive element must have tabindex and role attributes"
            );
        }
    }

    /**
     * Assert that error messages are properly associated
     *
     * @param string $html
     * @param string $inputId
     * @return void
     */
    protected function assertHasErrorAssociation(string $html, string $inputId): void
    {
        $hasAriaDescribedby = preg_match('/id=["\']' . preg_quote($inputId, '/') . '["\'][^>]+aria-describedby=["\'][^"\']*error/', $html);
        $hasAriaInvalid = str_contains($html, 'aria-invalid="true"');

        if ($hasAriaInvalid) {
            $this->assertTrue(
                $hasAriaDescribedby,
                "Input with error must have aria-describedby linking to error message"
            );
        }
    }

    /**
     * Assert that live regions are properly configured
     *
     * @param string $html
     * @param string $politeness ('polite' or 'assertive')
     * @return void
     */
    protected function assertHasLiveRegion(string $html, string $politeness = 'polite'): void
    {
        $this->assertStringContainsString("aria-live=\"{$politeness}\"", $html,
            "Missing aria-live=\"{$politeness}\" attribute");
    }

    /**
     * Assert that modal/dialog has proper accessibility
     *
     * @param string $html
     * @return void
     */
    protected function assertHasDialogAccessibility(string $html): void
    {
        $this->assertHasRole($html, 'dialog');
        $this->assertStringContainsString('aria-modal="true"', $html);
        $this->assertTrue(
            str_contains($html, 'aria-labelledby=') || str_contains($html, 'aria-label='),
            "Dialog must have aria-labelledby or aria-label"
        );
    }

    /**
     * Assert that table has proper accessibility
     *
     * @param string $html
     * @return void
     */
    protected function assertHasTableAccessibility(string $html): void
    {
        $this->assertStringContainsString('scope="col"', $html,
            "Table headers must have scope=\"col\" attribute");

        // Check for caption or aria-label
        $hasCaption = str_contains($html, '<caption>');
        $hasAriaLabel = str_contains($html, 'aria-label=');

        $this->assertTrue(
            $hasCaption || $hasAriaLabel,
            "Table should have a caption or aria-label"
        );
    }

    /**
     * Assert that navigation has proper landmarks
     *
     * @param string $html
     * @return void
     */
    protected function assertHasNavigationLandmark(string $html): void
    {
        $hasNav = str_contains($html, '<nav');
        $hasRole = str_contains($html, 'role="navigation"');

        $this->assertTrue(
            $hasNav || $hasRole,
            "Navigation must use <nav> element or role=\"navigation\""
        );
    }

    /**
     * Assert that images have alt text
     *
     * @param string $html
     * @return void
     */
    protected function assertImagesHaveAlt(string $html): void
    {
        // Find all img tags
        preg_match_all('/<img[^>]*>/i', $html, $images);

        foreach ($images[0] as $img) {
            $this->assertStringContainsString('alt=', $img,
                "All images must have alt attribute: {$img}");
        }
    }

    /**
     * Assert that focus indicators are present
     *
     * @param string $html
     * @return void
     */
    protected function assertHasFocusIndicators(string $html): void
    {
        // This would be better tested in browser tests
        // For unit tests, we can check for focus-related classes
        $hasFocusClasses = str_contains($html, 'focus:') ||
                          str_contains($html, 'focus-visible:') ||
                          str_contains($html, 'focus-within:');

        $this->assertTrue(
            $hasFocusClasses,
            "Interactive elements should have focus styling"
        );
    }

    /**
     * Render a component view for testing
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    protected function renderComponent(string $view, array $data = []): string
    {
        return View::make($view, $data)->render();
    }

    /**
     * Get common accessibility test data
     *
     * @return array
     */
    protected function getAccessibilityTestData(): array
    {
        return [
            'id' => 'test-component-' . uniqid(),
            'aria-label' => 'Test component',
            'required' => false,
        ];
    }
}
