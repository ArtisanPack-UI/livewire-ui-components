<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Avatar;
use Illuminate\Support\Facades\Blade;

/*
 * Avatar (#61): the design-system markup contract. The manifest parity check
 * is in tests/Feature/DesignSystemParityTest.php.
 */

test('renders initials as role=img named after the person', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-avatar name="Olivia Rhye" />'));
    $root = $dom->query('//span[contains(@class, "apui-avatar")]')->item(0);

    expect($root->getAttribute('role'))->toBe('img')
        ->and($root->getAttribute('aria-label'))->toBe('Olivia Rhye')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-shape'))->toBe('circle')
        ->and(trim($dom->query('//*[@data-part="initials"]')->item(0)->textContent))->toBe('OR');
});

test('initials take the first and last word, and keep multibyte letters', function (): void {
    expect(Avatar::initialsFor('Olivia Rhye'))->toBe('OR')
        ->and(Avatar::initialsFor('  Ana María de la Peña '))->toBe('AP')
        ->and(Avatar::initialsFor('émile'))->toBe('É')
        ->and(Avatar::initialsFor(''))->toBe('');
});

test('the color hashes from the name, the same way in every stack', function (): void {
    // Code points of "Olivia Rhye" sum to 1052; 1052 % 7 = 2 → accent (React and Vue test the same pair).
    expect(Avatar::colorFor('Olivia Rhye'))->toBe('accent')
        ->and(Avatar::colorFor('Émile Zola'))->toBe(Avatar::HASH_COLORS[(201 + 109 + 105 + 108 + 101 + 32 + 90 + 111 + 108 + 97) % 7])
        ->and(Avatar::colorFor(''))->toBe('neutral');

    $root = designSystemDom(Blade::render('<x-artisanpack-avatar name="Olivia Rhye" color="info" />'))->query('//span')->item(0);
    expect($root->getAttribute('data-color'))->toBe('info');
});

test('an image sits over the initials and is decorative', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-avatar name="Olivia Rhye" src="/a.jpg" />'));
    $img = $dom->query('//img[@data-part="image"]')->item(0);

    expect($img->getAttribute('src'))->toBe('/a.jpg')
        ->and($img->getAttribute('alt'))->toBe('')
        ->and($dom->query('//*[@data-part="initials"]'))->toHaveCount(1);
});

test('an unsafe src is dropped', function (): void {
    $html = Blade::render('<x-artisanpack-avatar name="X" src="javascript:alert(1)" />');

    expect($html)->not->toContain('javascript:')
        ->and(designSystemDom($html)->query('//img'))->toHaveCount(0);
});

test('without a name the avatar shows the user icon and is decorative', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-avatar />'));
    $root = $dom->query('//span[contains(@class, "apui-avatar")]')->item(0);

    expect($root->getAttribute('aria-hidden'))->toBe('true')
        ->and($root->hasAttribute('role'))->toBeFalse()
        ->and($root->getAttribute('data-color'))->toBe('neutral')
        ->and($dom->query('//span[contains(@class, "apui-avatar")]/svg'))->toHaveCount(1);
});

test('the status is a decorative dot and is announced in words', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-avatar name="Olivia Rhye" status="away" />'));
    $root = $dom->query('//span[contains(@class, "apui-avatar")]')->item(0);

    expect($root->getAttribute('aria-label'))->toBe('Olivia Rhye (Away)')
        ->and($dom->query('//*[@data-part="status"][@data-status="away"][@aria-hidden="true"]'))->toHaveCount(1);
});

test('the status words are translatable and overridable', function (): void {
    app('translator')->addLines(['*.Online' => 'En línea'], 'es');
    app()->setLocale('es');

    $translated = designSystemDom(Blade::render('<x-artisanpack-avatar name="Ana" status="online" />'))->query('//span')->item(0);
    $custom     = designSystemDom(Blade::render('<x-artisanpack-avatar name="Ana" status="online" status-label="Here" />'))->query('//span')->item(0);

    expect($translated->getAttribute('aria-label'))->toBe('Ana (En línea)')
        ->and($custom->getAttribute('aria-label'))->toBe('Ana (Here)');
});

test('the deprecated image and placeholder attributes still work', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-avatar image="/b.png" placeholder="Phoenix Baker" />'));

    expect($dom->query('//img[@src="/b.png"]'))->toHaveCount(1)
        ->and(trim($dom->query('//*[@data-part="initials"]')->item(0)->textContent))->toBe('PB')
        ->and($dom->query('//*[@placeholder or @image]'))->toHaveCount(0);
});

test('an avatar group overlaps data avatars and collapses the rest', function (): void {
    $html = Blade::render('<x-artisanpack-avatar-group :avatars="$people" :max="2" size="sm" label="Members" />', [
        'people' => [['name' => 'Olivia Rhye'], ['name' => 'Phoenix Baker'], ['name' => 'Lana Steiner'], ['name' => 'Demi Wilkinson']],
    ]);
    $dom  = designSystemDom($html);
    $root = $dom->query('//span[contains(@class, "apui-avatar-group")]')->item(0);

    expect($root->getAttribute('role'))->toBe('group')
        ->and($root->getAttribute('aria-label'))->toBe('Members')
        ->and($dom->query('//span[contains(@class, "apui-avatar-group")]/span[contains(@class, "apui-avatar")][@data-size="sm"]'))->toHaveCount(3)
        ->and($dom->query('//span[@aria-label="2 more"]'))->toHaveCount(1);
});
