# ArtisanPack UI Livewire UI Components

## [0.6.0] - 2025-08-16
- **NEW FEATURE**: Added vertical tabs support to the Tabs component
  - Added `orientation` property with support for `horizontal`, `vertical-left`, and `vertical-right` orientations
  - Implemented responsive design that adapts to mobile devices (horizontal on mobile, vertical on desktop)
  - Added comprehensive vertical-specific CSS class properties for full customization
  - Added proper ARIA attributes for accessibility (`aria-orientation="vertical"`)
  - Maintained 100% backward compatibility - all existing implementations continue to work unchanged
  - Added helper methods: `isVertical()`, `isVerticalRight()`, `getTabsContainerClass()`, `getLabelDivClass()`, `getLabelClass()`, `getActiveClass()`
  - Enhanced Blade template with conditional layout logic for proper DOM ordering
  - Comprehensive documentation and examples added for all vertical tab variants
  - Full test coverage with unit and feature tests
  - Edge case handling for disabled, hidden, and invalid orientation values

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
