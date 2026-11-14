<?php

declare(strict_types=1);

use ArtisanPackUI\Hooks\Facades\Filter;
use Illuminate\Support\Facades\Blade;

test('built-in components pass their classes through the componentClasses filter', function (string $blade, string $name, string $element): void {
    $seen = [];

    Filter::add(
        'ap.livewireUiComponents.componentClasses',
        function (array $classes, string $component) use (&$seen, $name) {
            $seen[] = $component;

            return $name === $component ? array_merge($classes, ['hooked-'.strtolower($name)]) : $classes;
        },
    );

    $html = Blade::render($blade);

    expect($seen)->toContain($name)
        ->and($html)->toMatch('/<'.$element.'[^>]*class="[^"]*hooked-'.strtolower($name).'/');
})->with([
    'button'         => ['<x-artisanpack-button>Save</x-artisanpack-button>', 'Button', 'button'],
    'card'           => ['<x-artisanpack-card title="Plan" />', 'Card', 'div'],
    'skeleton'       => ['<x-artisanpack-skeleton />', 'Skeleton', 'span'],
    'accordion item' => ['<x-artisanpack-accordion><x-artisanpack-accordion-item title="One">Body</x-artisanpack-accordion-item></x-artisanpack-accordion>', 'AccordionItem', 'div'],
    'menu item'      => ['<x-artisanpack-menu label="Main"><x-artisanpack-menu-item label="Home" href="/" /></x-artisanpack-menu>', 'MenuItem', 'a'],
    'menu title'     => ['<x-artisanpack-menu label="Main"><x-artisanpack-menu-title title="Section" /></x-artisanpack-menu>', 'MenuTitle', 'li'],
    'navbar item'    => ['<x-artisanpack-navbar label="Site"><x-artisanpack-navbar-item href="/">Home</x-artisanpack-navbar-item></x-artisanpack-navbar>', 'NavbarItem', 'a'],
    'timeline item'  => ['<x-artisanpack-timeline><x-artisanpack-timeline-item title="Shipped" /></x-artisanpack-timeline>', 'TimelineItem', 'li'],
    'tab'            => ['<x-artisanpack-tabs><x-artisanpack-tab name="one" label="One">Body</x-artisanpack-tab></x-artisanpack-tabs>', 'Tab', 'div'],
    'step'           => ['<x-artisanpack-steps><x-artisanpack-step label="One" /></x-artisanpack-steps>', 'Step', 'li'],
    'dropdown item'  => ['<x-artisanpack-dropdown-menu label="Options"><x-artisanpack-dropdown-menu-item value="edit">Edit</x-artisanpack-dropdown-menu-item></x-artisanpack-dropdown-menu>', 'DropdownMenuItem', 'div'],
]);

test('a child component without part classes renders no empty class attribute', function (): void {
    $html = Blade::render('<x-artisanpack-menu label="Main"><x-artisanpack-menu-item label="Home" href="/" /></x-artisanpack-menu>');

    expect($html)->not->toContain('class=""');
});
