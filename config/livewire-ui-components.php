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
    | Glass Design Token Settings
    |--------------------------------------------------------------------------
    |
    | These settings control the glassmorphism design system tokens.
    | You can customize the default values for glass effects here.
    |
    | @since 2.0.0
    */
	'glass' => [
		// Whether to include glass tokens in the generated theme CSS
		'enabled' => true,

		// Output path for standalone glass tokens CSS file
		'output_path' => resource_path('css/artisanpack-glass-tokens.css'),

		// Base glass token overrides (leave null to use defaults)
		'tokens' => [
			// Base glass tokens
			'blur'           => null, // Default: 12px
			'opacity'        => null, // Default: 0.7
			'border-width'   => null, // Default: 1px
			'border-opacity' => null, // Default: 0.2
			'shadow-opacity' => null, // Default: 0.1

			// Tint tokens
			'tint-color'   => null, // Default: transparent
			'tint-opacity' => null, // Default: 0.15

			// Frosted variant
			'frosted-blur'       => null, // Default: 16px
			'frosted-opacity'    => null, // Default: 0.8
			'frosted-saturation' => null, // Default: 180%

			// Liquid variant
			'liquid-blur'        => null, // Default: 24px
			'liquid-opacity'     => null, // Default: 0.6
			'liquid-refraction'  => null, // Default: 0.5
			'liquid-border-glow' => null, // Default: 0.3

			// Transparent variant
			'transparent-blur'    => null, // Default: 8px
			'transparent-opacity' => null, // Default: 0.3
		],
	],
];
