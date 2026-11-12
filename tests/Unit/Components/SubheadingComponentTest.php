<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * subheading (#58) is a deprecated alias of text with size lead: it was a
 * paragraph under a heading, not a heading.
 */

test('renders the text markup with size lead', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-subheading>Track every listing.</x-artisanpack-subheading>'))->query('//p[contains(@class, "apui-text")]')->item(0);

    expect($root)->not->toBeNull()
        ->and($root->getAttribute('data-size'))->toBe('lead')
        ->and($root->getAttribute('data-tone'))->toBe('default')
        ->and(trim($root->textContent))->toBe('Track every listing.');
});

test('takes the text tone and weight', function (): void {
    $root = designSystemDom(Blade::render('<x-artisanpack-subheading tone="muted" weight="medium">Hi</x-artisanpack-subheading>'))->query('//p')->item(0);

    expect($root->getAttribute('data-tone'))->toBe('muted')
        ->and($root->getAttribute('data-weight'))->toBe('medium');
});
