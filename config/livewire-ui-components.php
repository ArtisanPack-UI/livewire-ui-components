<?php

return [
    /**
     * Default component prefix.
     *
     * Make sure to clear view cache after renaming with `php artisan view:clear`
     *
     *    prefix => 'artisanpack'
     *               <x-artisanpack-button />
     *               <livewire:artisanpack-calendar />
     *
     *    prefix => 'ui'
     *               <x-ui-button />
     *
     *    prefix => ''
     *               <x-button />
     *
     * Every component also stays registered as `artisanpack-{name}`, which the
     * package's own views use to render nested components, so any prefix works.
     */
    'prefix' => 'artisanpack',

    /**
     * Default route prefix.
     *
     * Some Livewire UI Components make network request to its internal routes.
     *
     *      route_prefix => ''
     *          - Spotlight: '/artisanpack/spotlight'
     *          - RichTextEditor uploads: '/artisanpack/upload'
     *          - AppShell sidebar state: '/artisanpack/toogle-sidebar'
     *
     *      route_prefix => 'my-components'
     *          - Spotlight: '/my-components/artisanpack/spotlight'
     *          - RichTextEditor uploads: '/my-components/artisanpack/upload'
     *          - ...
     */
    'route_prefix' => '',

    /**
     * Image uploads from the RichTextEditor component
     * (the `artisanpack.upload` route).
     *
     *      disks    - Disks a component may store on. The first is the default.
     *      mimes    - Allowed image extensions. SVG is never allowed: it can carry script.
     *      max_size - The largest upload, in kilobytes.
     */
    'uploads' => [
        'disks' => ['public'],
        'mimes' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'],
        'max_size' => 5120,
    ],

    /**
     * Components settings
     *
     *      spotlight.class - The class whose search() the Spotlight route calls.
     */
    'components' => [
        'spotlight' => [
            'class' => 'App\Support\Spotlight',
        ],
    ],

	/*
    |--------------------------------------------------------------------------
    | Registered Icons
    |--------------------------------------------------------------------------
    |
    | Extra names for the design-system icon registry (D-017), on top of the
    | default Lucide set. Each value is the inner markup of a 24x24 SVG drawn
    | with stroke="currentColor"; the registry wraps it in the shared <svg>.
    | Bodies render unescaped, so use only trusted, static SVG shapes: bodies
    | with script, style, foreignObject, use, image, a, iframe/object/embed,
    | on*= handlers or javascript:/vbscript:/data: URLs throw an
    | InvalidArgumentException when the registry reads them.
    | A name here replaces a default icon of the same name. Names the registry
    | doesn't know fall back to blade-icons (o-plus, fas.user, ...).
    |
    |    'registered_icons' => [
    |        'rocket' => '<path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2" />',
    |    ],
    |
    | @since 3.0.0
    */
	'registered_icons' => [],

	/*
    |--------------------------------------------------------------------------
    | Theme Generation Settings (deprecated)
    |--------------------------------------------------------------------------
    |
    | Deprecated in 3.0: `theme_output_path`, `glass` and `design_tokens` only
    | configure the deprecated `artisanpack:generate-theme` command, whose 2.x
    | output nothing in 3.0 reads. Use `artisanpack:generate-preset` instead.
    | These keys will be removed with that command in a later major release.
    |
    | The default output path for the generated CSS theme file.
    |
    */
	'theme_output_path' => resource_path('css/artisanpack-ui-theme.css'),

	/*
    |--------------------------------------------------------------------------
    | Glass Design Token Settings (deprecated)
    |--------------------------------------------------------------------------
    |
    | Deprecated in 3.0: read only by `artisanpack:generate-theme`.
    |
    | These settings control the 2.x glassmorphism tokens.
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

		/*
        |--------------------------------------------------------------------------
        | Glass Theme Presets
        |--------------------------------------------------------------------------
        |
        | Pre-built glass theme configurations for common use cases.
        | These presets can be applied globally or per-component.
        |
        | Available presets:
        | - glass-frosted-light: Light mode frosted glass theme
        | - glass-frosted-dark: Dark mode frosted glass theme
        | - glass-liquid-light: Light mode liquid glass theme
        | - glass-liquid-dark: Dark mode liquid glass theme
        | - glass-minimal: Subtle glass effects for understated designs
        | - glass-bold: Strong glass effects for prominent UI elements
        |
        | @since 2.0.0
        */
		'presets' => [
			// Whether to include glass presets in the generated theme CSS
			'enabled' => true,

			// Output path for standalone glass presets CSS file
			'output_path' => resource_path( 'css/artisanpack-glass-presets.css' ),

			// Default preset to apply globally (null for no default)
			// Can be overridden per-component or per-container
			'default' => null,

			// List of presets to include (null or empty array includes all)
			// Example: ['glass-frosted-light', 'glass-minimal', 'glass-bold']
			'include' => null,
		],
	],

	/*
    |--------------------------------------------------------------------------
    | Design Tokens Settings (deprecated)
    |--------------------------------------------------------------------------
    |
    | Deprecated in 3.0: read only by `artisanpack:generate-theme`. The 3.0
    | design tokens come from the design system; see `artisanpack:generate-preset`.
    |
    | These settings control the comprehensive design token system including
    | typography, spacing, border radius, shadows, and animations.
    |
    | @since 2.0.0
    */
	'design_tokens' => [
		// Whether to include design tokens in the generated theme CSS
		'enabled' => true,

		// Output path for standalone design tokens CSS file
		'output_path' => resource_path( 'css/artisanpack-design-tokens.css' ),

		// Typography token overrides (leave null to use defaults)
		'typography' => [
			// 'font-family-sans' => null,  // Default: ui-sans-serif, system-ui, ...
			// 'font-family-serif' => null, // Default: ui-serif, Georgia, ...
			// 'font-family-mono' => null,  // Default: ui-monospace, SFMono-Regular, ...
			// 'font-size-base' => null,    // Default: 1rem
		],

		// Spacing token overrides (leave null to use defaults)
		'spacing' => [
			// 'spacing-4' => null, // Default: 1rem
		],

		// Border radius token overrides (leave null to use defaults)
		'radius' => [
			// 'radius-lg' => null, // Default: 0.5rem
		],

		// Shadow token overrides (leave null to use defaults)
		'shadow' => [
			// 'shadow-md' => null, // Default: 0 4px 6px -1px rgb(0 0 0 / 0.1), ...
		],

		// Animation token overrides (leave null to use defaults)
		'animation' => [
			// 'duration-200' => null, // Default: 200ms
			// 'ease-in-out' => null,  // Default: cubic-bezier(0.4, 0, 0.2, 1)
		],
	],

	/*
    |--------------------------------------------------------------------------
    | Prebuilt Design System Assets
    |--------------------------------------------------------------------------
    |
    | For apps without a bundler. Publish the assets with
    | `php artisan vendor:publish --tag=artisanpack-design-system-assets`
    | and put `@artisanpackStyles` and `@artisanpackScripts` in your layout's
    | <head>, before Livewire's script.
    |
    | - stylesheet: 'default' (component CSS included) or 'headless' (reset,
    |   tokens and utilities only). `@artisanpackStyles('headless')` overrides
    |   it per layout.
    | - path: where the assets are published, relative to the public directory.
    |
    | Apps with a bundler import `@artisanpack-ui/css` and
    | `@artisanpack-ui/behavior/alpine` from npm instead.
    |
    | @since 3.0.0
    */
	'assets' => [
		'stylesheet' => 'default',
		'path'       => 'vendor/artisanpack-ui/design-system',
	],
];
