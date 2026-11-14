<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * UserMenu (#58): Avatar + DropdownMenu. The manifest parity check is in
 * tests/Feature/DesignSystemParityTest.php.
 */

function userMenuDom(string $attributes = '', string $slot = ''): DOMXPath
{
    return designSystemDom(Blade::render(
        '<x-artisanpack-user-menu :user="$user" :portal="false" '.$attributes.'>'.$slot.'</x-artisanpack-user-menu>',
        ['user' => ['name' => 'Olivia Rhye', 'email' => 'olivia@company.com', 'role' => 'Admin · Apexify', 'status' => 'online']],
    ));
}

test('the trigger is a menu button with the avatar, name and role', function (): void {
    $dom     = userMenuDom();
    $trigger = $dom->query('//button[@data-scope="user-menu"][@data-part="trigger"]')->item(0);

    expect($trigger->getAttribute('aria-haspopup'))->toBe('menu')
        ->and($trigger->getAttribute('aria-expanded'))->toBe('false')
        ->and($trigger->hasAttribute('x-apui:trigger'))->toBeTrue()
        ->and(trim($dom->query('.//*[@data-part="name"]', $trigger)->item(0)->textContent))->toBe('Olivia Rhye')
        ->and(trim($dom->query('.//*[@data-part="role"]', $trigger)->item(0)->textContent))->toBe('Admin · Apexify')
        ->and($dom->query('.//*[@data-part="indicator"]/svg', $trigger))->toHaveCount(1);
});

test('the avatars inside are decorative but keep the initials and status', function (): void {
    $avatar = userMenuDom()->query('//button[@data-part="trigger"]/span[contains(@class, "apui-avatar")]')->item(0);

    expect($avatar->getAttribute('aria-hidden'))->toBe('true')
        ->and($avatar->getAttribute('data-part'))->toBe('avatar')
        ->and($avatar->getAttribute('data-size'))->toBe('sm')
        ->and(trim($avatar->textContent))->toBe('OR');
});

test('compact keeps only the avatar and names the trigger', function (): void {
    $trigger = userMenuDom('compact')->query('//button[@data-part="trigger"]')->item(0);

    expect($trigger->getAttribute('aria-label'))->toBe('Olivia Rhye')
        ->and($trigger->hasAttribute('data-avatar-only'))->toBeTrue();
});

test('the panel is a dropdown menu with the header, theme control and log out last', function (): void {
    $dom   = userMenuDom('open');
    $panel = $dom->query('//div[contains(@class, "apui-dropdown-menu")][contains(@class, "apui-user-menu")]')->item(0);
    $items = $dom->query('.//*[@data-scope="dropdown-menu"][@data-part="item"]', $panel);

    expect($panel->getAttribute('role'))->toBe('menu')
        ->and($panel->hasAttribute('hidden'))->toBeFalse()
        ->and(trim($dom->query('.//*[@data-part="header"]//*[@data-part="email"]', $panel)->item(0)->textContent))->toBe('olivia@company.com')
        ->and($dom->query('.//*[@data-part="theme-options"]/*[@role="menuitemradio"]', $panel))->toHaveCount(3)
        ->and($dom->query('.//*[@data-part="theme-options"]/*[@aria-checked="true"]', $panel)->item(0)->getAttribute('data-value'))->toBe('theme:system')
        ->and($items->item($items->length - 1)->getAttribute('data-value'))->toBe('logout');
});

test('workspaces render as a radio group with the current one checked', function (): void {
    $dom = userMenuDom(':workspaces="[ [ \'id\' => \'a\', \'name\' => \'Apexify\' ], [ \'id\' => \'h\', \'name\' => \'Horizon\' ] ]" workspace="h"');

    expect($dom->query('//*[@role="group"]/*[@role="menuitemradio"][starts-with(@data-value, "workspace:")]'))->toHaveCount(2)
        ->and($dom->query('//*[@data-value="workspace:h"][@aria-checked="true"]'))->toHaveCount(1);
});

test('one workspace, or hide-theme, leaves those groups out', function (): void {
    $dom = userMenuDom(':workspaces="[ [ \'id\' => \'a\', \'name\' => \'Apexify\' ] ]" hide-theme');

    expect($dom->query('//*[starts-with(@data-value, "workspace:")]'))->toHaveCount(0)
        ->and($dom->query('//*[@data-part="theme"]'))->toHaveCount(0);
});

test('items and composed children render before log out', function (): void {
    $dom    = userMenuDom(':items="[ [ \'value\' => \'profile\', \'label\' => \'Profile\', \'icon\' => \'user\' ] ]"', '<x-artisanpack-dropdown-menu-item value="billing" label="Billing" />');
    $values = array_map(static fn (DOMElement $item): string => $item->getAttribute('data-value'), iterator_to_array($dom->query('//*[@role="menuitem"]')));

    expect($values)->toBe(['profile', 'billing', 'logout']);
});

test('the labels are translatable', function (): void {
    app('translator')->addLines(['*.Log out' => 'Abmelden', '*.Theme' => 'Design', '*.Dark' => 'Dunkel'], 'de');
    app()->setLocale('de');

    $html = Blade::render('<x-artisanpack-user-menu :user="[ \'name\' => \'A\' ]" :portal="false" />');

    expect($html)->toContain('Abmelden')->toContain('Design')->toContain('Dunkel');
});

test('logout-action posts a form with the CSRF token', function (): void {
    $dom = userMenuDom('logout-action="/logout"');

    expect($dom->query('//form[@method="POST"][@action="/logout"]/input[@name="_token"]'))->toHaveCount(1);
});

test('attributes land on the event host and class on the panel', function (): void {
    $dom = userMenuDom('wire:select="go" class="x"');

    expect(Blade::render('<x-artisanpack-user-menu :user="[ \'name\' => \'A\' ]" wire:select="go" />'))->toMatch('/<div wire:select="go"[^>]*>\s*<div\s+id="[^"]+"\s+x-data="apuiDropdownMenu/')
        ->and($dom->query('//div[contains(@class, "apui-user-menu")][contains(@class, "x")]'))->toHaveCount(1);
});
