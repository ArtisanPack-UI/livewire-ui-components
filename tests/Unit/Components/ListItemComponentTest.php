<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;

/*
 * ListItem and its List (#58): the design-system markup contract. The
 * manifest parity check is in tests/Feature/DesignSystemParityTest.php.
 */

test('renders a div row with the title and description on its own', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-list-item title="Olivia Rhye" description="olivia@company.com" />'));
    $root = $dom->query('//div[contains(@class, "apui-list-item")]')->item(0);

    expect($root)->not->toBeNull()
        ->and($root->hasAttribute('data-interactive'))->toBeFalse()
        ->and(trim($dom->query('//span[@data-part="title"]')->item(0)->textContent))->toBe('Olivia Rhye')
        ->and(trim($dom->query('//span[@data-part="description"]')->item(0)->textContent))->toBe('olivia@company.com');
});

test('rows in a list render as li with the variant on the list', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-list variant="bordered"><x-artisanpack-list-item title="A" /><x-artisanpack-list-item title="B" /></x-artisanpack-list>'));

    expect($dom->query('//ul[contains(@class, "apui-list")][@data-variant="bordered"]/li[contains(@class, "apui-list-item")]'))->toHaveCount(2);
});

test('a row after a list is a div again, and an unknown variant falls back', function (): void {
    $dom = designSystemDom(Blade::render('<x-artisanpack-list variant="zebra" ordered><x-artisanpack-list-item title="A" /></x-artisanpack-list><x-artisanpack-list-item title="B" />'));

    expect($dom->query('//ol[@data-variant="plain"]/li'))->toHaveCount(1)
        ->and($dom->query('//div[contains(@class, "apui-list-item")]'))->toHaveCount(1);
});

test('href makes the title a link covering the row, with wire:navigate', function (): void {
    $dom   = designSystemDom(Blade::render('<x-artisanpack-list-item title="Olivia" href="/members/1" selected />'));
    $root  = $dom->query('//div[contains(@class, "apui-list-item")]')->item(0);
    $title = $dom->query('//a[@data-part="title"]')->item(0);

    expect($root->hasAttribute('data-interactive'))->toBeTrue()
        ->and($root->hasAttribute('data-selected'))->toBeTrue()
        ->and($title->getAttribute('href'))->toBe('/members/1')
        ->and($title->getAttribute('aria-current'))->toBe('true')
        ->and($title->hasAttribute('wire:navigate'))->toBeTrue();
});

test('navigate off and unsafe hrefs', function (): void {
    $off    = designSystemDom(Blade::render('<x-artisanpack-list-item title="A" href="/a" :navigate="false" />'))->query('//a')->item(0);
    $unsafe = Blade::render('<x-artisanpack-list-item title="A" href="javascript:alert(1)" />');

    expect($off->hasAttribute('wire:navigate'))->toBeFalse()
        ->and($unsafe)->not->toContain('javascript:')
        ->and(designSystemDom($unsafe)->query('//span[@data-part="title"]'))->toHaveCount(1);
});

test('interactive makes the title a button and keeps wire:click on the row', function (): void {
    $dom  = designSystemDom(Blade::render('<x-artisanpack-list-item title="Offer" interactive wire:click="open( 1 )" />'));
    $root = $dom->query('//div[contains(@class, "apui-list-item")]')->item(0);

    expect($root->getAttribute('wire:click'))->toBe('open( 1 )')
        ->and($dom->query('//button[@data-part="title"][@type="button"]'))->toHaveCount(1);
});

test('disabled rows disable their control', function (): void {
    $button = designSystemDom(Blade::render('<x-artisanpack-list-item title="A" interactive disabled />'));
    $link   = designSystemDom(Blade::render('<x-artisanpack-list-item title="A" href="/a" disabled />'))->query('//a')->item(0);

    expect($button->query('//div[@data-disabled]/button[@disabled]'))->toHaveCount(1)
        ->and($link->hasAttribute('href'))->toBeFalse()
        ->and($link->getAttribute('aria-disabled'))->toBe('true')
        ->and($link->hasAttribute('wire:navigate'))->toBeFalse();
});

test('leading icon, trailing text and slots fill the parts', function (): void {
    $icon  = designSystemDom(Blade::render('<x-artisanpack-list-item title="A" leading-icon="mail" trailing="2m" />'));
    $slots = designSystemDom(Blade::render('<x-artisanpack-list-item title="A" trailing="x"><x-slot:leading><i>L</i></x-slot:leading><x-slot:trailing><b>T</b></x-slot:trailing></x-artisanpack-list-item>'));

    expect($icon->query('//*[@data-part="leading"]/svg'))->toHaveCount(1)
        ->and(trim($icon->query('//*[@data-part="trailing"]')->item(0)->textContent))->toBe('2m')
        ->and($slots->query('//*[@data-part="leading"]/i'))->toHaveCount(1)
        ->and($slots->query('//*[@data-part="trailing"]/b'))->toHaveCount(1);
});

test('the deprecated item attributes fill the row', function (): void {
    $html = Blade::render('<x-artisanpack-list-item :item="$user" value="name" sub-value="email" />', [
        'user' => ['name' => 'Olivia Rhye', 'email' => 'olivia@company.com', 'avatar' => '/o.png'],
    ]);
    $dom = designSystemDom($html);

    expect(trim($dom->query('//*[@data-part="title"]')->item(0)->textContent))->toBe('Olivia Rhye')
        ->and(trim($dom->query('//*[@data-part="description"]')->item(0)->textContent))->toBe('olivia@company.com')
        ->and($dom->query('//*[@data-part="leading"]//img[@src="/o.png"]'))->toHaveCount(1)
        ->and($dom->query('//*[@item or @value or @sub-value]'))->toHaveCount(0);
});
