# ArtisanPack UI Livewire UI Components

## [2.0.1] - 2026-01-28

### Changed
- Updated Livewire dependency constraint from `^3.6` to `^3.6|^4.0` to support Livewire 4.0

---

## [2.0.0] - 2026-01-23

### Added

#### Dashboard Components
- **KpiCard**: New component for displaying KPIs with sparklines, trend indicators, and glass effects
  - Supports sparkline visualization (area, line, bar charts)
  - Automatic trend indicator coloring (green for positive, red for negative)
  - Glass effect support with customizable tints
  - Footer slot for additional content
- **WidgetGrid**: Responsive grid helper for dashboard layouts
  - Configurable column count (1-6 columns)
  - Automatic responsive breakpoints
  - Configurable gap spacing
- **Sparkline**: Inline sparkline chart component
  - Supports area, line, and bar chart types
  - Customizable colors

#### Table Data Export
- **TableExporter**: New utility class for exporting table data
  - CSV export (client-side)
  - XLSX export via PhpSpreadsheet (server-side)
  - PDF export via DomPDF (server-side)
  - Automatic header normalization for Table component format
  - Hidden column filtering
- **WithTableExport**: New trait for Livewire components
  - Handles `table-export-request` events
  - Provides `exportTableToCsv()` and `exportTableToXlsx()` methods
  - Abstract `getTableExportData()` method for customization
- **Table component**: New export props
  - `exportable` - Enable export functionality
  - `export-formats` - Configure available formats (csv, xlsx, pdf)
  - `export-filename` - Custom filename for exports

#### Streaming Content (Livewire 4+)
- **StreamableContent**: New component for displaying real-time streaming content
  - Supports Livewire 4's `wire:stream` directive
  - Blinking cursor animation option
  - Placeholder text support
  - Prose styling for rich text
  - Graceful degradation on Livewire 3

#### Glass Effects
- **GlassHelper**: New utility class for frosted glass UI effects
  - Three glass variants: frost, blur, subtle
  - Customizable tint colors with accessible text colors
  - WCAG 2.0 AA compliant contrast handling
- **GlassPresets**: Six pre-built glass theme configurations
  - `glass-frosted-light` - Professional frosted glass for light backgrounds
  - `glass-frosted-dark` - Sophisticated frosted glass for dark backgrounds
  - `glass-liquid-light` - Premium liquid glass effect for light backgrounds
  - `glass-liquid-dark` - Dramatic liquid glass with enhanced glow
  - `glass-minimal` - Subtle, accessibility-friendly glass effect (adaptive)
  - `glass-bold` - Strong, prominent glass for CTAs and heroes (adaptive)
  - CSS class-based application system
  - Programmatic API for generating custom presets

#### Accessibility & High Contrast Themes
- **HighContrastTheme**: WCAG AAA compliant high-contrast theme system
  - `high-contrast-light` - Maximum contrast on light backgrounds (WCAG AAA)
  - `high-contrast-dark` - Maximum contrast on dark backgrounds (WCAG AAA)
  - `enhanced-contrast-light` - Improved contrast on light (WCAG AA)
  - `enhanced-contrast-dark` - Improved contrast on dark (WCAG AA)
  - Automatic `prefers-contrast` and `prefers-reduced-motion` media query support
  - Enhanced focus indicators for keyboard navigation
  - Text scaling classes (`.text-larger`, `.text-extra-large`)
  - Skip link styling for accessibility

#### Theme Preview Tool
- **ThemePreview**: Browser-based interactive theme customization tool
  - Real-time color adjustment with Tailwind colors or custom hex
  - Component preview gallery organized by category
  - Glass effect customization with presets and tint options
  - Accessibility preset selection
  - Dark mode toggle
  - Export to CSS or JSON
  - Shareable preview URLs with all settings encoded
  - Generated artisan command output
  - Available at `/artisanpack/theme-preview` route

#### Livewire 4 Features
- **Table component**: Native drag-and-drop sorting via `wire:sort`
  - `sortable` prop with sort handles
  - Cross-list dragging via `sort-group`
- **Table component**: Infinite scroll via `wire:intersect`
  - `infinite-scroll` prop with customizable loading text
  - Modifier support (once, half, full)
- **LivewireHelper**: Version detection utility for feature compatibility

### Changed
- Updated component count from 60+ to 70+ in documentation
- Table component now supports associative array rows with automatic normalization
- Updated home.md and components.md with v2.0 features
- Added Dashboard Components category to documentation
- **GenerateThemeCss command**: Enhanced with new options
  - `--preset` option for applying glass presets
  - `--presets-only` to generate only glass preset CSS
  - `--high-contrast-only` to generate only accessibility CSS
  - `--accessibility-preset` to apply specific accessibility preset
  - `--no-high-contrast` to skip high contrast CSS generation
  - `--json` to export theme configuration as JSON
  - `--interactive` mode for guided theme creation

### Dependencies
- Added `phpoffice/phpspreadsheet` as suggested dependency for XLSX export
- Added `barryvdh/laravel-dompdf` as suggested dependency for PDF export

### Notes
- Livewire 4-specific features (wire:stream, wire:sort, wire:intersect) are gracefully ignored on Livewire 3
- XLSX and PDF exports require optional dependencies to be installed
- Theme preview tool available at `/artisanpack/theme-preview` (named route: `artisanpack.theme-preview`)
- Glass presets use CSS custom properties for easy theming
- High contrast themes respect system `prefers-contrast` and `prefers-reduced-motion` media queries
- All v2.0 features are additive with no breaking changes from v1.x

---

## [1.0.2] - 2026-01-10

### Fixed
- **Button component**: Fixed loading state content (spinners, loading text/icons) showing in default state instead of only during loading
  - Changed from `wire:loading` to CSS-safe approach using `class="hidden"` with `wire:loading.class.remove="hidden"`
  - Ensures loading indicators are hidden by default via CSS, preventing flash of unstyled content
  - Loading content is now properly hidden on initial page render regardless of JavaScript initialization timing

### Changed
- **Button documentation**: Updated loading states section with recommended usage patterns and explanation of CSS-safe implementation

---

## [1.0.1] - 2026-01-04

### Fixed
- **Demo views**: Fixed image-gallery and image-slider demo views to use correct `<x-artisanpack-*>` component prefixes instead of incorrect `<x-*>` syntax
- **ColorGenerator**: Removed unused `$a11y` variable and `ArtisanPackUI\Accessibility\Facades\A11y` import in `generateThemeCss()` method

---

## [1.0.0] - 2026-01-02

### First Stable Release

This is the first stable release of ArtisanPack UI Livewire UI Components, a comprehensive UI component library for the TALL stack (Tailwind CSS, Alpine.js, Laravel, and Livewire).

### Highlights

- **77 Production-Ready Components**: Complete library spanning form inputs, layout elements, navigation, data display, feedback, and utility components
- **Full Test Coverage**: 1,567+ tests with 6,490 assertions ensuring reliability
- **Laravel 10, 11, and 12 Support**: Future-proof compatibility with current and upcoming Laravel versions
- **Livewire 3.6+ Compatible**: Built for the latest Livewire features
- **Tailwind CSS 4 and daisyUI**: Modern styling with full dark mode support
- **Accessibility Focused**: WCAG 2.1 compliant components
- **Comprehensive Documentation**: Detailed guides and examples for every component

### Component Categories

- **Form Components (25)**: Button, Input, Select, Checkbox, DatePicker, Editor, File upload, and more
- **Layout Components (11)**: Card, Modal, Tabs, Accordion, Drawer, Dropdown
- **Navigation Components (9)**: Menu, Breadcrumbs, Pagination, Spotlight Search
- **Data Display Components (22)**: Table, Chart, Calendar, Avatar, Badge, Progress
- **Feedback Components (4)**: Alert, Toast, Loading, Errors
- **Utility Components (6)**: Carousel, Icon, ThemeToggle, Swap

### Migration from Beta

If upgrading from a beta version, no breaking changes are expected. The component API remains stable.

---

## [1.0.0-beta.4] - 2025-12-16

### Added
- CodeRabbit AI code review configuration file for automated code quality checks
- New enhancement issue template for improvement requests
- Comprehensive default merge request template with standardized sections

### Changed
- **CONTRIBUTING.md**: Completely overhauled contributing guidelines with extensive documentation
  - Added detailed development workflow and Git branching strategy
  - Expanded code style guidelines and best practices
  - Included comprehensive testing requirements and documentation standards
  - Added merge request and issue management guidelines
- **Issue templates**: Updated and streamlined issue templates
  - Enhanced bug report template with better structure
  - Improved feature request template with clearer sections
  - Updated task template with standardized format
- **Merge request templates**: Restructured merge request templates
  - Consolidated templates into default and release templates
  - Enhanced release merge request template with comprehensive checklist
  - Removed redundant bug, feature, and task-specific templates
- **Drawer component**: Code improvements and optimizations

### Fixed
- **Menu item component**: Fixed padding issues for better visual consistency (menu-item.blade.php:line 2)
- **Input component**: Resolved rendering and functionality issues
  - Fixed input field rendering (input.blade.php, Input.php)
  - Fixed checkbox component rendering (checkbox.blade.php, Checkbox.php)
  - Fixed toggle component rendering (toggle.blade.php, Toggle.php)

### Infrastructure
- Enhanced project documentation and contribution guidelines
- Improved automated code review workflow with CodeRabbit integration
- Streamlined GitLab issue and merge request templates

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
