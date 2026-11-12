<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Carousel;
use Illuminate\Support\Facades\Blade;

/*
 * Carousel (#65): the design-system markup contract on the carousel machine
 * (scope `carousel`). The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function carouselSlides(int $count = 4): array
{
    return array_map(static fn (int $i): array => [
        'image'       => "/photos/{$i}.jpg",
        'alt'         => "Photo {$i}",
        'title'       => "Property {$i}",
        'description' => "City {$i}",
    ], range(1, $count));
}

function carouselDom(string $props = '', ?array $slides = null, string $inner = ''): DOMXPath
{
    return designSystemDom(Blade::render(
        '<x-artisanpack-carousel :slides="$slides" '.$props.'>'.$inner.'</x-artisanpack-carousel>',
        ['slides' => $slides ?? carouselSlides()],
    ));
}

test('renders a named carousel region on the carousel machine', function (): void {
    $dom  = carouselDom();
    $root = $dom->query('//div[@data-part="root"]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-carousel')
        ->and($root->getAttribute('data-scope'))->toBe('carousel')
        ->and($root->getAttribute('role'))->toBe('region')
        ->and($root->getAttribute('aria-roledescription'))->toBe('carousel')
        ->and($root->getAttribute('aria-label'))->toBe('Carousel')
        ->and($root->getAttribute('style'))->toStartWith('--slides-per-page: 1; --slide-spacing: 12px;')
        ->and($root->getAttribute('x-data'))->toContain("scope: 'carousel', slideCount: 4, perView: 1, spacing: '12px'")
        ->and($dom->query('//div[@data-part="viewport"]/div[@data-part="item-group"][@tabindex="0"]/div[@data-part="item"]'))->toHaveCount(4);
});

test('slides are named groups; only those in view are exposed', function (): void {
    $items = carouselDom(':per-view="3"')->query('//div[@data-part="item"]');

    expect($items->item(0)->getAttribute('role'))->toBe('group')
        ->and($items->item(0)->getAttribute('aria-roledescription'))->toBe('slide')
        ->and($items->item(1)->getAttribute('aria-label'))->toBe('2 of 4')
        ->and($items->item(2)->hasAttribute('aria-hidden'))->toBeFalse()
        ->and($items->item(3)->getAttribute('aria-hidden'))->toBe('true');
});

test('per-view above one peeks and has one position per start slide', function (): void {
    $dom  = carouselDom(':per-view="3"');
    $root = $dom->query('//div[@data-part="root"]')->item(0);

    expect($root->getAttribute('style'))->toStartWith('--slides-per-page: 3.15;')
        ->and($dom->query('//button[@data-part="indicator"]'))->toHaveCount(2)
        ->and($dom->query('//button[@data-part="indicator"]')->item(1)->getAttribute('aria-label'))->toBe('Go to slide 2');

    expect((new Carousel(slides: carouselSlides(2), perView: 3))->pageCount())->toBe(1)
        ->and((new Carousel(slides: carouselSlides(6)))->pageCount())->toBe(6);
});

test('each slide is a Card by default', function (): void {
    $dom = carouselDom();

    expect($dom->query('//div[@data-part="item"]/*[contains(@class, "apui-card")]'))->toHaveCount(4)
        ->and($dom->query('//div[@data-part="item"]//img')->item(0)->getAttribute('alt'))->toBe('Photo 1')
        ->and($dom->query('//div[@data-part="item"]')->item(1)->textContent)->toContain('Property 2');
});

test('the slide scope renders each slide', function (): void {
    $dom = carouselDom('', [['text' => 'One'], ['text' => 'Two']], "@scope('slide', \$slide)<q data-i=\"{{ \$loop->index }}\">{{ \$slide['text'] }}</q>@endscope");

    expect($dom->query('//div[@data-part="item"]/q'))->toHaveCount(2)
        ->and(trim($dom->query('//q')->item(1)->textContent))->toBe('Two')
        ->and($dom->query('//q')->item(1)->getAttribute('data-i'))->toBe('1')
        ->and($dom->query('//*[contains(@class, "apui-card")]'))->toHaveCount(0);
});

test('a title puts the heading and outline buttons in the header', function (): void {
    $dom = carouselDom('title="Featured properties" :heading-level="2"');

    expect($dom->query('//div[@data-part="root"]')->item(0)->getAttribute('aria-label'))->toBe('Featured properties')
        ->and(trim($dom->query('//div[@data-part="header"]/h2[@data-part="title"]')->item(0)->textContent))->toBe('Featured properties')
        ->and($dom->query('//div[@data-part="header"]/*[@data-part="prev-trigger"][@data-variant="outline"]'))->toHaveCount(1)
        ->and($dom->query('//div[@data-part="viewport"]/*[@data-part="next-trigger"]'))->toHaveCount(0);
});

test('without a title soft buttons overlay the viewport', function (): void {
    $dom = carouselDom('label="Customer notes"');

    expect($dom->query('//div[@data-scope="carousel"][@data-part="header"]'))->toHaveCount(0)
        ->and($dom->query('//div[@data-part="root"]')->item(0)->getAttribute('aria-label'))->toBe('Customer notes')
        ->and($dom->query('//div[@data-part="viewport"]/*[@data-part="prev-trigger"][@data-variant="soft"][@disabled]'))->toHaveCount(1)
        ->and($dom->query('//div[@data-part="viewport"]/*[@data-part="next-trigger"][@aria-label="Next slide"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="next-trigger"]/span[@data-part="icon"][@data-mirror]'))->toHaveCount(1);
});

test('loop keeps both buttons enabled; the dot shown carries aria-current', function (): void {
    $dom = carouselDom('loop :index="2"');

    expect($dom->query('//*[@data-part="prev-trigger"][@disabled]'))->toHaveCount(0)
        ->and($dom->query('//button[@data-part="indicator"][@aria-current="true"]')->item(0)->getAttribute('id'))->toEndWith('indicator-2')
        ->and($dom->query('//div[@data-part="item"]')->item(2)->hasAttribute('aria-hidden'))->toBeFalse();
});

test('autoplay renders a pause button and turns the live region off', function (): void {
    $dom = carouselDom('autoplay :interval="7000"');

    expect($dom->query('//button[@data-part="autoplay-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Stop slide rotation')
        ->and($dom->query('//div[@data-part="item-group"]')->item(0)->getAttribute('aria-live'))->toBe('off')
        ->and($dom->query('//div[@data-part="root"]')->item(0)->getAttribute('x-data'))->toContain('autoplay: true, interval: 7000');
});

test('labels are translatable and overridable', function (): void {
    $dom = carouselDom('previous-label="Back" next-label="Forward" indicator-label="Page {index}" slide-label="Slide {index} / {count}"');

    expect($dom->query('//*[@data-part="prev-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Back')
        ->and($dom->query('//button[@data-part="indicator"]')->item(0)->getAttribute('aria-label'))->toBe('Page 1')
        ->and($dom->query('//div[@data-part="item"]')->item(3)->getAttribute('aria-label'))->toBe('Slide 4 / 4');
});

test('the 2.x props keep working or are dropped from the root', function (): void {
    $dom  = carouselDom('aria-label="Old name" without-arrows without-indicators :respects-reduced-motion="true"', [
        ['image' => '/a.jpg', 'title' => 'A', 'url' => '/a'],
        ['image' => '/b.jpg', 'title' => 'B'],
    ]);
    $root = $dom->query('//div[@data-part="root"]')->item(0);

    expect($root->getAttribute('aria-label'))->toBe('Old name')
        ->and($root->hasAttribute('without-arrows'))->toBeFalse()
        ->and($root->hasAttribute('without-indicators'))->toBeFalse()
        ->and($dom->query('//div[@data-part="item"]//a[@href="/a"]'))->toHaveCount(1);
});

test('every part keeps a stable id across renders', function (): void {
    $first  = carouselDom('id="tour"')->query('//*[@data-part="indicator"]')->item(1)->getAttribute('id');
    $second = carouselDom('id="tour"')->query('//*[@data-part="indicator"]')->item(1)->getAttribute('id');

    expect($first)->toBe('tour-indicator-1')->and($second)->toBe($first);
});
