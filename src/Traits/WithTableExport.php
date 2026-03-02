<?php

declare(strict_types=1);
/**
 * WithTableExport Trait
 *
 * This trait provides table export functionality for Livewire components.
 *
 * @author     Jacob Martella
 * @copyright  2024 Jacob Martella
 * @license    MIT
 *
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      2.0.0
 */

namespace ArtisanPack\LivewireUiComponents\Traits;

use ArtisanPack\LivewireUiComponents\Support\TableExporter;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Livewire\Attributes\On;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * WithTableExport Trait
 *
 * Provides methods for handling table export requests from Table components.
 * Use this trait in your Livewire component that contains a Table component.
 *
 * @since 2.0.0
 */
trait WithTableExport
{
    /**
     * Handle table export request.
     *
     * Listen for the 'table-export-request' event dispatched from the Table component.
     *
     * @since 2.0.0
     *
     * @param  string  $format  The export format (csv, xlsx, or pdf).
     * @param  string  $tableId  The table identifier.
     *
     * @return Response|StreamedResponse|null The download response or null on failure.
     */
    #[On('table-export-request')]
    public function handleTableExport(string $format, string $tableId = 'default'): StreamedResponse|Response|null
    {
        try {
            $exportData = $this->getTableExportData($tableId);

            if (empty($exportData)) {
                return null;
            }

            $headers  = $exportData['headers'] ?? [];
            $rows     = $exportData['rows'] ?? [];
            $filename = $exportData['filename'] ?? 'table-export-'.date('Y-m-d');

            return TableExporter::make($headers, $rows, $filename)->export($format);
        } catch (RuntimeException|InvalidArgumentException $e) {
            // Log the error for debugging
            report($e);

            // Return a 500 error response with a safe message
            return response(__('Export failed. Please try again or contact support.'), 500);
        }
    }

    /**
     * Export table to CSV.
     *
     * @since 2.0.0
     *
     * @param  string  $tableId  The table identifier.
     *
     * @return Response|StreamedResponse|null The CSV download response, error response, or null.
     */
    public function exportTableToCsv(string $tableId = 'default'): StreamedResponse|Response|null
    {
        return $this->handleTableExport('csv', $tableId);
    }

    /**
     * Export table to XLSX.
     *
     * @since 2.0.0
     *
     * @param  string  $tableId  The table identifier.
     *
     * @return Response|StreamedResponse|null The XLSX download response, error response, or null.
     */
    public function exportTableToXlsx(string $tableId = 'default'): StreamedResponse|Response|null
    {
        return $this->handleTableExport('xlsx', $tableId);
    }

    /**
     * Get the export data for a table.
     *
     * Override this method in your Livewire component to provide the export data.
     *
     * @since 2.0.0
     *
     * @param  string  $tableId  The table identifier.
     *
     * @return array{headers: array, rows: array, filename?: string} The export data.
     */
    abstract public function getTableExportData(string $tableId = 'default'): array;

    /**
     * Check if XLSX export is available.
     *
     * @since 2.0.0
     *
     * @return bool True if XLSX export is supported.
     */
    public function canExportXlsx(): bool
    {
        return TableExporter::supportsXlsx();
    }
}
