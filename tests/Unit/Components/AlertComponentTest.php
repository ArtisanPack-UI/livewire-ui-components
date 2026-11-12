<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use Illuminate\Support\Facades\Blade;

/*
 * Alert (#60). The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function alertRoot(DOMXPath $dom): DOMElement
{
    return $dom->query('//div[contains(@class, "apui-alert")]')->item(0);
}

test('the fixture renders the contract markup in order', function (): void {
    $html = Blade::render('<x-artisanpack-alert title="Listing published" description="Lakeview Lofts is live." actions="Undo" dismissible />');
    $dom  = designSystemDom($html);
    $root = alertRoot($dom);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="alert"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect($parts)->toBe(['root', 'icon', 'title', 'description', 'actions', 'close-trigger'])
        ->and($root->getAttribute('role'))->toBe('status')
        ->and($root->getAttribute('data-color'))->toBe('neutral')
        ->and($root->getAttribute('data-variant'))->toBe('soft')
        ->and($root->getAttribute('data-surface'))->toBe('default')
        ->and($root->getAttribute('x-bind:hidden'))->toBe('! open')
        ->and($dom->query('//p[@data-part="title"]')->item(0)->textContent)->toBe('Listing published')
        ->and($dom->query('//div[@data-part="description"]')->item(0)->textContent)->toBe('Lakeview Lofts is live.')
        ->and($html)->toContain('<span data-scope="alert" data-part="icon" aria-hidden="true">'.IconRegistry::render('info').'</span>');
});

test('error is an alert and the color picks the status icon', function (string $color, string $icon, string $role): void {
    $html = Blade::render('<x-artisanpack-alert title="Done" :color="$color" />', ['color' => $color]);
    $root = alertRoot(designSystemDom($html));

    expect($root->getAttribute('role'))->toBe($role)
        ->and($root->getAttribute('data-color'))->toBe($color)
        ->and($html)->toContain(IconRegistry::render($icon));
})->with([
    'success' => ['success', 'circle-check', 'status'],
    'warning' => ['warning', 'triangle-alert', 'status'],
    'error'   => ['error', 'circle-alert', 'alert'],
    'primary' => ['primary', 'info', 'status'],
]);

test('variant, surface and icon render as given', function (): void {
    $html = Blade::render('<x-artisanpack-alert title="Hi" variant="solid" surface="glass" icon="trash" />');
    $root = alertRoot(designSystemDom($html));

    expect($root->getAttribute('data-variant'))->toBe('solid')
        ->and($root->getAttribute('data-surface'))->toBe('glass')
        ->and($html)->toContain(IconRegistry::render('trash'));
});

test('the close button hides the alert, dispatches open-change and has a translated name', function (): void {
    app('translator')->addLines(['*.Dismiss' => 'Fermer'], 'fr');
    app()->setLocale('fr');

    $close = designSystemDom(Blade::render('<x-artisanpack-alert title="Saved" dismissible />'))->query('//button[@data-part="close-trigger"]')->item(0);

    expect($close->getAttribute('aria-label'))->toBe('Fermer')
        ->and($close->getAttribute('x-on:click'))->toBe("open = false; \$dispatch( 'open-change', { open: false } )")
        ->and($close->getAttribute('data-variant'))->toBe('ghost')
        ->and($close->getAttribute('data-color'))->toBe('neutral')
        ->and($close->getAttribute('data-size'))->toBe('xs')
        ->and($close->hasAttribute('data-icon-only'))->toBeTrue();

    app()->setLocale('en');
});

test('closeLabel names the close button, and there is none unless dismissible', function (): void {
    $close = designSystemDom(Blade::render('<x-artisanpack-alert title="Saved" dismissible close-label="Hide" />'))->query('//button[@data-part="close-trigger"]')->item(0);

    expect($close->getAttribute('aria-label'))->toBe('Hide')
        ->and(designSystemDom(Blade::render('<x-artisanpack-alert title="Saved" />'))->query('//*[@data-part="close-trigger"]'))->toHaveCount(0);
});

test('the default slot fills the description and named slots override props', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-alert class="mb-4" :classes="['title' => 'font-bold']" title="Plain">
            <x-slot:icon><svg data-test="own"></svg></x-slot:icon>
            <x-slot:title>Rich <em>title</em></x-slot:title>
            <x-slot:actions><button>Retry</button></x-slot:actions>
            We couldn't charge the card.
        </x-artisanpack-alert>
        BLADE));

    expect(alertRoot($dom)->getAttribute('class'))->toBe('apui-alert mb-4')
        ->and($dom->query('//*[@data-part="icon"]/svg[@data-test="own"]'))->toHaveCount(1)
        ->and($dom->query('//p[@data-part="title"]/em')->item(0)->textContent)->toBe('title')
        ->and($dom->query('//p[@data-part="title"]')->item(0)->getAttribute('class'))->toBe('font-bold')
        ->and(trim($dom->query('//div[@data-part="description"]')->item(0)->textContent))->toBe("We couldn't charge the card.")
        ->and($dom->query('//*[@data-part="actions"]/button')->item(0)->textContent)->toBe('Retry');
});

test('empty parts are left out', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-alert />'));

    expect($dom->query('//*[@data-scope="alert" and @data-part!="root" and @data-part!="icon"]'))->toHaveCount(0);
});

test('an author id lands on the root unchanged', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-alert id="notice" title="Saved" />'));

    expect($dom->query('//*[@data-part="root"][@id="notice"]'))->toHaveCount(1)
        ->and($dom->query('//*[@id="notice-root"]'))->toHaveCount(0);
});
