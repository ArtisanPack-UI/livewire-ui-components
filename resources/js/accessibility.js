/**
 * Accessibility JavaScript Module
 *
 * Provides Alpine.js directives, stores, and helpers for accessibility features.
 * Implements WCAG 2.1 AA compliant keyboard navigation, focus management,
 * and screen reader support.
 *
 * @package ArtisanPack\LivewireUiComponents
 */

(function() {
    'use strict';

    // ========================================================================
    // Configuration
    // ========================================================================

    const A11Y_CONFIG = {
        focusableSelector: 'a[href]:not([disabled]), button:not([disabled]), textarea:not([disabled]), input:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"]):not([disabled])',
        keyboardDelay: 10, // Debounce delay for keyboard events
        announcementDelay: 100, // Delay before announcing to screen readers
    };

    // ========================================================================
    // Utility Functions
    // ========================================================================

    /**
     * Get all focusable elements within a container
     */
    function getFocusableElements(container) {
        return Array.from(
            container.querySelectorAll(A11Y_CONFIG.focusableSelector)
        ).filter(el => {
            // Filter out hidden elements
            return el.offsetWidth > 0 && el.offsetHeight > 0 &&
                   window.getComputedStyle(el).visibility !== 'hidden';
        });
    }

    /**
     * Move focus to next/previous element
     */
    function moveFocus(elements, currentIndex, direction) {
        const newIndex = direction === 'next'
            ? Math.min(currentIndex + 1, elements.length - 1)
            : Math.max(currentIndex - 1, 0);

        if (elements[newIndex]) {
            elements[newIndex].focus();
            return newIndex;
        }
        return currentIndex;
    }

    /**
     * Announce message to screen readers
     */
    function announce(message, priority = 'polite') {
        const announcement = document.createElement('div');
        announcement.setAttribute('role', priority === 'assertive' ? 'alert' : 'status');
        announcement.setAttribute('aria-live', priority);
        announcement.setAttribute('aria-atomic', 'true');
        announcement.className = 'sr-only';
        announcement.textContent = message;

        document.body.appendChild(announcement);

        // Remove after announcement
        setTimeout(() => {
            document.body.removeChild(announcement);
        }, 1000);
    }

    /**
     * Check if reduced motion is preferred
     */
    function prefersReducedMotion() {
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    /**
     * Check if high contrast is preferred
     */
    function prefersHighContrast() {
        return window.matchMedia('(prefers-contrast: high)').matches;
    }

    // ========================================================================
    // Alpine.js Integration
    // ========================================================================

    document.addEventListener('alpine:init', () => {

        // ====================================================================
        // Alpine Store: Accessibility State
        // ====================================================================

        Alpine.store('accessibility', {
            reducedMotion: prefersReducedMotion(),
            highContrast: prefersHighContrast(),
            keyboardMode: false,
            shortcuts: {},

            init() {
                // Listen for preference changes
                window.matchMedia('(prefers-reduced-motion: reduce)')
                    .addEventListener('change', (e) => {
                        this.reducedMotion = e.matches;
                    });

                window.matchMedia('(prefers-contrast: high)')
                    .addEventListener('change', (e) => {
                        this.highContrast = e.matches;
                    });

                // Detect keyboard usage
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Tab') {
                        this.keyboardMode = true;
                        document.body.setAttribute('data-keyboard-mode', 'true');
                    }
                });

                document.addEventListener('mousedown', () => {
                    this.keyboardMode = false;
                    document.body.removeAttribute('data-keyboard-mode');
                });
            },

            registerShortcut(key, callback) {
                this.shortcuts[key] = callback;
            },

            announce(message, priority = 'polite') {
                announce(message, priority);
            },

            getAnimationDuration(defaultDuration) {
                return this.reducedMotion ? 1 : defaultDuration;
            }
        });

        // ====================================================================
        // Directive: x-keyboard-nav
        // ====================================================================

        Alpine.directive('keyboard-nav', (el, { expression }, { evaluate, cleanup }) => {
            const config = expression ? evaluate(expression) : {};
            const direction = config.direction || 'both';
            const homeEnd = config.homeEnd !== false;
            const pageKeys = config.pageKeys || false;

            let focusableElements = [];
            let currentIndex = 0;

            function updateFocusableElements() {
                focusableElements = getFocusableElements(el);
                currentIndex = focusableElements.indexOf(document.activeElement);
            }

            function handleArrowKey(e, dir) {
                e.preventDefault();
                updateFocusableElements();

                if (dir === 'up' || dir === 'left') {
                    currentIndex = moveFocus(focusableElements, currentIndex, 'previous');
                } else if (dir === 'down' || dir === 'right') {
                    currentIndex = moveFocus(focusableElements, currentIndex, 'next');
                }
            }

            function handleKey(e) {
                switch(e.key) {
                    case 'ArrowUp':
                        if (direction === 'vertical' || direction === 'both') {
                            handleArrowKey(e, 'up');
                        }
                        break;
                    case 'ArrowDown':
                        if (direction === 'vertical' || direction === 'both') {
                            handleArrowKey(e, 'down');
                        }
                        break;
                    case 'ArrowLeft':
                        if (direction === 'horizontal' || direction === 'both') {
                            handleArrowKey(e, 'left');
                        }
                        break;
                    case 'ArrowRight':
                        if (direction === 'horizontal' || direction === 'both') {
                            handleArrowKey(e, 'right');
                        }
                        break;
                    case 'Home':
                        if (homeEnd) {
                            e.preventDefault();
                            updateFocusableElements();
                            if (focusableElements.length > 0) {
                                focusableElements[0].focus();
                                currentIndex = 0;
                            }
                        }
                        break;
                    case 'End':
                        if (homeEnd) {
                            e.preventDefault();
                            updateFocusableElements();
                            if (focusableElements.length > 0) {
                                const lastIndex = focusableElements.length - 1;
                                focusableElements[lastIndex].focus();
                                currentIndex = lastIndex;
                            }
                        }
                        break;
                }
            }

            el.addEventListener('keydown', handleKey);

            cleanup(() => {
                el.removeEventListener('keydown', handleKey);
            });
        });

        // ====================================================================
        // Directive: x-focus-trap
        // ====================================================================

        Alpine.directive('focus-trap', (el, { expression, modifiers }, { evaluate, cleanup }) => {
            const isActive = expression ? evaluate(expression) : true;
            const inert = modifiers.includes('inert');
            const noScroll = modifiers.includes('noscroll');

            if (!isActive) return;

            let focusableElements = [];
            let previouslyFocusedElement = document.activeElement;

            function updateFocusableElements() {
                focusableElements = getFocusableElements(el);
            }

            function handleFocusTrap(e) {
                if (e.key !== 'Tab') return;

                updateFocusableElements();

                if (focusableElements.length === 0) return;

                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];

                if (e.shiftKey && document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                } else if (!e.shiftKey && document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }

            // Store previously focused element
            if (!window.lastFocusedElement) {
                window.lastFocusedElement = previouslyFocusedElement;
            }

            // Focus first element
            updateFocusableElements();
            if (focusableElements.length > 0) {
                setTimeout(() => {
                    focusableElements[0].focus();
                }, 50);
            }

            // Set inert on other content
            if (inert) {
                const siblings = Array.from(document.body.children).filter(
                    child => !child.contains(el) && child !== el
                );
                siblings.forEach(sibling => sibling.setAttribute('inert', ''));
            }

            // Prevent scrolling
            if (noScroll) {
                document.body.style.overflow = 'hidden';
            }

            // Add event listener
            el.addEventListener('keydown', handleFocusTrap);

            cleanup(() => {
                el.removeEventListener('keydown', handleFocusTrap);

                // Restore focus
                if (window.lastFocusedElement && typeof window.lastFocusedElement.focus === 'function') {
                    window.lastFocusedElement.focus();
                    window.lastFocusedElement = null;
                }

                // Remove inert
                if (inert) {
                    document.querySelectorAll('[inert]').forEach(element => {
                        element.removeAttribute('inert');
                    });
                }

                // Restore scrolling
                if (noScroll) {
                    document.body.style.overflow = '';
                }
            });
        });

        // ====================================================================
        // Directive: x-live-region
        // ====================================================================

        Alpine.directive('live-region', (el, { modifiers }) => {
            const politeness = modifiers.includes('assertive') ? 'assertive' : 'polite';
            const atomic = modifiers.includes('atomic') ? 'true' : 'false';

            el.setAttribute('aria-live', politeness);
            el.setAttribute('aria-atomic', atomic);
            el.setAttribute('role', 'status');
        });

        // ====================================================================
        // Directive: x-announce
        // ====================================================================

        Alpine.directive('announce', (el, { expression }, { evaluateLater, effect }) => {
            const getMessage = evaluateLater(expression);

            effect(() => {
                getMessage(message => {
                    if (message) {
                        announce(message);
                    }
                });
            });
        });

        // ====================================================================
        // Directive: x-skip-link
        // ====================================================================

        Alpine.directive('skip-link', (el, { expression }, { evaluate }) => {
            const targetId = expression ? evaluate(expression) : null;

            if (!targetId) {
                console.warn('x-skip-link requires a target element ID');
                return;
            }

            el.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.getElementById(targetId);

                if (target) {
                    // Make target focusable if it isn't
                    if (!target.hasAttribute('tabindex')) {
                        target.setAttribute('tabindex', '-1');
                    }

                    target.focus();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // ====================================================================
        // Magic: $a11y
        // ====================================================================

        Alpine.magic('a11y', () => ({
            announce: (message, priority = 'polite') => announce(message, priority),

            focusFirst: (container = document) => {
                const elements = getFocusableElements(container);
                if (elements.length > 0) {
                    elements[0].focus();
                }
            },

            focusLast: (container = document) => {
                const elements = getFocusableElements(container);
                if (elements.length > 0) {
                    elements[elements.length - 1].focus();
                }
            },

            trapFocus: (container) => {
                return {
                    elements: getFocusableElements(container),
                    trap() {
                        // Implement focus trap logic
                    }
                };
            },

            prefersReducedMotion: () => prefersReducedMotion(),
            prefersHighContrast: () => prefersHighContrast(),

            getAnimationDuration: (defaultMs) => {
                return prefersReducedMotion() ? 1 : defaultMs;
            }
        }));

    });

    // ========================================================================
    // Global Keyboard Shortcuts Manager
    // ========================================================================

    class KeyboardShortcutManager {
        constructor() {
            this.shortcuts = new Map();
            this.enabled = true;

            document.addEventListener('keydown', (e) => this.handleKeydown(e));
        }

        register(key, handler, options = {}) {
            const normalizedKey = this.normalizeKey(key);
            this.shortcuts.set(normalizedKey, { handler, options });
        }

        unregister(key) {
            const normalizedKey = this.normalizeKey(key);
            this.shortcuts.delete(normalizedKey);
        }

        normalizeKey(key) {
            const parts = key.toLowerCase().split('+');
            const sorted = parts.sort().join('+');
            return sorted;
        }

        handleKeydown(e) {
            if (!this.enabled) return;

            const parts = [];
            if (e.ctrlKey) parts.push('ctrl');
            if (e.altKey) parts.push('alt');
            if (e.shiftKey) parts.push('shift');
            if (e.metaKey) parts.push('meta');

            const key = e.key.toLowerCase();
            if (!['control', 'alt', 'shift', 'meta'].includes(key)) {
                parts.push(key);
            }

            const normalizedKey = parts.sort().join('+');
            const shortcut = this.shortcuts.get(normalizedKey);

            if (shortcut) {
                const { handler, options } = shortcut;

                // Check if shortcut should be prevented in inputs
                if (options.preventInInputs !== false) {
                    const target = e.target;
                    if (target.tagName === 'INPUT' || target.tagName === 'TEXTAREA' ||
                        target.isContentEditable) {
                        return;
                    }
                }

                e.preventDefault();
                handler(e);
            }
        }

        enable() {
            this.enabled = true;
        }

        disable() {
            this.enabled = false;
        }
    }

    // Create global instance
    window.keyboardShortcuts = new KeyboardShortcutManager();

    // ========================================================================
    // Accessibility Utilities
    // ========================================================================

    window.a11yUtils = {
        announce,
        getFocusableElements,
        prefersReducedMotion,
        prefersHighContrast,

        /**
         * Create a roving tabindex manager
         */
        createRovingTabindex(container) {
            const elements = getFocusableElements(container);
            let currentIndex = 0;

            // Set initial tabindex
            elements.forEach((el, index) => {
                el.setAttribute('tabindex', index === 0 ? '0' : '-1');
            });

            return {
                next() {
                    elements[currentIndex].setAttribute('tabindex', '-1');
                    currentIndex = (currentIndex + 1) % elements.length;
                    elements[currentIndex].setAttribute('tabindex', '0');
                    elements[currentIndex].focus();
                },

                previous() {
                    elements[currentIndex].setAttribute('tabindex', '-1');
                    currentIndex = (currentIndex - 1 + elements.length) % elements.length;
                    elements[currentIndex].setAttribute('tabindex', '0');
                    elements[currentIndex].focus();
                },

                first() {
                    elements[currentIndex].setAttribute('tabindex', '-1');
                    currentIndex = 0;
                    elements[currentIndex].setAttribute('tabindex', '0');
                    elements[currentIndex].focus();
                },

                last() {
                    elements[currentIndex].setAttribute('tabindex', '-1');
                    currentIndex = elements.length - 1;
                    elements[currentIndex].setAttribute('tabindex', '0');
                    elements[currentIndex].focus();
                }
            };
        },

        /**
         * Generate unique ID for ARIA relationships
         */
        generateId(prefix = 'a11y') {
            return `${prefix}-${Math.random().toString(36).substr(2, 9)}`;
        },

        /**
         * Check color contrast ratio
         */
        checkContrast(foreground, background) {
            // Simple contrast calculation (can be enhanced)
            const luminance = (color) => {
                const rgb = parseInt(color.replace('#', ''), 16);
                const r = ((rgb >> 16) & 0xff) / 255;
                const g = ((rgb >> 8) & 0xff) / 255;
                const b = (rgb & 0xff) / 255;

                const [rs, gs, bs] = [r, g, b].map(c =>
                    c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4)
                );

                return 0.2126 * rs + 0.7152 * gs + 0.0722 * bs;
            };

            const l1 = luminance(foreground);
            const l2 = luminance(background);
            const ratio = (Math.max(l1, l2) + 0.05) / (Math.min(l1, l2) + 0.05);

            return {
                ratio,
                passAA: ratio >= 4.5,
                passAAA: ratio >= 7,
                passAALarge: ratio >= 3
            };
        }
    };

    // ========================================================================
    // Initialize
    // ========================================================================

    // Add keyboard mode detection
    let keyboardMode = false;

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            keyboardMode = true;
            document.body.setAttribute('data-keyboard-mode', 'true');
        }
    });

    document.addEventListener('mousedown', () => {
        keyboardMode = false;
        document.body.removeAttribute('data-keyboard-mode');
    });

    // Register common keyboard shortcuts
    keyboardShortcuts.register('?', () => {
        // Show keyboard shortcuts help
        announce('Keyboard shortcuts help. Press Escape to close.', 'polite');
        // Dispatch event for components to listen to
        window.dispatchEvent(new CustomEvent('show-keyboard-help'));
    });

    keyboardShortcuts.register('escape', () => {
        // Global escape handler
        window.dispatchEvent(new CustomEvent('escape-pressed'));
    });

    // Announce page changes for screen readers
    if (typeof Livewire !== 'undefined') {
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                // Announce significant changes
                if (effect.redirect || effect.dispatch) {
                    setTimeout(() => {
                        announce('Page updated', 'polite');
                    }, 100);
                }
            });
        });
    }

    console.log('✅ Accessibility module loaded');

})();
