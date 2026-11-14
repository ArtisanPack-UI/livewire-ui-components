<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use ArtisanPack\LivewireUiComponents\View\Components\Icon;
use Illuminate\Support\Facades\Blade;

/*
 * Icon (#65): the design-system markup contract. The manifest parity check
 * is in tests/Feature/DesignSystemParityTest.php.
 */

function iconDom(string $blade): DOMXPath
{
    return designSystemDom(Blade::render($blade));
}

afterEach(fn () => IconRegistry::reset());

test('renders a decorative registry glyph at md by default', function (): void {
    $dom  = iconDom('<x-artisanpack-icon name="house" />');
    $root = $dom->query('//span[@data-part="root"]')->item(0);
    $svg  = $dom->query('//span[@data-part="root"]/svg[@data-part="svg"]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-icon')
        ->and($root->getAttribute('data-scope'))->toBe('icon')
        ->and($root->getAttribute('data-variant'))->toBe('plain')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('aria-hidden'))->toBe('true')
        ->and($root->hasAttribute('role'))->toBeFalse()
        ->and($root->hasAttribute('data-color'))->toBeFalse()
        ->and($svg->getAttribute('data-scope'))->toBe('icon')
        ->and($svg->getAttribute('viewbox'))->toBe('0 0 24 24')
        ->and($svg->getAttribute('stroke-width'))->toBe('1.5')
        ->and($svg->getAttribute('aria-hidden'))->toBe('true');
});

test('a label makes it a named image', function (): void {
    $root = iconDom('<x-artisanpack-icon name="circle-alert" label="Payment failed" />')
        ->query('//span[@data-part="root"]')->item(0);

    expect($root->getAttribute('role'))->toBe('img')
        ->and($root->getAttribute('aria-label'))->toBe('Payment failed')
        ->and($root->hasAttribute('aria-hidden'))->toBeFalse();
});

test('a caller aria-label or aria-labelledby names it and keeps it exposed', function (): void {
    $root = static fn (string $attributes): DOMElement => iconDom('<x-artisanpack-icon name="circle-alert" '.$attributes.' />')
        ->query('//span[@data-part="root"]')->item(0);

    $byLabel = $root('aria-label="Error"');
    expect($byLabel->getAttribute('aria-label'))->toBe('Error')
        ->and($byLabel->getAttribute('role'))->toBe('img')
        ->and($byLabel->hasAttribute('aria-hidden'))->toBeFalse();

    $byId = $root('aria-labelledby="msg"');
    expect($byId->getAttribute('aria-labelledby'))->toBe('msg')
        ->and($byId->getAttribute('role'))->toBe('img')
        ->and($byId->hasAttribute('aria-hidden'))->toBeFalse();

    expect($root('label="Payment failed" aria-label="Error"')->getAttribute('aria-label'))->toBe('Payment failed')
        ->and($root('aria-label="Error" role="presentation"')->getAttribute('role'))->toBe('presentation')
        ->and($root('aria-hidden="false"')->getAttribute('aria-hidden'))->toBe('true');
});

test('size, color and the tile variant write their data attributes', function (string $size): void {
    $root = iconDom('<x-artisanpack-icon name="wallet" variant="tile" color="success" size="'.$size.'" />')
        ->query('//span[@data-part="root"]')->item(0);

    expect($root->getAttribute('class'))->toBe('apui-icon apui-icon-tile')
        ->and($root->getAttribute('data-variant'))->toBe('tile')
        ->and($root->getAttribute('data-size'))->toBe($size)
        ->and($root->getAttribute('data-color'))->toBe('success');
})->with(['sm', 'md', 'lg', 'xl']);

test('unknown variants, sizes and colors fall back', function (): void {
    $root = iconDom('<x-artisanpack-icon name="house" variant="huge" size="xxl" color="pink" />')
        ->query('//span[@data-part="root"]')->item(0);

    expect($root->getAttribute('data-variant'))->toBe('plain')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->hasAttribute('data-color'))->toBeFalse();
});

test('directional icons mirror unless mirror says otherwise', function (): void {
    expect(iconDom('<x-artisanpack-icon name="arrow-right" />')->query('//span[@data-mirror]'))->toHaveCount(1)
        ->and(iconDom('<x-artisanpack-icon name="house" />')->query('//span[@data-mirror]'))->toHaveCount(0)
        ->and(iconDom('<x-artisanpack-icon name="arrow-right" :mirror="false" />')->query('//span[@data-mirror]'))->toHaveCount(0)
        ->and(iconDom('<x-artisanpack-icon name="house" mirror />')->query('//span[@data-mirror]'))->toHaveCount(1);
});

test('apps can mark their own names directional', function (): void {
    IconRegistry::register(['send-forward' => '<path d="M4 12h16" />']);
    IconRegistry::registerDirectional(['send-forward']);

    expect(iconDom('<x-artisanpack-icon name="send-forward" />')->query('//span[@data-mirror]'))->toHaveCount(1);

    IconRegistry::reset();

    expect(IconRegistry::isDirectional('send-forward'))->toBeFalse();
});

test('the default slot draws your own svg instead of the name', function (): void {
    $dom = iconDom('<x-artisanpack-icon name="arrow-right" label="Grid"><svg viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" /></svg></x-artisanpack-icon>');

    expect($dom->query('//span[@data-part="root"]/svg/rect'))->toHaveCount(1)
        ->and($dom->query('//svg[@data-part="svg"]'))->toHaveCount(0)
        ->and($dom->query('//span[@data-mirror]'))->toHaveCount(0);
});

test('2.x blade-icons names still render, with the svg part', function (): void {
    $svg = iconDom('<x-artisanpack-icon name="o-user" class="w-4 h-4" />')
        ->query('//span[contains(@class, "apui-icon")][contains(@class, "w-4")]/svg')->item(0);

    expect($svg)->not->toBeNull()
        ->and($svg->getAttribute('data-part'))->toBe('svg')
        ->and($svg->getAttribute('aria-hidden'))->toBe('true');
});

test('classes reach the root and the svg', function (): void {
    $dom = iconDom('<x-artisanpack-icon name="house" :classes="[ \'root\' => \'r\', \'svg\' => \'s\' ]" />');

    expect($dom->query('//span[@class="apui-icon r"]'))->toHaveCount(1)
        ->and($dom->query('//svg[@class="s"]'))->toHaveCount(1);
});

test('classes.svg reaches a 2.x blade-icons fallback too', function (): void {
    $svg = iconDom('<x-artisanpack-icon name="o-user" :classes="[ \'svg\' => \'s\' ]" />')
        ->query('//span[@data-part="root"]/svg')->item(0);

    expect($svg)->not->toBeNull()
        ->and(explode(' ', (string) $svg->getAttribute('class')))->toContain('s')
        ->and($svg->getAttribute('data-part'))->toBe('svg');
});

test('the iconAlias filter still maps 2.x names for blade-icons', function (): void {
    expect((string) (new Icon(name: 'o-user'))->icon())->toBe('heroicon-o-user')
        ->and((string) (new Icon(name: 'fa.solid.user'))->icon())->toBe('fa-solid-user');
});
