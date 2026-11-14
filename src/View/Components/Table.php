<?php

/**
 * Table Component
 *
 * The design-system Table (#62): rows of structured data in real table
 * markup, with sorting, selection, expandable rows, loading and empty states
 * and Pagination in the footer.
 *
 * @author     Jacob Martella
 * @copyright  2026 Jacob Martella
 * @license    MIT
 *
 * @link       https://github.com/ArtisanPack-UI/livewire-ui-components
 * @since      1.0.0
 */

declare(strict_types=1);

namespace ArtisanPack\LivewireUiComponents\View\Components;

use ArrayAccess;
use ArtisanPack\LivewireUiComponents\Support\LivewireHelper;
use ArtisanPack\LivewireUiComponents\Support\SafeUrl;
use ArtisanPack\LivewireUiComponents\View\Concerns\HasStablePartIds;
use ArtisanPack\LivewireUiComponents\View\Concerns\RendersDesignSystemParts;
use Closure;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;
use ReflectionObject;
use Traversable;

/**
 * Table Component Class
 *
 * Renders `.apui-table`: the toolbar (title and toolbar content), the scroll
 * part holding the native table, and the footer (footer content, the
 * "Rows per page" Select and Pagination). Selection and expansion run in
 * Alpine; the selection binds `wire:model` through `$wire.entangle()`.
 * Cells come from `@scope('cell_<key>', $row)`, headers from
 * `@scope('header_<key>', $header)`, row actions from `@scope('actions', $row)`
 * and expanded content from `@scope('expansion', $row)`. Every attribute
 * except `wire:model` and the 2.x options lands on the root.
 *
 * @since 1.0.0
 */
class Table extends BaseComponent
{
    use HasStablePartIds;
    use RendersDesignSystemParts;

    /**
     * The 2.x attributes the view reads as options; they never reach the root.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    public const LEGACY_ATTRIBUTES = [
        'compact', 'no-headers', 'noHeaders', 'with-pagination', 'withPagination',
        'selectable-key', 'selectableKey', 'expandable-key', 'expandableKey',
        'expandable-condition', 'expandableCondition', 'link', 'row-decoration',
        'rowDecoration', 'cell-decoration', 'cellDecoration', 'show-empty-text',
        'showEmptyText', 'striped', 'no-hover', 'noHover', 'container-class',
        'containerClass', 'uuid', 'header-glass', 'headerGlass', 'header-glass-tint',
        'headerGlassTint', 'header-glass-tint-opacity', 'headerGlassTintOpacity',
        'sortable', 'sortable-key', 'sortableKey', 'sort-group', 'sortGroup',
        'sort-handle', 'sortHandle', 'infinite-scroll', 'infiniteScroll',
        'infinite-scroll-method', 'infiniteScrollMethod', 'infinite-scroll-modifier',
        'infiniteScrollModifier', 'infinite-scroll-text', 'infiniteScrollText',
        'has-more-pages', 'hasMorePages', 'virtual-scroll', 'virtualScroll',
        'virtual-row-height', 'virtualRowHeight', 'virtual-buffer', 'virtualBuffer',
        'virtual-container-height', 'virtualContainerHeight', 'exportable',
        'export-formats', 'exportFormats', 'export-filename', 'exportFilename',
    ];

    /**
     * The id prefix for HasStablePartIds.
     *
     * @since 3.0.0
     */
    protected string $idPrefix = 'table';

    /**
     * Props that never change the base id: data, state and text.
     *
     * @since 3.0.0
     *
     * @var list<string>
     */
    protected array $idIgnore = [
        'rows', 'value', 'expanded', 'sortBy', 'loading', 'title', 'label', 'emptyText',
        'toolbar', 'footer', 'pagination', 'selectAllLabel', 'selectRowLabel', 'sortLabel',
        'expandLabel', 'actionsLabel',
    ];

    /**
     * The columns after the `ap.livewireUiComponents.tableColumns` filter.
     *
     * @since 3.0.0
     *
     * @var list<array<string, mixed>>|null
     */
    private ?array $resolvedColumns = null;

    /**
     * Creates the component.
     *
     * @since 3.0.0
     *
     * @param  array<int, mixed>  $headers  The columns: `{ key, label, sortable, numeric, hidden, secondary, class }`, plus 2.x's `format`, `sortBy` and `disableLink`.
     * @param  array<int, mixed>|ArrayAccess  $rows  The rows: an array, a Collection or a paginator.
     * @param  string  $keyBy  The dot path to each row's key.
     * @param  string|null  $title  The toolbar title; it names the table. A `title` slot overrides it.
     * @param  string|null  $label  The table's accessible name, instead of the title.
     * @param  string|null  $density  compact, default or comfortable. Unset follows the page; 2.x's `compact` sets compact.
     * @param  string  $surface  default or glass.
     * @param  bool  $stickyHeader  Keeps the header row in view while the rows scroll.
     * @param  bool  $hideHeaders  Hides the header row visually. 2.x's `no-headers` still works.
     * @param  bool  $selectable  Adds row checkboxes and a select-all box.
     * @param  array<int, int|string>  $value  The selected keys, when there is no `wire:model`.
     * @param  bool  $expandable  Adds an expand button to each row.
     * @param  array<int, int|string>  $expanded  The expanded keys.
     * @param  array{column?: string, direction?: string}  $sortBy  The current sort. Inside a Livewire component with a public `sortBy` property, clicking a header sets it.
     * @param  bool  $loading  Shows skeleton rows and sets aria-busy.
     * @param  string|null  $emptyText  The empty state's title. Defaults to __( 'No records found.' ).
     * @param  string|null  $toolbar  Toolbar content after the title. A `toolbar` slot overrides it.
     * @param  string|null  $footer  Footer content. A `footer` slot overrides it.
     * @param  array<string, mixed>  $pagination  Pagination's props, rendered at the end of the footer.
     * @param  bool  $paginated  Renders Pagination from the paginator in rows. 2.x's `with-pagination` still works.
     * @param  string|null  $perPage  The Livewire property the "Rows per page" Select binds.
     * @param  array<int, int|string>  $perPageValues  The "Rows per page" options.
     * @param  string|null  $selectAllLabel  The select-all box's name. Defaults to __( 'Select all' ).
     * @param  string|null  $selectRowLabel  Each row box's name, with {row}. Defaults to __( 'Select {row}' ).
     * @param  string|null  $sortLabel  Each sort button's name, with {column}. Defaults to __( 'Sort by {column}' ).
     * @param  string|null  $expandLabel  Each expand button's name, with {row}. Defaults to __( 'Show details for {row}' ).
     * @param  string|null  $actionsLabel  The actions column's hidden header. Defaults to __( 'Actions' ).
     * @param  array<string, string>  $classes  Extra classes per part.
     */
    public function __construct(
        public array $headers,
        public ArrayAccess|array $rows,
        public string $keyBy = 'id',
        public ?string $title = null,
        public ?string $label = null,
        public ?string $density = null,
        public string $surface = 'default',
        public bool $stickyHeader = true,
        public bool $hideHeaders = false,
        public bool $selectable = false,
        public array $value = [],
        public bool $expandable = false,
        public array $expanded = [],
        public array $sortBy = [],
        public bool $loading = false,
        public ?string $emptyText = null,
        public ?string $toolbar = null,
        public ?string $footer = null,
        public array $pagination = [],
        public bool $paginated = false,
        public ?string $perPage = null,
        public array $perPageValues = [10, 20, 50, 100],
        public ?string $selectAllLabel = null,
        public ?string $selectRowLabel = null,
        public ?string $sortLabel = null,
        public ?string $expandLabel = null,
        public ?string $actionsLabel = null,
        public array $classes = [],
    ) {
        $this->emptyText ??= __('No records found.');
        $this->selectAllLabel ??= __('Select all');
        $this->selectRowLabel ??= __('Select {row}');
        $this->sortLabel ??= __('Sort by {column}');
        $this->expandLabel ??= __('Show details for {row}');
        $this->actionsLabel ??= __('Actions');
    }

    /**
     * A 2.x attribute, by its kebab-case or camelCase name.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The kebab-case name, such as `selectable-key`.
     * @param  mixed  $default  The value when it isn't set.
     *
     * @return mixed The value; a bare attribute (`compact`) is true.
     */
    public function legacy(string $name, mixed $default = null): mixed
    {
        foreach ([$name, Str::camel($name)] as $key) {
            if ($this->attributes->has($key)) {
                $value = $this->attributes->get($key);

                return '' === $value || null === $value ? true : $value;
            }
        }

        return $default;
    }

    /**
     * A 2.x boolean attribute: present, `true` or a truthy binding.
     *
     * @since 3.0.0
     *
     * @param  string  $name  The kebab-case name.
     *
     * @return bool Whether it is on.
     */
    public function legacyFlag(string $name): bool
    {
        $value = $this->legacy($name, false);

        return true === $value || (is_string($value) ? ! in_array(strtolower($value), ['false', '0'], true) : (bool) $value);
    }

    /**
     * The root's attributes: everything but `wire:model`, the drag-and-drop
     * `wire:sort` and the 2.x options, scalars only.
     *
     * @since 3.0.0
     *
     * @return ComponentAttributeBag The attributes.
     */
    public function rootAttributes(): ComponentAttributeBag
    {
        return $this->attributes
            ->whereDoesntStartWith('wire:model')
            ->whereDoesntStartWith('wire:sort')
            ->except(self::LEGACY_ATTRIBUTES)
            ->filter(static fn (mixed $value): bool => is_scalar($value));
    }

    /**
     * The density: the prop, else 2.x's `compact`, else unset.
     *
     * @since 3.0.0
     *
     * @return string|null The density.
     */
    public function resolvedDensity(): ?string
    {
        if (in_array($this->density, ['compact', 'default', 'comfortable'], true)) {
            return $this->density;
        }

        return $this->legacyFlag('compact') ? 'compact' : null;
    }

    /**
     * Whether the header row is hidden: the prop or 2.x's `no-headers`.
     *
     * @since 3.0.0
     *
     * @return bool True when hidden.
     */
    public function headersHidden(): bool
    {
        return $this->hideHeaders || $this->legacyFlag('no-headers');
    }

    /**
     * The columns, through the `ap.livewireUiComponents.tableColumns` filter
     * (with the table's `id`, or `default`), without the malformed ones.
     *
     * @since 3.0.0
     *
     * @return list<array<string, mixed>> The columns.
     */
    public function columns(): array
    {
        if (null !== $this->resolvedColumns) {
            return $this->resolvedColumns;
        }

        $id      = $this->attributes?->get('id');
        $columns = applyFilters(
            'ap.livewireUiComponents.tableColumns',
            $this->headers,
            is_string($id) && '' !== $id ? $id : 'default',
        );

        return $this->resolvedColumns = array_values(array_filter(
            is_array($columns) ? $columns : [],
            static fn (mixed $column): bool => is_array($column) && isset($column['key']) && is_scalar($column['key']),
        ));
    }

    /**
     * The columns that show.
     *
     * @since 3.0.0
     *
     * @return list<array<string, mixed>> The columns.
     */
    public function visibleColumns(): array
    {
        return array_values(array_filter($this->columns(), static fn (array $column): bool => ! ($column['hidden'] ?? false)));
    }

    /**
     * The rows on screen as a list: a paginator's items, a Collection's or an
     * iterable's values, or the array.
     *
     * @since 3.0.0
     *
     * @return list<mixed> The rows.
     */
    public function rowList(): array
    {
        $rows = $this->rows;

        if ($rows instanceof Paginator || (is_object($rows) && method_exists($rows, 'items') && ! $rows instanceof Collection)) {
            $rows = $rows->items();
        }

        if ($rows instanceof Collection) {
            return array_values($rows->all());
        }

        if ($rows instanceof Traversable) {
            return array_values(iterator_to_array($rows, false));
        }

        return is_array($rows) ? array_values($rows) : [];
    }

    /**
     * Whether rows is a paginator Pagination can read.
     *
     * @since 3.0.0
     *
     * @return bool True for a paginator.
     */
    public function hasPaginator(): bool
    {
        return $this->rows instanceof Paginator || (is_object($this->rows) && method_exists($this->rows, 'previousPageUrl'));
    }

    /**
     * Whether Pagination renders from the paginator: `paginated` or 2.x's
     * `with-pagination`, with a paginator in rows.
     *
     * @since 3.0.0
     *
     * @return bool True to render it.
     */
    public function rendersPaginator(): bool
    {
        return ($this->paginated || $this->legacyFlag('with-pagination')) && $this->hasPaginator();
    }

    /**
     * A row's key.
     *
     * @since 3.0.0
     *
     * @param  mixed  $row  The row.
     *
     * @return mixed The key.
     */
    public function rowKey(mixed $row): mixed
    {
        return data_get($row, $this->keyBy);
    }

    /**
     * A row's selection key: 2.x's `selectable-key`, else keyBy.
     *
     * @since 3.0.0
     *
     * @param  mixed  $row  The row.
     *
     * @return mixed The key.
     */
    public function selectionKey(mixed $row): mixed
    {
        $path = $this->legacy('selectable-key');

        return data_get($row, is_string($path) && '' !== $path ? $path : $this->keyBy);
    }

    /**
     * A row's expansion key: 2.x's `expandable-key`, else keyBy.
     *
     * @since 3.0.0
     *
     * @param  mixed  $row  The row.
     *
     * @return mixed The key.
     */
    public function expansionKey(mixed $row): mixed
    {
        $path = $this->legacy('expandable-key');

        return data_get($row, is_string($path) && '' !== $path ? $path : $this->keyBy);
    }

    /**
     * Whether a row can expand: always, unless 2.x's `expandable-condition`
     * names a dot path that is falsy for the row.
     *
     * @since 3.0.0
     *
     * @param  mixed  $row  The row.
     *
     * @return bool True when it can.
     */
    public function canExpand(mixed $row): bool
    {
        $condition = $this->legacy('expandable-condition');

        return ! is_string($condition) || '' === $condition || (bool) data_get($row, $condition);
    }

    /**
     * Whether `wire:model` binds the expanded keys instead of the selection:
     * 2.x's expandable tables without selectable.
     *
     * @since 3.0.0
     *
     * @return bool True when it does.
     */
    public function modelBindsExpansion(): bool
    {
        return $this->expandable && ! $this->selectable && null !== $this->modelName();
    }

    /**
     * The selected keys the server renders: the bound Livewire property with
     * `wire:model`, else `value`.
     *
     * @since 3.0.0
     *
     * @return list<mixed> The keys.
     */
    public function selectedKeys(): array
    {
        if ($this->selectable && null !== $this->modelName()) {
            $component = $this->livewireComponent();
            $bound     = null !== $component ? data_get($component, (string) $this->modelName()) : null;
            if (is_array($bound) || $bound instanceof Collection) {
                return array_values(collect($bound)->all());
            }
        }

        return array_values($this->value);
    }

    /**
     * The expanded keys the server renders: the bound property for a 2.x
     * expandable table with `wire:model`, else `expanded`.
     *
     * @since 3.0.0
     *
     * @return list<mixed> The keys.
     */
    public function expandedKeys(): array
    {
        if ($this->modelBindsExpansion()) {
            $component = $this->livewireComponent();
            $bound     = null !== $component ? data_get($component, (string) $this->modelName()) : null;
            if (is_array($bound) || $bound instanceof Collection) {
                return array_values(collect($bound)->all());
            }
        }

        return array_values($this->expanded);
    }

    /**
     * Whether a key is in a list, comparing as strings (keys from the client
     * are often strings).
     *
     * @since 3.0.0
     *
     * @param  list<mixed>  $keys  The list.
     * @param  mixed  $key  The key.
     *
     * @return bool True when it's there.
     */
    public function hasKey(array $keys, mixed $key): bool
    {
        foreach ($keys as $item) {
            if (is_scalar($item) && is_scalar($key) && (string) $item === (string) $key) {
                return true;
            }
        }

        return false;
    }

    /**
     * The select-all box's state for the rows on screen.
     *
     * @since 3.0.0
     *
     * @param  list<mixed>  $pageKeys  The keys on screen.
     * @param  list<mixed>  $selected  The selected keys.
     *
     * @return string unchecked, checked or indeterminate.
     */
    public function selectAllState(array $pageKeys, array $selected): string
    {
        $count = count(array_filter($pageKeys, fn (mixed $key): bool => $this->hasKey($selected, $key)));

        if (0 === $count) {
            return 'unchecked';
        }

        return $count === count($pageKeys) ? 'checked' : 'indeterminate';
    }

    /**
     * An Alpine expression for the selection: `$wire.entangle()` of
     * `wire:model` (`.live` with `wire:model.live`), else the keys.
     *
     * @since 3.0.0
     *
     * @param  list<mixed>  $keys  The keys without a binding.
     *
     * @return string The expression.
     */
    public function modelExpression(array $keys): string
    {
        $model = $this->modelName();
        if (null === $model) {
            return json_encode(array_values($keys), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?: '[]';
        }

        $live = [] !== array_filter(
            array_keys($this->attributes->whereStartsWith('wire:model')->getAttributes()),
            static fn (string $key): bool => str_contains($key, '.live'),
        );

        return '$wire.entangle('.json_encode($model, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP).')'.($live ? '.live' : '');
    }

    /**
     * Whether a column sorts. With any column setting `sortable` to true, only
     * those do; otherwise every column does once `sortBy` is set, unless it
     * sets `sortable` to false, as in 2.x.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $column  The column.
     *
     * @return bool True when it sorts.
     */
    public function isSortable(array $column): bool
    {
        $explicit = [] !== array_filter($this->columns(), static fn (array $item): bool => true === ($item['sortable'] ?? null));

        if ($explicit) {
            return (bool) ($column['sortable'] ?? false);
        }

        return [] !== $this->sortBy && ($column['sortable'] ?? true);
    }

    /**
     * The column a header sorts by: 2.x's `sortBy`, else its key.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $column  The column.
     *
     * @return string The column name.
     */
    public function sortColumn(array $column): string
    {
        return (string) ($column['sortBy'] ?? $column['key']);
    }

    /**
     * A sortable header's aria-sort: ascending or descending when the table
     * is sorted by it, else none.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $column  The column.
     *
     * @return string The value.
     */
    public function ariaSort(array $column): string
    {
        if (($this->sortBy['column'] ?? null) !== $this->sortColumn($column)) {
            return 'none';
        }

        return 'desc' === ($this->sortBy['direction'] ?? 'asc') ? 'descending' : 'ascending';
    }

    /**
     * The sort a header click asks for: ascending first, then toggling.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $column  The column.
     *
     * @return array{column: string, direction: string} The sort.
     */
    public function nextSort(array $column): array
    {
        $direction = 'ascending' === $this->ariaSort($column) ? 'desc' : 'asc';

        return ['column' => $this->sortColumn($column), 'direction' => $direction];
    }

    /**
     * The sort icon for a header: arrow-up, arrow-down or chevrons-up-down.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $column  The column.
     *
     * @return string The icon name.
     */
    public function sortIcon(array $column): string
    {
        return match ($this->ariaSort($column)) {
            'ascending'  => 'arrow-up',
            'descending' => 'arrow-down',
            default      => 'chevrons-up-down',
        };
    }

    /**
     * Fills a `{name}` template.
     *
     * @since 3.0.0
     *
     * @param  string|null  $template  The template.
     * @param  array<string, string>  $values  The values by name.
     *
     * @return string The text.
     */
    public function fill(?string $template, array $values): string
    {
        $text = (string) $template;
        foreach ($values as $name => $value) {
            $text = str_replace('{'.$name.'}', $value, $text);
        }

        return $text;
    }

    /**
     * A column's label as text, for accessible names.
     *
     * @since 3.0.0
     *
     * @param  array<string, mixed>  $column  The column.
     *
     * @return string The text.
     */
    public function columnName(array $column): string
    {
        $label = $column['label'] ?? $column['key'];

        return trim(strip_tags($label instanceof Htmlable ? $label->toHtml() : (string) (is_scalar($label) ? $label : $column['key'])));
    }

    /**
     * A row's name for {row} in the box and expand labels: its first visible
     * column's value, else its key.
     *
     * @since 3.0.0
     *
     * @param  mixed  $row  The row.
     *
     * @return string The name.
     */
    public function rowName(mixed $row): string
    {
        $first = $this->visibleColumns()[0] ?? null;
        $value = null !== $first ? $this->format($row, data_get($row, $first['key']), $first) : null;

        if (! is_scalar($value) || '' === trim((string) $value)) {
            $value = $this->rowKey($row);
        }

        return trim(strip_tags(is_scalar($value) ? (string) $value : ''));
    }

    /**
     * A cell's value through the column's `format`: a closure `(row, value)`,
     * `['currency', '2.,', '$']` (decimals, decimal and thousands separators,
     * prefix) or `['date', 'M j, Y']`.
     *
     * @since 1.0.0
     *
     * @param  mixed  $row  The row.
     * @param  mixed  $field  The raw value.
     * @param  array<string, mixed>  $header  The column.
     *
     * @return mixed The value.
     */
    public function format(mixed $row, mixed $field, array $header): mixed
    {
        $format = $header['format'] ?? null;

        if (! $format) {
            return $field;
        }

        if ($format instanceof Closure || (is_callable($format) && ! is_array($format) && ! is_string($format))) {
            return $format($row, $field);
        }

        if (is_array($format) && 'currency' === ($format[0] ?? null)) {
            [$decimals, $decimalSeparator, $thousandsSeparator] = str_split((string) ($format[1] ?? '')) + [0, '.', ','];

            return ($format[2] ?? '').number_format((float) $field, (int) $decimals, $decimalSeparator, $thousandsSeparator);
        }

        if (is_array($format) && 'date' === ($format[0] ?? null) && $field) {
            return Carbon::parse($field)->translatedFormat((string) ($format[1] ?? 'Y-m-d'));
        }

        return $field;
    }

    /**
     * The row link for a cell: 2.x's `link` with `{key}` tokens filled from
     * the row, unless the column sets `disableLink`. Unsafe schemes give null.
     *
     * @since 3.0.0
     *
     * @param  mixed  $row  The row.
     * @param  array<string, mixed>  $column  The column.
     *
     * @return string|null The href.
     */
    public function cellHref(mixed $row, array $column): ?string
    {
        $link = $this->legacy('link');
        if (! is_string($link) || '' === $link || ! empty($column['disableLink'])) {
            return null;
        }

        $link = str_replace(['%5B', '%5D'], ['{', '}'], $link);
        $link = (string) preg_replace_callback(
            '/\{(.*?)\}/',
            static fn (array $match): string => rawurlencode((string) data_get($row, $match[1])),
            $link,
        );

        return SafeUrl::href($link);
    }

    /**
     * A row's classes from 2.x's `row-decoration`: `[ 'class' => fn ( $row ) => bool ]`.
     *
     * @since 1.0.0
     *
     * @param  mixed  $row  The row.
     *
     * @return string The classes.
     */
    public function rowClasses(mixed $row): string
    {
        $classes = [];
        foreach ((array) $this->legacy('row-decoration', []) as $class => $condition) {
            if ($condition instanceof Closure && $condition($row)) {
                $classes[] = $class;
            }
        }

        return Arr::join(array_filter([$this->classes['row'] ?? null, ...$classes]), ' ');
    }

    /**
     * A cell's classes: `classes.cell`, the column's `class` and 2.x's
     * `cell-decoration`: `[ 'key' => [ 'class' => fn ( $row ) => bool ] ]`.
     *
     * @since 1.0.0
     *
     * @param  mixed  $row  The row.
     * @param  array<string, mixed>  $header  The column.
     *
     * @return string The classes.
     */
    public function cellClasses(mixed $row, array $header): string
    {
        $classes     = [$this->classes['cell'] ?? null, is_string($header['class'] ?? null) ? $header['class'] : null];
        $decorations = (array) $this->legacy('cell-decoration', []);
        foreach ((array) ($decorations[$header['key']] ?? []) as $class => $condition) {
            if ($condition instanceof Closure && $condition($row)) {
                $classes[] = $class;
            }
        }

        return Arr::join(array_filter($classes, static fn (mixed $class): bool => is_string($class) && '' !== trim($class)), ' ');
    }

    /**
     * A ` class="…"` attribute from classes, or nothing.
     *
     * @since 3.0.0
     *
     * @param  string  $classes  The classes.
     *
     * @return string The attribute, with a leading space.
     */
    public function classAttribute(string $classes): string
    {
        return '' === trim($classes) ? '' : ' class="'.e(trim($classes)).'"';
    }

    /**
     * Whether something listens for row-click on the table.
     *
     * @since 3.0.0
     *
     * @return bool True when rows dispatch it.
     */
    public function listensForRowClick(): bool
    {
        foreach (array_keys($this->attributes->getAttributes()) as $name) {
            if (preg_match('/^(@|x-on:|wire:)row-click(\.|$)/', (string) $name)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Whether rows drag to reorder: 2.x's `sortable` with Livewire 4's wire:sort.
     *
     * @since 2.0.0
     *
     * @return bool True when enabled.
     */
    public function isSortingEnabled(): bool
    {
        return $this->legacyFlag('sortable') && LivewireHelper::supportsWireSort();
    }

    /**
     * Whether rows drag by a handle column (2.x's `sort-handle`).
     *
     * @since 3.0.0
     *
     * @return bool True with a handle.
     */
    public function hasSortHandle(): bool
    {
        return $this->isSortingEnabled() && $this->legacyFlag('sort-handle');
    }

    /**
     * The `wire:sort` method: the attribute's value, else `updateOrder`.
     *
     * @since 3.0.0
     *
     * @return string The method.
     */
    public function sortMethod(): string
    {
        $method = $this->attributes->get('wire:sort');

        return is_string($method) && '' !== $method ? $method : 'updateOrder';
    }

    /**
     * A row's `wire:sort:item` value: 2.x's `sortable-key`, else keyBy.
     *
     * @since 2.0.0
     *
     * @param  mixed  $row  The row.
     *
     * @return mixed The value.
     */
    public function getSortableItemValue(mixed $row): mixed
    {
        $path = $this->legacy('sortable-key');

        return data_get($row, is_string($path) && '' !== $path ? $path : $this->keyBy);
    }

    /**
     * Whether the infinite-scroll trigger renders: 2.x's `infinite-scroll`
     * with Livewire 4's wire:intersect, while `has-more-pages` holds.
     *
     * @since 2.0.0
     *
     * @return bool True to render it.
     */
    public function isInfiniteScrollEnabled(): bool
    {
        return $this->legacyFlag('infinite-scroll')
            && LivewireHelper::supportsWireIntersect()
            && (null === $this->legacy('has-more-pages') || $this->legacyFlag('has-more-pages'));
    }

    /**
     * The infinite-scroll directive, such as `wire:intersect.once`.
     *
     * @since 2.0.0
     *
     * @return string The directive.
     */
    public function getInfiniteScrollDirective(): string
    {
        $modifier = $this->legacy('infinite-scroll-modifier');

        return 'wire:intersect'.(is_string($modifier) && in_array(ltrim($modifier, '.'), ['once', 'half', 'full'], true) ? '.'.ltrim($modifier, '.') : '');
    }

    /**
     * The infinite-scroll method: 2.x's `infinite-scroll-method`, else loadMore.
     *
     * @since 3.0.0
     *
     * @return string The method.
     */
    public function infiniteScrollMethod(): string
    {
        $method = $this->legacy('infinite-scroll-method');

        return is_string($method) && preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $method) ? $method : 'loadMore';
    }

    /**
     * Whether a footer slot holds table rows (2.x's tfoot content).
     *
     * @since 3.0.0
     *
     * @param  mixed  $footer  The footer prop or slot.
     *
     * @return bool True for rows.
     */
    public function footerHoldsRows(mixed $footer): bool
    {
        return $footer instanceof Htmlable && str_starts_with(ltrim($footer->toHtml()), '<tr');
    }

    /**
     * Whether a sort button sets the rendering Livewire component's `sortBy`:
     * inside Livewire, when that component has a public `sortBy` property,
     * whatever its current value (an empty `[]` start included).
     *
     * @since 3.0.0
     *
     * @return bool True when `$wire.set( 'sortBy' )` is safe.
     */
    public function setsLivewireSortBy(): bool
    {
        $component = $this->livewireComponent();

        if (null === $component) {
            return false;
        }

        $reflection = new ReflectionObject($component);

        return $reflection->hasProperty('sortBy') && $reflection->getProperty('sortBy')->isPublic();
    }

    /**
     * Get the view that represents the component.
     *
     * @since 1.0.0
     */
    public function render(): View
    {
        return view('livewire-ui-components::components.table');
    }
}
