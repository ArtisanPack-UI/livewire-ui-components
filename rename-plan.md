# Livewire UI Components Renaming Plan

This document outlines the plan for renaming the MaryUI package to Livewire UI Components.

## Overview

The MaryUI package has been forked and needs to be renamed to "Livewire UI Components" to align with the new branding under ArtisanPack UI. This involves changing file names, namespaces, class names, and references throughout the codebase.

## Files to Modify

### 1. Configuration Files

- **config/mary.php** → **config/livewire-ui-components.php**
  - Update all internal references to "mary" and "maryUI"
  - Update route prefixes from "/mary/" to "/livewire-ui-components/"
  - Update component prefix references from "mary-" to "artisanpack"

### 2. Source Files

- **src/Mary.php** → **src/LivewireUiComponents.php**
  - Update namespace from `Mary` to `ArtisanPackUi\LivewireUiComponents`
  - Update class name from `Mary` to `LivewireUiComponents`

- **src/MaryServiceProvider.php** → **src/LivewireUiComponentsServiceProvider.php**
  - Update namespace from `Mary` to `ArtisanPackUi\LivewireUiComponents`
  - Update class name from `MaryServiceProvider` to `LivewireUiComponentsServiceProvider`
  - Update all imports from `Mary\*` to `ArtisanPackUi\LivewireUiComponents\*`
  - Update all references to config('mary.*') to config('livewire-ui-components.*')
  - Update all references to "mary-" component prefixes to "artisanpack"
  - Update service registration from 'mary' to 'livewire-ui-components'
  - Update config file references from 'mary.php' to 'livewire-ui-components.php'
  - Update publishing tags from 'mary.config' to 'livewire-ui-components.config'

### 3. Command Files

- **src/Console/Commands/MaryBootcampCommand.php** → **src/Console/Commands/LivewireUiComponentsBootcampCommand.php**
  - Update namespace
  - Update class name
  - Update any internal references to "Mary" or "MaryUI"

- **src/Console/Commands/MaryInstallCommand.php** → **src/Console/Commands/LivewireUiComponentsInstallCommand.php**
  - Update namespace
  - Update class name
  - Update any internal references to "Mary" or "MaryUI"

### 4. Composer Configuration

- **composer.json**
  - Update autoload PSR-4 namespace from `"Mary\\": "src/"` to `"ArtisanPackUi\\LivewireUiComponents\\": "src/"`
  - Update autoload-dev PSR-4 namespace from `"Mary\\Tests\\": "tests"` to `"ArtisanPackUi\\LivewireUiComponents\\Tests\\": "tests"`
  - Update service provider reference from `"Mary\\MaryServiceProvider"` to `"ArtisanPackUi\\LivewireUiComponents\\LivewireUiComponentsServiceProvider"`
  - Update facade alias from `"Mary": "Mary\\Facades\\Mary"` to `"LivewireUiComponents": "ArtisanPackUi\\LivewireUiComponents\\Facades\\LivewireUiComponents"`

### 5. Documentation

Note: The README.md and license.md files will not be updated as part of this renaming process.

## Potential Issues and Considerations

1. **Backward Compatibility**: This is a significant change that will break backward compatibility. Users will need to update their code to use the new namespace and component names.

2. **Component Prefixes**: Consider whether to keep the same component prefix structure or adopt a new one (e.g., "lwui-" instead of "mary-").

3. **Documentation**: Ensure all documentation is updated to reflect the new package name and usage.

4. **Tests**: Update all test files to use the new namespace and class names.

5. **View Files**: Check if there are any view files that might contain references to "Mary" or "MaryUI".

6. **Facades**: Update any facade classes to reflect the new package name.

## Implementation Steps

1. Create a backup of the current codebase.

2. Rename and update configuration files:
   - Rename config/mary.php to config/livewire-ui-components.php
   - Update content of the configuration file

3. Update composer.json with new namespaces and service provider references.

4. Rename and update source files:
   - Rename src/Mary.php to src/LivewireUiComponents.php
   - Rename src/MaryServiceProvider.php to src/LivewireUiComponentsServiceProvider.php
   - Update content of these files

5. Rename and update command files:
   - Rename command files in src/Console/Commands/
   - Update content of these files

6. Note: Documentation files (README.md and license.md) will not be updated as part of this renaming process.

7. Run tests to ensure everything works correctly.

8. Update version number to reflect the significant change.

9. Create a migration guide for users to help them update their code.

## Conclusion

This renaming plan provides a comprehensive approach to changing the package name from MaryUI to Livewire UI Components. Following these steps will ensure a smooth transition while maintaining the functionality of the package.
