<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits;

/**
 * Trait for testing ARIA attributes
 *
 * @package ArtisanPack\LivewireUiComponents\Tests\Accessibility\Traits
 */
trait TestsAriaAttributes
{
    /**
     * Assert element has aria-label or aria-labelledby
     *
     * @param string $html
     * @return void
     */
    protected function assertHasAriaLabel(string $html): void
    {
        $hasAriaLabel = str_contains($html, 'aria-label=');
        $hasAriaLabelledby = str_contains($html, 'aria-labelledby=');

        $this->assertTrue(
            $hasAriaLabel || $hasAriaLabelledby,
            "Element must have aria-label or aria-labelledby"
        );
    }

    /**
     * Assert element has aria-describedby
     *
     * @param string $html
     * @param string $expectedId
     * @return void
     */
    protected function assertHasAriaDescribedby(string $html, string $expectedId = null): void
    {
        if ($expectedId) {
            $this->assertStringContainsString("aria-describedby=\"{$expectedId}\"", $html);
        } else {
            $this->assertStringContainsString('aria-describedby=', $html);
        }
    }

    /**
     * Assert element has correct aria-expanded state
     *
     * @param string $html
     * @param bool|string $state
     * @return void
     */
    protected function assertHasAriaExpanded(string $html, $state = null): void
    {
        if ($state === null) {
            $this->assertStringContainsString('aria-expanded=', $html);
        } else {
            $stateStr = is_bool($state) ? ($state ? 'true' : 'false') : $state;
            $this->assertStringContainsString("aria-expanded=\"{$stateStr}\"", $html);
        }
    }

    /**
     * Assert element has aria-selected attribute
     *
     * @param string $html
     * @param bool|string $state
     * @return void
     */
    protected function assertHasAriaSelected(string $html, $state = null): void
    {
        if ($state === null) {
            $this->assertStringContainsString('aria-selected=', $html);
        } else {
            $stateStr = is_bool($state) ? ($state ? 'true' : 'false') : $state;
            $this->assertStringContainsString("aria-selected=\"{$stateStr}\"", $html);
        }
    }

    /**
     * Assert element has aria-current attribute
     *
     * @param string $html
     * @param string $value
     * @return void
     */
    protected function assertHasAriaCurrent(string $html, string $value = 'page'): void
    {
        $this->assertStringContainsString("aria-current=\"{$value}\"", $html);
    }

    /**
     * Assert element has aria-controls attribute
     *
     * @param string $html
     * @param string $expectedId
     * @return void
     */
    protected function assertHasAriaControls(string $html, string $expectedId = null): void
    {
        if ($expectedId) {
            $this->assertStringContainsString("aria-controls=\"{$expectedId}\"", $html);
        } else {
            $this->assertStringContainsString('aria-controls=', $html);
        }
    }

    /**
     * Assert element has aria-haspopup attribute
     *
     * @param string $html
     * @param string $type
     * @return void
     */
    protected function assertHasAriaHaspopup(string $html, string $type = 'true'): void
    {
        $this->assertStringContainsString("aria-haspopup=\"{$type}\"", $html);
    }

    /**
     * Assert element has aria-invalid when there's an error
     *
     * @param string $html
     * @param bool $hasError
     * @return void
     */
    protected function assertHasAriaInvalid(string $html, bool $hasError = true): void
    {
        if ($hasError) {
            $this->assertStringContainsString('aria-invalid="true"', $html);
        }
    }

    /**
     * Assert element has aria-required attribute
     *
     * @param string $html
     * @param bool $isRequired
     * @return void
     */
    protected function assertHasAriaRequired(string $html, bool $isRequired = true): void
    {
        if ($isRequired) {
            $this->assertStringContainsString('aria-required="true"', $html);
        }
    }

    /**
     * Assert element has aria-disabled attribute
     *
     * @param string $html
     * @param bool $isDisabled
     * @return void
     */
    protected function assertHasAriaDisabled(string $html, bool $isDisabled = true): void
    {
        if ($isDisabled) {
            $this->assertStringContainsString('aria-disabled="true"', $html);
        }
    }

    /**
     * Assert element has aria-hidden attribute
     *
     * @param string $html
     * @return void
     */
    protected function assertHasAriaHidden(string $html): void
    {
        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    /**
     * Assert progress element has proper ARIA
     *
     * @param string $html
     * @return void
     */
    protected function assertHasProgressAria(string $html): void
    {
        $this->assertHasRole($html, 'progressbar');
        $this->assertStringContainsString('aria-valuemin=', $html);
        $this->assertStringContainsString('aria-valuemax=', $html);
    }

    /**
     * Assert menu button has proper ARIA
     *
     * @param string $html
     * @return void
     */
    protected function assertHasMenuButtonAria(string $html): void
    {
        $this->assertHasAriaHaspopup($html, 'menu');
        $this->assertHasAriaExpanded($html);
    }
}
