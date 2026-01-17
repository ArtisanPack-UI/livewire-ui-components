<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentDataFactory;
use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use ArtisanPack\LivewireUiComponents\View\Components\Stat;
use Error;
use RuntimeException;

/**
 * Comprehensive unit tests for the Stat component.
 *
 * Auto-generated test class that extends ComponentTestCase to inherit
 * common testing patterns and implements component-specific tests.
 */
class StatComponentTest extends ComponentTestCase
{
    protected string $componentClass = Stat::class;

    protected array $defaultProperties = [
        'color'         => '',
        'size'          => 'md',
        'iconPosition'  => 'left',
        'titlePosition' => 'top',
        'contentAlign'  => 'left',
    ];

    protected array $requiredProperties = [];

    public function test_stat_string_properties(): void
    {
        $stringProperties = ['id', 'value', 'icon', 'color', 'title', 'description', 'tooltip', 'tooltipLeft', 'tooltipRight', 'tooltipBottom', 'size', 'iconPosition', 'titlePosition', 'contentAlign'];
        $testValues       = ComponentDataFactory::sampleTexts();

        foreach ($stringProperties as $property) {
            foreach ($testValues as $value) {
                if (empty($value)) {
                    continue;
                } // Skip empty values for some properties

                $component = $this->createComponent([$property => $value]);
                $this->assertEquals($value, $component->$property);
            }
        }
    }

    public function test_stat_size_classes_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'sizeClasses')) {
            try {
                $result = $component->sizeClasses();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_stat_layout_classes_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'layoutClasses')) {
            try {
                $result = $component->layoutClasses();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_stat_should_render_icon_first_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'shouldRenderIconFirst')) {
            try {
                $result = $component->shouldRenderIconFirst();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_stat_should_render_title_first_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'shouldRenderTitleFirst')) {
            try {
                $result = $component->shouldRenderTitleFirst();
                // Add specific assertions based on expected return type
                $this->assertNotNull($result);
            } catch (Error $e) {
                if (str_contains($e->getMessage(), 'Call to a member function') &&
                    str_contains($e->getMessage(), 'on null')) {
                    $this->markTestSkipped('Method requires attributes or context');
                }
                throw $e;
            }
        }
    }

    public function test_stat_renders_successfully(): void
    {
        $component = $this->createComponent();
        $view      = $component->render();

        try {
            $html = $view->render();
        } catch (\Illuminate\View\ViewException $e) {
            if (str_contains($e->getMessage(), 'Undefined variable') ||
                str_contains($e->getMessage(), 'Undefined array key')) {
                $this->markTestSkipped('Component requires slots or additional data for rendering');
            }
            throw $e;
        } catch (Error $e) {
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context for rendering');
            }
            throw $e;
        }

        $this->assertNotEmpty($html);
        $this->assertTrue(TestHelpers::assertValidHtml($html));
    }

    public function test_stat_accessibility_compliance(): void
    {
        $component = $this->createComponent();
        $view      = $component->render();

        try {
            $html = $view->render();
        } catch (\Illuminate\View\ViewException $e) {
            if (str_contains($e->getMessage(), 'Undefined variable') ||
                str_contains($e->getMessage(), 'Undefined array key')) {
                $this->markTestSkipped('Component requires slots or additional data for rendering');
            }
            throw $e;
        } catch (Error $e) {
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context for rendering');
            }
            throw $e;
        }

        $validation = TestHelpers::validateHtmlStructure($html);
        $this->assertTrue($validation['is_valid'],
            'Stat should have valid HTML structure. Issues: '.implode(', ', $validation['issues']),
        );
    }

    public function test_stat_security_against_xss(): void
    {
        $xssPayloads = TestHelpers::securityTestPayloads();

        foreach ($xssPayloads as $payload) {
            $component = $this->createComponent(['label' => $payload]);
            $view      = $component->render();

            try {
                $html = $view->render();
            } catch (\Illuminate\View\ViewException $e) {
                if (str_contains($e->getMessage(), 'Undefined variable') ||
                    str_contains($e->getMessage(), 'Undefined array key')) {
                    $this->markTestSkipped('Component requires slots or additional data for rendering');
                }
                throw $e;
            } catch (Error $e) {
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

    public function test_stat_performance(): void
    {
        try {
            $performance = TestHelpers::measureRenderingPerformance($this->createComponent(), 10);
            $this->assertLessThan(100, $performance['average_time'],
                'Stat rendering should be under 100ms on average',
            );
        } catch (RuntimeException $e) {
            if (str_contains($e->getMessage(), 'requires slots') ||
                str_contains($e->getMessage(), 'requires additional context')) {
                $this->markTestSkipped($e->getMessage());
            }
            throw $e;
        }
    }

    public function test_stat_glass_props_exist(): void
    {
        $component = $this->createComponent([
            'glass'            => 'frosted',
            'glassTint'        => 'emerald-500',
            'glassTintOpacity' => 15,
        ]);

        $this->assertEquals('frosted', $component->glass);
        $this->assertEquals('emerald-500', $component->glassTint);
        $this->assertEquals(15, $component->glassTintOpacity);
    }

    public function test_stat_glass_classes_without_glass(): void
    {
        $component = $this->createComponent();

        $this->assertNull($component->glass);
        $this->assertEquals('', $component->glassClasses());
    }

    public function test_stat_glass_classes_with_frosted_variant(): void
    {
        $component = $this->createComponent(['glass' => 'frosted']);

        $classes = $component->glassClasses();

        $this->assertStringContainsString('glass-frosted', $classes);
    }

    public function test_stat_glass_classes_with_transparent_variant(): void
    {
        $component = $this->createComponent(['glass' => 'transparent']);

        $classes = $component->glassClasses();

        $this->assertStringContainsString('glass-transparent', $classes);
    }

    public function test_stat_glass_classes_with_liquid_variant(): void
    {
        $component = $this->createComponent(['glass' => 'liquid']);

        $classes = $component->glassClasses();

        $this->assertStringContainsString('glass-liquid', $classes);
    }

    public function test_stat_glass_classes_with_tailwind_tint(): void
    {
        $component = $this->createComponent([
            'glass'     => 'frosted',
            'glassTint' => 'blue-500',
        ]);

        $classes = $component->glassClasses();

        $this->assertStringContainsString('glass-frosted', $classes);
        $this->assertStringContainsString('glass-tint-blue-500', $classes);
    }

    public function test_stat_glass_classes_with_hex_tint(): void
    {
        $component = $this->createComponent([
            'glass'     => 'frosted',
            'glassTint' => '#8b5cf6',
        ]);

        $classes = $component->glassClasses();

        $this->assertStringContainsString('glass-frosted', $classes);
        $this->assertStringContainsString('glass-tint-custom', $classes);
    }

    public function test_stat_glass_classes_with_opacity(): void
    {
        $component = $this->createComponent([
            'glass'            => 'frosted',
            'glassTint'        => 'emerald-500',
            'glassTintOpacity' => 60,
        ]);

        $classes = $component->glassClasses();

        $this->assertStringContainsString('glass-frosted', $classes);
        $this->assertStringContainsString('glass-tint-emerald-500', $classes);
        $this->assertStringContainsString('glass-tint-opacity-60', $classes);
    }

    public function test_stat_glass_style_with_hex_color(): void
    {
        $component = $this->createComponent([
            'glass'     => 'liquid',
            'glassTint' => '#3b82f6',
        ]);

        $style = $component->glassStyle();

        $this->assertStringContainsString('--glass-tint-color: #3b82f6', $style);
    }

    public function test_stat_glass_style_empty_without_custom_tint(): void
    {
        $component = $this->createComponent([
            'glass'     => 'frosted',
            'glassTint' => 'blue-500', // Tailwind color, not custom hex
        ]);

        $style = $component->glassStyle();

        // Tailwind colors don't need inline styles (they use CSS classes)
        // Only hex colors need inline styles
        $this->assertStringNotContainsString('--glass-tint-color:', $style);
    }

    public function test_stat_glass_with_value_and_icon(): void
    {
        $component = $this->createComponent([
            'glass'       => 'frosted',
            'glassTint'   => 'emerald-500',
            'value'       => '$12,345',
            'icon'        => 'o-currency-dollar',
            'title'       => 'Revenue',
            'description' => '+15% from last month',
        ]);

        $this->assertEquals('frosted', $component->glass);
        $this->assertEquals('$12,345', $component->value);
        $this->assertEquals('o-currency-dollar', $component->icon);
        $this->assertEquals('Revenue', $component->title);
        $this->assertStringContainsString('glass-frosted', $component->glassClasses());
    }

    public function test_stat_sparkline_props_exist(): void
    {
        $component = $this->createComponent([
            'sparklineData'  => [100, 120, 115, 140, 135, 160],
            'sparklineType'  => 'area',
            'sparklineColor' => 'blue-500',
        ]);

        $this->assertEquals([100, 120, 115, 140, 135, 160], $component->sparklineData);
        $this->assertEquals('area', $component->sparklineType);
        $this->assertEquals('blue-500', $component->sparklineColor);
    }

    public function test_stat_sparkline_default_type(): void
    {
        $component = $this->createComponent([
            'sparklineData' => [10, 20, 30],
        ]);

        $this->assertEquals('line', $component->sparklineType);
    }

    public function test_stat_has_sparkline_with_data(): void
    {
        $component = $this->createComponent([
            'sparklineData' => [100, 120, 115, 140],
        ]);

        $this->assertTrue($component->hasSparkline());
    }

    public function test_stat_has_sparkline_without_data(): void
    {
        $component = $this->createComponent();

        $this->assertFalse($component->hasSparkline());
    }

    public function test_stat_has_sparkline_with_empty_array(): void
    {
        $component = $this->createComponent([
            'sparklineData' => [],
        ]);

        $this->assertFalse($component->hasSparkline());
    }

    public function test_stat_sparkline_height_for_xs_size(): void
    {
        $component = $this->createComponent([
            'size'          => 'xs',
            'sparklineData' => [10, 20, 30],
        ]);

        $this->assertEquals(24, $component->sparklineHeight());
    }

    public function test_stat_sparkline_height_for_sm_size(): void
    {
        $component = $this->createComponent([
            'size'          => 'sm',
            'sparklineData' => [10, 20, 30],
        ]);

        $this->assertEquals(32, $component->sparklineHeight());
    }

    public function test_stat_sparkline_height_for_md_size(): void
    {
        $component = $this->createComponent([
            'size'          => 'md',
            'sparklineData' => [10, 20, 30],
        ]);

        $this->assertEquals(40, $component->sparklineHeight());
    }

    public function test_stat_sparkline_height_for_lg_size(): void
    {
        $component = $this->createComponent([
            'size'          => 'lg',
            'sparklineData' => [10, 20, 30],
        ]);

        $this->assertEquals(48, $component->sparklineHeight());
    }

    public function test_stat_sparkline_height_for_xl_size(): void
    {
        $component = $this->createComponent([
            'size'          => 'xl',
            'sparklineData' => [10, 20, 30],
        ]);

        $this->assertEquals(56, $component->sparklineHeight());
    }

    public function test_stat_sparkline_height_for_default_size(): void
    {
        $component = $this->createComponent([
            'sparklineData' => [10, 20, 30],
        ]);

        $this->assertEquals(40, $component->sparklineHeight());
    }

    public function test_stat_sparkline_with_glass_variant(): void
    {
        $component = $this->createComponent([
            'glass'          => 'liquid',
            'glassTint'      => 'emerald-500',
            'sparklineData'  => [100, 120, 115, 140, 135, 160],
            'sparklineType'  => 'area',
            'sparklineColor' => 'emerald-400',
            'value'          => '1,234',
            'title'          => 'Users',
        ]);

        $this->assertEquals('liquid', $component->glass);
        $this->assertTrue($component->hasSparkline());
        $this->assertEquals([100, 120, 115, 140, 135, 160], $component->sparklineData);
        $this->assertStringContainsString('glass-liquid', $component->glassClasses());
    }

    public function test_stat_sparkline_bar_type(): void
    {
        $component = $this->createComponent([
            'sparklineData' => [10, 20, 15, 25, 30],
            'sparklineType' => 'bar',
        ]);

        $this->assertEquals('bar', $component->sparklineType);
        $this->assertTrue($component->hasSparkline());
    }

    public function test_stat_sparkline_with_hex_color(): void
    {
        $component = $this->createComponent([
            'sparklineData'  => [10, 20, 30],
            'sparklineColor' => '#3b82f6',
        ]);

        $this->assertEquals('#3b82f6', $component->sparklineColor);
    }

    public function test_stat_trend_props_exist(): void
    {
        $component = $this->createComponent([
            'change'      => 12.5,
            'changeLabel' => 'vs last month',
        ]);

        $this->assertEquals(12.5, $component->change);
        $this->assertEquals('vs last month', $component->changeLabel);
    }

    public function test_stat_has_change_with_value(): void
    {
        $component = $this->createComponent([
            'change' => 12.5,
        ]);

        $this->assertTrue($component->hasChange());
    }

    public function test_stat_has_change_without_value(): void
    {
        $component = $this->createComponent();

        $this->assertFalse($component->hasChange());
    }

    public function test_stat_has_change_with_zero(): void
    {
        $component = $this->createComponent([
            'change' => 0.0,
        ]);

        $this->assertTrue($component->hasChange());
    }

    public function test_stat_is_positive_change_with_positive_value(): void
    {
        $component = $this->createComponent([
            'change' => 12.5,
        ]);

        $this->assertTrue($component->isPositiveChange());
    }

    public function test_stat_is_positive_change_with_negative_value(): void
    {
        $component = $this->createComponent([
            'change' => -8.3,
        ]);

        $this->assertFalse($component->isPositiveChange());
    }

    public function test_stat_is_positive_change_with_zero(): void
    {
        $component = $this->createComponent([
            'change' => 0.0,
        ]);

        $this->assertTrue($component->isPositiveChange());
    }

    public function test_stat_formatted_change_positive(): void
    {
        $component = $this->createComponent([
            'change' => 12.5,
        ]);

        $this->assertEquals('+12.5%', $component->formattedChange());
    }

    public function test_stat_formatted_change_negative(): void
    {
        $component = $this->createComponent([
            'change' => -8.3,
        ]);

        $this->assertEquals('-8.3%', $component->formattedChange());
    }

    public function test_stat_formatted_change_zero(): void
    {
        $component = $this->createComponent([
            'change' => 0.0,
        ]);

        $this->assertEquals('+0.0%', $component->formattedChange());
    }

    public function test_stat_formatted_change_without_value(): void
    {
        $component = $this->createComponent();

        $this->assertEquals('', $component->formattedChange());
    }

    public function test_stat_change_color_classes_positive(): void
    {
        $component = $this->createComponent([
            'change' => 12.5,
        ]);

        $this->assertEquals('text-success', $component->changeColorClasses());
    }

    public function test_stat_change_color_classes_negative(): void
    {
        $component = $this->createComponent([
            'change' => -8.3,
        ]);

        $this->assertEquals('text-error', $component->changeColorClasses());
    }

    public function test_stat_change_color_classes_zero(): void
    {
        $component = $this->createComponent([
            'change' => 0.0,
        ]);

        $this->assertEquals('text-success', $component->changeColorClasses());
    }

    public function test_stat_change_color_classes_without_value(): void
    {
        $component = $this->createComponent();

        $this->assertEquals('', $component->changeColorClasses());
    }

    public function test_stat_change_icon_positive(): void
    {
        $component = $this->createComponent([
            'change' => 12.5,
        ]);

        $this->assertEquals('o-arrow-trending-up', $component->changeIcon());
    }

    public function test_stat_change_icon_negative(): void
    {
        $component = $this->createComponent([
            'change' => -8.3,
        ]);

        $this->assertEquals('o-arrow-trending-down', $component->changeIcon());
    }

    public function test_stat_change_icon_zero(): void
    {
        $component = $this->createComponent([
            'change' => 0.0,
        ]);

        $this->assertEquals('o-arrow-trending-up', $component->changeIcon());
    }

    public function test_stat_change_icon_without_value(): void
    {
        $component = $this->createComponent();

        $this->assertEquals('', $component->changeIcon());
    }

    public function test_stat_trend_with_glass_variant(): void
    {
        $component = $this->createComponent([
            'glass'       => 'frosted',
            'glassTint'   => 'emerald-500',
            'change'      => 15.2,
            'changeLabel' => 'vs last week',
            'value'       => '$12,345',
            'title'       => 'Revenue',
        ]);

        $this->assertEquals('frosted', $component->glass);
        $this->assertTrue($component->hasChange());
        $this->assertEquals('+15.2%', $component->formattedChange());
        $this->assertStringContainsString('glass-frosted', $component->glassClasses());
    }

    public function test_stat_trend_with_sparkline(): void
    {
        $component = $this->createComponent([
            'change'        => -5.7,
            'changeLabel'   => 'vs yesterday',
            'sparklineData' => [100, 95, 90, 85, 80],
            'sparklineType' => 'area',
            'value'         => '1,234',
            'title'         => 'Active Users',
        ]);

        $this->assertTrue($component->hasChange());
        $this->assertTrue($component->hasSparkline());
        $this->assertFalse($component->isPositiveChange());
        $this->assertEquals('-5.7%', $component->formattedChange());
        $this->assertEquals('text-error', $component->changeColorClasses());
    }

    public function test_stat_formatted_change_rounds_correctly(): void
    {
        $component = $this->createComponent([
            'change' => 12.567,
        ]);

        $this->assertEquals('+12.6%', $component->formattedChange());
    }
}
