<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * profile (#58) is a deprecated alias of user-menu: it is an avatar dropdown,
 * not a profile page.
 */

test('renders the user menu from the 2.x props', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-profile title="Olivia Rhye" subtitle="Admin" image="/o.png" right :portal="false" />'));

    expect($dom->query('//div[contains(@class, "apui-user-menu")][@data-align="end"]'))->toHaveCount(1)
        ->and(trim($dom->query('//button[@data-part="trigger"]//*[@data-part="name"]')->item(0)->textContent))->toBe('Olivia Rhye')
        ->and(trim($dom->query('//button[@data-part="trigger"]//*[@data-part="role"]')->item(0)->textContent))->toBe('Admin')
        ->and($dom->query('//button[@data-part="trigger"]//img[@src="/o.png"]'))->toHaveCount(1)
        ->and($dom->query('//*[@data-part="theme"]'))->toHaveCount(0);
});
