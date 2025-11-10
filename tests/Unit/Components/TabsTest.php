<?php

declare(strict_types=1);

use ArtisanPack\LivewireUiComponents\View\Components\Tabs;

describe('Tabs Component', function () {
    describe('Orientation Detection', function () {
        test('defaults to horizontal orientation', function () {
            $component = new Tabs();
            
            expect($component->orientation)->toBe('horizontal');
            expect($component->isVertical())->toBeFalse();
            expect($component->isVerticalRight())->toBeFalse();
        });

        test('detects vertical-left orientation', function () {
            $component = new Tabs(orientation: 'vertical-left');
            
            expect($component->orientation)->toBe('vertical-left');
            expect($component->isVertical())->toBeTrue();
            expect($component->isVerticalRight())->toBeFalse();
        });

        test('detects vertical-right orientation', function () {
            $component = new Tabs(orientation: 'vertical-right');
            
            expect($component->orientation)->toBe('vertical-right');
            expect($component->isVertical())->toBeTrue();
            expect($component->isVerticalRight())->toBeTrue();
        });

        test('handles invalid orientation gracefully', function () {
            $component = new Tabs(orientation: 'invalid');
            
            expect($component->orientation)->toBe('invalid');
            expect($component->isVertical())->toBeFalse();
            expect($component->isVerticalRight())->toBeFalse();
        });
    });

    describe('Container Classes', function () {
        test('returns horizontal classes for horizontal orientation', function () {
            $component = new Tabs();
            
            expect($component->getTabsContainerClass())
                ->toBe('relative w-full');
        });

        test('returns vertical classes for vertical-left orientation', function () {
            $component = new Tabs(orientation: 'vertical-left');
            
            expect($component->getTabsContainerClass())
                ->toBe('relative w-full flex flex-col md:flex-row');
        });

        test('returns vertical classes for vertical-right orientation', function () {
            $component = new Tabs(orientation: 'vertical-right');
            
            expect($component->getTabsContainerClass())
                ->toBe('relative w-full flex flex-col md:flex-row');
        });
    });

    describe('Label Div Classes', function () {
        test('returns horizontal label div classes for horizontal orientation', function () {
            $component = new Tabs();
            
            expect($component->getLabelDivClass())
                ->toBe('border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto');
        });

        test('returns vertical-left label div classes for vertical-left orientation', function () {
            $component = new Tabs(orientation: 'vertical-left');
            
            expect($component->getLabelDivClass())
                ->toBe('border-r-[length:var(--border)] border-r-base-content/10 flex flex-col overflow-y-auto min-w-48');
        });

        test('returns vertical-right label div classes for vertical-right orientation', function () {
            $component = new Tabs(orientation: 'vertical-right');
            
            expect($component->getLabelDivClass())
                ->toBe('border-l-[length:var(--border)] border-l-base-content/10 flex flex-col overflow-y-auto min-w-48');
        });
    });

    describe('Label Classes', function () {
        test('returns horizontal label classes for horizontal orientation', function () {
            $component = new Tabs();
            
            expect($component->getFinalLabelClass())
                ->toBe('font-semibold pb-1');
        });

        test('returns vertical label classes for vertical orientations', function () {
            $componentLeft = new Tabs(orientation: 'vertical-left');
            $componentRight = new Tabs(orientation: 'vertical-right');
            
            expect($componentLeft->getFinalLabelClass())
                ->toBe('font-semibold w-full px-3 py-2 md:pr-1 md:pl-1 md:py-2');
            
            expect($componentRight->getFinalLabelClass())
                ->toBe('font-semibold w-full px-3 py-2 md:pr-1 md:pl-1 md:py-2');
        });
    });

    describe('Active Classes', function () {
        test('returns horizontal active classes for horizontal orientation', function () {
            $component = new Tabs();
            
            expect($component->getActiveClass())
                ->toBe('border-b-[length:var(--border)] border-b-base-content/50');
        });

        test('returns vertical-left active classes for vertical-left orientation', function () {
            $component = new Tabs(orientation: 'vertical-left');
            
            expect($component->getActiveClass())
                ->toBe('border-r-[length:var(--border)] border-r-base-content/50');
        });

        test('returns vertical-right active classes for vertical-right orientation', function () {
            $component = new Tabs(orientation: 'vertical-right');
            
            expect($component->getActiveClass())
                ->toBe('border-l-[length:var(--border)] border-l-base-content/50');
        });
    });

    describe('Custom Class Overrides', function () {
        test('allows custom vertical classes to be overridden', function () {
            $component = new Tabs(
                orientation: 'vertical-left',
                verticalTabsClass: 'custom-container',
                verticalLabelClass: 'custom-label',
                verticalActiveClass: 'custom-active',
                verticalLabelDivClass: 'custom-label-div'
            );
            
            expect($component->getTabsContainerClass())->toBe('custom-container');
            expect($component->getFinalLabelClass())->toBe('custom-label');
            expect($component->getActiveClass())->toBe('custom-active');
            expect($component->getLabelDivClass())->toBe('custom-label-div');
        });

        test('allows custom vertical-right classes to be overridden', function () {
            $component = new Tabs(
                orientation: 'vertical-right',
                verticalRightActiveClass: 'custom-right-active',
                verticalRightLabelDivClass: 'custom-right-label-div'
            );
            
            expect($component->getActiveClass())->toBe('custom-right-active');
            expect($component->getLabelDivClass())->toBe('custom-right-label-div');
        });
    });

    describe('UUID Generation', function () {
        test('generates unique UUID for each instance', function () {
            $component1 = new Tabs(id: 'test1');
            $component2 = new Tabs(id: 'test2');
            
            expect($component1->uuid)->not->toBe($component2->uuid);
            expect($component1->uuid)->toContain('test1');
            expect($component2->uuid)->toContain('test2');
        });
    });

    describe('Backward Compatibility', function () {
        test('maintains all existing properties and defaults', function () {
            $component = new Tabs();
            
            expect($component->id)->toBeNull();
            expect($component->selected)->toBeNull();
            expect($component->labelClass)->toBe('font-semibold pb-1');
            expect($component->activeClass)->toBe('border-b-[length:var(--border)] border-b-base-content/50');
            expect($component->labelDivClass)->toBe('border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto');
            expect($component->tabsClass)->toBe('relative w-full');
        });

        test('existing horizontal behavior remains unchanged', function () {
            $component = new Tabs(
                id: 'test',
                selected: 'tab1',
                labelClass: 'custom-label',
                activeClass: 'custom-active',
                labelDivClass: 'custom-label-div',
                tabsClass: 'custom-tabs'
            );
            
            expect($component->getTabsContainerClass())->toBe('custom-tabs');
            expect($component->getFinalLabelClass())->toBe('custom-label');
            expect($component->getActiveClass())->toBe('custom-active');
            expect($component->getLabelDivClass())->toBe('custom-label-div');
        });
    });
});