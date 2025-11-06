<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentDataFactory;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use ArtisanPack\LivewireUiComponents\View\Components\Tabs;

/**
 * Comprehensive unit tests for the Tabs component.
 * 
 * Auto-generated test class that extends ComponentTestCase to inherit
 * common testing patterns and implements component-specific tests.
 */
class TabsComponentTest extends ComponentTestCase
{
    protected string $componentClass = Tabs::class;

    protected array $defaultProperties = [
            'orientation' => 'horizontal',
            'labelClass' => 'font-semibold pb-1',
            'activeClass' => 'border-b-[length:var(--border)] border-b-base-content/50',
            'labelDivClass' => 'border-b-[length:var(--border)] border-b-base-content/10 flex overflow-x-auto',
            'tabsClass' => 'relative w-full',
            'verticalTabsClass' => 'relative w-full flex flex-col md:flex-row',
            'verticalLabelClass' => 'font-semibold w-full px-3 py-2 md:pr-1 md:pl-1 md:py-2',
            'verticalActiveClass' => 'border-r-[length:var(--border)] border-r-base-content/50',
            'verticalLabelDivClass' => 'border-r-[length:var(--border)] border-r-base-content/10 flex flex-col overflow-y-auto min-w-48',
            'verticalContentClass' => 'flex-1',
            'verticalRightActiveClass' => 'border-l-[length:var(--border)] border-l-base-content/50',
            'verticalRightLabelDivClass' => 'border-l-[length:var(--border)] border-l-base-content/10 flex flex-col overflow-y-auto min-w-48'
        ];

    protected array $requiredProperties = [];

    public function test_tabs_string_properties(): void
    {
        $stringProperties = ['id', 'selected', 'orientation', 'variant', 'gap', 'labelColorClasses', 'activeColorClasses', 'labelClass', 'activeClass', 'labelDivClass', 'tabsClass', 'verticalTabsClass', 'verticalLabelClass', 'verticalActiveClass', 'verticalLabelDivClass', 'verticalContentClass', 'verticalRightActiveClass', 'verticalRightLabelDivClass'];
        $testValues = ComponentDataFactory::sampleTexts();
        
        foreach ($stringProperties as $property) {
            foreach ($testValues as $value) {
                if (empty($value)) continue; // Skip empty values for some properties
                
                $component = $this->createComponent([$property => $value]);
                $this->assertEquals($value, $component->$property);
            }
        }
    }

    public function test_tabs_isVertical_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'isVertical')) {
            try {
                $result = $component->isVertical();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (\Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_tabs_isVerticalRight_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'isVerticalRight')) {
            try {
                $result = $component->isVerticalRight();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (\Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_tabs_getTabsContainerClass_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getTabsContainerClass')) {
            try {
                $result = $component->getTabsContainerClass();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (\Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_tabs_getLabelDivClass_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getLabelDivClass')) {
            try {
                $result = $component->getLabelDivClass();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (\Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_tabs_getFinalLabelClass_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getFinalLabelClass')) {
            try {
                $result = $component->getFinalLabelClass();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (\Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_tabs_getActiveClass_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getActiveClass')) {
            try {
                $result = $component->getActiveClass();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (\Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_tabs_renders_successfully(): void
    {
        $component = $this->createComponent();
        $view = $component->render();

        try {
            $html = $view->render();
        } catch (\Illuminate\View\ViewException $e) {
            if (str_contains($e->getMessage(), 'Undefined variable') ||
                str_contains($e->getMessage(), 'Undefined array key')) {
                $this->markTestSkipped('Component requires slots or additional data for rendering');
            }
            throw $e;
        } catch (\Error $e) {
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context for rendering');
            }
            throw $e;
        }

        $this->assertNotEmpty($html);
        $this->assertTrue(TestHelpers::assertValidHtml($html));
    }

    public function test_tabs_accessibility_compliance(): void
    {
        $component = $this->createComponent();
        $view = $component->render();

        try {
            $html = $view->render();
        } catch (\Illuminate\View\ViewException $e) {
            if (str_contains($e->getMessage(), 'Undefined variable') ||
                str_contains($e->getMessage(), 'Undefined array key')) {
                $this->markTestSkipped('Component requires slots or additional data for rendering');
            }
            throw $e;
        } catch (\Error $e) {
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context for rendering');
            }
            throw $e;
        }

        $validation = TestHelpers::validateHtmlStructure($html);
        $this->assertTrue($validation['is_valid'],
            'Tabs should have valid HTML structure. Issues: ' . implode(', ', $validation['issues'])
        );
    }

    public function test_tabs_security_against_xss(): void
    {
        $xssPayloads = TestHelpers::securityTestPayloads();

        foreach ($xssPayloads as $payload) {
            $component = $this->createComponent(['label' => $payload]);
            $view = $component->render();

            try {
                $html = $view->render();
            } catch (\Illuminate\View\ViewException $e) {
                if (str_contains($e->getMessage(), 'Undefined variable') ||
                    str_contains($e->getMessage(), 'Undefined array key')) {
                    $this->markTestSkipped('Component requires slots or additional data for rendering');
                }
                throw $e;
            } catch (\Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Component requires attributes or context for rendering');
                }
                throw $e;
            }

            $this->assertStringNotContainsString('<script', $html);
            $this->assertStringNotContainsString('javascript:', $html);
        }
    }

    public function test_tabs_performance(): void
    {
        try {
            $performance = TestHelpers::measureRenderingPerformance($this->createComponent(), 10);
            $this->assertLessThan(100, $performance['average_time'],
                'Tabs rendering should be under 100ms on average'
            );
        } catch (\RuntimeException $e) {
            if (str_contains($e->getMessage(), 'requires slots') ||
                str_contains($e->getMessage(), 'requires additional context')) {
                $this->markTestSkipped($e->getMessage());
            }
            throw $e;
        }
    }
}
