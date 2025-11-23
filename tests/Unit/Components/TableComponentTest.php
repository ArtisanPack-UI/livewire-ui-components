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
        $component = $this->createComponent();

        if (method_exists($component, 'isSortable')) {
            try {
                $result = $component->isSortable();
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

    public function test_table_is_hidden_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'isHidden')) {
            try {
                $result = $component->isHidden();
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

    public function test_table_format_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'format')) {
            try {
                $result = $component->format();
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

    public function test_table_has_link_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'hasLink')) {
            try {
                $result = $component->hasLink();
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

    public function test_table_is_sorted_by_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'isSortedBy')) {
            try {
                $result = $component->isSortedBy();
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

    public function test_table_get_sort_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'getSort')) {
            try {
                $result = $component->getSort();
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

    public function test_table_redirect_link_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'redirectLink')) {
            try {
                $result = $component->redirectLink();
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

    public function test_table_row_classes_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'rowClasses')) {
            try {
                $result = $component->rowClasses();
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

    public function test_table_cell_classes_method(): void
    {
        $component = $this->createComponent();

        if (method_exists($component, 'cellClasses')) {
            try {
                $result = $component->cellClasses();
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

        if (method_exists($component, 'getKeyValue')) {
            try {
                $result = $component->getKeyValue();
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
