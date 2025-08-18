<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\ImageSlider;
use Illuminate\Support\Facades\View;

test('renders with basic images array', function () {
    $images = [
        'https://example.com/image1.jpg',
        'https://example.com/image2.jpg',
        'https://example.com/image3.jpg'
    ];

    $component = new ImageSlider($images);
    $view = $component->render();

    expect($view)->toBeInstanceOf(\Illuminate\Contracts\View\View::class);
    expect($component->images)->toBe($images);
    expect($component->withArrows)->toBe(true);
    expect($component->withIndicators)->toBe(true);
});

test('renders with image objects containing metadata', function () {
    $images = [
        [
            'url' => 'https://example.com/image1.jpg',
            'alt' => 'First image',
            'width' => 800,
            'height' => 600
        ],
        [
            'src' => 'https://example.com/image2.jpg',
            'alt' => 'Second image',
            'width' => 1200,
            'height' => 800
        ]
    ];

    $component = new ImageSlider($images);

    expect($component->images)->toBe($images);
    expect(count($component->images))->toBe(2);
});

test('generates unique UUID with custom ID', function () {
    $images = ['https://example.com/image1.jpg'];
    $customId = 'my-slider';

    $component = new ImageSlider($images, $customId);

    expect($component->uuid)->toContain('artisanpack-slider-');
    expect($component->uuid)->toContain($customId);
    expect($component->id)->toBe($customId);
});

test('generates unique UUID without custom ID', function () {
    $images = ['https://example.com/image1.jpg'];

    $component = new ImageSlider($images);

    expect($component->uuid)->toContain('artisanpack-slider-');
    expect($component->id)->toBeNull();
});

test('configures navigation arrows correctly', function () {
    $images = ['image1.jpg', 'image2.jpg'];

    $componentWithArrows = new ImageSlider($images, withArrows: true);
    $componentWithoutArrows = new ImageSlider($images, withArrows: false);

    expect($componentWithArrows->withArrows)->toBe(true);
    expect($componentWithoutArrows->withArrows)->toBe(false);
});

test('configures indicators correctly', function () {
    $images = ['image1.jpg', 'image2.jpg'];

    $componentWithIndicators = new ImageSlider($images, withIndicators: true);
    $componentWithoutIndicators = new ImageSlider($images, withIndicators: false);

    expect($componentWithIndicators->withIndicators)->toBe(true);
    expect($componentWithoutIndicators->withIndicators)->toBe(false);
});

test('configures auto-play settings', function () {
    $images = ['image1.jpg', 'image2.jpg'];

    $component = new ImageSlider(
        $images,
        autoPlay: true,
        autoPlayInterval: 3000,
        pauseOnHover: false
    );

    expect($component->autoPlay)->toBe(true);
    expect($component->autoPlayInterval)->toBe(3000);
    expect($component->pauseOnHover)->toBe(false);
});

test('configures loop and transition settings', function () {
    $images = ['image1.jpg', 'image2.jpg'];

    $component = new ImageSlider(
        $images,
        loop: false,
        transition: 'fade',
        transitionDuration: 500
    );

    expect($component->loop)->toBe(false);
    expect($component->transition)->toBe('fade');
    expect($component->transitionDuration)->toBe(500);
});

test('configures lightbox and counter settings', function () {
    $images = ['image1.jpg', 'image2.jpg'];

    $component = new ImageSlider(
        $images,
        showCounter: true,
        enableLightbox: false
    );

    expect($component->showCounter)->toBe(true);
    expect($component->enableLightbox)->toBe(false);
});

test('returns correct aspect ratio classes', function () {
    $images = ['image1.jpg'];

    $component16x9 = new ImageSlider($images, aspectRatio: '16:9');
    $componentSquare = new ImageSlider($images, aspectRatio: 'square');
    $component4x3 = new ImageSlider($images, aspectRatio: '4:3');
    $component3x4 = new ImageSlider($images, aspectRatio: '3:4');
    $componentInvalid = new ImageSlider($images, aspectRatio: 'invalid');

    expect($component16x9->getAspectRatioClass())->toBe('aspect-video');
    expect($componentSquare->getAspectRatioClass())->toBe('aspect-square');
    expect($component4x3->getAspectRatioClass())->toBe('aspect-[4/3]');
    expect($component3x4->getAspectRatioClass())->toBe('aspect-[3/4]');
    expect($componentInvalid->getAspectRatioClass())->toBe('aspect-video'); // defaults to aspect-video
});

test('returns correct transition classes', function () {
    $images = ['image1.jpg'];

    $componentSlide = new ImageSlider($images, transition: 'slide');
    $componentFade = new ImageSlider($images, transition: 'fade');
    $componentInvalid = new ImageSlider($images, transition: 'invalid');

    expect($componentSlide->getTransitionClass())->toBe('transition-transform');
    expect($componentFade->getTransitionClass())->toBe('transition-opacity');
    expect($componentInvalid->getTransitionClass())->toBe('transition-transform'); // defaults to transition-transform
});

test('handles empty images array gracefully', function () {
    $component = new ImageSlider([]);

    expect($component->images)->toBe([]);
    expect($component->uuid)->toContain('artisanpack-slider-');
});

test('applies custom CSS classes through attributes', function () {
    $images = ['image1.jpg', 'image2.jpg'];
    $component = new ImageSlider($images);

    // Create a mock view to test attribute handling
    $view = View::make('livewire-ui-components::components.image-slider', $component->data());

    expect($view->getData())->toHaveKeys([
        'images', 'id', 'withArrows', 'withIndicators', 'autoPlay',
        'autoPlayInterval', 'pauseOnHover', 'loop', 'transition',
        'transitionDuration', 'showCounter', 'enableLightbox', 'aspectRatio', 'uuid'
    ]);
});

test('default values are set correctly', function () {
    $images = ['image1.jpg'];
    $component = new ImageSlider($images);

    expect($component->withArrows)->toBe(true);
    expect($component->withIndicators)->toBe(true);
    expect($component->autoPlay)->toBe(false);
    expect($component->autoPlayInterval)->toBe(5000);
    expect($component->pauseOnHover)->toBe(true);
    expect($component->loop)->toBe(true);
    expect($component->transition)->toBe('slide');
    expect($component->transitionDuration)->toBe(300);
    expect($component->showCounter)->toBe(false);
    expect($component->enableLightbox)->toBe(true);
    expect($component->aspectRatio)->toBe('16:9');
});

test('supports different aspect ratio formats', function () {
    $images = ['image1.jpg'];

    $component1x1 = new ImageSlider($images, aspectRatio: '1:1');
    $component21x9 = new ImageSlider($images, aspectRatio: '21:9');
    $component2x1 = new ImageSlider($images, aspectRatio: '2:1');

    expect($component1x1->getAspectRatioClass())->toBe('aspect-square');
    expect($component21x9->getAspectRatioClass())->toBe('aspect-[21/9]');
    expect($component2x1->getAspectRatioClass())->toBe('aspect-[2/1]');
});

test('component renders view correctly', function () {
    $images = [
        'https://example.com/image1.jpg',
        'https://example.com/image2.jpg'
    ];

    $component = new ImageSlider($images, 'test-slider');
    $view = $component->render();

    expect($view)->toBeInstanceOf(\Illuminate\Contracts\View\View::class);
    expect($view->getName())->toBe('livewire-ui-components::components.image-slider');
});

test('UUID generation is consistent for same parameters', function () {
    $images = ['image1.jpg', 'image2.jpg'];
    $id = 'test-id';

    $component1 = new ImageSlider($images, $id);
    $component2 = new ImageSlider($images, $id);

    // UUIDs should be the same for identical parameters
    expect($component1->uuid)->toBe($component2->uuid);
});

test('UUID generation is different for different parameters', function () {
    $images1 = ['image1.jpg'];
    $images2 = ['image2.jpg'];

    $component1 = new ImageSlider($images1);
    $component2 = new ImageSlider($images2);

    // UUIDs should be different for different parameters
    expect($component1->uuid)->not->toBe($component2->uuid);
});

test('validates boolean props correctly', function () {
    $images = ['image1.jpg'];

    // Test with boolean values
    $component = new ImageSlider(
        $images,
        withArrows: false,
        withIndicators: false,
        autoPlay: true,
        pauseOnHover: false,
        loop: false,
        showCounter: true,
        enableLightbox: false
    );

    expect($component->withArrows)->toBe(false);
    expect($component->withIndicators)->toBe(false);
    expect($component->autoPlay)->toBe(true);
    expect($component->pauseOnHover)->toBe(false);
    expect($component->loop)->toBe(false);
    expect($component->showCounter)->toBe(true);
    expect($component->enableLightbox)->toBe(false);
});

test('validates integer props correctly', function () {
    $images = ['image1.jpg'];

    $component = new ImageSlider(
        $images,
        autoPlayInterval: 2500,
        transitionDuration: 450
    );

    expect($component->autoPlayInterval)->toBe(2500);
    expect($component->transitionDuration)->toBe(450);
});
