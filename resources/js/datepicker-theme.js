/**
 * DatePicker Theme Integration
 * 
 * JavaScript utilities for ArtisanPack UI DatePicker theming system.
 * Handles dark mode detection, theme switching, and FlatPicker integration.
 *
 * @package ArtisanPack\LivewireUiComponents
 * @since 1.1.0
 */

/**
 * DatePicker Theme Manager
 */
class DatePickerThemeManager {
    constructor() {
        this.darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        this.observers = new Map();
        this.init();
    }

    /**
     * Initialize theme management
     */
    init() {
        // Listen for system theme changes
        this.darkModeMediaQuery.addListener((e) => {
            this.updateAllCalendars();
        });

        // Listen for manual theme changes (class changes on html/body)
        this.observeThemeChanges();

        // Update existing calendars on page load
        document.addEventListener('DOMContentLoaded', () => {
            this.updateAllCalendars();
        });

        // Handle Livewire navigation
        document.addEventListener('livewire:navigated', () => {
            this.updateAllCalendars();
        });
    }

    /**
     * Observe theme class changes on document
     */
    observeThemeChanges() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && 
                    (mutation.attributeName === 'class' || mutation.attributeName === 'data-theme')) {
                    this.updateAllCalendars();
                }
            });
        });

        // Observe document element for class changes
        observer.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['class', 'data-theme']
        });

        // Observe body element for class changes
        if (document.body) {
            observer.observe(document.body, {
                attributes: true,
                attributeFilter: ['class', 'data-theme']
            });
        }
    }

    /**
     * Determine if dark mode is currently active
     */
    isDarkMode() {
        // Check for explicit dark class
        if (document.documentElement.classList.contains('dark') || 
            document.body.classList.contains('dark')) {
            return true;
        }

        // Check for explicit light class (overrides system preference)
        if (document.documentElement.classList.contains('light') || 
            document.body.classList.contains('light')) {
            return false;
        }

        // Fall back to system preference
        return this.darkModeMediaQuery.matches;
    }

    /**
     * Update all DatePicker calendars on the page
     */
    updateAllCalendars() {
        const calendars = document.querySelectorAll('.flatpickr-calendar[data-theme="artisanpack"]');
        calendars.forEach(calendar => {
            this.updateCalendarTheme(calendar);
        });
    }

    /**
     * Update a specific calendar's theme
     */
    updateCalendarTheme(calendar) {
        if (!calendar) return;

        const isDark = this.isDarkMode();
        
        // Update data attribute
        calendar.setAttribute('data-mode', isDark ? 'dark' : 'light');
        
        // Trigger any custom theme update events
        calendar.dispatchEvent(new CustomEvent('theme:updated', {
            detail: { isDark, mode: isDark ? 'dark' : 'light' }
        }));
    }

    /**
     * Apply theme to a new calendar instance
     */
    applyThemeToCalendar(calendarElement, options = {}) {
        if (!calendarElement) return;

        // Set initial theme
        this.updateCalendarTheme(calendarElement);

        // Apply custom colors if provided
        if (options.customColor) {
            calendarElement.style.setProperty('--flatpickr-selected-bg', options.customColor);
        }

        if (options.textColor) {
            calendarElement.style.setProperty('--flatpickr-selected-text', options.textColor);
        }

        // Apply font configurations
        if (options.fontFamily) {
            calendarElement.style.setProperty('--flatpickr-font-family', options.fontFamily);
        }

        if (options.fontSize) {
            calendarElement.style.setProperty('--flatpickr-font-size', options.fontSize);
        }

        if (options.fontWeight) {
            calendarElement.style.setProperty('--flatpickr-font-weight', options.fontWeight);
        }
    }

    /**
     * Enhanced FlatPicker configuration with theme support
     */
    static getEnhancedConfig(baseConfig = {}, themeOptions = {}) {
        const manager = window.datePickerThemeManager;
        
        const enhancedConfig = {
            ...baseConfig,
            
            // Override onReady to apply theming
            onReady: function(selectedDates, dateStr, instance) {
                // Call original onReady if it exists
                if (baseConfig.onReady && typeof baseConfig.onReady === 'function') {
                    baseConfig.onReady.call(this, selectedDates, dateStr, instance);
                }

                // Apply theme
                if (instance.calendarContainer && manager) {
                    manager.applyThemeToCalendar(instance.calendarContainer, themeOptions);
                }
            },

            // Override onOpen to ensure theme is applied
            onOpen: function(selectedDates, dateStr, instance) {
                // Call original onOpen if it exists
                if (baseConfig.onOpen && typeof baseConfig.onOpen === 'function') {
                    baseConfig.onOpen.call(this, selectedDates, dateStr, instance);
                }

                // Ensure theme is current
                if (instance.calendarContainer && manager) {
                    manager.updateCalendarTheme(instance.calendarContainer);
                }
            }
        };

        return enhancedConfig;
    }
}

/**
 * Utility functions for theme integration
 */
const DatePickerThemeUtils = {
    /**
     * Extract theme options from HTML attributes
     */
    extractThemeOptions(element) {
        const options = {};
        
        // Extract data attributes
        const customColor = element.getAttribute('data-custom-color') || 
                           element.style.getPropertyValue('--artisanpack-custom-color');
        const textColor = element.getAttribute('data-text-color') || 
                         element.style.getPropertyValue('--artisanpack-text-color');
        
        if (customColor) options.customColor = customColor;
        if (textColor) options.textColor = textColor;

        // Extract font options
        const fontFamily = element.style.getPropertyValue('--artisanpack-datepicker-font-family');
        const fontSize = element.style.getPropertyValue('--artisanpack-datepicker-font-size');
        const fontWeight = element.style.getPropertyValue('--artisanpack-datepicker-font-weight');

        if (fontFamily) options.fontFamily = fontFamily;
        if (fontSize) options.fontSize = fontSize;
        if (fontWeight) options.fontWeight = fontWeight;

        return options;
    },

    /**
     * Initialize theme management for the page
     */
    init() {
        if (!window.datePickerThemeManager) {
            window.datePickerThemeManager = new DatePickerThemeManager();
        }
        return window.datePickerThemeManager;
    },

    /**
     * Create a themed FlatPicker instance
     */
    createThemedInstance(element, config = {}) {
        const manager = this.init();
        const themeOptions = this.extractThemeOptions(element.closest('[style]') || element);
        const enhancedConfig = DatePickerThemeManager.getEnhancedConfig(config, themeOptions);
        
        return flatpickr(element, enhancedConfig);
    }
};

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    DatePickerThemeUtils.init();
});

// Export for use in modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { DatePickerThemeManager, DatePickerThemeUtils };
} else {
    window.DatePickerThemeManager = DatePickerThemeManager;
    window.DatePickerThemeUtils = DatePickerThemeUtils;
}