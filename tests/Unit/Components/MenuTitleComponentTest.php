<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * MenuTitle (#56): a section title between items.
 */

test('renders the slot, label or the deprecated title as a title item', function (): void {
    foreach ([
        '<x-artisanpack-menu-title>Settings</x-artisanpack-menu-title>',
        '<x-artisanpack-menu-title label="Settings" />',
        '<x-artisanpack-menu-title title="Settings" />',
    ] as $title) {
        $html = Blade::render('<x-artisanpack-menu id="m">'.$title.'</x-artisanpack-menu>');
        $li   = designSystemDom($html)->query('//li[@data-scope="menu"][@data-part="title"]')->item(0);

        expect(trim($li->textContent))->toBe('Settings');
    }
});

test('escapes the label', function (): void {
    expect(Blade::render('<x-artisanpack-menu-title label="<i>x</i>" />'))->toContain('&lt;i&gt;x&lt;/i&gt;');
});
