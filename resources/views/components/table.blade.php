@php
    // Virtual scroll configuration
    $isVirtual = $virtualScroll && $getTotalRowCount() > 0;
    $virtualStart = 0;
    $virtualEnd = $isVirtual ? min(50 + $virtualBuffer * 2, $getTotalRowCount()) : $getTotalRowCount();
    $visibleRows = $isVirtual ? $getVisibleRows($virtualStart, $virtualEnd) : $rows;
    $totalRowCount = $getTotalRowCount();
@endphp

<div x-data="{
                selection: @entangle($attributes->wire('model')),
                pageIds: {{ json_encode($getAllIds()) }},
                isSelectable: {{ json_encode($selectable) }},
                colspanSize: 0,

                // Virtual scroll state
                virtualScroll: {{ json_encode($virtualScroll) }},
                virtualRowHeight: {{ $virtualRowHeight }},
                virtualBuffer: {{ $virtualBuffer }},
                totalRows: {{ $totalRowCount }},
                scrollTop: 0,
                containerHeight: 0,
                visibleStartIndex: 0,
                visibleEndIndex: {{ $virtualEnd }},
                isScrolling: false,
                scrollTimeout: null,

                init() {
                    this.colspanSize = $refs.headers.childElementCount

                    if (this.isSelectable) {
                        this.handleCheckAll()
                    }

                    // Initialize virtual scroll
                    if (this.virtualScroll) {
                        this.initVirtualScroll()
                    }
                },

                // Virtual scroll methods
                initVirtualScroll() {
                    this.$nextTick(() => {
                        if (this.$refs.virtualContainer) {
                            this.containerHeight = this.$refs.virtualContainer.clientHeight || 400;
                            this.calculateVisibleRange();
                        }
                    });
                },
                handleVirtualScroll(e) {
                    if (!this.virtualScroll) return;
                    this.scrollTop = e.target.scrollTop;

                    // Throttle scroll updates
                    if (!this.isScrolling) {
                        this.isScrolling = true;
                        this.calculateVisibleRange();

                        clearTimeout(this.scrollTimeout);
                        this.scrollTimeout = setTimeout(() => {
                            this.isScrolling = false;
                            this.calculateVisibleRange();
                        }, 100);
                    }
                },
                calculateVisibleRange() {
                    const buffer = this.virtualBuffer;
                    const start = Math.max(0, Math.floor(this.scrollTop / this.virtualRowHeight) - buffer);
                    const visibleCount = Math.ceil(this.containerHeight / this.virtualRowHeight);
                    const end = Math.min(this.totalRows, start + visibleCount + buffer * 2);

                    // Only update if range changed significantly
                    if (Math.abs(start - this.visibleStartIndex) > 5 || Math.abs(end - this.visibleEndIndex) > 5) {
                        this.visibleStartIndex = start;
                        this.visibleEndIndex = end;
                    }
                },
                getTopSpacerHeight() {
                    return this.visibleStartIndex * this.virtualRowHeight;
                },
                getBottomSpacerHeight() {
                    return Math.max(0, (this.totalRows - this.visibleEndIndex) * this.virtualRowHeight);
                },

                isExpanded(key) {
                    return this.selection.includes(key)
                },
                isPageFullSelected() {
                    return this.pageIds.length && [...this.selection]
                                .sort((a, b) => b - a)
                                .toString()
                                .includes([...this.pageIds].sort((a, b) => b - a).toString())
                },
                toggleCheck(checked, content) {
                    this.$dispatch('row-selection', { row: content, selected: checked });
                    this.handleCheckAll()
                },
                toggleCheckAll(checked) {
                    this.$dispatch('row-selection-all', { selected: checked });
                    checked ? this.pushIds() : this.removeIds()
                },
                toggleExpand(key) {
                     this.selection.includes(key)
                        ? this.selection = this.selection.filter(i => i !== key)
                        : this.selection.push(key)
                },
                pushIds() {
                    this.selection.push(...this.pageIds.filter(i => !this.selection.includes(i)))
                },
                removeIds() {
                    this.selection =  this.selection.filter(i => !this.pageIds.includes(i) )
                },
                handleCheckAll() {
                    this.$nextTick(() => {
                            this.isPageFullSelected()
                                ? this.$refs.mainCheckbox.checked = true
                                : this.$refs.mainCheckbox.checked = false
                        })
                },

                // Export functionality
                exportable: {{ json_encode($exportable) }},
                exportData: {{ json_encode($exportable ? $getExportData() : ['headers' => [], 'rows' => []]) }},
                exportFilename: {{ json_encode($getExportFilename('csv')) }},
                exportFormats: {{ json_encode($exportFormats) }},
                supportsXlsx: {{ json_encode(\ArtisanPack\LivewireUiComponents\Support\TableExporter::supportsXlsx()) }},
                supportsPdf: {{ json_encode(\ArtisanPack\LivewireUiComponents\Support\TableExporter::supportsPdf()) }},
                tableId: {{ json_encode($id ?? $uuid) }},
                isExporting: false,

                exportToCSV() {
                    if (!this.exportable || this.isExporting) return;

                    this.isExporting = true;

                    try {
                        const csvContent = this.generateCSV();
                        this.downloadCSV(csvContent, this.exportFilename);
                        this.$dispatch('table-exported', { format: 'csv', filename: this.exportFilename });
                    } catch (error) {
                        console.error('Export failed:', error);
                        this.$dispatch('table-export-error', { error: error.message });
                    } finally {
                        this.isExporting = false;
                    }
                },

                exportToXlsx() {
                    if (!this.exportable || this.isExporting || !this.supportsXlsx) return;

                    this.isExporting = true;

                    // Dispatch event to parent Livewire component for server-side XLSX generation
                    this.$dispatch('table-export-request', { format: 'xlsx', tableId: this.tableId });

                    // Reset exporting state after a delay (the actual download is handled server-side)
                    setTimeout(() => {
                        this.isExporting = false;
                    }, 1000);
                },

                exportToPdf() {
                    if (!this.exportable || this.isExporting || !this.supportsPdf) return;

                    this.isExporting = true;

                    // Dispatch event to parent Livewire component for server-side PDF generation
                    this.$dispatch('table-export-request', { format: 'pdf', tableId: this.tableId });

                    // Reset exporting state after a delay (the actual download is handled server-side)
                    setTimeout(() => {
                        this.isExporting = false;
                    }, 1000);
                },

                generateCSV() {
                    const { headers, rows } = this.exportData;
                    const lines = [];

                    // Add header row
                    lines.push(headers.map(h => this.escapeCSVValue(h)).join(','));

                    // Add data rows
                    rows.forEach(row => {
                        lines.push(row.map(cell => this.escapeCSVValue(cell)).join(','));
                    });

                    return lines.join('\n');
                },

                escapeCSVValue(value) {
                    if (value === null || value === undefined) return '&quot;&quot;';

                    const stringValue = String(value);
                    const dq = '&quot;';

                    // Check if value needs quoting (contains comma, quote, or newline)
                    if (stringValue.includes(',') || stringValue.includes(dq) || stringValue.includes('\n') || stringValue.includes('\r')) {
                        // Escape double quotes by doubling them
                        return dq + stringValue.replace(new RegExp(dq, 'g'), dq + dq) + dq;
                    }

                    return stringValue;
                },

                downloadCSV(content, filename) {
                    const blob = new Blob([content], { type: 'text/csv;charset=utf-8;' });
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');

                    link.setAttribute('href', url);
                    link.setAttribute('download', filename);
                    link.style.visibility = 'hidden';

                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    URL.revokeObjectURL(url);
                }
             }"
>
    {{-- Export toolbar --}}
    @if($exportable)
        <div class="flex justify-end mb-2">
            {{-- Show dropdown if multiple export formats are available --}}
            <template x-if="(exportFormats.includes('csv') && ((supportsXlsx && exportFormats.includes('xlsx')) || (supportsPdf && exportFormats.includes('pdf')))) || ((supportsXlsx && exportFormats.includes('xlsx')) && (supportsPdf && exportFormats.includes('pdf')))">
                <x-artisanpack-dropdown>
                    <x-slot:trigger>
                        <x-artisanpack-button
                            type="button"
                            color="ghost"
                            size="sm"
                            icon="o-arrow-down-tray"
                            x-bind:disabled="isExporting"
                        >
                            <span x-show="!isExporting">{{ __('Export') }}</span>
                            <span x-show="isExporting" x-cloak>{{ __('Exporting...') }}</span>
                            <x-artisanpack-icon name="o-chevron-down" class="w-4 h-4 ml-1" />
                        </x-artisanpack-button>
                    </x-slot:trigger>

                    <x-artisanpack-menu>
                        <template x-if="exportFormats.includes('csv')">
                            <x-artisanpack-menu-item
                                icon="o-document-text"
                                x-on:click="exportToCSV()"
                            >
                                {{ __('Export as CSV') }}
                            </x-artisanpack-menu-item>
                        </template>

                        <template x-if="supportsXlsx && exportFormats.includes('xlsx')">
                            <x-artisanpack-menu-item
                                icon="o-table-cells"
                                x-on:click="exportToXlsx()"
                            >
                                {{ __('Export as Excel') }}
                            </x-artisanpack-menu-item>
                        </template>

                        <template x-if="supportsPdf && exportFormats.includes('pdf')">
                            <x-artisanpack-menu-item
                                icon="o-document"
                                x-on:click="exportToPdf()"
                            >
                                {{ __('Export as PDF') }}
                            </x-artisanpack-menu-item>
                        </template>
                    </x-artisanpack-menu>
                </x-artisanpack-dropdown>
            </template>

            {{-- Show simple button if only one format is available --}}
            <template x-if="exportFormats.includes('csv') && !((supportsXlsx && exportFormats.includes('xlsx')) || (supportsPdf && exportFormats.includes('pdf')))">
                <x-artisanpack-button
                    type="button"
                    color="ghost"
                    size="sm"
                    icon="o-arrow-down-tray"
                    x-on:click="exportToCSV()"
                    x-bind:disabled="isExporting"
                >
                    <span x-show="!isExporting">{{ __('Export CSV') }}</span>
                    <span x-show="isExporting" x-cloak>{{ __('Exporting...') }}</span>
                </x-artisanpack-button>
            </template>

            {{-- Show simple Excel button if only xlsx is available --}}
            <template x-if="!exportFormats.includes('csv') && (supportsXlsx && exportFormats.includes('xlsx')) && !(supportsPdf && exportFormats.includes('pdf'))">
                <x-artisanpack-button
                    type="button"
                    color="ghost"
                    size="sm"
                    icon="o-table-cells"
                    x-on:click="exportToXlsx()"
                    x-bind:disabled="isExporting"
                >
                    <span x-show="!isExporting">{{ __('Export Excel') }}</span>
                    <span x-show="isExporting" x-cloak>{{ __('Exporting...') }}</span>
                </x-artisanpack-button>
            </template>

            {{-- Show simple PDF button if only pdf is available --}}
            <template x-if="!exportFormats.includes('csv') && !(supportsXlsx && exportFormats.includes('xlsx')) && (supportsPdf && exportFormats.includes('pdf'))">
                <x-artisanpack-button
                    type="button"
                    color="ghost"
                    size="sm"
                    icon="o-document"
                    x-on:click="exportToPdf()"
                    x-bind:disabled="isExporting"
                >
                    <span x-show="!isExporting">{{ __('Export PDF') }}</span>
                    <span x-show="isExporting" x-cloak>{{ __('Exporting...') }}</span>
                </x-artisanpack-button>
            </template>
        </div>
    @endif

    {{-- Virtual scroll container wrapper --}}
    @if($isVirtual)
    <div
        x-ref="virtualContainer"
        class="overflow-auto"
        style="height: {{ $getVirtualContainerHeight() }};"
        @scroll.throttle.50ms="handleVirtualScroll($event)"
    >
    @endif

    <div class="{{ $containerClass }}" x-classes="overflow-x-auto">
        <table
            {{
				$attributes
					->whereDoesntStartWith('wire:model')
					->class([
						'table',
						'table-zebra' => $striped,
						'[&_tr:nth-child(4n+3)]:bg-base-200' => $striped && $expandable,
						'cursor-pointer' => $attributes->hasAny(['@row-click', 'link'])
					])
			}}
        >
            <!-- HEADERS -->
            <thead
                @class([
                    "text-base-content",
                    "hidden" => $noHeaders,
                    $headerGlassClasses() => $headerGlass,
                ])
                @if($headerGlassStyle())
                    style="{{ $headerGlassStyle() }}"
                @endif
            >
            <tr x-ref="headers">
                <!-- CHECKALL -->
                @if($selectable)
                    <th class="w-1" wire:key="{{ $uuid }}-checkall-{{ implode(',', $getAllIds()) }}">
                        <input
                            id="checkAll-{{ $uuid }}"
                            type="checkbox"
                            class="checkbox checkbox-sm"
                            x-ref="mainCheckbox"
                            x-bind:disabled="pageIds.length === 0"
                            @click="toggleCheckAll($el.checked)" />
                    </th>
                @endif

                <!-- EXPAND EXTRA HEADER -->
                @if($expandable)
                    <th class="w-1"></th>
                @endif

                <!-- SORT HANDLE HEADER -->
                @if($isSortingEnabled() && $sortHandle)
                    <th class="w-1"></th>
                @endif

                @foreach($headers as $header)
                    @php
                        # SKIP THE HIDDEN COLUMN
                        if($isHidden($header)) continue;

                        # Scoped slot`s name like `user.city` are compiled to `user___city` through `@scope / @endscope`.
                        # So we use current `$header` key  to find that slot on context.
                        $temp_key = str_replace('.', '___', $header['key'])
                    @endphp

                    <th
                        class="@if($isSortable($header)) cursor-pointer hover:bg-base-200 @endif {{ $header['class'] ?? ' ' }}"

                        @if($sortBy && $isSortable($header))
                            @click="$wire.set('sortBy', {column: '{{ $getSort($header)['column'] }}', direction: '{{ $getSort($header)['direction'] }}' })"
                        @endif
                    >
                        {{ isset(${"header_".$temp_key}) ? ${"header_".$temp_key}($header) : $header['label'] }}

                        @if($isSortable($header))
                            <x-artisanpack-icon :name="$isSortedBy($header) ? $getSort($header)['direction'] == 'asc' ? 'o-chevron-down' : 'o-chevron-up' : 'o-chevron-up-down'"  class="size-3! mb-1 ms-1" />
                        @endif
                    </th>
                @endforeach

                <!-- ACTIONS (Just a empty column) -->
                @if($actions)
                    <th class="w-1"></th>
                @endif
            </tr>
            </thead>

            <!-- ROWS -->
            <tbody
                @if($isSortingEnabled())
                    wire:sort="{{ $attributes->wire('sort')->value() ?: 'updateOrder' }}"
                    @if($sortGroup)
                        wire:sort:group="{{ $sortGroup }}"
                    @endif
                @endif
            >
                {{-- Top spacer for virtual scrolling --}}
                @if($isVirtual)
                    <tr x-show="virtualScroll" aria-hidden="true" class="virtual-spacer-top">
                        <td colspan="999" :style="'height:' + getTopSpacerHeight() + 'px; padding:0; border:none;'"></td>
                    </tr>
                @endif

            @foreach($visibleRows as $k => $row)
                <tr
                    wire:key="artisan-pack-table-row-{{ data_get($row, $keyBy) }}"
                    @class([$rowClasses($row), "hover:bg-base-200" => !$noHover, "cursor-move" => $isSortingEnabled() && !$sortHandle])
                    @if($isSortingEnabled())
                        wire:sort:item="{{ $getSortableItemValue($row) }}"
                    @endif
                    @if($attributes->has('@row-click'))
                        @click="$dispatch('row-click', {{ json_encode($row) }});"
                    @endif
                >
                    <!-- CHECKBOX -->
                    @if($selectable)
                        <td class="w-1">
                            <input
                                id="checkbox-{{ $uuid }}-{{ $k }}"
                                type="checkbox"
                                class="checkbox checkbox-sm"
                                value="{{ data_get($row, $selectableKey) }}"
                                x-model{{ $selectableModifier() }}="selection"
                                @click.stop="toggleCheck($el.checked, {{ json_encode($row) }})" />
                        </td>
                    @endif

                    <!-- EXPAND ICON -->
                    @if($expandable)
                        <td class="w-1 pe-0 py-0">
                            @if(data_get($row, $expandableCondition))
                                <x-artisanpack-icon
                                    name="o-chevron-down"
                                    ::class="isExpanded({{ $getKeyValue($row, 'expandableKey') }}) || '-rotate-90 !text-current'"
                                    class="cursor-pointer p-2 w-8 h-8 bg-base-300 rounded-lg"
                                    @click="toggleExpand({{ $getKeyValue($row, 'expandableKey') }});" />
                            @endif
                        </td>
                    @endif

                    <!-- SORT HANDLE -->
                    @if($isSortingEnabled() && $sortHandle)
                        <td class="w-1 pe-0 py-0" wire:sort:handle>
                            @if($sortHandleSlot)
                                {{ $sortHandleSlot($row) }}
                            @else
                                <x-artisanpack-icon
                                    name="o-bars-3"
                                    class="cursor-move p-2 w-8 h-8 text-base-content/50 hover:text-base-content" />
                            @endif
                        </td>
                    @endif

                    <!--  ROW VALUES -->
                    @foreach($headers as $header)
                        @php
                            # SKIP THE HIDDEN COLUMN
                            if($isHidden($header)) continue;

                            # Scoped slot`s name like `user.city` are compiled to `user___city` through `@scope / @endscope`.
                            # So we use current `$header` key  to find that slot on context.
                            $temp_key = str_replace('.', '___', $header['key'])
                        @endphp

                            <!--  HAS CUSTOM SLOT ? -->
                        @if(isset(${"cell_".$temp_key}))
                            <td @class([$cellClasses($row, $header), "p-0" => $hasLink($header)])>
                                @if($hasLink($header))
                                    <a href="{{ $redirectLink($row) }}" wire:navigate class="block py-3 px-4">
                                        @endif

                                        {{ ${"cell_".$temp_key}($row)  }}

                                        @if($hasLink($header))
                                    </a>
                                @endif
                            </td>
                        @else
                            <td @class([$cellClasses($row, $header), "p-0" => $hasLink($header)])>
                                @if($hasLink($header))
                                    <a href="{{ $redirectLink($row) }}" wire:navigate class="block py-3 px-4">
                                        @endif

                                        {{ $format($row, data_get($row, $header['key']), $header) }}

                                        @if($hasLink($header))
                                    </a>
                                @endif
                            </td>
                        @endif
                    @endforeach

                    <!-- ACTIONS -->
                    @if($actions)
                        <td class="text-right py-0">{{ $actions($row) }}</td>
                    @endif
                </tr>

                <!-- EXPANSION SLOT -->
                @if($expandable)
                    <tr wire:key="{{ $uuid }}-{{ $k }}--expand" class="!bg-inherit" :class="isExpanded({{ $getKeyValue($row, 'expandableKey') }}) || 'hidden'">
                        <td :colspan="colspanSize">
                            {{ $expansion($row) }}
                        </td>
                    </tr>
                @endif
            @endforeach

            {{-- Bottom spacer for virtual scrolling --}}
            @if($isVirtual)
                <tr x-show="virtualScroll" aria-hidden="true" class="virtual-spacer-bottom">
                    <td colspan="999" :style="'height:' + getBottomSpacerHeight() + 'px; padding:0; border:none;'"></td>
                </tr>
            @endif
            </tbody>

            @php
                $hasFooterSlot = isset($footer);
                $hasInfiniteScroll = $isInfiniteScrollEnabled() && $hasMorePages;
                $infiniteScrollColspan = collect($headers)->filter(fn($h) => !$isHidden($h))->count() + ($selectable ? 1 : 0) + ($expandable ? 1 : 0) + ($isSortingEnabled() && $sortHandle ? 1 : 0) + ($actions ? 1 : 0);
            @endphp

            <!-- FOOTER (combines footer slot and infinite scroll into single tfoot) -->
            @if($hasFooterSlot || $hasInfiniteScroll)
                <tfoot {{ $hasFooterSlot ? ($footer->attributes ?? '') : '' }}>
                    {{-- Footer slot content --}}
                    @if($hasFooterSlot)
                        {{ $footer }}
                    @endif

                    {{-- Infinite scroll row (Livewire 4+) --}}
                    @if($hasInfiniteScroll)
                        <tr>
                            <td colspan="{{ $infiniteScrollColspan }}">
                                @if($infiniteScrollModifier === 'once')
                                    <div wire:intersect.once="{{ $infiniteScrollMethod }}" class="py-4 text-center">
                                @elseif($infiniteScrollModifier === 'half')
                                    <div wire:intersect.half="{{ $infiniteScrollMethod }}" class="py-4 text-center">
                                @elseif($infiniteScrollModifier === 'full')
                                    <div wire:intersect.full="{{ $infiniteScrollMethod }}" class="py-4 text-center">
                                @else
                                    <div wire:intersect="{{ $infiniteScrollMethod }}" class="py-4 text-center">
                                @endif
                                    <span wire:loading.remove wire:target="{{ $infiniteScrollMethod }}" class="text-base-content/50">{{ $infiniteScrollText }}</span>
                                    <x-artisanpack-loading wire:loading wire:target="{{ $infiniteScrollMethod }}" class="hidden" />
                                </div>
                            </td>
                        </tr>
                    @endif
                </tfoot>
            @endif
        </table>

        @if(count($rows) === 0)
            @if($showEmptyText)
                <div class="text-center py-4 text-base-content/50">
                    {{ $emptyText }}
                </div>
            @endif
            @if($empty)
                <div class="text-center py-4 text-base-content/50">
                    {{ $empty }}
                </div>
            @endif
        @endif
    </div>

    {{-- Close virtual scroll container --}}
    @if($isVirtual)
    </div>
    @endif

    <!-- Pagination -->
    @if($withPagination)
        @if($perPage)
            <x-artisanpack-pagination :rows="$rows" :per-page-values="$perPageValues" wire:model.live="{{ $perPage }}" />
        @else
            <x-artisanpack-pagination :rows="$rows" :per-page-values="$perPageValues" />
        @endif
    @endif
</div>