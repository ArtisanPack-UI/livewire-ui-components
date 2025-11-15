# ArtisanPack UI Livewire UI Components

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
