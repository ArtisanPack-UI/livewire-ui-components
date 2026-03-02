<?php

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\Tests\Unit\Support;

use ArtisanPack\LivewireUiComponents\Support\TableExporter;
use ArtisanPack\LivewireUiComponents\Tests\TestCase;
use InvalidArgumentException;
use ReflectionClass;
use RuntimeException;

/**
 * Comprehensive unit tests for the TableExporter class.
 *
 * Tests CSV export, header/row normalization, filename sanitization,
 * and various export configuration options.
 *
 * @since 2.0.0
 */
class TableExporterTest extends TestCase
{
    /**
     * Test that TableExporter can be instantiated.
     */
    public function test_table_exporter_can_be_instantiated(): void
    {
        $exporter = new TableExporter;

        $this->assertInstanceOf(TableExporter::class, $exporter);
    }

    /**
     * Test static make factory method.
     */
    public function test_make_factory_creates_instance(): void
    {
        $headers  = ['Name', 'Email'];
        $rows     = [['John', 'john@example.com']];
        $filename = 'test-export';

        $exporter = TableExporter::make($headers, $rows, $filename);

        $this->assertInstanceOf(TableExporter::class, $exporter);
    }

    /**
     * Test setting headers via fluent API.
     */
    public function test_set_headers_returns_self(): void
    {
        $exporter = new TableExporter;
        $result   = $exporter->setHeaders(['Name', 'Email']);

        $this->assertSame($exporter, $result);
    }

    /**
     * Test setting rows via fluent API.
     */
    public function test_set_rows_returns_self(): void
    {
        $exporter = new TableExporter;
        $result   = $exporter->setRows([['John', 'john@example.com']]);

        $this->assertSame($exporter, $result);
    }

    /**
     * Test setting filename via fluent API.
     */
    public function test_set_filename_returns_self(): void
    {
        $exporter = new TableExporter;
        $result   = $exporter->setFilename('custom-export');

        $this->assertSame($exporter, $result);
    }

    /**
     * Test normalizing flat array headers.
     */
    public function test_normalize_headers_handles_flat_array(): void
    {
        $headers  = ['ID', 'Name', 'Email'];
        $exporter = new TableExporter($headers);

        // Use reflection to access protected method
        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('normalizeHeaders');
        $method->setAccessible(true);

        $result = $method->invoke($exporter);

        $this->assertEquals(['ID', 'Name', 'Email'], $result);
    }

    /**
     * Test normalizing Table component format headers.
     */
    public function test_normalize_headers_handles_table_component_format(): void
    {
        $headers = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email Address'],
        ];
        $exporter = new TableExporter($headers);

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('normalizeHeaders');
        $method->setAccessible(true);

        $result = $method->invoke($exporter);

        $this->assertEquals(['ID', 'Name', 'Email Address'], $result);
    }

    /**
     * Test normalizing empty headers.
     */
    public function test_normalize_headers_handles_empty_array(): void
    {
        $exporter = new TableExporter([]);

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('normalizeHeaders');
        $method->setAccessible(true);

        $result = $method->invoke($exporter);

        $this->assertEquals([], $result);
    }

    /**
     * Test getting header keys for Table component format.
     */
    public function test_get_header_keys_from_table_format(): void
    {
        $headers = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Name'],
        ];
        $exporter = new TableExporter($headers);

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('getHeaderKeys');
        $method->setAccessible(true);

        $result = $method->invoke($exporter);

        $this->assertEquals(['id', 'name'], $result);
    }

    /**
     * Test normalizing rows with associative arrays.
     */
    public function test_normalize_rows_handles_associative_arrays(): void
    {
        $headers = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Name'],
        ];
        $rows = [
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Jane'],
        ];
        $exporter = new TableExporter($headers, $rows);

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('normalizeRows');
        $method->setAccessible(true);

        $result = $method->invoke($exporter);

        $this->assertEquals([
            [1, 'John'],
            [2, 'Jane'],
        ], $result);
    }

    /**
     * Test normalizing rows with flat arrays.
     */
    public function test_normalize_rows_handles_flat_arrays(): void
    {
        $headers  = ['ID', 'Name'];
        $rows     = [
            [1, 'John'],
            [2, 'Jane'],
        ];
        $exporter = new TableExporter($headers, $rows);

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('normalizeRows');
        $method->setAccessible(true);

        $result = $method->invoke($exporter);

        $this->assertEquals([
            [1, 'John'],
            [2, 'Jane'],
        ], $result);
    }

    /**
     * Test associative array detection.
     */
    public function test_is_associative_array_returns_true_for_associative(): void
    {
        $exporter = new TableExporter;

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('isAssociativeArray');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($exporter, ['key' => 'value', 'foo' => 'bar']));
    }

    /**
     * Test associative array detection returns false for indexed arrays.
     */
    public function test_is_associative_array_returns_false_for_indexed(): void
    {
        $exporter = new TableExporter;

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('isAssociativeArray');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke($exporter, ['value1', 'value2', 'value3']));
    }

    /**
     * Test associative array detection handles empty array.
     */
    public function test_is_associative_array_returns_false_for_empty(): void
    {
        $exporter = new TableExporter;

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('isAssociativeArray');
        $method->setAccessible(true);

        $this->assertFalse($method->invoke($exporter, []));
    }

    /**
     * Test filename sanitization removes dangerous characters.
     */
    public function test_sanitize_filename_removes_dangerous_characters(): void
    {
        $exporter = new TableExporter;

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('sanitizeFilename');
        $method->setAccessible(true);

        // Test CRLF injection
        $result = $method->invoke($exporter, "test\r\ninjection");
        $this->assertStringNotContainsString("\r", $result);
        $this->assertStringNotContainsString("\n", $result);

        // Test quote removal
        $result = $method->invoke($exporter, 'test"file\'name');
        $this->assertStringNotContainsString('"', $result);
        $this->assertStringNotContainsString("'", $result);

        // Test path traversal prevention
        $result = $method->invoke($exporter, '../../../etc/passwd');
        $this->assertEquals('passwd', $result);
    }

    /**
     * Test filename sanitization handles empty result.
     */
    public function test_sanitize_filename_provides_fallback_for_empty_result(): void
    {
        $exporter = new TableExporter;

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('sanitizeFilename');
        $method->setAccessible(true);

        $result = $method->invoke($exporter, "\r\n\0");
        $this->assertStringStartsWith('export-', $result);
    }

    /**
     * Test content disposition header building.
     */
    public function test_build_content_disposition_creates_valid_header(): void
    {
        $exporter = new TableExporter;

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('buildContentDisposition');
        $method->setAccessible(true);

        $result = $method->invoke($exporter, 'test-file.csv');

        $this->assertStringContainsString('attachment', $result);
        $this->assertStringContainsString('filename=', $result);
        $this->assertStringContainsString('filename*=', $result);
    }

    /**
     * Test HTML sanitization removes script tags.
     */
    public function test_sanitize_html_removes_script_tags(): void
    {
        $exporter = new TableExporter;

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('sanitizeHtml');
        $method->setAccessible(true);

        $html   = '<p>Safe content</p><script>alert("xss")</script>';
        $result = $method->invoke($exporter, $html);

        $this->assertStringContainsString('<p>Safe content</p>', $result);
        $this->assertStringNotContainsString('<script', $result);
        $this->assertStringNotContainsString('alert', $result);
    }

    /**
     * Test HTML sanitization removes event handlers.
     */
    public function test_sanitize_html_removes_event_handlers(): void
    {
        $exporter = new TableExporter;

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('sanitizeHtml');
        $method->setAccessible(true);

        $html   = '<div onclick="alert(1)">Content</div>';
        $result = $method->invoke($exporter, $html);

        $this->assertStringNotContainsString('onclick', $result);
    }

    /**
     * Test HTML sanitization removes javascript URLs.
     */
    public function test_sanitize_html_removes_javascript_urls(): void
    {
        $exporter = new TableExporter;

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('sanitizeHtml');
        $method->setAccessible(true);

        $html   = '<a href="javascript:alert(1)">Link</a>';
        $result = $method->invoke($exporter, $html);

        $this->assertStringNotContainsString('javascript:', $result);
    }

    /**
     * Test PDF settings fluent API.
     */
    public function test_pdf_settings_are_chainable(): void
    {
        $exporter = new TableExporter;

        $result = $exporter
            ->setPdfTitle('Test Report')
            ->setPdfOrientation('landscape')
            ->setPdfPaperSize('letter')
            ->setPdfHeader('<h1>Header</h1>')
            ->setPdfFooter('<p>Footer</p>');

        $this->assertSame($exporter, $result);
    }

    /**
     * Test hasPhpSpreadsheet method returns boolean.
     */
    public function test_has_php_spreadsheet_returns_boolean(): void
    {
        $result = TableExporter::hasPhpSpreadsheet();

        $this->assertIsBool($result);
    }

    /**
     * Test supportsXlsx is equivalent to hasPhpSpreadsheet.
     */
    public function test_supports_xlsx_returns_same_as_has_php_spreadsheet(): void
    {
        $this->assertEquals(
            TableExporter::hasPhpSpreadsheet(),
            TableExporter::supportsXlsx(),
        );
    }

    /**
     * Test hasDomPdf method returns boolean.
     */
    public function test_has_dom_pdf_returns_boolean(): void
    {
        $result = TableExporter::hasDomPdf();

        $this->assertIsBool($result);
    }

    /**
     * Test supportsPdf is equivalent to hasDomPdf.
     */
    public function test_supports_pdf_returns_same_as_has_dom_pdf(): void
    {
        $this->assertEquals(
            TableExporter::hasDomPdf(),
            TableExporter::supportsPdf(),
        );
    }

    /**
     * Test export method throws exception for unsupported format.
     */
    public function test_export_throws_exception_for_invalid_format(): void
    {
        $exporter = new TableExporter(['Name'], [['John']]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported export format: invalid');

        $exporter->export('invalid');
    }

    /**
     * Test toCsv generates StreamedResponse.
     */
    public function test_to_csv_returns_streamed_response(): void
    {
        $headers  = ['Name', 'Email'];
        $rows     = [
            ['John Doe', 'john@example.com'],
            ['Jane Smith', 'jane@example.com'],
        ];
        $exporter = new TableExporter($headers, $rows, 'test-export');

        $response = $exporter->toCsv();

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class, $response);
        $this->assertEquals('text/csv; charset=UTF-8', $response->headers->get('Content-Type'));
    }

    /**
     * Test getCsvContent returns valid CSV string.
     */
    public function test_get_csv_content_returns_valid_csv(): void
    {
        $headers  = ['Name', 'Email'];
        $rows     = [
            ['John', 'john@example.com'],
        ];
        $exporter = new TableExporter($headers, $rows);

        $content = $exporter->getCsvContent();

        // Check for BOM
        $this->assertStringStartsWith("\xEF\xBB\xBF", $content);

        // Check headers are present
        $this->assertStringContainsString('Name', $content);
        $this->assertStringContainsString('Email', $content);

        // Check data is present
        $this->assertStringContainsString('John', $content);
        $this->assertStringContainsString('john@example.com', $content);
    }

    /**
     * Test toXlsx throws exception when PhpSpreadsheet not installed.
     */
    public function test_to_xlsx_throws_exception_without_phpspreadsheet(): void
    {
        if (TableExporter::hasPhpSpreadsheet()) {
            $this->markTestSkipped('PhpSpreadsheet is installed, cannot test exception path');
        }

        $exporter = new TableExporter(['Name'], [['John']]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('PhpSpreadsheet is required');

        $exporter->toXlsx();
    }

    /**
     * Test toPdf throws exception when DomPDF not installed.
     */
    public function test_to_pdf_throws_exception_without_dompdf(): void
    {
        if (TableExporter::hasDomPdf()) {
            $this->markTestSkipped('DomPDF is installed, cannot test exception path');
        }

        $exporter = new TableExporter(['Name'], [['John']]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('DomPDF is required');

        $exporter->toPdf();
    }

    /**
     * Test generatePdfHtml creates valid HTML.
     */
    public function test_generate_pdf_html_creates_valid_html(): void
    {
        $headers  = ['Name', 'Email'];
        $rows     = [['John', 'john@example.com']];
        $exporter = new TableExporter($headers, $rows, 'test-report');

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('generatePdfHtml');
        $method->setAccessible(true);

        $html = $method->invoke($exporter);

        // Check HTML structure
        $this->assertStringContainsString('<!DOCTYPE html>', $html);
        $this->assertStringContainsString('<html>', $html);
        $this->assertStringContainsString('<head>', $html);
        $this->assertStringContainsString('<body>', $html);

        // Check for table with headers
        $this->assertStringContainsString('<table>', $html);
        $this->assertStringContainsString('<th>', $html);
        $this->assertStringContainsString('Name', $html);
        $this->assertStringContainsString('Email', $html);

        // Check for data rows
        $this->assertStringContainsString('<td>', $html);
        $this->assertStringContainsString('John', $html);
    }

    /**
     * Test PDF HTML includes custom header and footer.
     */
    public function test_generate_pdf_html_includes_custom_header_footer(): void
    {
        $exporter = new TableExporter(['Name'], [['John']], 'test');
        $exporter
            ->setPdfHeader('<div>Custom Header</div>')
            ->setPdfFooter('<div>Custom Footer</div>');

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('generatePdfHtml');
        $method->setAccessible(true);

        $html = $method->invoke($exporter);

        $this->assertStringContainsString('Custom Header', $html);
        $this->assertStringContainsString('Custom Footer', $html);
    }

    /**
     * Test export method with csv format.
     */
    public function test_export_handles_csv_format(): void
    {
        $exporter = new TableExporter(['Name'], [['John']]);

        $response = $exporter->export('csv');

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class, $response);
    }

    /**
     * Test rows maintain column order from headers.
     */
    public function test_rows_maintain_column_order_from_headers(): void
    {
        $headers = [
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'name', 'label' => 'Name'],
        ];
        $rows = [
            ['name' => 'John', 'email' => 'john@example.com'],
        ];
        $exporter = new TableExporter($headers, $rows);

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('normalizeRows');
        $method->setAccessible(true);

        $result = $method->invoke($exporter);

        // Email should be first because it's first in headers
        $this->assertEquals([['john@example.com', 'John']], $result);
    }

    /**
     * Test handling of missing keys in row data.
     */
    public function test_normalize_rows_handles_missing_keys(): void
    {
        $headers = [
            ['key' => 'id', 'label' => 'ID'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'missing', 'label' => 'Missing'],
        ];
        $rows = [
            ['id' => 1, 'name' => 'John'],
        ];
        $exporter = new TableExporter($headers, $rows);

        $reflection = new ReflectionClass($exporter);
        $method     = $reflection->getMethod('normalizeRows');
        $method->setAccessible(true);

        $result = $method->invoke($exporter);

        // Missing key should be empty string
        $this->assertEquals([[1, 'John', '']], $result);
    }
}
