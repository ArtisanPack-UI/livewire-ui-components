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
     *
     * @since 1.0.0
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

        // Slots
        public mixed $actions = null,
        public mixed $tr = null,
        public mixed $cell = null,
        public mixed $expansion = null,
        public mixed $empty = null,
        public mixed $footer = null,

    ) {
        if ($this->selectable && $this->expandable) {
            throw new Exception('You can not combine `expandable` with `selectable`.');
        }

        // Temp
        $rowDecoration  = $this->rowDecoration;
        $cellDecoration = $this->cellDecoration;
        $headers        = $this->headers;

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
        $this->rowDecoration  = $rowDecoration;
        $this->cellDecoration = $cellDecoration;
        $this->headers        = $headers;
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

        if ('currency' == $format[0]) {
            return ($format[2] ?? '').number_format($field, ...str_split($format[1]));
        }

        if ('date' == $format[0] && $field) {
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
        if (0 == count($this->sortBy)) {
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

        if (0 == count($this->sortBy)) {
            return ['column' => '', 'direction' => ''];
        }

        $direction = $this->isSortedBy($header)
            ? ('asc' == $this->sortBy['direction']) ? 'desc' : 'asc'
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

    public function getKeyValue($row, $key): mixed
    {
        $value = data_get($row, $this->$key);

        return is_numeric($value) && ! str($value)->startsWith('0') ? $value : "'$value'";
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
