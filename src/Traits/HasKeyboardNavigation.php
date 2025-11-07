<?php

namespace ArtisanPack\LivewireUiComponents\Traits;

/**
 * Trait HasKeyboardNavigation
 *
 * Provides keyboard navigation capabilities for components.
 * Implements WCAG 2.1 keyboard accessibility requirements.
 *
 * @package ArtisanPack\LivewireUiComponents\Traits
 */
trait HasKeyboardNavigation
{
    /**
     * Whether the component is keyboard navigable
     */
    public bool $keyboardNavigable = true;

    /**
     * Keyboard shortcut for the component (e.g., "ctrl+s", "alt+h")
     */
    public ?string $keyboardShortcut = null;

    /**
     * Whether to show keyboard shortcuts in tooltips
     */
    public bool $showKeyboardHints = true;

    /**
     * Navigation direction (vertical, horizontal, both)
     */
    public string $navigationDirection = 'both';

    /**
     * Whether to enable Home/End keys
     */
    public bool $enableHomeEnd = true;

    /**
     * Whether to enable Page Up/Page Down keys
     */
    public bool $enablePageKeys = false;

    /**
     * Get keyboard navigation attributes
     *
     * @return array<string, string|bool>
     */
    protected function getKeyboardAttributes(): array
    {
        if (!$this->keyboardNavigable) {
            return [];
        }

        $attrs = [];

        if ($this->keyboardShortcut) {
            $attrs['data-keyboard-shortcut'] = $this->keyboardShortcut;

            if ($this->showKeyboardHints) {
                $attrs['data-keyboard-hint'] = $this->formatKeyboardShortcut($this->keyboardShortcut);
            }
        }

        if ($this->navigationDirection !== 'both') {
            $attrs['data-nav-direction'] = $this->navigationDirection;
        }

        if ($this->enableHomeEnd) {
            $attrs['data-enable-home-end'] = 'true';
        }

        if ($this->enablePageKeys) {
            $attrs['data-enable-page-keys'] = 'true';
        }

        return $attrs;
    }

    /**
     * Format keyboard shortcut for display
     *
     * @param string $shortcut
     * @return string
     */
    protected function formatKeyboardShortcut(string $shortcut): string
    {
        $parts = explode('+', strtolower($shortcut));
        $formatted = [];

        foreach ($parts as $part) {
            $formatted[] = match($part) {
                'ctrl' => '⌃',
                'alt' => '⌥',
                'shift' => '⇧',
                'cmd', 'meta' => '⌘',
                'enter' => '↵',
                'esc', 'escape' => '⎋',
                'tab' => '⇥',
                'space' => '␣',
                'up' => '↑',
                'down' => '↓',
                'left' => '←',
                'right' => '→',
                default => strtoupper($part)
            };
        }

        return implode('', $formatted);
    }

    /**
     * Get keyboard navigation help text
     *
     * @return string
     */
    protected function getKeyboardNavigationHelp(): string
    {
        $help = [];

        if ($this->navigationDirection === 'vertical' || $this->navigationDirection === 'both') {
            $help[] = '↑↓ Navigate';
        }

        if ($this->navigationDirection === 'horizontal' || $this->navigationDirection === 'both') {
            $help[] = '←→ Navigate';
        }

        if ($this->enableHomeEnd) {
            $help[] = 'Home/End Jump to first/last';
        }

        if ($this->enablePageKeys) {
            $help[] = 'Page Up/Down Scroll';
        }

        $help[] = 'Enter Activate';
        $help[] = 'Space Select';
        $help[] = 'Esc Close';

        return implode(', ', $help);
    }

    /**
     * Check if component supports keyboard navigation
     *
     * @return bool
     */
    protected function supportsKeyboardNavigation(): bool
    {
        return $this->keyboardNavigable;
    }

    /**
     * Get Alpine.js keyboard navigation directive
     *
     * @return string
     */
    protected function getAlpineKeyboardDirective(): string
    {
        if (!$this->keyboardNavigable) {
            return '';
        }

        $config = [
            'direction' => $this->navigationDirection,
            'homeEnd' => $this->enableHomeEnd,
            'pageKeys' => $this->enablePageKeys,
        ];

        return sprintf('x-keyboard-nav=\'%s\'', json_encode($config));
    }

    /**
     * Register keyboard shortcut
     *
     * @param string $shortcut
     * @param string $action
     * @return void
     */
    protected function registerKeyboardShortcut(string $shortcut, string $action): void
    {
        $this->keyboardShortcut = $shortcut;
        $this->dispatch('register-keyboard-shortcut', [
            'shortcut' => $shortcut,
            'action' => $action,
            'component' => static::class,
        ]);
    }

    /**
     * Get standard keyboard event handlers for blade templates
     *
     * @return string
     */
    protected function getKeyboardEventHandlers(): string
    {
        if (!$this->keyboardNavigable) {
            return '';
        }

        $handlers = [
            '@keydown.arrow-up.prevent="handleArrowUp"',
            '@keydown.arrow-down.prevent="handleArrowDown"',
            '@keydown.enter="handleEnter"',
            '@keydown.space.prevent="handleSpace"',
            '@keydown.escape="handleEscape"',
        ];

        if ($this->navigationDirection === 'horizontal' || $this->navigationDirection === 'both') {
            $handlers[] = '@keydown.arrow-left.prevent="handleArrowLeft"';
            $handlers[] = '@keydown.arrow-right.prevent="handleArrowRight"';
        }

        if ($this->enableHomeEnd) {
            $handlers[] = '@keydown.home.prevent="handleHome"';
            $handlers[] = '@keydown.end.prevent="handleEnd"';
        }

        if ($this->enablePageKeys) {
            $handlers[] = '@keydown.page-up.prevent="handlePageUp"';
            $handlers[] = '@keydown.page-down.prevent="handlePageDown"';
        }

        return implode(' ', $handlers);
    }
}
