<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * MenuSeparator (#56): a rule hidden from assistive technology.
 */

test('renders a hidden separator item', function (): void {
    $html = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-separator /></x-artisanpack-menu>');
    $li   = designSystemDom($html)->query('//li[@data-part="separator"]')->item(0);

    expect($li->getAttribute('aria-hidden'))->toBe('true')
        ->and(trim($li->textContent))->toBe('');
});

test('follows the rule with a title for the deprecated title', function (): void {
    $html = Blade::render('<x-artisanpack-menu id="m"><x-artisanpack-menu-separator title="More" /></x-artisanpack-menu>');

    expect(trim(designSystemDom($html)->query('//li[@data-part="title"]')->item(0)->textContent))->toBe('More');
});
