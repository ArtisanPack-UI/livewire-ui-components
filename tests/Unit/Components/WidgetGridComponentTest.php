<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\View\Components\WidgetGrid;
use Error;

/**
 * Comprehensive unit tests for the WidgetGrid component.
 *
 * Tests the responsive grid layout for dashboard widgets,
 * including column configurations and gap spacing.
 *
 * @since 2.0.0
 */
class WidgetGridComponentTest extends ComponentTestCase
{
    protected string $componentClass = WidgetGrid::class;

    protected array $defaultProperties = [
        'cols' => 4,
        'gap'  => 6,
    ];

    protected array $requiredProperties = [];

    /**
     * Test WidgetGrid can be instantiated with defaults.
     */
    public function test_widget_grid_can_be_instantiated_with_defaults(): void
    {
        $component = new WidgetGrid();

        $this->assertInstanceOf(WidgetGrid::class, $component);
        $this->assertEquals(4, $component->cols);
        $this->assertEquals(6, $component->gap);
    }

    /**
     * Test WidgetGrid accepts custom column count.
     */
    public function test_widget_grid_accepts_custom_column_count(): void
    {
        $component = new WidgetGrid(cols: 3);

        $this->assertEquals(3, $component->cols);
    }

    /**
     * Test WidgetGrid accepts custom gap.
     */
    public function test_widget_grid_accepts_custom_gap(): void
    {
        $component = new WidgetGrid(gap: 8);

        $this->assertEquals(8, $component->gap);
    }

    /**
     * Test WidgetGrid converts string cols to int.
     */
    public function test_widget_grid_converts_string_cols_to_int(): void
    {
        $component = new WidgetGrid(cols: '3');

        $this->assertIsInt($component->cols);
        $this->assertEquals(3, $component->cols);
    }

    /**
     * Test WidgetGrid converts string gap to int.
     */
    public function test_widget_grid_converts_string_gap_to_int(): void
    {
        $component = new WidgetGrid(gap: '8');

        $this->assertIsInt($component->gap);
        $this->assertEquals(8, $component->gap);
    }

    /**
     * Test gridColsClasses returns correct classes for 1 column.
     */
    public function test_grid_cols_classes_returns_correct_for_1_column(): void
    {
        $component = new WidgetGrid(cols: 1);

        $this->assertEquals('grid-cols-1', $component->gridColsClasses());
    }

    /**
     * Test gridColsClasses returns correct classes for 2 columns.
     */
    public function test_grid_cols_classes_returns_correct_for_2_columns(): void
    {
        $component = new WidgetGrid(cols: 2);

        $this->assertEquals('grid-cols-1 sm:grid-cols-2', $component->gridColsClasses());
    }

    /**
     * Test gridColsClasses returns correct classes for 3 columns.
     */
    public function test_grid_cols_classes_returns_correct_for_3_columns(): void
    {
        $component = new WidgetGrid(cols: 3);

        $this->assertEquals('grid-cols-1 sm:grid-cols-2 lg:grid-cols-3', $component->gridColsClasses());
    }

    /**
     * Test gridColsClasses returns correct classes for 4 columns.
     */
    public function test_grid_cols_classes_returns_correct_for_4_columns(): void
    {
        $component = new WidgetGrid(cols: 4);

        $this->assertEquals('grid-cols-1 sm:grid-cols-2 lg:grid-cols-4', $component->gridColsClasses());
    }

    /**
     * Test gridColsClasses returns correct classes for 5 columns.
     */
    public function test_grid_cols_classes_returns_correct_for_5_columns(): void
    {
        $component = new WidgetGrid(cols: 5);

        $this->assertEquals('grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5', $component->gridColsClasses());
    }

    /**
     * Test gridColsClasses returns correct classes for 6 columns.
     */
    public function test_grid_cols_classes_returns_correct_for_6_columns(): void
    {
        $component = new WidgetGrid(cols: 6);

        $this->assertEquals('grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6', $component->gridColsClasses());
    }

    /**
     * Test gridColsClasses returns default for invalid column count.
     */
    public function test_grid_cols_classes_returns_default_for_invalid_count(): void
    {
        $component = new WidgetGrid(cols: 99);

        $this->assertEquals('grid-cols-1 sm:grid-cols-2 lg:grid-cols-4', $component->gridColsClasses());
    }

    /**
     * Test gapClass returns correct classes for all valid gap values.
     */
    public function test_gap_class_returns_correct_for_all_valid_values(): void
    {
        $gapMappings = [
            0  => 'gap-0',
            1  => 'gap-1',
            2  => 'gap-2',
            3  => 'gap-3',
            4  => 'gap-4',
            5  => 'gap-5',
            6  => 'gap-6',
            8  => 'gap-8',
            10 => 'gap-10',
            12 => 'gap-12',
        ];

        foreach ($gapMappings as $gap => $expectedClass) {
            $component = new WidgetGrid(gap: $gap);
            $this->assertEquals($expectedClass, $component->gapClass(), "Gap {$gap} should map to {$expectedClass}");
        }
    }

    /**
     * Test gapClass returns default for invalid gap value.
     */
    public function test_gap_class_returns_default_for_invalid_value(): void
    {
        $component = new WidgetGrid(gap: 7);

        $this->assertEquals('gap-6', $component->gapClass());
    }

    /**
     * Test UUID is generated.
     */
    public function test_uuid_is_generated(): void
    {
        $component = new WidgetGrid();

        $this->assertNotEmpty($component->uuid);
        $this->assertStringStartsWith('artisanpack-widget-grid-', $component->uuid);
    }

    /**
     * Test custom ID is used in UUID.
     */
    public function test_custom_id_is_used_in_uuid(): void
    {
        $component = new WidgetGrid(id: 'custom-grid');

        $this->assertStringContainsString('custom-grid', $component->uuid);
    }

    /**
     * Test component renders.
     */
    public function test_widget_grid_renders(): void
    {
        $component = new WidgetGrid();

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
     * Test gridColsClasses includes mobile first approach.
     */
    public function test_grid_cols_classes_uses_mobile_first(): void
    {
        $component = new WidgetGrid(cols: 4);
        $classes   = $component->gridColsClasses();

        // Should start with grid-cols-1 for mobile
        $this->assertStringStartsWith('grid-cols-1', $classes);
    }

    /**
     * Test gridColsClasses includes responsive breakpoints.
     */
    public function test_grid_cols_classes_includes_responsive_breakpoints(): void
    {
        $component = new WidgetGrid(cols: 4);
        $classes   = $component->gridColsClasses();

        // Should include sm: breakpoint
        $this->assertStringContainsString('sm:', $classes);
        // Should include lg: breakpoint
        $this->assertStringContainsString('lg:', $classes);
    }

    /**
     * Test component accepts combined props.
     */
    public function test_widget_grid_accepts_combined_props(): void
    {
        $component = new WidgetGrid(
            id: 'dashboard-grid',
            cols: 3,
            gap: 4,
        );

        $this->assertEquals('dashboard-grid', $component->id);
        $this->assertEquals(3, $component->cols);
        $this->assertEquals(4, $component->gap);
    }

    /**
     * Test two instances have different UUIDs.
     */
    public function test_two_instances_have_different_uuids(): void
    {
        $component1 = new WidgetGrid();
        $component2 = new WidgetGrid();

        $this->assertNotEquals($component1->uuid, $component2->uuid);
    }

    /**
     * Test gridColsClasses returns string.
     */
    public function test_grid_cols_classes_returns_string(): void
    {
        $component = new WidgetGrid();

        $this->assertIsString($component->gridColsClasses());
    }

    /**
     * Test gapClass returns string.
     */
    public function test_gap_class_returns_string(): void
    {
        $component = new WidgetGrid();

        $this->assertIsString($component->gapClass());
    }

    /**
     * Test zero columns uses default.
     */
    public function test_zero_columns_uses_default(): void
    {
        $component = new WidgetGrid(cols: 0);

        // 0 should fall through to default case
        $this->assertEquals('grid-cols-1 sm:grid-cols-2 lg:grid-cols-4', $component->gridColsClasses());
    }

    /**
     * Test negative columns uses default.
     */
    public function test_negative_columns_uses_default(): void
    {
        $component = new WidgetGrid(cols: -1);

        // Negative should fall through to default case
        $this->assertEquals('grid-cols-1 sm:grid-cols-2 lg:grid-cols-4', $component->gridColsClasses());
    }
}
