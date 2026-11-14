<?php

declare(strict_types=1);

use ArtisanPackUI\DesignSystem\Testing\BladeFixtureHost;

/*
 * The Blade fixture host (#39, D-016): renders every manifest fixture case
 * with the registered components into build/gates/html/livewire, which
 * apui-gates.config.json points stacks.livewire.html at. Run it before
 * `apui-gates check`: vendor/bin/pest --group=gates
 */

it('renders the design-system fixtures', function (): void {
    $components = ['Button', 'Input', 'Select', 'Checkbox', 'Card', 'Dialog', 'DropdownMenu', 'Toast', 'Textarea', 'Password', 'Pin', 'Range', 'Toggle', 'Fieldset', 'Form', 'ValidationSummary', 'Link', 'Breadcrumbs', 'Pagination', 'Steps', 'Combobox', 'RadioGroup', 'CheckboxGroup', 'Rating', 'ColorPicker', 'File', 'DatePicker', 'Spotlight', 'Tabs', 'Menu', 'Navbar', 'Signature', 'RichTextEditor', 'CodeEditor', 'Table', 'Drawer', 'Popover', 'Tooltip', 'Stat', 'KpiCard', 'Sparkline', 'Timeline', 'Diff', 'Accordion', 'Collapse', 'AppShell', 'PageHeader', 'Divider', 'Grid', 'Stack', 'Alert', 'Loading', 'Progress', 'ProgressRadial', 'StreamableContent', 'Skeleton', 'EmptyState', 'ErrorState', 'Heading', 'Text', 'ListItem', 'UserMenu', 'Avatar', 'Badge', 'ImageGallery', 'ImageSlider', 'ImageLibrary', 'Chart', 'Calendar', 'Icon', 'ThemeToggle', 'ThemePreview', 'Markdown', 'Code', 'Kbd', 'Swap', 'Carousel', 'Clipboard'];
    $written    = (new BladeFixtureHost)->write($components, dirname(__DIR__, 2).'/build/gates/html/livewire');

    expect($written)->not->toBeEmpty();

    foreach ($components as $component) {
        expect(array_filter($written, static fn (string $file): bool => str_contains($file, '/'.$component.'/')))->not->toBeEmpty();
    }
})->group('gates');
