---
title: Migration Guides
---

# Migration Guides

This section contains migration guides to help you upgrade between different versions of ArtisanPack UI Livewire Components or when component APIs change significantly.

## Overview

Migration guides provide step-by-step instructions for updating your code when breaking changes are introduced. Each guide includes:

- **What changed**: Clear explanation of the changes and why they were made
- **Migration scenarios**: Different use cases and how to handle each one
- **Code examples**: Before and after code snippets showing the exact changes needed
- **Troubleshooting**: Common issues and their solutions

## Available Migration Guides

### Version Upgrades

- [**Upgrading from v1.x to v2.0**](migration/v1-to-v2) - Guide for upgrading to v2.0, including new dashboard components, table export, streaming content, and theme preview tool (no breaking changes)

### Livewire 4 Features

- [**Livewire 4 Features (v2.0)**](migration/livewire-4-features) - Guide for using new Livewire 4-specific features including `wire:intersect`, `wire:sort`, and `data-loading` CSS variants

### Component Migrations

- [**Chart Component Migration (v2.0)**](migration/chart) - Guide for migrating from the Chart.js-based Chart component to the new ApexCharts implementation
- [**ImageGallery Component Migration**](migration/image-gallery) - Guide for migrating from the old carousel-based ImageGallery component to the new ImageSlider and grid-based ImageGallery components

## Migration Best Practices

When migrating your components, we recommend:

1. **Read the entire guide** before starting the migration
2. **Test in a development environment** first
3. **Update one component at a time** to isolate any issues
4. **Review breaking changes** in the changelog
5. **Check for deprecated features** that may need updating

## Getting Help

If you encounter issues during migration:

- Check the troubleshooting section in the relevant migration guide
- Review the [GitLab Issues](https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components/-/issues) for similar problems
- Create a new issue with details about your migration problem
- Contact support at [me@jacobmartella.com](mailto:me@jacobmartella.com)

## Contributing

Found an issue with a migration guide or have suggestions for improvement? We welcome contributions to help make migrations smoother for everyone. Please open an issue or submit a merge request on GitLab.
