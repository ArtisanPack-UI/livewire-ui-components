<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\ImageSlider;
use Illuminate\Support\Facades\Blade;

/*
 * ImageSlider (#61): the design-system markup contract. The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php.
 */

function sliderImages(): array
{
    return [
        ['src' => '/photos/lake.jpg', 'alt' => 'Lake view from 4A', 'caption' => 'Lake view', 'description' => 'South-facing'],
        ['src' => '/photos/ridge.jpg', 'alt' => 'Sunrise over the ridge'],
        ['src' => '/photos/roof.jpg', 'alt' => 'Rooftop terrace', 'caption' => 'Rooftop'],
    ];
}

function sliderDom(string $props = '', ?array $images = null): DOMXPath
{
    return designSystemDom(Blade::render(
        '<x-artisanpack-image-slider :images="$images" '.$props.' />',
        ['images' => $images ?? sliderImages()],
    ));
}

test('renders a named carousel region with one slide per image', function (): void {
    $dom  = sliderDom();
    $root = $dom->query('//div[@data-part="root"]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-image-slider')
        ->and($root->getAttribute('role'))->toBe('region')
        ->and($root->getAttribute('aria-roledescription'))->toBe('carousel')
        ->and($root->getAttribute('aria-label'))->toBe('Photos')
        ->and($root->getAttribute('style'))->toStartWith('--apui-_image-slider-ratio: 21 / 9; --slides-per-page: 1;')
        ->and($dom->query('//div[@data-part="item-group"][@tabindex="0"]')->item(0)->getAttribute('style'))->toContain('scroll-snap-type: x mandatory')
        ->and($root->getAttribute('x-data'))->toContain('apuiCarousel(')
        ->and($root->getAttribute('x-data'))->toContain("scope: 'image-slider', slideCount: 3")
        ->and($dom->query('//div[@data-part="viewport"]/div[@data-part="item-group"]/div[@data-part="item"]'))->toHaveCount(3);
});

test('slides are named groups; only the shown one is exposed', function (): void {
    $items = sliderDom()->query('//div[@data-part="item"]');

    expect($items->item(0)->getAttribute('role'))->toBe('group')
        ->and($items->item(0)->getAttribute('aria-roledescription'))->toBe('slide')
        ->and($items->item(1)->getAttribute('aria-label'))->toBe('2 of 3')
        ->and($items->item(0)->hasAttribute('aria-hidden'))->toBeFalse()
        ->and($items->item(1)->getAttribute('aria-hidden'))->toBe('true');
});

test('images keep their alt text; captions are a title and a description', function (): void {
    $dom = sliderDom();

    expect($dom->query('//img[@data-part="image"]')->item(0)->getAttribute('alt'))->toBe('Lake view from 4A')
        ->and($dom->query('//img[@data-part="image"]')->item(0)->hasAttribute('loading'))->toBeFalse()
        ->and($dom->query('//img[@data-part="image"]')->item(1)->getAttribute('loading'))->toBe('lazy')
        ->and($dom->query('//div[@data-part="caption"]'))->toHaveCount(2)
        ->and(trim($dom->query('//p[@data-part="title"]')->item(0)->textContent))->toBe('Lake view')
        ->and(trim($dom->query('//p[@data-part="description"]')->item(0)->textContent))->toBe('South-facing');
});

test('the counter, arrows and dots show the current slide', function (): void {
    $dom = sliderDom(':index="1"');

    expect(trim($dom->query('//span[@data-part="progress-text"]')->item(0)->textContent))->toBe('2 / 3')
        ->and($dom->query('//*[@data-part="prev-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Previous photo')
        ->and($dom->query('//*[@data-part="next-trigger"]/span[@data-part="icon"][@data-mirror]'))->toHaveCount(1)
        ->and($dom->query('//button[@data-part="indicator"]'))->toHaveCount(3)
        ->and($dom->query('//button[@data-part="indicator"]')->item(2)->getAttribute('aria-label'))->toBe('Photo 3')
        ->and($dom->query('//button[@data-part="indicator"][@aria-current="true"]')->item(0)->getAttribute('id'))->toEndWith('indicator-1');
});

test('thumbnails and autoplay render their parts', function (): void {
    $dom = sliderDom('thumbnails autoplay :interval="7000"');

    expect($dom->query('//div[@data-part="thumbs"]/button[@data-part="thumb"]'))->toHaveCount(3)
        ->and($dom->query('//button[@data-part="thumb"]')->item(0)->getAttribute('aria-current'))->toBe('true')
        ->and($dom->query('//button[@data-part="autoplay-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Stop slide rotation')
        ->and($dom->query('//div[@data-part="item-group"]')->item(0)->getAttribute('aria-live'))->toBe('off')
        ->and($dom->query('//div[@data-part="root"]')->item(0)->getAttribute('x-data'))->toContain('autoplay: true, interval: 7000');
});

test('labels are props, with :index and :count in the translated defaults', function (): void {
    $dom = sliderDom('label="Tour" previous-label="Anterior" indicator-label="Foto {index}" counter-label="{index} de {count}"');

    expect($dom->query('//div[@data-part="root"]')->item(0)->getAttribute('aria-label'))->toBe('Tour')
        ->and($dom->query('//*[@data-part="prev-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Anterior')
        ->and($dom->query('//button[@data-part="indicator"]')->item(0)->getAttribute('aria-label'))->toBe('Foto 1')
        ->and(trim($dom->query('//span[@data-part="progress-text"]')->item(0)->textContent))->toBe('1 de 3');
});

test('accepts the 2.x props and image shapes', function (): void {
    $dom  = sliderDom('aspect-ratio="16:9" :auto-play="true" :auto-play-interval="3000" :with-arrows="false" show-counter', [
        ['url' => '/photos/a.jpg', 'title' => 'A'],
        '/photos/b.jpg',
    ]);
    $root = $dom->query('//div[@data-part="root"]')->item(0);

    expect($root->getAttribute('style'))->toStartWith('--apui-_image-slider-ratio: 16 / 9;')
        ->and($root->hasAttribute('with-arrows'))->toBeFalse()
        ->and($root->hasAttribute('show-counter'))->toBeFalse()
        ->and($root->getAttribute('x-data'))->toContain('autoplay: true, interval: 3000')
        ->and($dom->query('//img[@data-part="image"]')->item(0)->getAttribute('src'))->toBe('/photos/a.jpg')
        ->and(trim($dom->query('//p[@data-part="title"]')->item(0)->textContent))->toBe('A');
});

test('every part has a stable id from the id attribute', function (): void {
    $dom = sliderDom('id="tour" thumbnails autoplay');

    foreach ($dom->query('//*[@data-scope="image-slider"][not(self::img)][not(self::p)][not(@data-part="caption")]') as $part) {
        expect($part->getAttribute('id'))->toStartWith('tour');
    }

    expect($dom->query('//*[@id="tour-item-2"]'))->toHaveCount(1)
        ->and($dom->query('//*[@id="tour-indicator-0"]'))->toHaveCount(1);
});

test('the class normalizes its props', function (): void {
    $slider = new ImageSlider(images: sliderImages(), aspectRatio: 'square', index: 7, interval: 10);

    expect($slider->aspectRatio)->toBe('1 / 1')
        ->and($slider->interval)->toBe(1000)
        ->and($slider->currentIndex())->toBe(2);
});

test('drops image sources with a script scheme, so the client never sees them', function (): void {
    $component = new ImageSlider(images: [
        ['src' => '/photos/ridge.jpg', 'alt' => 'Ridge'],
        ['src' => "java\tscript:alert(1)", 'alt' => 'Bad'],
        'JavaScript:alert(2)',
        ['url' => 'data:image/png;base64,AAAA', 'alt' => 'Inline'],
    ]);

    expect(array_column($component->entries(), 'src'))
        ->toBe(['/photos/ridge.jpg', '', '', 'data:image/png;base64,AAAA']);
});

test('merges an author style into the carousel layout style', function (): void {
    $html = Blade::render('<x-artisanpack-image-slider :images="$images" style="max-width:40rem" />', ['images' => sliderImages()]);
    preg_match('/<div\b[^>]*>/', $html, $root);
    $style = designSystemDom($html)->query('//div[@data-part="root"]')->item(0)->getAttribute('style');

    expect(substr_count($root[0], ' style="'))->toBe(1)
        ->and($style)->toStartWith('--apui-_image-slider-ratio: 21 / 9;')
        ->and($style)->toEndWith('; max-width:40rem');
});
