# ArtisanPack UI Livewire UI Components

## [1.0.0-beta.3] - 2025-11-23

### Added
- Laravel Boost AI guidelines in `resources/boost/guidelines/core.blade.php` for enhanced AI-assisted development
- New `loading` property for Button component that accepts either text or icon name (auto-detected by prefix)
- Developer tools support with comprehensive documentation

### Changed
- **Button component**: Added support for custom loading states with `loading` prop
  - Automatically detects if value is an icon (o-, s-, fa-, c-, heroicon-, icon- prefixes) or text
  - Replaces default spinner with custom content during loading state
  - Hides regular button content when loading text is provided
- **Documentation**: Completely rewrote table component documentation to match actual implementation
  - Fixed all examples to use correct `:headers` array with object structure
  - Documented `@scope` directives for custom cell/header rendering
  - Added comprehensive examples for sorting, pagination, row selection, expandable rows
  - Updated button, checkbox, input, and form component documentation
- Updated dependency versions:
  - artisanpack-ui/accessibility: 2.0.0 → 2.1.0
  - artisanpack-ui/code-style: 1.0.5 → 1.1.0
  - artisanpack-ui/icons: 2.0.0 → 2.1.0
  - Added artisanpack-ui/hooks: 1.2.0
  - laravel/serializable-closure: v1.3.7 → v2.0.6
  - nette/utils: v4.0.8 → v4.0.9
- Removed deprecated package: tormjens/eventy

### Fixed
- **Security**: Fixed XSS vulnerabilities in icon rendering
  - ToastException: Added icon name validation to prevent XSS attacks
  - Toast trait: Added icon name validation with regex pattern and safe Blade bindings
  - Icons now validated against allowlist pattern before rendering
- **GitLab CI**: Fixed build stage failure with PHP 8.5
  - Changed build stage from `composer:2` image (PHP 8.5) to `php:8.2` for consistency
  - Added PHP zip extension installation to resolve dependency requirements
- **Type safety**: Improved strict type compliance throughout codebase
  - Button component: Changed loose comparison `1 == $spinner` to strict `"1" === $spinner`
  - MenuItem component: Changed loose comparison `1 == $spinner` to strict `"1" === $spinner`
  - Header component: Changed `1 == $progressIndicator` to `true === $progressIndicator`
  - Card component: Changed `1 == $progressIndicator` to `true === $progressIndicator`
- **Type declarations**: Fixed property type declarations for boolean attributes
  - Card component: Changed `progressIndicator` type from `?string` to `string|bool|null`
  - Header component: Changed `progressIndicator` type from `?string` to `string|bool|null`
- **PHPDoc**: Corrected namespace in GenerateThemeCss command
  - Fixed `@param` type from incorrect `\ArtisanPackUI\LivewireUIComponents\Styling\ColorGenerator` to correct `\ArtisanPack\LivewireUiComponents\Styling\ColorGenerator`
- **Code quality**: Removed development markers from production code
  - Checkbox component: Removed inline comment "// [CHANGED] Added value prop"
- **Editor component**: Replaced fragile quote replacement with Laravel's `Js::from()` helper
  - Prevents JavaScript errors when config values contain single quotes
  - Uses proper JSON/JS-safe representation instead of string manipulation

### Infrastructure
- Updated GitLab CI configuration to ensure PHP version consistency across all pipeline stages
- Enhanced code security with comprehensive input validation

## [1.0.0-beta.2] - 2025-11-15

### Changed
- Updated artisanpack-ui/accessibility to ^2.0.0 for Laravel 12 compatibility
- Updated artisanpack-ui/icons to ^2.0 for Laravel 12 compatibility
- Updated phpstan/phpstan to ^2.0
- Updated larastan/larastan to ^3.0 for Laravel 12 support
- Updated rector/rector to ^2.0 for PHPStan 2.0 compatibility

### Infrastructure
- Added support for Laravel 12
- Updated development tooling dependencies for improved code analysis

## [1.0.0-beta.1] - 2025-11-09

### Added
- New profile component for user profile displays
- New fieldset component for grouping form fields
- Comprehensive testing framework using Pest PHP
- Code style checking with PHP_CodeSniffer
- Support for artisanpack-ui/core package for improved configuration handling
- AI guidelines for development
- Component name standardization across the package

### Changed
- **Button component**: Complete overhaul with improved functionality and consistency
- **Toast component**: Enhanced accessibility support
- **Drawer component**: Updated to support multiple interaction modes
- **Tabs component**: Added extensive customization options
- **Menu item component**: Improved icon/spinner spacing logic and added menu-item class

### Fixed
- Fatal error in the toast trait
- Accessibility issues in dropdown component
- Accessibility issues in profile component
- Accessibility issues in sub menu component
- Rendering issues with checkbox component
- Rendering issues with radio component
- Rendering issues with toggle component
- Issues with input component
- Issues with select component
- Issues with textarea component

### Infrastructure
- Updated .gitlab-ci.yml configuration
- Removed unnecessary development files from repository
- Updated .gitattributes to exclude development files from releases
- Updated .gitignore to prevent committing build artifacts
- Enhanced documentation

## [0.6.0] - 2025-08-18
- Updated rating component functionality and accessibility.
- Added image slider and image gallery components.
- Updated carousel component with customizable icons and accessibility improvements.
- Added support for vertical tabs.
- Added customization options for stat component.
- Added search functionality for tags component.
- Added pagination component options.
- Added drag-and-drop upload functionality for file and image library components.
- Added custom icon support for ListItem and Loading components.
- Added color and theme support for datepicker component.
- Added color variants for avatar, alert, badge, button and toast components.
- Added button component variants.
- Repository cleanup and documentation improvements.

## [0.5.0] - 2025-08-10
- Added random color generator functionality.
- Added icon options for accordion and collapse components.
- Added card variant support for checkbox and radio components.
- Added card figure positioning options.
- Updated HR separator component.
- Updated menu documentation.
- Fixed calendar component issues.
- Fixed Mary references throughout the codebase.

## [0.4.4] - 2025-08-03
- Fixed issue with the editor component.

## [0.4.3] - 2025-07-29
- Fixed issue with the rich text editor component.
- Added in ability to customize the background for hover and active states for menu items.

## [0.4.2] - 2025-07-28
- Added in some various fixes to the package.

## [0.4.1] - 2025-07-28
- Added a fix for the custom event modal for the calendar.

## [0.4.0] - 2025-07-27
- Updated the calendar component to be able to show events and event modals.

## [0.3.3] - 2025-07-25
- Fixed issues with tables and Livewire components.

## [0.3.2] - 2025-07-22
- Fixed styling for the sidebar on the main component.

## [0.3.1] - 2025-07-21

- Fixed a problem with the theme generator CSS overwriting dark mode CSS variables.
- Fixed a problem with color stops not being generated for primary, secondary and accent colors.

## [0.3.0] - 2025-07-20
- Added fixes for rendering the components.

## [0.2.0] - 2025-07-20
- Added in typography components for heading, subheading, text and link.

## [0.1.0] - 2025-07-17

- Initial test release of the ArtisanPack UI Livewire UI Components package.
