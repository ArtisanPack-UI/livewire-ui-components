<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\ImageGallery;
use Illuminate\Support\Facades\View;

test('renders grid layout with default columns', function (): void {
    $images = [
        'https://example.com/image1.jpg',
        'https://example.com/image2.jpg',
        'https://example.com/image3.jpg',
    ];

    $component = new ImageGallery($images);
    $view      = $component->render();

    expect($view)->toBeInstanceOf(Illuminate\Contracts\View\View::class);
    expect($component->images)->toBe($images);
    expect($component->columns)->toBe([
        'default' => 1,
        'sm'      => 2,
        'md'      => 3,
        'lg'      => 4,
        'xl'      => 5,
    ]);
});

test('renders with image objects containing metadata', function (): void {
    $images = [
        [
            'url'      => 'https://example.com/image1.jpg',
            'alt'      => 'First image',
            'caption'  => 'Beautiful landscape',
            'category' => 'nature',
            'width'    => 800,
            'height'   => 600,
        ],
        [
            'src'     => 'https://example.com/image2.jpg',
            'alt'     => 'Second image',
            'caption' => 'City skyline',
            'tags'    => ['urban', 'architecture'],
            'width'   => 1200,
            'height'  => 800,
        ],
    ];

    $component = new ImageGallery($images);

    expect($component->images)->toBe($images);
    expect(count($component->images))->toBe(2);
});

test('generates unique UUID with custom ID', function (): void {
    $images   = ['https://example.com/image1.jpg'];
    $customId = 'my-gallery';

    $component = new ImageGallery($images, $customId);

    expect($component->uuid)->toContain('artisanpack-gallery-');
    expect($component->uuid)->toContain($customId);
    expect($component->id)->toBe($customId);
});

test('configures custom responsive columns', function (): void {
    $images        = ['image1.jpg', 'image2.jpg'];
    $customColumns = [
        'default' => 2,
        'md'      => 4,
        'lg'      => 6,
    ];

    $component = new ImageGallery($images, columns: $customColumns);

    expect($component->columns)->toBe($customColumns);
});

test('configures aspect ratio correctly', function (): void {
    $images = ['image1.jpg', 'image2.jpg'];

    $componentSquare    = new ImageGallery($images, aspectRatio: 'square');
    $componentLandscape = new ImageGallery($images, aspectRatio: 'landscape');
    $componentPortrait  = new ImageGallery($images, aspectRatio: 'portrait');
    $componentAuto      = new ImageGallery($images, aspectRatio: 'auto');

    expect($componentSquare->aspectRatio)->toBe('square');
    expect($componentLandscape->aspectRatio)->toBe('landscape');
    expect($componentPortrait->aspectRatio)->toBe('portrait');
    expect($componentAuto->aspectRatio)->toBe('auto');
});

test('configures gap settings', function (): void {
    $images = ['image1.jpg', 'image2.jpg'];

    $component = new ImageGallery($images, gap: 'lg');

    expect($component->gap)->toBe('lg');
});

test('configures lightbox and caption settings', function (): void {
    $images = ['image1.jpg', 'image2.jpg'];

    $component = new ImageGallery(
        $images,
        enableLightbox: false,
        showCaptions: true,
    );

    expect($component->enableLightbox)->toBe(false);
    expect($component->showCaptions)->toBe(true);
});

test('configures layout and lazy loading', function (): void {
    $images = ['image1.jpg', 'image2.jpg'];

    $component = new ImageGallery(
        $images,
        layout: 'masonry',
        lazyLoad: false,
    );

    expect($component->layout)->toBe('masonry');
    expect($component->lazyLoad)->toBe(false);
});

test('configures filtering system', function (): void {
    $images  = ['image1.jpg', 'image2.jpg'];
    $filters = ['nature', 'urban', 'architecture'];

    $component = new ImageGallery($images, filters: $filters);

    expect($component->filters)->toBe($filters);
});

test('configures pagination settings', function (): void {
    $images = ['image1.jpg', 'image2.jpg'];

    $component = new ImageGallery(
        $images,
        itemsPerPage: 12,
        loadingStyle: 'spinner',
    );

    expect($component->itemsPerPage)->toBe(12);
    expect($component->loadingStyle)->toBe('spinner');
});

test('returns correct gap classes', function (): void {
    $images = ['image1.jpg'];

    $componentXs      = new ImageGallery($images, gap: 'xs');
    $componentSm      = new ImageGallery($images, gap: 'sm');
    $componentMd      = new ImageGallery($images, gap: 'md');
    $componentLg      = new ImageGallery($images, gap: 'lg');
    $componentXl      = new ImageGallery($images, gap: 'xl');
    $componentInvalid = new ImageGallery($images, gap: 'invalid');

    expect($componentXs->getGapClass())->toBe('gap-1');
    expect($componentSm->getGapClass())->toBe('gap-2');
    expect($componentMd->getGapClass())->toBe('gap-4');
    expect($componentLg->getGapClass())->toBe('gap-6');
    expect($componentXl->getGapClass())->toBe('gap-8');
    expect($componentInvalid->getGapClass())->toBe('gap-4'); // defaults to gap-4
});

test('returns correct aspect ratio classes', function (): void {
    $images = ['image1.jpg'];

    $componentSquare    = new ImageGallery($images, aspectRatio: 'square');
    $componentLandscape = new ImageGallery($images, aspectRatio: 'landscape');
    $componentPortrait  = new ImageGallery($images, aspectRatio: 'portrait');
    $componentAuto      = new ImageGallery($images, aspectRatio: 'auto');
    $componentInvalid   = new ImageGallery($images, aspectRatio: 'invalid');

    expect($componentSquare->getAspectRatioClass())->toBe('aspect-square');
    expect($componentLandscape->getAspectRatioClass())->toBe('aspect-[4/3]');
    expect($componentPortrait->getAspectRatioClass())->toBe('aspect-[3/4]');
    expect($componentAuto->getAspectRatioClass())->toBe('');
    expect($componentInvalid->getAspectRatioClass())->toBe('aspect-square'); // defaults to aspect-square
});

test('returns correct grid column classes', function (): void {
    $images        = ['image1.jpg'];
    $customColumns = [
        'default' => 2,
        'sm'      => 3,
        'md'      => 4,
        'lg'      => 5,
        'xl'      => 6,
    ];

    $component = new ImageGallery($images, columns: $customColumns);
    $classes   = $component->getGridColumnClasses();

    expect($classes)->toContain('grid-cols-2');
    expect($classes)->toContain('sm:grid-cols-3');
    expect($classes)->toContain('md:grid-cols-4');
    expect($classes)->toContain('lg:grid-cols-5');
    expect($classes)->toContain('xl:grid-cols-6');
});

test('returns correct loading classes', function (): void {
    $images = ['image1.jpg'];

    $componentSkeleton = new ImageGallery($images, loadingStyle: 'skeleton');
    $componentSpinner  = new ImageGallery($images, loadingStyle: 'spinner');
    $componentFade     = new ImageGallery($images, loadingStyle: 'fade');
    $componentInvalid  = new ImageGallery($images, loadingStyle: 'invalid');

    expect($componentSkeleton->getLoadingClass())->toBe('bg-gray-200 dark:bg-gray-700 animate-pulse');
    expect($componentSpinner->getLoadingClass())->toBe('bg-gray-100 dark:bg-gray-800');
    expect($componentFade->getLoadingClass())->toBe('bg-gray-100 dark:bg-gray-800 opacity-50');
    expect($componentInvalid->getLoadingClass())->toBe('bg-gray-200 dark:bg-gray-700 animate-pulse'); // defaults to skeleton
});

test('handles empty images array gracefully', function (): void {
    $component = new ImageGallery([]);

    expect($component->images)->toBe([]);
    expect($component->uuid)->toContain('artisanpack-gallery-');
});

test('applies custom CSS classes through attributes', function (): void {
    $images    = ['image1.jpg', 'image2.jpg'];
    $component = new ImageGallery($images);

    // Create a mock view to test attribute handling
    $view = View::make('livewire-ui-components::components.image-gallery', $component->data());

    expect($view->getData())->toHaveKeys([
        'images', 'id', 'columns', 'aspectRatio', 'gap', 'enableLightbox',
        'showCaptions', 'layout', 'lazyLoad', 'filters', 'itemsPerPage',
        'loadingStyle', 'uuid',
    ]);
});

test('default values are set correctly', function (): void {
    $images    = ['image1.jpg'];
    $component = new ImageGallery($images);

    expect($component->columns)->toBe([
        'default' => 1,
        'sm'      => 2,
        'md'      => 3,
        'lg'      => 4,
        'xl'      => 5,
    ]);
    expect($component->aspectRatio)->toBe('square');
    expect($component->gap)->toBe('md');
    expect($component->enableLightbox)->toBe(true);
    expect($component->showCaptions)->toBe(false);
    expect($component->layout)->toBe('grid');
    expect($component->lazyLoad)->toBe(true);
    expect($component->filters)->toBeNull();
    expect($component->itemsPerPage)->toBe(0);
    expect($component->loadingStyle)->toBe('skeleton');
});

test('component renders view correctly', function (): void {
    $images = [
        'https://example.com/image1.jpg',
        'https://example.com/image2.jpg',
    ];

    $component = new ImageGallery($images, 'test-gallery');
    $view      = $component->render();

    expect($view)->toBeInstanceOf(Illuminate\Contracts\View\View::class);
    expect($view->getName())->toBe('livewire-ui-components::components.image-gallery');
});

test('UUID generation is consistent for same parameters', function (): void {
    $images = ['image1.jpg', 'image2.jpg'];
    $id     = 'test-id';

    $component1 = new ImageGallery($images, $id);
    $component2 = new ImageGallery($images, $id);

    // UUIDs should be the same for identical parameters
    expect($component1->uuid)->toBe($component2->uuid);
});

test('UUID generation is different for different parameters', function (): void {
    $images1 = ['image1.jpg'];
    $images2 = ['image2.jpg'];

    $component1 = new ImageGallery($images1);
    $component2 = new ImageGallery($images2);

    // UUIDs should be different for different parameters
    expect($component1->uuid)->not->toBe($component2->uuid);
});

test('validates boolean props correctly', function (): void {
    $images = ['image1.jpg'];

    $component = new ImageGallery(
        $images,
        enableLightbox: false,
        showCaptions: true,
        lazyLoad: false,
    );

    expect($component->enableLightbox)->toBe(false);
    expect($component->showCaptions)->toBe(true);
    expect($component->lazyLoad)->toBe(false);
});

test('validates integer props correctly', function (): void {
    $images = ['image1.jpg'];

    $component = new ImageGallery(
        $images,
        itemsPerPage: 24,
    );

    expect($component->itemsPerPage)->toBe(24);
});

test('validates array props correctly', function (): void {
    $images        = ['image1.jpg'];
    $customColumns = ['default' => 3, 'lg' => 6];
    $filters       = ['category1', 'category2'];

    $component = new ImageGallery(
        $images,
        columns: $customColumns,
        filters: $filters,
    );

    expect($component->columns)->toBe($customColumns);
    expect($component->filters)->toBe($filters);
});

test('validates string props correctly', function (): void {
    $images = ['image1.jpg'];

    $component = new ImageGallery(
        $images,
        aspectRatio: 'landscape',
        gap: 'xl',
        layout: 'masonry',
        loadingStyle: 'fade',
    );

    expect($component->aspectRatio)->toBe('landscape');
    expect($component->gap)->toBe('xl');
    expect($component->layout)->toBe('masonry');
    expect($component->loadingStyle)->toBe('fade');
});

test('handles complex column configuration', function (): void {
    $images         = ['image1.jpg'];
    $complexColumns = [
        'default' => 1,
        'sm'      => 2,
        'md'      => 3,
        'lg'      => 4,
        'xl'      => 6,
        '2xl'     => 8,
    ];

    $component = new ImageGallery($images, columns: $complexColumns);
    $classes   = $component->getGridColumnClasses();

    expect($classes)->toContain('grid-cols-1');
    expect($classes)->toContain('sm:grid-cols-2');
    expect($classes)->toContain('md:grid-cols-3');
    expect($classes)->toContain('lg:grid-cols-4');
    expect($classes)->toContain('xl:grid-cols-6');
    expect($classes)->toContain('2xl:grid-cols-8');
});

test('supports null filters correctly', function (): void {
    $images = ['image1.jpg'];

    $component = new ImageGallery($images, filters: null);

    expect($component->filters)->toBeNull();
});

test('supports empty filters array', function (): void {
    $images = ['image1.jpg'];

    $component = new ImageGallery($images, filters: []);

    expect($component->filters)->toBe([]);
});

test('supports different layout options', function (): void {
    $images = ['image1.jpg'];

    $gridComponent    = new ImageGallery($images, layout: 'grid');
    $masonryComponent = new ImageGallery($images, layout: 'masonry');

    expect($gridComponent->layout)->toBe('grid');
    expect($masonryComponent->layout)->toBe('masonry');
});

test('supports pagination disabled with zero items per page', function (): void {
    $images = ['image1.jpg'];

    $component = new ImageGallery($images, itemsPerPage: 0);

    expect($component->itemsPerPage)->toBe(0);
});

test('supports different loading styles', function (): void {
    $images = ['image1.jpg'];

    $skeletonComponent = new ImageGallery($images, loadingStyle: 'skeleton');
    $spinnerComponent  = new ImageGallery($images, loadingStyle: 'spinner');
    $fadeComponent     = new ImageGallery($images, loadingStyle: 'fade');

    expect($skeletonComponent->loadingStyle)->toBe('skeleton');
    expect($spinnerComponent->loadingStyle)->toBe('spinner');
    expect($fadeComponent->loadingStyle)->toBe('fade');
});
