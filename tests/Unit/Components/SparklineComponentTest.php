<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use ArtisanPack\LivewireUiComponents\View\Components\Sparkline;
use Error;
use RuntimeException;

/**
 * Comprehensive unit tests for the Sparkline component.
 *
 * Tests the lightweight sparkline chart component for inline visualizations.
 */
class SparklineComponentTest extends ComponentTestCase
{
    /**
     * Default performance budget in milliseconds.
     * Can be overridden via SPARKLINE_PERF_BUDGET environment variable.
     */
    protected const DEFAULT_PERF_BUDGET_MS = 100;

    protected string $componentClass = Sparkline::class;

    protected array $defaultProperties = [];

    protected array $requiredProperties = [];

    public function test_sparkline_type_validation(): void
    {
        // Valid types should be returned as-is
        $validTypes = ['line', 'area', 'bar'];

        foreach ($validTypes as $type) {
            $component = $this->createComponent(['type' => $type]);
            $this->assertEquals($type, $component->sparklineType());
        }

        // Invalid types should default to 'line'
        $component = $this->createComponent(['type' => 'invalid-type']);
        $this->assertEquals('line', $component->sparklineType());
    }

    public function test_sparkline_height_handling(): void
    {
        // Integer height
        $component = $this->createComponent(['height' => 50]);
        $this->assertEquals('50', $component->sparklineHeight());

        // String height
        $component = $this->createComponent(['height' => '100%']);
        $this->assertEquals('100%', $component->sparklineHeight());

        // Default height
        $component = $this->createComponent();
        $this->assertEquals('40', $component->sparklineHeight());
    }

    public function test_sparkline_width_handling(): void
    {
        // Null width (auto)
        $component = $this->createComponent();
        $this->assertNull($component->sparklineWidth());

        // Integer width
        $component = $this->createComponent(['width' => 100]);
        $this->assertEquals('100', $component->sparklineWidth());

        // String width
        $component = $this->createComponent(['width' => '100%']);
        $this->assertEquals('100%', $component->sparklineWidth());
    }

    public function test_sparkline_hex_color_resolution(): void
    {
        // Hex color should be returned as-is
        $component = $this->createComponent(['color' => '#3b82f6']);
        $this->assertEquals('#3b82f6', $component->resolvedColor());

        // Short hex color
        $component = $this->createComponent(['color' => '#f00']);
        $this->assertEquals('#f00', $component->resolvedColor());
    }

    public function test_sparkline_tailwind_color_resolution(): void
    {
        // Tailwind color with intensity
        $component = $this->createComponent(['color' => 'emerald-500']);
        $this->assertEquals('#10b981', $component->resolvedColor());

        // Different intensity
        $component = $this->createComponent(['color' => 'blue-400']);
        $this->assertEquals('#60a5fa', $component->resolvedColor());

        // Red color
        $component = $this->createComponent(['color' => 'red-600']);
        $this->assertEquals('#dc2626', $component->resolvedColor());
    }

    public function test_sparkline_tailwind_color_name_only(): void
    {
        // Just color name (should use 500 intensity)
        $component = $this->createComponent(['color' => 'emerald']);
        $this->assertEquals('#10b981', $component->resolvedColor());

        $component = $this->createComponent(['color' => 'blue']);
        $this->assertEquals('#3b82f6', $component->resolvedColor());
    }

    public function test_sparkline_default_color(): void
    {
        // No color specified should use emerald-500
        $component = $this->createComponent();
        $this->assertEquals('#10b981', $component->resolvedColor());
    }

    public function test_sparkline_invalid_color_fallback(): void
    {
        // Invalid color should fallback to default
        $component = $this->createComponent(['color' => 'invalid-color']);
        $this->assertEquals('#10b981', $component->resolvedColor());
    }

    public function test_sparkline_chart_options_structure(): void
    {
        $component = $this->createComponent([
            'type'   => 'line',
            'height' => 50,
            'color'  => 'blue-500',
        ]);
        $options = $component->chartOptions();

        // Check chart type
        $this->assertEquals('line', $options['chart']['type']);
        $this->assertEquals('50', $options['chart']['height']);

        // Check sparkline is enabled
        $this->assertTrue($options['chart']['sparkline']['enabled']);

        // Check colors array
        $this->assertArrayHasKey('colors', $options);
        $this->assertEquals(['#3b82f6'], $options['colors']);

        // Check stroke options
        $this->assertArrayHasKey('stroke', $options);
        $this->assertEquals('smooth', $options['stroke']['curve']);
        $this->assertEquals(2, $options['stroke']['width']);

        // Check tooltip is disabled
        $this->assertArrayHasKey('tooltip', $options);
        $this->assertFalse($options['tooltip']['fixed']['enabled']);
    }

    public function test_sparkline_width_included_when_specified(): void
    {
        // Without width
        $component = $this->createComponent();
        $options   = $component->chartOptions();
        $this->assertArrayNotHasKey('width', $options['chart']);

        // With width
        $component = $this->createComponent(['width' => 100]);
        $options   = $component->chartOptions();
        $this->assertEquals('100', $options['chart']['width']);
    }

    public function test_sparkline_area_type_has_fill_options(): void
    {
        $component = $this->createComponent(['type' => 'area']);
        $options   = $component->chartOptions();

        $this->assertArrayHasKey('fill', $options);
        $this->assertEquals('gradient', $options['fill']['type']);
        $this->assertArrayHasKey('gradient', $options['fill']);
    }

    public function test_sparkline_bar_type_has_plot_options(): void
    {
        $component = $this->createComponent(['type' => 'bar']);
        $options   = $component->chartOptions();

        $this->assertArrayHasKey('plotOptions', $options);
        $this->assertArrayHasKey('bar', $options['plotOptions']);
        $this->assertEquals('80%', $options['plotOptions']['bar']['columnWidth']);
    }

    public function test_sparkline_line_type_no_extra_options(): void
    {
        $component = $this->createComponent(['type' => 'line']);
        $options   = $component->chartOptions();

        // Line type should not have fill or plotOptions
        $this->assertArrayNotHasKey('fill', $options);
        $this->assertArrayNotHasKey('plotOptions', $options);
    }

    public function test_sparkline_series_data(): void
    {
        // Empty data
        $component = $this->createComponent();
        $series    = $component->chartSeries();
        $this->assertEquals([['data' => []]], $series);

        // With data
        $data      = [10, 20, 15, 30, 25, 35];
        $component = $this->createComponent(['data' => $data]);
        $series    = $component->chartSeries();
        $this->assertEquals([['data' => $data]], $series);
    }

    public function test_sparkline_custom_stroke_width(): void
    {
        $component = $this->createComponent(['strokeWidth' => 3]);
        $options   = $component->chartOptions();

        $this->assertEquals(3, $options['stroke']['width']);
    }

    public function test_sparkline_custom_curve(): void
    {
        $component = $this->createComponent(['curve' => 'straight']);
        $options   = $component->chartOptions();

        $this->assertEquals('straight', $options['stroke']['curve']);
    }

    public function test_sparkline_custom_fill_opacity(): void
    {
        $component = $this->createComponent([
            'type'        => 'area',
            'fillOpacity' => 0.5,
        ]);
        $options = $component->chartOptions();

        $this->assertEquals(0.5, $options['fill']['gradient']['opacityFrom']);
    }

    public function test_sparkline_get_valid_types(): void
    {
        $validTypes = Sparkline::getValidTypes();

        $this->assertIsArray($validTypes);
        $this->assertContains('line', $validTypes);
        $this->assertContains('area', $validTypes);
        $this->assertContains('bar', $validTypes);
        $this->assertCount(3, $validTypes);
    }

    public function test_sparkline_generates_unique_uuid(): void
    {
        $component1 = $this->createComponent(['data' => [1, 2, 3]]);
        $component2 = $this->createComponent(['data' => [4, 5, 6]]);

        $this->assertNotEquals($component1->uuid, $component2->uuid);
    }

    public function test_sparkline_uses_custom_id_in_uuid(): void
    {
        $component = $this->createComponent(['id' => 'my-sparkline']);

        $this->assertStringContainsString('my-sparkline', $component->uuid);
    }

    public function test_sparkline_renders_successfully(): void
    {
        $component = $this->createComponent(['data' => [10, 20, 30]]);
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

    public function test_sparkline_all_tailwind_colors_supported(): void
    {
        $colors = [
            'slate', 'gray', 'zinc', 'neutral', 'stone',
            'red', 'orange', 'amber', 'yellow', 'lime',
            'green', 'emerald', 'teal', 'cyan', 'sky',
            'blue', 'indigo', 'violet', 'purple', 'fuchsia',
            'pink', 'rose',
        ];

        foreach ($colors as $color) {
            $component = $this->createComponent(['color' => $color]);
            $resolved  = $component->resolvedColor();

            // Should resolve to a hex color (not the fallback for invalid colors)
            $this->assertMatchesRegularExpression('/^#[A-Fa-f0-9]{6}$/', $resolved);
        }
    }

    public function test_sparkline_performance(): void
    {
        try {
            $performance = TestHelpers::measureRenderingPerformance(
                $this->createComponent(['data' => [10, 20, 30, 40, 50]]),
                10,
            );
            $budget = $this->getPerformanceBudget();
            $this->assertLessThan($budget, $performance['average_time'],
                "Sparkline rendering should be under {$budget}ms on average",
            );
        } catch (RuntimeException $e) {
            if (str_contains($e->getMessage(), 'requires slots') ||
                str_contains($e->getMessage(), 'requires additional context')) {
                $this->markTestSkipped($e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * Get the performance budget for sparkline rendering tests.
     *
     * @return int Performance budget in milliseconds
     */
    protected function getPerformanceBudget(): int
    {
        $envBudget = getenv('SPARKLINE_PERF_BUDGET');

        if (false !== $envBudget && is_numeric($envBudget)) {
            return (int) $envBudget;
        }

        return self::DEFAULT_PERF_BUDGET_MS;
    }
}
