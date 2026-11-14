<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\AppShell;
use ArtisanPack\LivewireUiComponents\View\Components\Main;
use Illuminate\Support\Facades\Blade;

/*
 * Main (deprecated 3.0.0): the 2.x frame, rendered by the design-system
 * AppShell (#58).
 */

test('is an AppShell', function (): void {
    expect(new Main)->toBeInstanceOf(AppShell::class);
});

test('renders the 2.x slots on the AppShell', function (): void {
    $html = Blade::render(<<<'BLADE'
        <x-artisanpack-main>
            <x-slot:sidebar collapsible collapse-text="Fold">Nav</x-slot:sidebar>
            <x-slot:content>Body</x-slot:content>
            <x-slot:footer>Copyright</x-slot:footer>
        </x-artisanpack-main>
        BLADE);
    $dom = designSystemDom($html);

    expect($dom->query('//div[contains(@class, "apui-app-shell")]')->length)->toBe(1)
        ->and(trim($dom->query('//aside')->item(0)->firstChild->textContent))->toBe('Nav')
        ->and($dom->query('//button[@data-part="collapse-trigger"]')->item(0)->getAttribute('aria-label'))->toBe('Fold')
        ->and(trim($dom->query('//*[@data-part="content"]')->item(0)->firstChild->textContent))->toBe('Body')
        ->and($dom->query('//*[@data-part="content"]/footer')->item(0)->textContent)->toBe('Copyright')
        ->and($html)->not->toContain('drawer');
});
