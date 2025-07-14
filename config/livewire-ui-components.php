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
     *    prefix => 'artisanpack-'
     *               <x-artisanpack-button />
     *               <x-artisanpack-card />
     *
     */
    'prefix' => '',

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