<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\IconRegistry;
use Illuminate\Support\Facades\Blade;

/*
 * ErrorState (#60). Not ValidationSummary (the 2.x errors). The manifest
 * parity check is in tests/Feature/DesignSystemParityTest.php.
 */

function errorStateRoot(DOMXPath $dom): DOMElement
{
    return $dom->query('//div[contains(@class, "apui-error-state")]')->item(0);
}

test('a failed request renders the contract markup as an alert', function (): void {
    $html = Blade::render('<x-artisanpack-error-state title="Couldn’t load transactions" description="Your data is safe." code="Error 503 · req_8f2c91" actions="Try again" />');
    $dom  = designSystemDom($html);
    $root = errorStateRoot($dom);

    $parts = [];
    foreach ($dom->query('//*[@data-scope="error-state"]') as $element) {
        $parts[] = $element->getAttribute('data-part');
    }

    expect($parts)->toBe(['root', 'icon', 'title', 'description', 'code', 'actions'])
        ->and($root->getAttribute('role'))->toBe('alert')
        ->and($root->getAttribute('data-variant'))->toBe('request')
        ->and($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-align'))->toBe('center')
        ->and($dom->query('//span[@data-part="code"]')->item(0)->textContent)->toBe('Error 503 · req_8f2c91')
        ->and($html)->toContain(IconRegistry::render('server-crash'));
});

test('each variant has its default icon; not-found shows 404 instead', function (string $variant, ?string $icon, ?string $numeral): void {
    $html = Blade::render('<x-artisanpack-error-state title="T" :variant="$variant" />', ['variant' => $variant]);
    $dom  = designSystemDom($html);

    expect($dom->query('//*[@data-part="icon"]'))->toHaveCount(null === $icon ? 0 : 1)
        ->and($dom->query('//*[@data-part="numeral"]'))->toHaveCount(null === $numeral ? 0 : 1)
        ->and(null === $icon || str_contains($html, IconRegistry::render($icon)))->toBeTrue()
        ->and(null === $numeral || $numeral === $dom->query('//*[@data-part="numeral"]')->item(0)->textContent)->toBeTrue();
})->with([
    'request'   => ['request', 'server-crash', null],
    'offline'   => ['offline', 'wifi-off', null],
    'forbidden' => ['forbidden', 'lock', null],
    'not-found' => ['not-found', null, '404'],
]);

test('a numeral replaces the default icon; an explicit icon shows beside it', function (): void {
    $numeralOnly = designSystemDom(Blade::render('<x-artisanpack-error-state title="T" numeral="503" />'));
    $both        = Blade::render('<x-artisanpack-error-state title="T" numeral="503" icon="lock" />');

    expect($numeralOnly->query('//*[@data-part="icon"]'))->toHaveCount(0)
        ->and($numeralOnly->query('//*[@data-part="numeral"]')->item(0)->textContent)->toBe('503')
        ->and(designSystemDom($both)->query('//*[@data-part="icon"]'))->toHaveCount(1)
        ->and($both)->toContain(IconRegistry::render('lock'));
});

test('focusTitle makes the title focusable and focuses it', function (): void {
    $title = designSystemDom(Blade::render('<x-artisanpack-error-state title="This page doesn’t exist" :heading-level="1" focus-title />'))->query('//h1[@data-part="title"]')->item(0);

    expect($title->getAttribute('tabindex'))->toBe('-1')
        ->and($title->hasAttribute('data-focus-title'))->toBeTrue()
        ->and($title->getAttribute('x-init'))->toBe('$el.focus()');

    $plain = designSystemDom(Blade::render('<x-artisanpack-error-state title="T" />'))->query('//h3[@data-part="title"]')->item(0);
    expect($plain->hasAttribute('tabindex'))->toBeFalse();
});

test('slots override props and the default slot is the description', function (): void {
    $dom = designSystemDom(Blade::render(<<<'BLADE'
        <x-artisanpack-error-state variant="offline" size="sm" align="start" :classes="['code' => 'c']" code="X1">
            <x-slot:icon><svg data-test="own"></svg></x-slot:icon>
            <x-slot:title>You’re <em>offline</em></x-slot:title>
            <x-slot:actions><button>Retry</button></x-slot:actions>
            Changes are saved.
        </x-artisanpack-error-state>
        BLADE));

    expect(errorStateRoot($dom)->getAttribute('data-size'))->toBe('sm')
        ->and(errorStateRoot($dom)->getAttribute('data-align'))->toBe('start')
        ->and($dom->query('//*[@data-part="icon"]/svg[@data-test="own"]'))->toHaveCount(1)
        ->and($dom->query('//h3[@data-part="title"]/em')->item(0)->textContent)->toBe('offline')
        ->and(trim($dom->query('//div[@data-part="description"]')->item(0)->textContent))->toBe('Changes are saved.')
        ->and($dom->query('//span[@data-part="code"]')->item(0)->getAttribute('class'))->toBe('c')
        ->and($dom->query('//*[@data-part="actions"]/button')->item(0)->textContent)->toBe('Retry');
});
