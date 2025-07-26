<div x-data="{
                selection: @entangle($attributes->wire('model')),
                pageIds: {{ json_encode($getAllIds()) }},
                isSelectable: {{ json_encode($selectable) }},
                colspanSize: 0,
                init() {
                    this.colspanSize = $refs.headers.childElementCount

                    if (this.isSelectable) {
                        this.handleCheckAll()
                    }
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
                }
             }"
>
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
            <thead @class(["text-base-content", "hidden" => $noHeaders])>
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
            <tbody>
            @foreach($rows as $k => $row)
                <tr
                    wire:key="artisan-pack-table-row-{{ data_get($row, $keyBy) }}"
                    @class([$rowClasses($row), "hover:bg-base-200" => !$noHover])
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
            </tbody>

            <!-- FOOTER SLOT -->
            @isset ($footer)
                <tfoot {{ $footer->attributes ?? '' }}>
                {{ $footer }}
                </tfoot>
            @endisset
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
    <!-- Pagination -->
    @if($withPagination)
        @if($perPage)
            <x-artisanpack-pagination :rows="$rows" :per-page-values="$perPageValues" wire:model.live="{{ $perPage }}" />
        @else
            <x-artisanpack-pagination :rows="$rows" :per-page-values="$perPageValues" />
        @endif
    @endif
</div>