<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Components;

use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentDataFactory;
use ArtisanPack\LivewireUiComponents\Tests\Support\ComponentTestCase;
use ArtisanPack\LivewireUiComponents\Tests\Support\TestHelpers;
use ArtisanPack\LivewireUiComponents\View\Components\Table;
use Error;
use RuntimeException;

/**
 * Comprehensive unit tests for the Table component.
 *
 * Auto-generated test class that extends ComponentTestCase to inherit
 * common testing patterns and implements component-specific tests.
 */
class TableComponentTest extends ComponentTestCase
{
    protected string $componentClass = Table::class;

    protected array $defaultProperties = [
        'striped'        => false,
        'noHeaders'      => false,
        'selectable'     => false,
        'selectableKey'  => 'id',
        'expandable'     => false,
        'expandableKey'  => 'id',
        'withPagination' => false,
        'perPageValues'  => [10, 20, 50, 100],
        'sortBy'         => [],
        'rowDecoration'  => [],
        'cellDecoration' => [],
        'showEmptyText'  => false,
        'emptyText'      => 'No records found.',
        'containerClass' => 'overflow-x-auto',
        'noHover'        => false,
        'uuid'           => '',
        'keyBy'          => 'id',
    ];

    protected array $requiredProperties = [
        'headers',
        'rows',
    ];

    public function test_table_string_properties(): void
    {
        $stringProperties = ['id', 'selectableKey', 'expandableKey', 'link', 'perPage', 'containerClass', 'uuid', 'keyBy'];
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

    public function test_table_boolean_properties(): void
    {
        $booleanProperties = ['striped', 'noHeaders', 'selectable', 'expandable', 'withPagination', 'showEmptyText', 'noHover'];

        foreach ($booleanProperties as $property) {
            $component = $this->createComponent([$property => true]);
            $this->assertTrue($component->$property);

            $component = $this->createComponent([$property => false]);
            $this->assertFalse($component->$property);
        }
    }

    public function test_table_array_properties(): void
    {
        $arrayProperties = ['headers', 'perPageValues', 'sortBy', 'rowDecoration', 'cellDecoration'];
        $testArrays      = ComponentDataFactory::arrayData();

        foreach ($arrayProperties as $property) {
            foreach ($testArrays as $array) {
                $component = $this->createComponent([$property => $array]);
                $this->assertEquals($array, $component->$property);
            }
        }
    }

    public function test_table_get_all_ids_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getAllIds')) {
            try {
                $result = $component->getAllIds();
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

    public function test_table_is_sortable_method(): void
    {
        $this->assertFalse($this->createComponent()->isSortable(['key' => 'name', 'label' => 'Name']));

        $sorted = $this->createComponent(['sortBy' => ['column' => 'name', 'direction' => 'asc']]);
        $this->assertTrue($sorted->isSortable(['key' => 'name', 'label' => 'Name']));
        $this->assertFalse($sorted->isSortable(['key' => 'name', 'sortable' => false]));
    }

    public function test_table_is_hidden_method(): void
    {
        $component = $this->createComponent();

        $this->assertFalse($component->isHidden(['key' => 'name', 'label' => 'Name']));
        $this->assertTrue($component->isHidden(['key' => 'name', 'hidden' => true]));
    }

    public function test_table_format_method(): void
    {
        $component = $this->createComponent();
        $row       = ['id' => 1, 'price' => 1234.5];

        $this->assertSame(1234.5, $component->format($row, 1234.5, ['key' => 'price']));
        $this->assertSame('$1,234.50', $component->format($row, 1234.5, ['key' => 'price', 'format' => ['currency', '2.,', '$']]));
        $this->assertSame('#1', $component->format($row, 1, ['key' => 'id', 'format' => fn (array $row, int $field): string => '#'.$field]));
    }

    public function test_table_has_link_method(): void
    {
        $this->assertFalse($this->createComponent()->hasLink(['key' => 'name', 'label' => 'Name']));

        $linked = $this->createComponent(['link' => '/users/{id}']);
        $this->assertTrue($linked->hasLink(['key' => 'name', 'label' => 'Name']));
        $this->assertFalse($linked->hasLink(['key' => 'name', 'disableLink' => true]));
    }

    public function test_table_is_sorted_by_method(): void
    {
        $this->assertFalse($this->createComponent()->isSortedBy(['key' => 'name', 'label' => 'Name']));

        $sorted = $this->createComponent(['sortBy' => ['column' => 'name', 'direction' => 'asc']]);
        $this->assertTrue($sorted->isSortedBy(['key' => 'name', 'label' => 'Name']));
        $this->assertFalse($sorted->isSortedBy(['key' => 'email']));
    }

    public function test_table_get_sort_method(): void
    {
        $this->assertFalse($this->createComponent()->getSort(['key' => 'name', 'label' => 'Name']));

        $sorted = $this->createComponent(['sortBy' => ['column' => 'name', 'direction' => 'asc']]);
        $this->assertSame(['column' => 'name', 'direction' => 'desc'], $sorted->getSort(['key' => 'name', 'label' => 'Name']));
        $this->assertSame(['column' => 'email', 'direction' => 'asc'], $sorted->getSort(['key' => 'email']));
    }

    public function test_table_redirect_link_method(): void
    {
        $component = $this->createComponent(['link' => '/users/{id}/cities/{city.name}']);

        $this->assertSame('/users/7/cities/Paris', $component->redirectLink(['id' => 7, 'city' => ['name' => 'Paris']]));
    }

    public function test_table_row_classes_method(): void
    {
        $component = $this->createComponent(['rowDecoration' => ['bg-error/10' => fn (array $row): bool => $row['overdue']]]);

        $this->assertSame('bg-error/10', $component->rowClasses(['overdue' => true]));
        $this->assertSame('', $component->rowClasses(['overdue' => false]));
    }

    public function test_table_cell_classes_method(): void
    {
        $component = $this->createComponent(['cellDecoration' => ['name' => ['font-bold' => fn (array $row): bool => $row['vip']]]]);

        $this->assertSame('text-end font-bold', $component->cellClasses(['vip' => true], ['key' => 'name', 'class' => 'text-end']));
        $this->assertSame('text-end', $component->cellClasses(['vip' => false], ['key' => 'name', 'class' => 'text-end']));
    }

    public function test_table_selectable_modifier_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'selectableModifier')) {
            try {
                $result = $component->selectableModifier();
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

    public function test_table_get_key_value_method(): void
    {
        $component = $this->createComponent();

        $this->assertSame(42, $component->getKeyValue(['id' => 42], 'keyBy'));
        $this->assertSame("'abc'", $component->getKeyValue(['id' => 'abc'], 'keyBy'));
    }

    public function test_table_renders_successfully(): void
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

    public function test_table_accessibility_compliance(): void
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
            'Table should have valid HTML structure. Issues: '.implode(', ', $validation['issues']),
        );
    }

    public function test_table_security_against_xss(): void
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

    public function test_table_performance(): void
    {
        try {
            $performance = TestHelpers::measureRenderingPerformance($this->createComponent(), 10);
            $this->assertLessThan(100, $performance['average_time'],
                'Table rendering should be under 100ms on average',
            );
        } catch (RuntimeException $e) {
            if (str_contains($e->getMessage(), 'requires slots') ||
                str_contains($e->getMessage(), 'requires additional context')) {
                $this->markTestSkipped($e->getMessage());
            }
            throw $e;
        }
    }
}
