<?php

return [
    /**
     * Default component prefix.
     *
     * Make sure to clear view cache after renaming with `php artisan view:clear`
     *
     *    prefix => ''
     *              <x-button />
     *              <x-card />
     *
     *    prefix => 'artisanpack'
     *               <x-artisanpack-button />
     *               <x-artisanpack-card />
     *
     */
    'prefix' => 'artisanpack',

    /**
     * Default route prefix.
     *
     * Some Livewire UI Components make network request to its internal routes.
     *
     *      route_prefix => ''
     *          - Spotlight: '/livewire-ui-components/spotlight'
     *          - Editor: '/livewire-ui-components/upload'
     *          - ...
     *
     *      route_prefix => 'my-components'
     *          - Spotlight: '/my-components/livewire-ui-components/spotlight'
     *          - Editor: '/my-components/livewire-ui-components/upload'
     *          - ...
     */
    'route_prefix' => '',

    /**
     * Components settings
     */
    'components' => [
        'spotlight' => [
            'class' => 'App\Support\Spotlight',
        ]
    ],
    /**
     * Icons configuration for components
     */
    'icons' => [
        'list_item' => [
            // Default list style icons
            'bullet' => 'o-minus',
            'checkmark' => 'o-check',
            'arrow' => 'o-chevron-right',
            'dot' => 'o-ellipsis-horizontal',
            
            // Status icons
            'status' => [
                'new' => 'o-sparkles',
                'completed' => 'o-check-circle',
                'error' => 'o-exclamation-triangle',
                'warning' => 'o-exclamation-circle',
                'info' => 'o-information-circle',
            ],
            
            // Type icons
            'type' => [
                'user' => 'o-user',
                'file' => 'o-document',
                'folder' => 'o-folder',
                'email' => 'o-envelope',
                'notification' => 'o-bell',
            ]
        ],
        
        'loading' => [
            // Custom loading icons (SVG-based)
            'spinner' => 'o-arrow-path',
            'dots' => null, // Keep CSS-based
            'ring' => null, // Keep CSS-based
            'custom_svg' => null, // Allow custom SVG content
            
            // Default loading type
            'default_type' => 'css', // 'css' or 'svg'
        ]
    ],

	/*
    |--------------------------------------------------------------------------
    | Theme Generation Settings
    |--------------------------------------------------------------------------
    |
    | This value specifies the default output path for the generated CSS theme
    | file within the user's Laravel application. Developers can publish this
    | file and modify the path to suit their project structure.
    |
    */
	'theme_output_path' => resource_path('css/artisanpack-ui-theme.css'),

    /*
    |--------------------------------------------------------------------------
    | Accessibility Settings
    |--------------------------------------------------------------------------
    |
    | Configure accessibility features to ensure WCAG 2.1 AA compliance.
    | These settings control various accessibility enhancements across all
    | components including keyboard navigation, screen reader support, and
    | visual accessibility features.
    |
    */
    'accessibility' => [
        /**
         * Enable accessibility features globally
         * Set to false to disable all accessibility enhancements
         */
        'enabled' => env('ACCESSIBILITY_ENABLED', true),

        /**
         * High contrast mode support
         * When enabled, components adapt to system high contrast preferences
         */
        'high_contrast' => env('ACCESSIBILITY_HIGH_CONTRAST', true),

        /**
         * Reduced motion support
         * When enabled, respects prefers-reduced-motion user preferences
         */
        'reduced_motion' => env('ACCESSIBILITY_REDUCED_MOTION', true),

        /**
         * Minimum color contrast ratio (WCAG AA = 4.5:1 for normal text)
         * Used for automated contrast validation
         */
        'min_contrast_ratio' => 4.5,

        /**
         * Minimum color contrast ratio for large text (WCAG AA = 3:1)
         * Large text is defined as 18pt+ or 14pt+ bold
         */
        'min_contrast_ratio_large' => 3.0,

        /**
         * Focus indicator settings
         * Customize the appearance of focus indicators for keyboard navigation
         */
        'focus_indicator' => [
            'width' => '2px',
            'color' => 'var(--primary)',
            'offset' => '2px',
            'style' => 'solid',
        ],

        /**
         * Screen reader preferences
         * Configure screen reader behavior and announcements
         */
        'screen_reader' => [
            'announce_changes' => true,
            'live_regions' => true,
            'announce_navigation' => true,
        ],

        /**
         * Keyboard navigation settings
         * Configure keyboard navigation behavior
         */
        'keyboard' => [
            'enabled' => true,
            'show_hints' => env('ACCESSIBILITY_KEYBOARD_HINTS', true),
            'allow_shortcuts' => true,
        ],

        /**
         * Focus management settings
         * Configure focus behavior for modals, dialogs, and other components
         */
        'focus_management' => [
            'trap_in_modals' => true,
            'restore_on_close' => true,
            'auto_focus_first' => true,
            'skip_links' => true,
        ],

        /**
         * ARIA attributes
         * Configure default ARIA attribute behavior
         */
        'aria' => [
            'auto_generate_ids' => true,
            'verbose_labels' => false,
            'announce_errors' => true,
        ],

        /**
         * Testing and validation
         * Configure accessibility testing features
         */
        'testing' => [
            'validate_contrast' => env('ACCESSIBILITY_VALIDATE_CONTRAST', false),
            'strict_mode' => env('ACCESSIBILITY_STRICT_MODE', false),
            'log_violations' => env('ACCESSIBILITY_LOG_VIOLATIONS', false),
        ],
    ],
];
