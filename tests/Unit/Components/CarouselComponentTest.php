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

test('a caller aria-label wins over the title; label wins over both', function (): void {
    $name = static fn (string $props): string => carouselDom($props)->query('//div[@data-part="root"]')->item(0)->getAttribute('aria-label');

    expect($name('title="Featured" aria-label="Homes for sale"'))->toBe('Homes for sale')
        ->and($name('title="Featured" label="Picked" aria-label="Homes for sale"'))->toBe('Picked');
});

test('the autoplay button is the root\'s first child, first in the Tab order', function (): void {
    $dom  = carouselDom('autoplay title="Featured"');
    $root = $dom->query('//div[@data-part="root"]')->item(0);

    $first = null;
    foreach ($root->childNodes as $child) {
        if ($child instanceof DOMElement) {
            $first = $child;
            break;
        }
    }

    expect($first?->getAttribute('data-part'))->toBe('autoplay-trigger')
        ->and($dom->query('.//*[@data-part="viewport"]//*[@data-part="autoplay-trigger"]', $root))->toHaveCount(0);
});

test('a unique slide id keys each slide; otherwise its position does', function (): void {
    $keys = static fn (array $slides): array => array_map(
        static fn (DOMElement $item): string => $item->getAttribute('wire:key'),
        iterator_to_array(carouselDom('id="tour"', $slides)->query('//div[@data-part="item"]')),
    );

    expect($keys([['id' => 'b', 'title' => 'B'], ['id' => 7, 'title' => 'A']]))->toBe(['tour-item-id-b', 'tour-item-id-7'])
        ->and($keys([['id' => 'x'], ['id' => 'x']]))->toBe(['tour-item-0', 'tour-item-1'])
        ->and($keys([['id' => 'x'], ['title' => 'No id']]))->toBe(['tour-item-0', 'tour-item-1']);
});

test('the root key changes with the slide count and options, so Livewire remounts the carousel', function (): void {
    $key = static fn (string $props, int $count = 4): string => carouselDom('id="tour" '.$props, carouselSlides($count))
        ->query('//div[@data-part="root"]')->item(0)->getAttribute('wire:key');

    expect($key(''))->toStartWith('tour-4-')
        ->and($key('', 5))->toStartWith('tour-5-')
        ->and($key(''))->toBe($key(''))
        ->and($key(':index="2"'))->toBe($key(''))
        ->and($key(':per-view="2"'))->not->toBe($key(''))
        ->and($key('loop'))->not->toBe($key(''))
        ->and($key('wire:key="mine"'))->toStartWith('mine-4-');

    // Without an id the base id doesn't hash the slides, so reordering keeps the root
    // (and the keyed slides' state); adding or removing a slide still remounts it.
    $withIds  = array_map(static fn (array $slide, int $i): array => $slide + ['id' => "s{$i}"], carouselSlides(), range(1, 4));
    $rootKey  = static function (array $slides): string {
        // Each Livewire render is its own request, so forget this request's ids.
        app()->forgetInstance(\ArtisanPack\LivewireUiComponents\Support\RenderedIds::class);

        return carouselDom('', $slides)->query('//div[@data-part="root"]')->item(0)->getAttribute('wire:key');
    };
    expect($rootKey(array_reverse($withIds)))->toBe($rootKey($withIds))
        ->and($rootKey(array_slice($withIds, 0, 3)))->not->toBe($rootKey($withIds));

    $html = Blade::render('<x-artisanpack-carousel :slides="$slides" wire:key="mine" />', ['slides' => carouselSlides()]);
    $open = substr($html, (int) strpos($html, '<div'), (int) strpos($html, '>') + 1);
    expect(substr_count($open, 'wire:key='))->toBe(1);
});
