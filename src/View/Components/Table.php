<?php

declare(strict_types=1);
/**
 * Table
 *
 * This file contains the Table class for the ArtisanPack UI Livewire UI Components package.
 *
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArrayAccess;
use ArtisanPack\LivewireUiComponents\Support\GlassHelper;
use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\View\Component;

/**
 * Table Class
 *
 * Provides functionality for the Table component.
 *
 * @since 1.0.0
 */
class Table extends Component
{
    /**
     * Loop variable for the table.
     *
     * @since 1.0.0
     */
    public mixed $loop = null;

    /**
     * Constructor for the Table component.
     *
     * @param  array  $headers  Array of header definitions.
     * @param  array|ArrayAccess  $rows  Data rows to display in the table.
     * @param  string|null  $id  Optional ID for the table.
     * @param  bool|null  $striped  Whether to use striped rows.
     * @param  bool|null  $noHeaders  Whether to hide table headers.
     * @param  bool|null  $selectable  Whether rows can be selected with checkboxes.
     * @param  string|null  $selectableKey  Key to use for selectable rows.
     * @param  bool|null  $expandable  Whether rows can be expanded.
     * @param  string|null  $expandableKey  Key to use for expandable rows.
     * @param  mixed|null  $expandableCondition  Condition to determine if a row is expandable.
     * @param  string|null  $link  URL pattern for row links.
     * @param  bool|null  $withPagination  Whether to show pagination controls.
     * @param  string|null  $perPage  Wire model for items per page.
     * @param  array|null  $perPageValues  Available options for items per page.
     * @param  array|null  $sortBy  Sorting configuration.
     * @param  array|null  $rowDecoration  Row decoration rules.
     * @param  array|null  $cellDecoration  Cell decoration rules.
     * @param  bool|null  $showEmptyText  Whether to show text when no records found.
     * @param  mixed|null  $emptyText  Text to display when no records found.
     * @param  string  $containerClass  CSS class for the table container.
     * @param  bool|null  $noHover  Whether to disable hover effect on rows.
     * @param  mixed|null  $actions  Slot for row actions.
     * @param  mixed|null  $tr  Slot for custom row rendering.
     * @param  mixed|null  $cell  Slot for custom cell rendering.
     * @param  mixed|null  $expansion  Slot for expanded row content.
     * @param  mixed|null  $empty  Slot for empty state content.
     * @param  mixed|null  $footer  Slot for table footer.
     * @param  bool  $sortable  Whether rows can be drag-and-drop sorted (Livewire 4+ only).
     * @param  string|null  $sortableKey  Key to use for sortable item identification. Defaults to $keyBy.
     * @param  string|null  $sortGroup  Group name for cross-list dragging (wire:sort:group).
     * @param  bool  $sortHandle  Whether to use a sort handle instead of the entire row.
     * @param  bool  $infiniteScroll  Whether to enable infinite scrolling (Livewire 4+ only).
     * @param  string  $infiniteScrollMethod  The Livewire method to call when scrolling (default 'loadMore').
     * @param  string|null  $infiniteScrollModifier  Modifier for wire:intersect (.once, .half, .full).
     * @param  string  $infiniteScrollText  Text to display while loading more items.
     * @param  bool  $hasMorePages  Whether there are more pages to load (for infinite scroll).
     *
     * @since 1.0.0
     * @since 2.0.0 Added sortable, sortableKey, sortGroup, and sortHandle props for Livewire 4 wire:sort support.
     * @since 2.0.0 Added infiniteScroll, infiniteScrollMethod, infiniteScrollModifier, infiniteScrollText, and hasMorePages props for Livewire 4 wire:intersect support.
     */
    public function __construct(
        public array $headers,
        public ArrayAccess|array $rows,
        public ?string $id = null,
        public ?bool $striped = false,
        public ?bool $noHeaders = false,
        public ?bool $selectable = false,
        public ?string $selectableKey = 'id',
        public ?bool $expandable = false,
        public ?string $expandableKey = 'id',
        public mixed $expandableCondition = null,
        public ?string $link = null,
        public ?bool $withPagination = false,
        public ?string $perPage = null,
        public ?array $perPageValues = [10, 20, 50, 100],
        public ?array $sortBy = [],
        public ?array $rowDecoration = [],
        public ?array $cellDecoration = [],
        public ?bool $showEmptyText = false,
        public mixed $emptyText = 'No records found.',
        public string $containerClass = 'overflow-x-auto',
        public ?bool $noHover = false,
        public string $uuid = '',
        public string $keyBy = 'id',

        // Glass effect props (for header)
        public ?string $headerGlass = null,
        public ?string $headerGlassTint = null,
        public ?int $headerGlassTintOpacity = null,

        // Drag-and-drop sorting props (Livewire 4+)
        public bool $sortable = false,
        public ?string $sortableKey = null,
        public ?string $sortGroup = null,
        public bool $sortHandle = false,

        // Infinite scroll props (Livewire 4+)
        public bool $infiniteScroll = false,
        public string $infiniteScrollMethod = 'loadMore',
        public ?string $infiniteScrollModifier = null,
        public string $infiniteScrollText = 'Scroll for more',
        public bool $hasMorePages = true,

        // Virtual scrolling props
        public bool $virtualScroll = false,
        public int $virtualRowHeight = 48,
        public int $virtualBuffer = 10,
        public ?int $virtualContainerHeight = null,

        // Export props
        public bool $exportable = false,
        public array $exportFormats = ['csv'],
        public ?string $exportFilename = null,

        // Slots
        public mixed $actions = null,
        public mixed $tr = null,
        public mixed $cell = null,
        public mixed $expansion = null,
        public mixed $empty = null,
        public mixed $footer = null,
        public mixed $sortHandleSlot = null,

    ) {
        if ($this->selectable && $this->expandable) {
            throw new Exception('You can not combine `expandable` with `selectable`.');
        }

        // Temp
        $rowDecoration = $this->rowDecoration;
        $cellDecoration = $this->cellDecoration;
        $headers = $this->headers;

        // Remove them from serialization, because they are closures.
        unset($this->rowDecoration);
        unset($this->cellDecoration);
        unset($this->headers);

        // Set uuid if not provided or empty
        if (empty($this->uuid)) {
            // Serialize
            $this->uuid = 'artisanpack'.md5(serialize($this)).$id;
        }

        // Put them back
        $this->rowDecoration = $rowDecoration;
        $this->cellDecoration = $cellDecoration;
        $this->headers = $headers;
    }

    // Get all ids for selectable and expandable features
    public function getAllIds(): array
    {
        if (is_array($this->rows)) {
            return collect($this->rows)->pluck($this->selectableKey)->all();
        }

        return $this->rows->pluck($this->selectableKey)->all();
    }

    // Check if header is sortable
    public function isSortable(mixed $header): bool
    {
        return count($this->sortBy) && ($header['sortable'] ?? true);
    }

    // Check if header is hidden
    public function isHidden(mixed $header): bool
    {
        return $header['hidden'] ?? false;
    }

    // Format header
    public function format(mixed $row, mixed $field, mixed $header): mixed
    {
        $format = $header['format'] ?? null;

        if (! $format) {
            return $field;
        }

        if (is_callable($format)) {
            return $format($row, $field);
        }

        if ($format[0] == 'currency') {
            return ($format[2] ?? '').number_format($field, ...str_split($format[1]));
        }

        if ($format[0] == 'date' && $field) {
            return Carbon::parse($field)->translatedFormat($format[1]);
        }

        return $field;
    }

    // Check if link should be shown in cell
    public function hasLink(mixed $header): bool
    {
        return $this->link && empty($header['disableLink']);
    }

    // Check if is currently sorted by this header
    public function isSortedBy(mixed $header): bool
    {
        if (count($this->sortBy) == 0) {
            return false;
        }

        return $this->sortBy['column'] == ($header['sortBy'] ?? $header['key']);
    }

    // Handle header sort
    public function getSort(mixed $header): mixed
    {
        if (! $this->isSortable($header)) {
            return false;
        }

        if (count($this->sortBy) == 0) {
            return ['column' => '', 'direction' => ''];
        }

        $direction = $this->isSortedBy($header)
            ? ($this->sortBy['direction'] == 'asc') ? 'desc' : 'asc'
            : 'asc';

        return ['column' => $header['sortBy'] ?? $header['key'], 'direction' => $direction];
    }

    // Build row link
    public function redirectLink(mixed $row): string
    {
        $link = $this->link;

        // Transform from `route()` pattern
        $link = Str::of($link)->replace('%5B', '{')->replace('%5D', '}');

        // Extract tokens like {id}, {city.name} ...
        $tokens = Str::of($link)->matchAll('/\{(.*?)\}/');

        // Replace tokens by actual row values
        $tokens->each(function (string $token) use ($row, &$link): void {
            $link = Str::of($link)->replace('{'.$token.'}', data_get($row, $token))->toString();
        });

        return $link;
    }

    public function rowClasses(mixed $row): ?string
    {
        $classes = [];

        foreach ($this->rowDecoration as $class => $condition) {
            if ($condition($row)) {
                $classes[] = $class;
            }
        }

        return Arr::join($classes, ' ');
    }

    public function cellClasses(mixed $row, array $header): ?string
    {
        $classes = Str::of($header['class'] ?? '')->explode(' ')->all();

        foreach ($this->cellDecoration[$header['key']] ?? [] as $class => $condition) {
            if ($condition($row)) {
                $classes[] = $class;
            }
        }

        return Arr::join($classes, ' ');
    }

    public function selectableModifier(): string
    {
        return is_string($this->getAllIds()[0] ?? null) ? '' : '.number';
    }

    /**
     * Get the glass effect CSS classes for the header.
     *
     * @since 2.0.0
     *
     * @return string Space-separated CSS classes.
     */
    public function headerGlassClasses(): string
    {
        return GlassHelper::getClasses($this->headerGlass, $this->headerGlassTint, $this->headerGlassTintOpacity);
    }

    /**
     * Get the glass effect inline styles for custom header tint colors.
     *
     * @since 2.0.0
     *
     * @return string Inline style string.
     */
    public function headerGlassStyle(): string
    {
        return GlassHelper::getFullInlineStyle($this->headerGlassTint);
    }

    public function getKeyValue($row, $key): mixed
    {
        $value = data_get($row, $this->$key);

        return is_numeric($value) && ! str($value)->startsWith('0') ? $value : "'$value'";
    }

    /**
     * Check if wire:sort is supported and enabled.
     *
     * @since 2.0.0
     *
     * @return bool True if sortable and Livewire 4+.
     */
    public function isSortingEnabled(): bool
    {
        return $this->sortable && LivewireHelper::supportsWireSort();
    }

    /**
     * Get the key to use for sortable item identification.
     *
     * Defaults to $keyBy if no specific sortableKey is provided.
     *
     * @since 2.0.0
     *
     * @return string The key to use for wire:sort:item.
     */
    public function getSortableKey(): string
    {
        return $this->sortableKey ?? $this->keyBy;
    }

    /**
     * Get the sortable item value for a row.
     *
     * @since 2.0.0
     *
     * @param  mixed  $row  The row data.
     * @return mixed The sortable item identifier.
     */
    public function getSortableItemValue(mixed $row): mixed
    {
        return data_get($row, $this->getSortableKey());
    }

    /**
     * Check if wire:intersect infinite scroll is supported and enabled.
     *
     * @since 2.0.0
     *
     * @return bool True if infiniteScroll is enabled and Livewire 4+.
     */
    public function isInfiniteScrollEnabled(): bool
    {
        return $this->infiniteScroll && LivewireHelper::supportsWireIntersect();
    }

    /**
     * Get the wire:intersect directive with optional modifier.
     *
     * @since 2.0.0
     *
     * @return string The wire:intersect directive (e.g., "wire:intersect" or "wire:intersect.once").
     */
    public function getInfiniteScrollDirective(): string
    {
        $directive = 'wire:intersect';

        if ($this->infiniteScrollModifier) {
            $modifier = ltrim($this->infiniteScrollModifier, '.');
            $directive .= '.'.$modifier;
        }

        return $directive;
    }

    /**
     * Check if virtual scrolling is enabled.
     *
     * @since 2.0.0
     *
     * @return bool True if virtualScroll is enabled and rows exist.
     */
    public function isVirtualScrollEnabled(): bool
    {
        $rowCount = is_array($this->rows) ? count($this->rows) : $this->rows->count();

        return $this->virtualScroll && $rowCount > 0;
    }

    /**
     * Get the total scrollable height based on row count.
     *
     * @since 2.0.0
     *
     * @return int Total height in pixels.
     */
    public function getTotalHeight(): int
    {
        $rowCount = is_array($this->rows) ? count($this->rows) : $this->rows->count();

        return $rowCount * $this->virtualRowHeight;
    }

    /**
     * Get the total row count.
     *
     * @since 2.0.0
     *
     * @return int Total number of rows.
     */
    public function getTotalRowCount(): int
    {
        return is_array($this->rows) ? count($this->rows) : $this->rows->count();
    }

    /**
     * Get the visible rows based on start/end indices.
     *
     * @since 2.0.0
     *
     * @param  int  $startIndex  The starting index.
     * @param  int  $endIndex  The ending index.
     * @return array|ArrayAccess The visible rows.
     */
    public function getVisibleRows(int $startIndex, int $endIndex): array|ArrayAccess
    {
        if (is_array($this->rows)) {
            return array_slice($this->rows, $startIndex, $endIndex - $startIndex);
        }

        return $this->rows->slice($startIndex, $endIndex - $startIndex);
    }

    /**
     * Get the container height for virtual scrolling.
     *
     * @since 2.0.0
     *
     * @return string CSS height value.
     */
    public function getVirtualContainerHeight(): string
    {
        if ($this->virtualContainerHeight) {
            return $this->virtualContainerHeight.'px';
        }

        return 'min(600px, 70vh)';
    }

    /**
     * Get visible headers for export (excluding hidden columns).
     *
     * @since 2.0.0
     *
     * @return array Array of visible headers.
     */
    public function getExportHeaders(): array
    {
        return collect($this->headers)
            ->filter(fn ($header) => ! $this->isHidden($header))
            ->values()
            ->all();
    }

    /**
     * Get the export filename with extension.
     *
     * @since 2.0.0
     *
     * @param  string  $format  The export format (csv, xlsx, pdf).
     * @return string The filename with extension.
     */
    public function getExportFilename(string $format = 'csv'): string
    {
        $base = $this->exportFilename ?? 'table-export-'.date('Y-m-d');

        return $base.'.'.$format;
    }

    /**
     * Get export data as array for client-side export.
     *
     * Returns headers and rows formatted for CSV generation.
     *
     * @since 2.0.0
     *
     * @return array{headers: array, rows: array} Export data structure.
     */
    public function getExportData(): array
    {
        $visibleHeaders = $this->getExportHeaders();

        // Extract header labels
        $headerLabels = collect($visibleHeaders)
            ->map(fn ($header) => $header['label'] ?? $header['key'])
            ->all();

        // Extract row data for visible columns only
        $rowsData = collect($this->rows)
            ->map(function ($row) use ($visibleHeaders) {
                return collect($visibleHeaders)
                    ->map(function ($header) use ($row) {
                        $value = data_get($row, $header['key']);

                        // Format the value if formatter exists
                        if (isset($header['format'])) {
                            $value = $this->format($row, $value, $header);
                        }

                        // Convert to string for export
                        return is_array($value) || is_object($value)
                            ? json_encode($value)
                            : (string) $value;
                    })
                    ->all();
            })
            ->all();

        return [
            'headers' => $headerLabels,
            'rows' => $rowsData,
        ];
    }

    /**
     * Check if export is enabled.
     *
     * @since 2.0.0
     *
     * @return bool True if export is enabled.
     */
    public function isExportEnabled(): bool
    {
        return $this->exportable;
    }

    /**
     * Check if a specific export format is supported.
     *
     * @since 2.0.0
     *
     * @param  string  $format  The format to check.
     * @return bool True if the format is supported.
     */
    public function supportsExportFormat(string $format): bool
    {
        return in_array($format, $this->exportFormats, true);
    }

    /**
     * Renders the table component.
     *
     * @return View The rendered component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.table');
    }
}
