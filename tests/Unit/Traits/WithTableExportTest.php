<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Traits;

use ArtisanPack\LivewireUiComponents\Support\TableExporter;
use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use ArtisanPack\LivewireUiComponents\Traits\WithTableExport;
use Illuminate\Http\Response;
use Livewire\Component;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Unit tests for the WithTableExport trait.
 *
 * Tests the trait's ability to handle table export requests
 * and integrate with TableExporter.
 *
 * @since 2.0.0
 */
class WithTableExportTest extends TestCase
{
    /**
     * Test trait can be used in a component.
     */
    public function test_trait_can_be_used_in_component(): void
    {
        $component = new class extends Component
        {
            use WithTableExport;

            public function getTableExportData(string $tableId = 'default'): array
            {
                return [
                    'headers' => ['Name', 'Email'],
                    'rows'    => [['John', 'john@example.com']],
                ];
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        $this->assertInstanceOf(Component::class, $component);
        $this->assertTrue(method_exists($component, 'handleTableExport'));
        $this->assertTrue(method_exists($component, 'exportTableToCsv'));
        $this->assertTrue(method_exists($component, 'exportTableToXlsx'));
        $this->assertTrue(method_exists($component, 'canExportXlsx'));
    }

    /**
     * Test handleTableExport returns null for empty export data.
     */
    public function test_handle_table_export_returns_null_for_empty_data(): void
    {
        $component = new class extends Component
        {
            use WithTableExport;

            public function getTableExportData(string $tableId = 'default'): array
            {
                return [];
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        $result = $component->handleTableExport('csv', 'default');

        $this->assertNull($result);
    }

    /**
     * Test handleTableExport returns StreamedResponse for CSV.
     */
    public function test_handle_table_export_returns_response_for_csv(): void
    {
        $component = new class extends Component
        {
            use WithTableExport;

            public function getTableExportData(string $tableId = 'default'): array
            {
                return [
                    'headers'  => ['Name', 'Email'],
                    'rows'     => [['John', 'john@example.com']],
                    'filename' => 'test-export',
                ];
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        $result = $component->handleTableExport('csv', 'default');

        $this->assertInstanceOf(StreamedResponse::class, $result);
    }

    /**
     * Test exportTableToCsv delegates to handleTableExport.
     */
    public function test_export_table_to_csv_delegates_to_handle_table_export(): void
    {
        $component = new class extends Component
        {
            use WithTableExport;

            public function getTableExportData(string $tableId = 'default'): array
            {
                return [
                    'headers' => ['Name'],
                    'rows'    => [['John']],
                ];
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        $result = $component->exportTableToCsv('default');

        $this->assertInstanceOf(StreamedResponse::class, $result);
    }

    /**
     * Test exportTableToXlsx delegates to handleTableExport.
     */
    public function test_export_table_to_xlsx_delegates_to_handle_table_export(): void
    {
        if (! TableExporter::hasPhpSpreadsheet()) {
            $this->markTestSkipped('PhpSpreadsheet is not installed');
        }

        $component = new class extends Component
        {
            use WithTableExport;

            public function getTableExportData(string $tableId = 'default'): array
            {
                return [
                    'headers' => ['Name'],
                    'rows'    => [['John']],
                ];
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        $result = $component->exportTableToXlsx('default');

        $this->assertInstanceOf(StreamedResponse::class, $result);
    }

    /**
     * Test canExportXlsx returns boolean.
     */
    public function test_can_export_xlsx_returns_boolean(): void
    {
        $component = new class extends Component
        {
            use WithTableExport;

            public function getTableExportData(string $tableId = 'default'): array
            {
                return [];
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        $result = $component->canExportXlsx();

        $this->assertIsBool($result);
        $this->assertEquals(TableExporter::supportsXlsx(), $result);
    }

    /**
     * Test handleTableExport uses default filename when not provided.
     */
    public function test_handle_table_export_uses_default_filename(): void
    {
        $component = new class extends Component
        {
            use WithTableExport;

            public function getTableExportData(string $tableId = 'default'): array
            {
                return [
                    'headers' => ['Name'],
                    'rows'    => [['John']],
                    // No filename provided
                ];
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        $result = $component->handleTableExport('csv', 'default');

        $this->assertInstanceOf(StreamedResponse::class, $result);
    }

    /**
     * Test handleTableExport catches RuntimeException.
     */
    public function test_handle_table_export_catches_runtime_exception(): void
    {
        if (TableExporter::hasPhpSpreadsheet()) {
            $this->markTestSkipped('Cannot test exception catching when PhpSpreadsheet is installed');
        }

        $component = new class extends Component
        {
            use WithTableExport;

            public function getTableExportData(string $tableId = 'default'): array
            {
                return [
                    'headers' => ['Name'],
                    'rows'    => [['John']],
                ];
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        // This should catch the RuntimeException and return error response
        $result = $component->handleTableExport('xlsx', 'default');

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(500, $result->getStatusCode());
    }

    /**
     * Test handleTableExport catches InvalidArgumentException.
     */
    public function test_handle_table_export_catches_invalid_argument_exception(): void
    {
        $component = new class extends Component
        {
            use WithTableExport;

            public function getTableExportData(string $tableId = 'default'): array
            {
                return [
                    'headers' => ['Name'],
                    'rows'    => [['John']],
                ];
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        // This should catch the InvalidArgumentException and return error response
        $result = $component->handleTableExport('invalid-format', 'default');

        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals(500, $result->getStatusCode());
    }

    /**
     * Test different table IDs are passed to getTableExportData.
     */
    public function test_table_id_is_passed_to_get_table_export_data(): void
    {
        $receivedTableId = null;

        $component = new class($receivedTableId) extends Component
        {
            use WithTableExport;

            private $receivedTableIdRef;

            public function __construct(&$receivedTableIdRef)
            {
                $this->receivedTableIdRef = &$receivedTableIdRef;
            }

            public function getTableExportData(string $tableId = 'default'): array
            {
                $this->receivedTableIdRef = $tableId;

                return [
                    'headers' => ['Name'],
                    'rows'    => [['John']],
                ];
            }

            public function render()
            {
                return '<div></div>';
            }
        };

        $component->handleTableExport('csv', 'users-table');

        $this->assertEquals('users-table', $receivedTableId);
    }

    /**
     * Test getTableExportData is abstract and must be implemented.
     *
     * Note: This test validates that the trait declares getTableExportData as abstract
     * by checking the reflection of the trait. PHP enforces that abstract methods
     * must be implemented at class instantiation time.
     */
    public function test_get_table_export_data_is_declared_abstract(): void
    {
        $reflection = new \ReflectionClass(WithTableExport::class);
        $method     = $reflection->getMethod('getTableExportData');

        $this->assertTrue($method->isAbstract(), 'getTableExportData should be declared as abstract');
    }
}
