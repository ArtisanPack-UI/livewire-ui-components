<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use ArtisanPack\LivewireUiComponents\View\Components\Tabs;
use ArtisanPack\LivewireUiComponents\View\Components\Tab;

describe('Vertical Tabs Feature Tests', function () {
    beforeEach(function () {
        // Mock the view paths for testing
        View::addNamespace('livewire-ui-components', resource_path('views'));
    });

    describe('Horizontal Tabs (Default)', function () {
        test('renders horizontal tabs correctly', function () {
            $html = Blade::render('
                <x-artisanpack-tabs>
                    <x-artisanpack-tab name="tab1" label="Tab 1">Content 1</x-artisanpack-tab>
                    <x-artisanpack-tab name="tab2" label="Tab 2">Content 2</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            expect($html)
                ->toContain('relative w-full')
                ->toContain('border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto')
                ->not->toContain('aria-orientation="vertical"')
                ->toContain('Content 1')
                ->toContain('Content 2');
        });

        test('maintains backward compatibility with existing props', function () {
            $html = Blade::render('
                <x-artisanpack-tabs 
                    id="test-tabs" 
                    selected="tab1"
                    label-class="custom-label"
                    active-class="custom-active"
                    tabs-class="custom-container">
                    <x-artisanpack-tab name="tab1" label="Tab 1">Content 1</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            expect($html)
                ->toContain('custom-container')
                ->toContain('custom-label')
                ->toContain('custom-active');
        });
    });

    describe('Vertical Left Tabs', function () {
        test('renders vertical-left tabs correctly', function () {
            $html = Blade::render('
                <x-artisanpack-tabs orientation="vertical-left">
                    <x-artisanpack-tab name="vl-tab1" label="Dashboard">Dashboard Content</x-artisanpack-tab>
                    <x-artisanpack-tab name="vl-tab2" label="Settings">Settings Content</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            expect($html)
                ->toContain('relative w-full flex flex-col md:flex-row')
                ->toContain('aria-orientation="vertical"')
                ->toContain('md:border-r-[length:var(--border)] md:border-r-base-content/10 md:flex-col')
                ->toContain('flex-1')
                ->toContain('Dashboard Content')
                ->toContain('Settings Content');
        });

        test('renders tabs before content in vertical-left layout', function () {
            $html = Blade::render('
                <x-artisanpack-tabs orientation="vertical-left">
                    <x-artisanpack-tab name="tab1" label="Tab 1">Content Area</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            // In vertical-left, tabs should come before content in DOM
            $tabsPosition = strpos($html, 'role="tablist"');
            $contentPosition = strpos($html, 'Content Area');
            
            expect($tabsPosition)->toBeLessThan($contentPosition);
        });

        test('supports custom vertical classes for vertical-left', function () {
            $html = Blade::render('
                <x-artisanpack-tabs 
                    orientation="vertical-left"
                    vertical-tabs-class="custom-vertical-container"
                    vertical-label-class="custom-vertical-label"
                    vertical-active-class="custom-vertical-active">
                    <x-artisanpack-tab name="tab1" label="Tab 1">Content</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            expect($html)
                ->toContain('custom-vertical-container')
                ->toContain('custom-vertical-label')
                ->toContain('custom-vertical-active');
        });
    });

    describe('Vertical Right Tabs', function () {
        test('renders vertical-right tabs correctly', function () {
            $html = Blade::render('
                <x-artisanpack-tabs orientation="vertical-right">
                    <x-artisanpack-tab name="vr-tab1" label="Messages">Messages Content</x-artisanpack-tab>
                    <x-artisanpack-tab name="vr-tab2" label="History">History Content</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            expect($html)
                ->toContain('relative w-full flex flex-col md:flex-row')
                ->toContain('aria-orientation="vertical"')
                ->toContain('md:border-l-[length:var(--border)] md:border-l-base-content/10 md:flex-col')
                ->toContain('flex-1')
                ->toContain('Messages Content')
                ->toContain('History Content');
        });

        test('renders content before tabs in vertical-right layout', function () {
            $html = Blade::render('
                <x-artisanpack-tabs orientation="vertical-right">
                    <x-artisanpack-tab name="tab1" label="Tab 1">Content Area</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            // In vertical-right, content should come before tabs in DOM
            $contentPosition = strpos($html, 'Content Area');
            $tabsPosition = strpos($html, 'role="tablist"');
            
            expect($contentPosition)->toBeLessThan($tabsPosition);
        });

        test('supports custom vertical-right classes', function () {
            $html = Blade::render('
                <x-artisanpack-tabs 
                    orientation="vertical-right"
                    vertical-right-active-class="custom-right-active"
                    vertical-right-label-div-class="custom-right-label-div">
                    <x-artisanpack-tab name="tab1" label="Tab 1">Content</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            expect($html)
                ->toContain('custom-right-active')
                ->toContain('custom-right-label-div');
        });
    });

    describe('Responsive Behavior', function () {
        test('includes responsive classes for mobile compatibility', function () {
            $html = Blade::render('
                <x-artisanpack-tabs orientation="vertical-left">
                    <x-artisanpack-tab name="tab1" label="Tab 1">Content</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            // Should include mobile-first responsive classes
            expect($html)
                ->toContain('flex flex-col md:flex-row') // Container responsive
                ->toContain('flex overflow-x-auto md:border-b-0 md:border-r-[length:var(--border)]') // Labels responsive
                ->toContain('px-3 py-2 md:pr-1 md:pl-1 md:py-2') // Label padding responsive
                ->toContain('pt-4 md:pt-0'); // Content padding responsive
        });
    });

    describe('Accessibility Features', function () {
        test('includes proper ARIA attributes for vertical tabs', function () {
            $htmlVerticalLeft = Blade::render('
                <x-artisanpack-tabs orientation="vertical-left">
                    <x-artisanpack-tab name="tab1" label="Tab 1">Content</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            $htmlVerticalRight = Blade::render('
                <x-artisanpack-tabs orientation="vertical-right">
                    <x-artisanpack-tab name="tab1" label="Tab 1">Content</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            expect($htmlVerticalLeft)->toContain('aria-orientation="vertical"');
            expect($htmlVerticalRight)->toContain('aria-orientation="vertical"');
        });

        test('does not include vertical ARIA for horizontal tabs', function () {
            $html = Blade::render('
                <x-artisanpack-tabs>
                    <x-artisanpack-tab name="tab1" label="Tab 1">Content</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            expect($html)->not->toContain('aria-orientation="vertical"');
        });

        test('maintains role attributes for all orientations', function () {
            $orientations = ['horizontal', 'vertical-left', 'vertical-right'];

            foreach ($orientations as $orientation) {
                $html = Blade::render('
                    <x-artisanpack-tabs orientation="' . $orientation . '">
                        <x-artisanpack-tab name="tab1" label="Tab 1">Content</x-artisanpack-tab>
                    </x-artisanpack-tabs>
                ');

                expect($html)
                    ->toContain('role="tablist"')
                    ->toContain('role="tab"')
                    ->toContain('role="tabpanel"');
            }
        });
    });

    describe('Alpine.js Integration', function () {
        test('maintains Alpine.js data structure for all orientations', function () {
            $orientations = ['horizontal', 'vertical-left', 'vertical-right'];

            foreach ($orientations as $orientation) {
                $html = Blade::render('
                    <x-artisanpack-tabs orientation="' . $orientation . '">
                        <x-artisanpack-tab name="tab1" label="Tab 1">Content</x-artisanpack-tab>
                    </x-artisanpack-tabs>
                ');

                expect($html)
                    ->toContain('x-data=')
                    ->toContain('tabs: []')
                    ->toContain('selected:')
                    ->toContain('x-for="tab in tabs"')
                    ->toContain('@click="tab.disabled ? null: selected = tab.name"')
                    ->toContain('x-show="selected === \'tab1\'"');
            }
        });
    });

    describe('Edge Cases', function () {
        test('handles empty tabs gracefully', function () {
            $html = Blade::render('<x-artisanpack-tabs orientation="vertical-left"></x-artisanpack-tabs>');

            expect($html)
                ->toContain('relative w-full flex flex-col md:flex-row')
                ->toContain('aria-orientation="vertical"')
                ->toContain('role="tablist"');
        });

        test('handles invalid orientation by defaulting to horizontal behavior', function () {
            $html = Blade::render('
                <x-artisanpack-tabs orientation="invalid">
                    <x-artisanpack-tab name="tab1" label="Tab 1">Content</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            expect($html)
                ->toContain('relative w-full') // Horizontal container class
                ->not->toContain('aria-orientation="vertical"')
                ->not->toContain('flex-col md:flex-row'); // Vertical container class
        });

        test('handles tabs with special characters and icons', function () {
            $html = Blade::render('
                <x-artisanpack-tabs orientation="vertical-left">
                    <x-artisanpack-tab name="special-tab" label="Tab & Special" icon="home">Special Content</x-artisanpack-tab>
                </x-artisanpack-tabs>
            ');

            expect($html)
                ->toContain('Special Content')
                ->toContain('Tab & Special');
        });
    });
});