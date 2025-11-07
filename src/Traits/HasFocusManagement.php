<?php

namespace ArtisanPack\LivewireUiComponents\Traits;

/**
 * Trait HasFocusManagement
 *
 * Provides focus management capabilities for components.
 * Implements WCAG 2.1 focus management requirements.
 *
 * @package ArtisanPack\LivewireUiComponents\Traits
 */
trait HasFocusManagement
{
    /**
     * Whether to auto-focus on component mount
     */
    public bool $autoFocus = false;

    /**
     * Whether to trap focus within the component
     */
    public bool $trapFocus = false;

    /**
     * Specific element to focus (CSS selector)
     */
    public ?string $focusTarget = null;

    /**
     * Whether to restore focus when component unmounts
     */
    public bool $restoreFocus = true;

    /**
     * Delay before focusing (in milliseconds)
     */
    public int $focusDelay = 0;

    /**
     * Whether focus should be visible (force visible focus indicator)
     */
    public bool $forceFocusVisible = false;

    /**
     * CSS class to add when element has focus
     */
    public string $focusClass = 'focus-visible';

    /**
     * Get focus management attributes
     *
     * @return array<string, string|bool>
     */
    protected function getFocusAttributes(): array
    {
        $attrs = [];

        if ($this->autoFocus) {
            $attrs['autofocus'] = 'autofocus';
        }

        if ($this->trapFocus) {
            $attrs['x-trap'] = 'open';
        }

        if ($this->forceFocusVisible) {
            $attrs['data-focus-visible-added'] = '';
        }

        return $attrs;
    }

    /**
     * Get Alpine.js focus management directives
     *
     * @return string
     */
    protected function getAlpineFocusDirectives(): string
    {
        $directives = [];

        if ($this->autoFocus) {
            if ($this->focusDelay > 0) {
                $directives[] = sprintf(
                    'x-init="setTimeout(() => { $el.focus() }, %d)"',
                    $this->focusDelay
                );
            } else {
                $directives[] = 'x-init="$nextTick(() => { $el.focus() })"';
            }
        }

        if ($this->trapFocus) {
            $directives[] = 'x-trap.inert.noscroll="open"';
        }

        if ($this->restoreFocus) {
            $directives[] = 'x-on:close="if (window.lastFocusedElement) { window.lastFocusedElement.focus(); }"';
        }

        if ($this->forceFocusVisible) {
            $directives[] = sprintf(
                'x-on:focus="$el.classList.add(\'%s\')"',
                $this->focusClass
            );
            $directives[] = sprintf(
                'x-on:blur="$el.classList.remove(\'%s\')"',
                $this->focusClass
            );
        }

        return implode(' ', $directives);
    }

    /**
     * Get focus trap initialization code
     *
     * @return string
     */
    protected function getFocusTrapInit(): string
    {
        if (!$this->trapFocus) {
            return '';
        }

        return <<<'JS'
x-init="
    // Store the last focused element before trap
    window.lastFocusedElement = document.activeElement;

    // Find all focusable elements
    const focusableElements = $el.querySelectorAll(
        'a[href]:not([disabled]), button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex=\"-1\"]):not([disabled])'
    );

    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];

    // Focus the first element
    $nextTick(() => {
        if (firstElement) {
            firstElement.focus();
        }
    });

    // Handle tab key cycling
    $el.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            if (e.shiftKey && document.activeElement === firstElement) {
                e.preventDefault();
                lastElement?.focus();
            } else if (!e.shiftKey && document.activeElement === lastElement) {
                e.preventDefault();
                firstElement?.focus();
            }
        }
    });
"
JS;
    }

    /**
     * Get focus restoration code
     *
     * @return string
     */
    protected function getFocusRestorationCode(): string
    {
        if (!$this->restoreFocus) {
            return '';
        }

        return <<<'JS'
x-on:close.window="
    if (window.lastFocusedElement && typeof window.lastFocusedElement.focus === 'function') {
        window.lastFocusedElement.focus();
        window.lastFocusedElement = null;
    }
"
JS;
    }

    /**
     * Get focusable elements query selector
     *
     * @return string
     */
    protected function getFocusableElementsSelector(): string
    {
        return 'a[href]:not([disabled]), button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"]):not([disabled])';
    }

    /**
     * Generate focus management Alpine data
     *
     * @return string
     */
    protected function getFocusManagementData(): string
    {
        return json_encode([
            'lastFocusedElement' => null,
            'focusableElements' => [],
            'currentFocusIndex' => 0,
            'init' => 'function() { this.updateFocusableElements(); }',
            'updateFocusableElements' => 'function() {
                this.focusableElements = Array.from(this.$el.querySelectorAll("' . $this->getFocusableElementsSelector() . '"));
            }',
            'focusFirst' => 'function() {
                this.updateFocusableElements();
                if (this.focusableElements.length > 0) {
                    this.focusableElements[0].focus();
                    this.currentFocusIndex = 0;
                }
            }',
            'focusLast' => 'function() {
                this.updateFocusableElements();
                if (this.focusableElements.length > 0) {
                    const lastIndex = this.focusableElements.length - 1;
                    this.focusableElements[lastIndex].focus();
                    this.currentFocusIndex = lastIndex;
                }
            }',
            'focusNext' => 'function() {
                this.updateFocusableElements();
                if (this.currentFocusIndex < this.focusableElements.length - 1) {
                    this.currentFocusIndex++;
                    this.focusableElements[this.currentFocusIndex].focus();
                }
            }',
            'focusPrevious' => 'function() {
                this.updateFocusableElements();
                if (this.currentFocusIndex > 0) {
                    this.currentFocusIndex--;
                    this.focusableElements[this.currentFocusIndex].focus();
                }
            }',
        ]);
    }

    /**
     * Check if focus management is enabled
     *
     * @return bool
     */
    protected function hasFocusManagement(): bool
    {
        return $this->autoFocus || $this->trapFocus || $this->restoreFocus;
    }

    /**
     * Get skip link HTML for bypassing content
     *
     * @param string $targetId
     * @param string $text
     * @return string
     */
    protected function getSkipLink(string $targetId, string $text = 'Skip to main content'): string
    {
        return sprintf(
            '<a href="#%s" class="skip-link" tabindex="0">%s</a>',
            e($targetId),
            e($text)
        );
    }
}
