<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\ImageGallery;
use Illuminate\Support\Facades\Blade;

/*
 * ImageGallery (#61): the design-system markup contract. The manifest parity
 * check is in tests/Feature/DesignSystemParityTest.php.
 */

function galleryImages(): array
{
    return [
        ['src' => '/photos/ridge.jpg', 'alt' => 'Sunrise over the ridge', 'caption' => 'Sunrise'],
        ['src' => '/photos/courtyard.jpg', 'alt' => 'Courtyard garden'],
        ['src' => '/photos/facade.jpg', 'alt' => 'The west facade at dusk', 'caption' => 'Evening, west façade'],
    ];
}

function galleryDom(string $props = '', ?array $images = null): DOMXPath
{
    return designSystemDom(Blade::render(
        '<x-artisanpack-image-gallery :images="$images" '.$props.' />',
        ['images' => $images ?? galleryImages()],
    ));
}

test('renders a grid of figures whose buttons open the lightbox', function (): void {
    $dom  = galleryDom();
    $root = $dom->query('//div[@data-part="root"]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-image-gallery')
        ->and($root->getAttribute('data-scope'))->toBe('image-gallery')
        ->and($root->getAttribute('style'))->toBe('--apui-_image-gallery-columns: 4')
        ->and($root->hasAttribute('data-featured'))->toBeFalse()
        ->and($root->getAttribute('x-data'))->toStartWith('apuiLightbox(')
        ->and($dom->query('//div[@data-part="grid"]/figure[@data-part="item"]'))->toHaveCount(3)
        ->and($dom->query('//figure/button[@data-part="trigger"][@type="button"][@aria-haspopup="dialog"]'))->toHaveCount(3);
});

test('every image keeps its alt text; captions are figcaptions', function (): void {
    $dom    = galleryDom();
    $images = $dom->query('//div[@data-part="grid"]//img[@data-part="image"]');

    expect($images->item(0)->getAttribute('alt'))->toBe('Sunrise over the ridge')
        ->and($images->item(0)->getAttribute('loading'))->toBe('lazy')
        ->and($images->item(1)->getAttribute('src'))->toBe('/photos/courtyard.jpg')
        ->and($dom->query('//figure/figcaption[@data-part="caption"]'))->toHaveCount(2)
        ->and(trim($dom->query('//figcaption')->item(1)->textContent))->toBe('Evening, west façade');
});

test('featured and columns set the root attribute and the column count', function (): void {
    $root = galleryDom(':columns="3" featured')->query('//div[@data-part="root"]')->item(0);

    expect($root->hasAttribute('data-featured'))->toBeTrue()
        ->and($root->getAttribute('style'))->toBe('--apui-_image-gallery-columns: 3');
});

test('the closed lightbox renders hidden, as a dialog named by its title', function (): void {
    $dom     = galleryDom();
    $content = $dom->query('//div[@data-part="content"]')->item(0);
    $title   = $dom->query('//h2[@data-part="title"]')->item(0);

    expect($content->getAttribute('class'))->toBe('apui-lightbox')
        ->and($content->getAttribute('role'))->toBe('dialog')
        ->and($content->getAttribute('aria-modal'))->toBe('true')
        ->and($content->hasAttribute('hidden'))->toBeTrue()
        ->and($content->getAttribute('aria-labelledby'))->toBe($title->getAttribute('id'))
        ->and(trim($title->textContent))->toBe('1 / 3 · Sunrise')
        ->and($title->getAttribute('aria-live'))->toBe('polite')
        ->and($dom->query('//template[@x-teleport="body"]'))->toHaveCount(1);
});

test('an open lightbox in place shows the image, the thumbs and the counter', function (): void {
    $dom = galleryDom(':open="true" :index="2" :portal="false" :modal="false" downloadable');

    $content = $dom->query('//div[@data-part="content"]')->item(0);
    $stage   = $dom->query('//div[@data-part="stage"]/img[@data-part="image"]')->item(0);

    expect($content->hasAttribute('hidden'))->toBeFalse()
        ->and($content->getAttribute('data-state'))->toBe('open')
        ->and($content->hasAttribute('aria-modal'))->toBeFalse()
        ->and($dom->query('//template'))->toHaveCount(0)
        ->and($stage->getAttribute('alt'))->toBe('The west facade at dusk')
        ->and(trim($dom->query('//h2[@data-part="title"]')->item(0)->textContent))->toBe('3 / 3 · Evening, west façade')
        ->and($dom->query('//button[@data-part="thumb"][@aria-current="true"]'))->toHaveCount(1)
        ->and($dom->query('//button[@data-part="thumb"]')->item(2)->getAttribute('aria-current'))->toBe('true')
        ->and($dom->query('//*[@data-part="download"]')->item(0)->getAttribute('href'))->toBe('/photos/facade.jpg');
});

test('built-in labels are translatable and overridable', function (): void {
    $dom = galleryDom(':portal="false" previous-label="Anterior" close-label="Cerrar" counter-label="{index} de {count}"');

    expect($dom->query('//*[@data-part="prev-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Anterior')
        ->and($dom->query('//*[@data-part="next-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Next photo')
        ->and($dom->query('//*[@data-part="close-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Cerrar')
        ->and($dom->query('//div[@data-part="thumbs"]')->item(0)->getAttribute('aria-label'))->toBe('Photos')
        ->and(trim($dom->query('//h2[@data-part="title"]')->item(0)->textContent))->toBe('1 de 3 · Sunrise');
});

test('the previous and next icons mirror in RTL', function (): void {
    $dom = galleryDom(':portal="false"');

    foreach (['prev-trigger', 'next-trigger'] as $part) {
        expect($dom->query('//*[@data-part="'.$part.'"]/span[@data-part="icon"][@data-mirror]'))->toHaveCount(1);
    }
});

test('without lightbox it renders plain figures and no widget', function (): void {
    $dom = galleryDom(':lightbox="false"');

    expect($dom->query('//button'))->toHaveCount(0)
        ->and($dom->query('//figure/img[@data-part="image"]'))->toHaveCount(3)
        ->and($dom->query('//div[@data-part="content"]'))->toHaveCount(0)
        ->and($dom->query('//div[@data-part="root"]')->item(0)->hasAttribute('x-data'))->toBeFalse();
});

test('accepts the 2.x shapes: url keys, strings, breakpoint columns and enable-lightbox', function (): void {
    $dom = galleryDom(':columns="[ \'default\' => 1, \'md\' => 3, \'xl\' => 5 ]" :enable-lightbox="false" aspect-ratio="square" gap="lg"', [
        ['url' => '/photos/a.jpg', 'alt' => 'A'],
        '/photos/b.jpg',
    ]);
    $root = $dom->query('//div[@data-part="root"]')->item(0);

    expect($root->getAttribute('style'))->toBe('--apui-_image-gallery-columns: 5')
        ->and($root->hasAttribute('gap'))->toBeFalse()
        ->and($root->hasAttribute('aspect-ratio'))->toBeFalse()
        ->and($dom->query('//img')->item(0)->getAttribute('src'))->toBe('/photos/a.jpg')
        ->and($dom->query('//img')->item(1)->getAttribute('alt'))->toBe('')
        ->and($dom->query('//button'))->toHaveCount(0);
});

test('every part has a stable id, from the id attribute when there is one', function (): void {
    $dom = galleryDom('id="photos"');

    // The widget's parts; grid images and captions are plain markup.
    foreach ($dom->query('//*[@data-scope="image-gallery"][not(ancestor::figure)][not(self::figure)]') as $part) {
        expect($part->getAttribute('id'))->toStartWith('photos');
    }

    expect($dom->query('//*[@id="photos-thumb-2"]'))->toHaveCount(1)
        ->and($dom->query('//*[@id="photos-trigger-0"]'))->toHaveCount(1);
});

test('the class normalizes its props', function (): void {
    $gallery = new ImageGallery(images: galleryImages(), index: 9, columns: 0);

    expect($gallery->currentIndex())->toBe(2)
        ->and($gallery->columns)->toBe(1)
        ->and($gallery->titleFor(1))->toBe('2 / 3');
});

test('drops image sources with a script scheme, so the client never sees them', function (): void {
    $component = new ImageGallery(images: [
        ['src' => '/photos/ridge.jpg', 'alt' => 'Ridge'],
        ['src' => "java\tscript:alert(1)", 'alt' => 'Bad'],
        'JavaScript:alert(2)',
        ['url' => 'data:image/png;base64,AAAA', 'alt' => 'Inline'],
    ]);

    expect(array_column($component->entries(), 'src'))
        ->toBe(['/photos/ridge.jpg', '', '', 'data:image/png;base64,AAAA']);
});

test('merges an author style into the columns style', function (): void {
    $html = Blade::render('<x-artisanpack-image-gallery :images="$images" style="max-width:40rem" />', ['images' => galleryImages()]);
    preg_match('/<div\b[^>]*>/', $html, $root);

    expect(substr_count($root[0], ' style="'))->toBe(1)
        ->and(designSystemDom($html)->query('//div[@data-part="root"]')->item(0)->getAttribute('style'))->toBe('--apui-_image-gallery-columns: 4; max-width:40rem');
});
