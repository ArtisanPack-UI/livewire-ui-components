<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * Text (#58): the design-system markup contract. The manifest parity check is
 * in tests/Feature/DesignSystemParityTest.php.
 */

test('renders a paragraph with the default size, tone and weight', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-text>Body copy</x-artisanpack-text>'))->query('//p[contains(@class, "apui-text")]')->item(0);

    expect($root->getAttribute('data-size'))->toBe('md')
        ->and($root->getAttribute('data-tone'))->toBe('default')
        ->and($root->getAttribute('data-weight'))->toBe('regular')
        ->and($root->hasAttribute('data-truncate'))->toBeFalse()
        ->and(trim($root->textContent))->toBe('Body copy');
});

test('as, size, tone and weight are honored; unknown values fall back', function (): void {
    $span    = designSystemDom(Blade::render('<x-artisanpack-text as="span" size="lead" tone="error" weight="semibold">Failed</x-artisanpack-text>'))->query('//span')->item(0);
    $unknown = designSystemDom(Blade::render('<x-artisanpack-text as="h1" size="huge" tone="red" weight="black">X</x-artisanpack-text>'))->query('//p')->item(0);

    expect($span->getAttribute('data-size'))->toBe('lead')
        ->and($span->getAttribute('data-tone'))->toBe('error')
        ->and($span->getAttribute('data-weight'))->toBe('semibold')
        ->and($unknown->getAttribute('data-size'))->toBe('md')
        ->and($unknown->getAttribute('data-tone'))->toBe('default')
        ->and($unknown->getAttribute('data-weight'))->toBe('regular');
});

test('truncate copies plain text to title', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-text truncate>ARK Genomic &amp; Co</x-artisanpack-text>'))->query('//p')->item(0);

    expect($root->hasAttribute('data-truncate'))->toBeTrue()
        ->and($root->getAttribute('title'))->toBe('ARK Genomic & Co');
});

test('truncate keeps an explicit title and skips markup', function (): void {
    $titled = designSystemDom(Blade::render('<x-artisanpack-text truncate title="Full">Short</x-artisanpack-text>'))->query('//p')->item(0);
    $markup = designSystemDom(Blade::render('<x-artisanpack-text truncate><b>Bold</b></x-artisanpack-text>'))->query('//p')->item(0);

    expect($titled->getAttribute('title'))->toBe('Full')
        ->and($markup->hasAttribute('title'))->toBeFalse();
});

test('clamp sets data-clamp and the line count', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-text :clamp="3" style="color: red">Long</x-artisanpack-text>'))->query('//p')->item(0);
    $zero = designSystemDom(Blade::render('<x-artisanpack-text :clamp="0">Long</x-artisanpack-text>'))->query('//p')->item(0);

    expect($root->getAttribute('data-clamp'))->toBe('3')
        ->and($root->getAttribute('style'))->toContain('--apui-text-clamp: 3')
        ->and($root->getAttribute('style'))->toContain('color: red')
        ->and($zero->hasAttribute('data-clamp'))->toBeFalse();
});
