<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\Support\SafeUrl;

/*
 * SafeUrl::navigable(): which links may load through wire:navigate.
 */

it('allows relative links and http(s) links on this host', function (string $url): void {
    expect(SafeUrl::navigable($url))->toBeTrue();
})->with([
    'path'      => '/dashboard',
    'relative'  => 'reports/2026',
    'query'     => '/search?q=lofts',
    'same host' => 'http://localhost/settings',
]);

it('rejects empty, fragment, protocol-relative, off-site and non-http links', function (?string $url): void {
    expect(SafeUrl::navigable($url))->toBeFalse();
})->with([
    'null'              => null,
    'empty'             => '  ',
    'fragment'          => '#section',
    'protocol-relative' => '//example.org/x',
    'off-site'          => 'https://example.org/docs',
    'mailto'            => 'mailto:team@example.com',
    'tel'               => 'tel:+15555550100',
    'javascript'        => 'javascript:alert(1)',
]);
