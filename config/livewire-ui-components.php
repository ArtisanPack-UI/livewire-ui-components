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
];
