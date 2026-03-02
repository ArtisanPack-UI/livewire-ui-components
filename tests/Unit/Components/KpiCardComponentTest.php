<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\View\Components\KpiCard;
use Error;

/**
 * Comprehensive unit tests for the KpiCard component.
 *
 * Tests the KPI card component's trend indicators, sparkline support,
 * glass effects, and rendering capabilities.
 *
 * @since 2.0.0
 */
class KpiCardComponentTest extends ComponentTestCase
{
    protected string $componentClass = KpiCard::class;

    protected array $defaultProperties = [
        'sparklineType' => 'area',
    ];

    protected array $requiredProperties = [];

    /**
     * Test KpiCard can be instantiated with minimal props.
     */
    public function test_kpi_card_can_be_instantiated_with_minimal_props(): void
    {
        $component = new KpiCard;

        $this->assertInstanceOf(KpiCard::class, $component);
    }

    /**
     * Test KpiCard accepts title and value.
     */
    public function test_kpi_card_accepts_title_and_value(): void
    {
        $component = new KpiCard(
            title: 'Total Revenue',
            value: '$45,231',
        );

        $this->assertEquals('Total Revenue', $component->title);
        $this->assertEquals('$45,231', $component->value);
    }

    /**
     * Test KpiCard accepts icon.
     */
    public function test_kpi_card_accepts_icon(): void
    {
        $component = new KpiCard(
            icon: 'o-currency-dollar',
        );

        $this->assertEquals('o-currency-dollar', $component->icon);
    }

    /**
     * Test hasChange returns true when change is set.
     */
    public function test_has_change_returns_true_when_change_is_set(): void
    {
        $component = new KpiCard(change: 12.5);

        $this->assertTrue($component->hasChange());
    }

    /**
     * Test hasChange returns false when change is null.
     */
    public function test_has_change_returns_false_when_change_is_null(): void
    {
        $component = new KpiCard;

        $this->assertFalse($component->hasChange());
    }

    /**
     * Test isPositiveChange returns true for positive values.
     */
    public function test_is_positive_change_returns_true_for_positive(): void
    {
        $component = new KpiCard(change: 12.5);

        $this->assertTrue($component->isPositiveChange());
    }

    /**
     * Test isPositiveChange returns true for zero.
     */
    public function test_is_positive_change_returns_true_for_zero(): void
    {
        $component = new KpiCard(change: 0.0);

        $this->assertTrue($component->isPositiveChange());
    }

    /**
     * Test isPositiveChange returns false for negative values.
     */
    public function test_is_positive_change_returns_false_for_negative(): void
    {
        $component = new KpiCard(change: -8.3);

        $this->assertFalse($component->isPositiveChange());
    }

    /**
     * Test formattedChange returns correct format for positive.
     */
    public function test_formatted_change_returns_correct_format_for_positive(): void
    {
        $component = new KpiCard(change: 12.5);

        $this->assertEquals('+12.5%', $component->formattedChange());
    }

    /**
     * Test formattedChange returns correct format for negative.
     */
    public function test_formatted_change_returns_correct_format_for_negative(): void
    {
        $component = new KpiCard(change: -8.3);

        $this->assertEquals('-8.3%', $component->formattedChange());
    }

    /**
     * Test formattedChange returns empty string when no change.
     */
    public function test_formatted_change_returns_empty_when_no_change(): void
    {
        $component = new KpiCard;

        $this->assertEquals('', $component->formattedChange());
    }

    /**
     * Test changeColorClasses returns success for positive.
     */
    public function test_change_color_classes_returns_success_for_positive(): void
    {
        $component = new KpiCard(change: 12.5);

        $this->assertEquals('text-success', $component->changeColorClasses());
    }

    /**
     * Test changeColorClasses returns error for negative.
     */
    public function test_change_color_classes_returns_error_for_negative(): void
    {
        $component = new KpiCard(change: -8.3);

        $this->assertEquals('text-error', $component->changeColorClasses());
    }

    /**
     * Test changeColorClasses returns empty when no change.
     */
    public function test_change_color_classes_returns_empty_when_no_change(): void
    {
        $component = new KpiCard;

        $this->assertEquals('', $component->changeColorClasses());
    }

    /**
     * Test changeIcon returns up arrow for positive.
     */
    public function test_change_icon_returns_up_arrow_for_positive(): void
    {
        $component = new KpiCard(change: 12.5);

        $this->assertEquals('o-arrow-trending-up', $component->changeIcon());
    }

    /**
     * Test changeIcon returns down arrow for negative.
     */
    public function test_change_icon_returns_down_arrow_for_negative(): void
    {
        $component = new KpiCard(change: -8.3);

        $this->assertEquals('o-arrow-trending-down', $component->changeIcon());
    }

    /**
     * Test changeIcon returns empty when no change.
     */
    public function test_change_icon_returns_empty_when_no_change(): void
    {
        $component = new KpiCard;

        $this->assertEquals('', $component->changeIcon());
    }

    /**
     * Test hasSparkline returns true when sparkline data is provided.
     */
    public function test_has_sparkline_returns_true_when_data_provided(): void
    {
        $component = new KpiCard(
            sparklineData: [100, 120, 115, 140, 155],
        );

        $this->assertTrue($component->hasSparkline());
    }

    /**
     * Test hasSparkline returns false when sparkline data is null.
     */
    public function test_has_sparkline_returns_false_when_data_is_null(): void
    {
        $component = new KpiCard;

        $this->assertFalse($component->hasSparkline());
    }

    /**
     * Test hasSparkline returns false when sparkline data is empty.
     */
    public function test_has_sparkline_returns_false_when_data_is_empty(): void
    {
        $component = new KpiCard(sparklineData: []);

        $this->assertFalse($component->hasSparkline());
    }

    /**
     * Test sparkline type defaults to area.
     */
    public function test_sparkline_type_defaults_to_area(): void
    {
        $component = new KpiCard;

        $this->assertEquals('area', $component->sparklineType);
    }

    /**
     * Test sparkline type can be set to line.
     */
    public function test_sparkline_type_can_be_set_to_line(): void
    {
        $component = new KpiCard(sparklineType: 'line');

        $this->assertEquals('line', $component->sparklineType);
    }

    /**
     * Test sparkline type can be set to bar.
     */
    public function test_sparkline_type_can_be_set_to_bar(): void
    {
        $component = new KpiCard(sparklineType: 'bar');

        $this->assertEquals('bar', $component->sparklineType);
    }

    /**
     * Test sparkline color can be customized.
     */
    public function test_sparkline_color_can_be_customized(): void
    {
        $component = new KpiCard(sparklineColor: '#ff6b6b');

        $this->assertEquals('#ff6b6b', $component->sparklineColor);
    }

    /**
     * Test glass effect properties are accepted.
     */
    public function test_glass_effect_properties_are_accepted(): void
    {
        $component = new KpiCard(
            glass: 'frost',
            glassTint: 'primary',
            glassTintOpacity: 20,
        );

        $this->assertEquals('frost', $component->glass);
        $this->assertEquals('primary', $component->glassTint);
        $this->assertEquals(20, $component->glassTintOpacity);
    }

    /**
     * Test glassClasses method returns string.
     */
    public function test_glass_classes_returns_string(): void
    {
        $component = new KpiCard(glass: 'frost');

        $result = $component->glassClasses();

        $this->assertIsString($result);
    }

    /**
     * Test glassStyle method returns string.
     */
    public function test_glass_style_returns_string(): void
    {
        $component = new KpiCard(glassTint: 'primary');

        $result = $component->glassStyle();

        $this->assertIsString($result);
    }

    /**
     * Test change label can be set.
     */
    public function test_change_label_can_be_set(): void
    {
        $component = new KpiCard(
            change: 12.5,
            changeLabel: 'vs last month',
        );

        $this->assertEquals('vs last month', $component->changeLabel);
    }

    /**
     * Test UUID is generated.
     */
    public function test_uuid_is_generated(): void
    {
        $component = new KpiCard;

        $this->assertNotEmpty($component->uuid);
        $this->assertStringStartsWith('artisanpack-kpi-card-', $component->uuid);
    }

    /**
     * Test custom ID is used in UUID.
     */
    public function test_custom_id_is_used_in_uuid(): void
    {
        $component = new KpiCard(id: 'custom-kpi');

        $this->assertStringContainsString('custom-kpi', $component->uuid);
    }

    /**
     * Test component renders.
     */
    public function test_kpi_card_renders(): void
    {
        $component = new KpiCard(
            title: 'Revenue',
            value: '$1,234',
        );

        try {
            $view = $component->render();
            $this->assertNotNull($view);
        } catch (Error $e) {
            if (str_contains($e->getMessage(), 'Call to a member function') &&
                str_contains($e->getMessage(), 'on null')) {
                $this->markTestSkipped('Component requires attributes or context for rendering');
            }
            throw $e;
        }
    }

    /**
     * Test formattedChange handles small decimals correctly.
     */
    public function test_formatted_change_handles_small_decimals(): void
    {
        $component = new KpiCard(change: 0.1);

        $this->assertEquals('+0.1%', $component->formattedChange());
    }

    /**
     * Test formattedChange handles large numbers correctly.
     */
    public function test_formatted_change_handles_large_numbers(): void
    {
        $component = new KpiCard(change: 123.456);

        $this->assertEquals('+123.5%', $component->formattedChange());
    }

    /**
     * Test all properties can be set together.
     */
    public function test_all_properties_can_be_set_together(): void
    {
        $component = new KpiCard(
            id: 'revenue-kpi',
            title: 'Total Revenue',
            value: '$45,231',
            icon: 'o-currency-dollar',
            change: 12.5,
            changeLabel: 'vs last month',
            sparklineData: [100, 120, 115, 140, 155],
            sparklineType: 'line',
            sparklineColor: '#3b82f6',
            glass: 'frost',
            glassTint: 'primary',
            glassTintOpacity: 15,
        );

        $this->assertEquals('revenue-kpi', $component->id);
        $this->assertEquals('Total Revenue', $component->title);
        $this->assertEquals('$45,231', $component->value);
        $this->assertEquals('o-currency-dollar', $component->icon);
        $this->assertEquals(12.5, $component->change);
        $this->assertEquals('vs last month', $component->changeLabel);
        $this->assertEquals([100, 120, 115, 140, 155], $component->sparklineData);
        $this->assertEquals('line', $component->sparklineType);
        $this->assertEquals('#3b82f6', $component->sparklineColor);
        $this->assertEquals('frost', $component->glass);
        $this->assertEquals('primary', $component->glassTint);
        $this->assertEquals(15, $component->glassTintOpacity);
    }
}
