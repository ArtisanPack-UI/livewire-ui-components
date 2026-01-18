<?php

declare(strict_types=1);
/**
 * Table Exporter
 *
 * This class provides export functionality for table data to various formats.
 *
 * @author     Jacob Martella
 * @copyright  2024 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      2.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Support;

use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * TableExporter Class
 *
 * Provides methods for exporting table data to CSV, XLSX, and PDF formats.
 *
 * @since 2.0.0
 */
class TableExporter
{
    /**
     * The headers for the export.
     *
     * @since 2.0.0
     */
    protected array $headers = [];

    /**
     * The rows for the export.
     *
     * @since 2.0.0
     */
    protected array $rows = [];

    /**
     * The filename for the export (without extension).
     *
     * @since 2.0.0
     */
    protected string $filename = 'table-export';

    /**
     * The title for PDF export.
     *
     * @since 2.0.0
     */
    protected ?string $pdfTitle = null;

    /**
     * The paper orientation for PDF export.
     *
     * @since 2.0.0
     */
    protected string $pdfOrientation = 'portrait';

    /**
     * The paper size for PDF export.
     *
     * @since 2.0.0
     */
    protected string $pdfPaperSize = 'a4';

    /**
     * Custom header content for PDF export.
     *
     * @since 2.0.0
     */
    protected ?string $pdfHeader = null;

    /**
     * Custom footer content for PDF export.
     *
     * @since 2.0.0
     */
    protected ?string $pdfFooter = null;

    /**
     * Create a new TableExporter instance.
     *
     * @since 2.0.0
     *
     * @param  array  $headers  The column headers.
     * @param  array  $rows  The data rows.
     * @param  string|null  $filename  The filename (without extension).
     */
    public function __construct(array $headers = [], array $rows = [], ?string $filename = null)
    {
        $this->headers = $headers;
        $this->rows = $rows;

        if ($filename) {
            $this->filename = $filename;
        }
    }

    /**
     * Create a new TableExporter instance statically.
     *
     * @since 2.0.0
     *
     * @param  array  $headers  The column headers.
     * @param  array  $rows  The data rows.
     * @param  string|null  $filename  The filename (without extension).
     */
    public static function make(array $headers = [], array $rows = [], ?string $filename = null): static
    {
        return new static($headers, $rows, $filename);
    }

    /**
     * Set the headers.
     *
     * @since 2.0.0
     *
     * @param  array  $headers  The column headers.
     * @return $this
     */
    public function setHeaders(array $headers): static
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Set the rows.
     *
     * @since 2.0.0
     *
     * @param  array  $rows  The data rows.
     * @return $this
     */
    public function setRows(array $rows): static
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * Set the filename.
     *
     * @since 2.0.0
     *
     * @param  string  $filename  The filename (without extension).
     *
     * @return $this
     */
    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Set the PDF title.
     *
     * @since 2.0.0
     *
     * @param  string  $title  The title for the PDF.
     *
     * @return $this
     */
    public function setPdfTitle(string $title): static
    {
        $this->pdfTitle = $title;

        return $this;
    }

    /**
     * Set the PDF orientation.
     *
     * @since 2.0.0
     *
     * @param  string  $orientation  The orientation (portrait or landscape).
     *
     * @return $this
     */
    public function setPdfOrientation(string $orientation): static
    {
        $this->pdfOrientation = $orientation;

        return $this;
    }

    /**
     * Set the PDF paper size.
     *
     * @since 2.0.0
     *
     * @param  string  $size  The paper size (a4, letter, legal, etc.).
     *
     * @return $this
     */
    public function setPdfPaperSize(string $size): static
    {
        $this->pdfPaperSize = $size;

        return $this;
    }

    /**
     * Set custom header content for PDF.
     *
     * WARNING: This method accepts HTML content. The content will be sanitized
     * to remove potentially dangerous elements (script, style, iframe, etc.),
     * but callers should still validate and sanitize user-provided content
     * before passing it to this method.
     *
     * @since 2.0.0
     *
     * @param  string  $header  The header HTML content.
     *
     * @return $this
     */
    public function setPdfHeader(string $header): static
    {
        $this->pdfHeader = $header;

        return $this;
    }

    /**
     * Set custom footer content for PDF.
     *
     * WARNING: This method accepts HTML content. The content will be sanitized
     * to remove potentially dangerous elements (script, style, iframe, etc.),
     * but callers should still validate and sanitize user-provided content
     * before passing it to this method.
     *
     * @since 2.0.0
     *
     * @param  string  $footer  The footer HTML content.
     *
     * @return $this
     */
    public function setPdfFooter(string $footer): static
    {
        $this->pdfFooter = $footer;

        return $this;
    }

    /**
     * Sanitize HTML content to prevent XSS attacks.
     *
     * Removes dangerous elements while preserving safe formatting HTML.
     *
     * @since 2.0.0
     *
     * @param  string  $html  The HTML content to sanitize.
     *
     * @return string The sanitized HTML.
     */
    protected function sanitizeHtml(string $html): string
    {
        // Remove script tags and their contents
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

        // Remove style tags and their contents
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);

        // Remove dangerous tags (keep content)
        $html = preg_replace('/<(iframe|object|embed|form|input|button|textarea|select|link|meta|base)[^>]*>/i', '', $html);
        $html = preg_replace('/<\/(iframe|object|embed|form|input|button|textarea|select)>/i', '', $html);

        // Remove event handlers from remaining tags
        $html = preg_replace('/\s+on\w+\s*=\s*(["\'])[^"\']*\1/i', '', $html);
        $html = preg_replace('/\s+on\w+\s*=\s*[^\s>]+/i', '', $html);

        // Remove javascript: and data: URLs
        $html = preg_replace('/\s+href\s*=\s*(["\'])?\s*javascript:[^"\'>\s]*/i', '', $html);
        $html = preg_replace('/\s+src\s*=\s*(["\'])?\s*javascript:[^"\'>\s]*/i', '', $html);
        $html = preg_replace('/\s+href\s*=\s*(["\'])?\s*data:[^"\'>\s]*/i', '', $html);
        $html = preg_replace('/\s+src\s*=\s*(["\'])?\s*data:[^"\'>\s]*/i', '', $html);

        return $html;
    }

    /**
     * Check if PhpSpreadsheet is available.
     *
     * @since 2.0.0
     *
     * @return bool True if PhpSpreadsheet is installed.
     */
    public static function hasPhpSpreadsheet(): bool
    {
        return class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class);
    }

    /**
     * Check if XLSX export is supported.
     *
     * @since 2.0.0
     *
     * @return bool True if XLSX export is available.
     */
    public static function supportsXlsx(): bool
    {
        return self::hasPhpSpreadsheet();
    }

    /**
     * Check if DomPDF is available.
     *
     * @since 2.0.0
     *
     * @return bool True if DomPDF is installed.
     */
    public static function hasDomPdf(): bool
    {
        return class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)
            || class_exists(\Barryvdh\DomPDF\PDF::class);
    }

    /**
     * Check if PDF export is supported.
     *
     * @since 2.0.0
     *
     * @return bool True if PDF export is available.
     */
    public static function supportsPdf(): bool
    {
        return self::hasDomPdf();
    }

    /**
     * Sanitize a filename to prevent header injection attacks.
     *
     * Removes CR, LF, and other dangerous characters that could be used
     * for HTTP header injection.
     *
     * @since 2.0.0
     *
     * @param  string  $filename  The filename to sanitize.
     *
     * @return string The sanitized filename.
     */
    protected function sanitizeFilename(string $filename): string
    {
        // Remove CR, LF, and null bytes (header injection prevention)
        $filename = str_replace(["\r", "\n", "\0"], '', $filename);

        // Remove or replace quotes that could break header syntax
        $filename = str_replace(['"', "'"], '', $filename);

        // Remove path traversal characters
        $filename = basename($filename);

        // Remove any remaining control characters
        $filename = preg_replace('/[\x00-\x1F\x7F]/', '', $filename);

        // Ensure filename is not empty after sanitization
        if ('' === trim($filename)) {
            $filename = 'export-'.date('Y-m-d-His');
        }

        return $filename;
    }

    /**
     * Build a Content-Disposition header value with RFC5987 encoding.
     *
     * Includes both ASCII fallback filename and UTF-8 encoded filename*
     * parameter for proper handling of international characters.
     *
     * @since 2.0.0
     *
     * @param  string  $filename  The filename (already sanitized).
     *
     * @return string The Content-Disposition header value.
     */
    protected function buildContentDisposition(string $filename): string
    {
        $sanitized = $this->sanitizeFilename($filename);

        // ASCII-safe fallback (replace non-ASCII with underscores)
        $asciiFallback = preg_replace('/[^\x20-\x7E]/', '_', $sanitized);

        // RFC5987 encoded filename for UTF-8 support
        $utf8Encoded = "UTF-8''".rawurlencode($sanitized);

        return sprintf(
            'attachment; filename="%s"; filename*=%s',
            $asciiFallback,
            $utf8Encoded
        );
    }

    /**
     * Export to CSV format.
     *
     * @since 2.0.0
     *
     * @return StreamedResponse The CSV download response.
     */
    public function toCsv(): StreamedResponse
    {
        $filename = $this->sanitizeFilename($this->filename.'.csv');
        $headers = $this->headers;
        $rows = $this->rows;

        return response()->streamDownload(function () use ($headers, $rows): void {
            $handle = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            // Write headers
            fputcsv($handle, $headers);

            // Write rows
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => $this->buildContentDisposition($filename),
        ]);
    }

    /**
     * Export to XLSX format.
     *
     * Requires phpoffice/phpspreadsheet to be installed.
     *
     * @since 2.0.0
     *
     * @return StreamedResponse The XLSX download response.
     *
     * @throws \RuntimeException If PhpSpreadsheet is not installed.
     */
    public function toXlsx(): StreamedResponse
    {
        if (! self::hasPhpSpreadsheet()) {
            throw new \RuntimeException(
                'PhpSpreadsheet is required for XLSX export. Install it with: composer require phpoffice/phpspreadsheet'
            );
        }

        $filename = $this->sanitizeFilename($this->filename.'.xlsx');
        $headers = $this->headers;
        $rows = $this->rows;

        return response()->streamDownload(function () use ($headers, $rows): void {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
            $sheet = $spreadsheet->getActiveSheet();

            // Set sheet title
            $sheet->setTitle('Export');

            // Write headers (row 1)
            $columnIndex = 1;
            foreach ($headers as $header) {
                $cell = $sheet->getCellByColumnAndRow($columnIndex, 1);
                $cell->setValue($header);

                // Make headers bold
                $sheet->getStyleByColumnAndRow($columnIndex, 1)->getFont()->setBold(true);

                // Set background color for headers
                $sheet->getStyleByColumnAndRow($columnIndex, 1)
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('E5E7EB');

                $columnIndex++;
            }

            // Write rows (starting at row 2)
            $rowIndex = 2;
            foreach ($rows as $row) {
                $columnIndex = 1;
                foreach ($row as $value) {
                    $sheet->getCellByColumnAndRow($columnIndex, $rowIndex)->setValue($value);
                    $columnIndex++;
                }
                $rowIndex++;
            }

            // Auto-size columns
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            }

            // Freeze header row
            $sheet->freezePane('A2');

            // Enable auto-filter
            $sheet->setAutoFilter($sheet->calculateWorksheetDimension());

            // Write to output
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');

            // Clean up
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => $this->buildContentDisposition($filename),
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    /**
     * Export to PDF format.
     *
     * Requires barryvdh/laravel-dompdf to be installed.
     *
     * @since 2.0.0
     *
     * @return \Illuminate\Http\Response The PDF download response.
     *
     * @throws \RuntimeException If DomPDF is not installed.
     */
    public function toPdf(): \Illuminate\Http\Response
    {
        if (! self::hasDomPdf()) {
            throw new \RuntimeException(
                'DomPDF is required for PDF export. Install it with: composer require barryvdh/laravel-dompdf'
            );
        }

        $filename = $this->sanitizeFilename($this->filename.'.pdf');

        // Generate HTML content for PDF
        $html = $this->generatePdfHtml();

        // Create PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)
            ->setPaper($this->pdfPaperSize, $this->pdfOrientation);

        return $pdf->download($filename);
    }

    /**
     * Generate HTML content for PDF export.
     *
     * @since 2.0.0
     *
     * @return string The HTML content.
     */
    protected function generatePdfHtml(): string
    {
        $title = $this->pdfTitle ?? $this->filename;
        $header = $this->pdfHeader;
        $footer = $this->pdfFooter;
        $headers = $this->headers;
        $rows = $this->rows;

        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>'.e( $title ).'</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }
        .header h1 {
            margin: 0;
            font-size: 16pt;
            color: #333;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 10px;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #fafafa;
        }
        tr:hover {
            background-color: #f0f0f0;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>';

        // Add custom header if provided, otherwise use title
        if ($header) {
            $html .= '<div class="header">'.$this->sanitizeHtml($header).'</div>';
        } else {
            $html .= '<div class="header"><h1>'.e( $title ).'</h1></div>';
        }

        // Add table
        $html .= '<table>
    <thead>
        <tr>';

        foreach ($headers as $headerText) {
            $html .= '<th>'.e( $headerText ).'</th>';
        }

        $html .= '
        </tr>
    </thead>
    <tbody>';

        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($row as $cell) {
                $html .= '<td>'.e( $cell ).'</td>';
            }
            $html .= '</tr>';
        }

        $html .= '
    </tbody>
</table>';

        // Add custom footer if provided
        if ($footer) {
            $html .= '<div class="footer">'.$this->sanitizeHtml($footer).'</div>';
        }

        $html .= '
</body>
</html>';

        return $html;
    }

    /**
     * Export to the specified format.
     *
     * @since 2.0.0
     *
     * @param  string  $format  The export format (csv, xlsx, or pdf).
     *
     * @return StreamedResponse|\Illuminate\Http\Response The download response.
     *
     * @throws \InvalidArgumentException If the format is not supported.
     */
    public function export(string $format = 'csv'): StreamedResponse|\Illuminate\Http\Response
    {
        return match ($format) {
            'csv' => $this->toCsv(),
            'xlsx', 'excel' => $this->toXlsx(),
            'pdf' => $this->toPdf(),
            default => throw new \InvalidArgumentException("Unsupported export format: {$format}"),
        };
    }

    /**
     * Get the raw CSV content as a string.
     *
     * @since 2.0.0
     *
     * @return string The CSV content.
     */
    public function getCsvContent(): string
    {
        $handle = fopen('php://temp', 'r+');

        // Add BOM for Excel UTF-8 compatibility
        fwrite($handle, "\xEF\xBB\xBF");

        // Write headers
        fputcsv($handle, $this->headers);

        // Write rows
        foreach ($this->rows as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return $content;
    }
}
