<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Tabs;
use Illuminate\Support\Facades\Blade;

/*
 * Tabs (#56): the design-system markup contract. The manifest parity check
 * is in tests/Feature/DesignSystemParityTest.php.
 */

function tabsComposition(string $attributes = 'id="account"'): string
{
    return Blade::render(<<<BLADE
        <x-artisanpack-tabs {$attributes}>
            <x-artisanpack-tab value="profile" label="Profile" icon="user">Profile panel</x-artisanpack-tab>
            <x-artisanpack-tab value="inbox" label="Inbox" count="4">Inbox panel</x-artisanpack-tab>
            <x-artisanpack-tab value="archived" label="Archived" disabled>Archived panel</x-artisanpack-tab>
        </x-artisanpack-tabs>
        BLADE);
}

function tabsData(): array
{
    return [
        ['value' => 'profile', 'label' => 'Profile', 'icon' => 'user', 'content' => 'Profile panel'],
        ['value' => 'inbox', 'label' => 'Inbox', 'count' => 4, 'content' => 'Inbox panel'],
        ['value' => 'archived', 'label' => 'Archived', 'disabled' => true, 'content' => 'Archived panel'],
    ];
}

/** @return list<string> */
function tabsParts(string $html): array
{
    $parts = [];
    foreach (designSystemDom($html)->query('//*[@data-scope="tabs"]') as $element) {
        $parts[] = $element->getAttribute('data-part').($element->hasAttribute('id') ? '#'.$element->getAttribute('id') : '');
    }

    return $parts;
}

test('renders the tablist before the panels, with stable ids per value', function (): void {
    expect(tabsParts(tabsComposition()))->toBe([
        'root#account',
        'list#account-list',
        'trigger#account-trigger-profile',
        'icon',
        'trigger#account-trigger-inbox',
        'count',
        'trigger#account-trigger-archived',
        'content#account-content-profile',
        'content#account-content-inbox',
        'content#account-content-archived',
    ]);
});

test('renders tab ARIA and selects the first enabled tab by default', function (): void {
    $dom = designSystemDom(tabsComposition());

    expect($dom->query('//*[@data-part="list"]')->item(0)->getAttribute('role'))->toBe('tablist');
    $profile = $dom->query('//*[@id="account-trigger-profile"]')->item(0);
    expect($profile->getAttribute('role'))->toBe('tab')
        ->and($profile->getAttribute('aria-selected'))->toBe('true')
        ->and($profile->getAttribute('tabindex'))->toBe('0')
        ->and($profile->getAttribute('aria-controls'))->toBe('account-content-profile');
    expect($dom->query('//*[@id="account-trigger-inbox"]')->item(0)->getAttribute('tabindex'))->toBe('-1');
    expect($dom->query('//*[@id="account-trigger-archived"]')->item(0)->hasAttribute('disabled'))->toBeTrue();

    $panel = $dom->query('//*[@id="account-content-profile"]')->item(0);
    expect($panel->getAttribute('role'))->toBe('tabpanel')
        ->and($panel->getAttribute('aria-labelledby'))->toBe('account-trigger-profile')
        ->and($panel->hasAttribute('hidden'))->toBeFalse();
    expect($dom->query('//*[@id="account-content-inbox"]')->item(0)->hasAttribute('hidden'))->toBeTrue();
});

test('renders the same markup from tabs as from composition', function (): void {
    $data      = Blade::render('<x-artisanpack-tabs id="account" :tabs="$tabs" />', ['tabs' => tabsData()]);
    $normalize = static fn (string $html): string => (string) preg_replace('/\s+/', ' ', trim($html));

    expect(tabsParts($data))->toBe(tabsParts(tabsComposition()));
    expect($normalize(strip_tags($data)))->toBe($normalize(strip_tags(tabsComposition())));
});

test('selects value and emits variant, size, orientation and activation mode', function (): void {
    $html = tabsComposition('id="account" value="inbox" variant="underline" size="sm" orientation="vertical" activation-mode="manual" label="Account"');
    $dom  = designSystemDom($html);
    $root = $dom->query('//*[contains(@class, "apui-tabs")]')->item(0);

    expect($root->getAttribute('data-variant'))->toBe('underline')
        ->and($root->getAttribute('data-size'))->toBe('sm')
        ->and($root->getAttribute('data-orientation'))->toBe('vertical')
        ->and($root->getAttribute('data-activation-mode'))->toBe('manual')
        ->and($root->getAttribute('x-data'))->toContain("value: 'inbox'")
        ->and($root->getAttribute('x-data'))->toContain("activationMode: 'manual'");
    expect($dom->query('//*[@data-part="list"]')->item(0)->getAttribute('aria-label'))->toBe('Account');
    expect($dom->query('//*[@data-part="list"]')->item(0)->getAttribute('aria-orientation'))->toBe('vertical');
    expect($dom->query('//*[@id="account-trigger-inbox"]')->item(0)->getAttribute('aria-selected'))->toBe('true');
});

test('falls back to the defaults for unknown values', function (): void {
    $tabs = new Tabs(variant: 'bordered', size: 'xl', orientation: 'diagonal', activationMode: 'later');

    expect($tabs->variant)->toBe('segmented')
        ->and($tabs->size)->toBe('md')
        ->and($tabs->orientation)->toBe('horizontal')
        ->and($tabs->activationMode)->toBe('automatic');
    $tabs->finishRendering();
});

test('entangles wire:model, live with wire:model.live', function (): void {
    $html = tabsComposition('id="account" wire:model.live="section"');
    $root = designSystemDom($html)->query('//*[contains(@class, "apui-tabs")]')->item(0);

    expect($root->getAttribute('x-data'))->toContain('$wire.entangle(\'section\').live')
        ->and($root->hasAttribute('wire:model.live'))->toBeFalse();
});

test('applies classes per part and class to the root', function (): void {
    $html = tabsComposition('id="account" class="mb-4" :classes="[\'list\' => \'my-list\', \'trigger\' => \'my-trigger\', \'content\' => \'my-panel\']"');
    $dom  = designSystemDom($html);

    expect($dom->query('//*[contains(@class, "apui-tabs") and contains(@class, "mb-4")]')->length)->toBe(1)
        ->and($dom->query('//*[@data-part="list" and @class="my-list"]')->length)->toBe(1)
        ->and($dom->query('//*[@data-part="trigger" and @class="my-trigger"]')->length)->toBe(3)
        ->and($dom->query('//*[@data-part="content" and contains(@class, "my-panel")]')->length)->toBe(3);
});

test('escapes labels and values', function (): void {
    $html = Blade::render('<x-artisanpack-tabs id="t"><x-artisanpack-tab value="a&quot;b" label="<b>x</b>">Panel</x-artisanpack-tab></x-artisanpack-tabs>');

    expect($html)->not->toContain('<b>x</b>')
        ->and($html)->toContain('&lt;b&gt;x&lt;/b&gt;');
});
