<?php

declare(strict_types=1);

use ArtisanPackUI\DesignSystem\Testing\ManifestParity;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * API parity with the design-system manifests (#41, D-016): every component
 * that ships in Livewire and is past `planned` renders its root, data
 * attributes, state evidence and parts, and its constructor declares exactly
 * the manifest's props.
 */

beforeEach(function (): void {
    // errorField reads $errors, so give the parity sample key a message.
    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag([
        'apui-parity-sample' => 'The sample field is invalid.',
    ])));
});

/*
 * Per-component exemptions for props whose effect a generic sample can't
 * show. Pagination's rows needs a real paginator, and Steps' clickable a
 * wire:model property to set: PaginationComponentTest and StepsComponentTest
 * cover them. navigate (on by default) only marks internal links, and the
 * Link and Breadcrumbs fixtures link to `#` (ListItem's has no href):
 * LinkComponentTest, BreadcrumbsComponentTest, ListItemComponentTest and
 * SafeUrlTest cover it. UserMenu mounts the DropdownMenu widget but stops its
 * checked-change, reporting workspace-change and theme-change instead. Table's emptyText
 * shows only without rows and actionsLabel only with an actions scope, and
 * paginated, perPage and perPageValues need a paginator: TableComponentTest
 * and TablePaginationTest cover them.
 *
 * AppShell binds collapsed with wire:model ($wire.entangle), which the
 * kit only maps for value, open and checked; its collapsed-change event is
 * dispatched too, and AppShellComponentTest covers the binding.
 */
const DESIGN_SYSTEM_PARITY_OPTIONS = [
    // The image components (#61). The 2.x props they still accept (enable-lightbox,
    // auto-play, preview, the *Text strings, with-drag-drop…) aren't in the manifests:
    // the component tests cover them. ImageSlider's index binds with wire:model
    // ($wire.entangle), which the kit only maps for value, open and checked.
    // downloadLabel, playLabel and pauseLabel show only with downloadable or autoplay,
    // and loop and interval only change the widget's options.
    'ImageGallery' => ['ignoreDeclared' => ['enableLightbox'], 'ignoreEffects' => ['downloadLabel']],
    'ImageSlider'  => ['ignoreDeclared' => ['wire:model', 'autoPlay', 'autoPlayInterval'], 'ignoreEffects' => ['playLabel', 'pauseLabel']],
    'ImageLibrary' => ['ignoreDeclared' => ['preview', 'changeText', 'cropText', 'removeText', 'cropTitleText', 'cropCancelText', 'cropSaveText', 'addFilesText', 'withDragDrop', 'dragDropText', 'dragDropMultipleText', 'dragDropClass']],
    'Breadcrumbs'  => ['ignoreEffects' => ['navigate']],
    'Link'         => ['ignoreEffects' => ['navigate']],
    'ListItem'     => ['ignoreEffects' => ['navigate']],
    'Pagination'   => ['ignoreEffects' => ['rows']],
    'Steps'        => ['ignoreEffects' => ['clickable']],
    'AppShell'     => ['ignoreDeclared' => ['wire:model']],
    // Only the trend's label shows: the fixture's change is down. StatComponentTest and
    // KpiCardComponentTest cover the others; Diff's emptyLabel shows only when nothing changed.
    'Stat'        => ['ignoreEffects' => ['upLabel', 'flatLabel']],
    'Diff'        => ['ignoreEffects' => ['emptyLabel']],
    'UserMenu'    => ['ignoreDeclared' => ['checked-change']],
    'Table'       => ['ignoreEffects' => ['emptyText', 'actionsLabel', 'paginated', 'perPage', 'perPageValues']],
];

it('matches its manifest', function (string $component): void {
    expect($component)->toMatchManifest(DESIGN_SYSTEM_PARITY_OPTIONS[$component] ?? []);
})->with(ManifestParity::targets());

it('checks the pilot components', function (): void {
    expect(array_keys(ManifestParity::targets()))->toContain('Button', 'Input', 'Select', 'Checkbox', 'Card', 'Dialog', 'DropdownMenu', 'Toast');
});
