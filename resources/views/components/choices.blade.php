<div x-data="{ focused: false, selection: $wire.entangle($attributes->wire('model')) }">
    <div
        @click.outside = "clear()"
        @keyup.esc = "clear()"

        x-data="{
            options: {{ json_encode($options) }},
            isSingle: {{ json_encode($single) }},
            isSearchable: {{ json_encode($searchable) }},
            isReadonly: {{ json_encode($isReadonly()) }},
            isDisabled: {{ json_encode($isDisabled()) }},
            isRequired: {{ json_encode($isRequired()) }},
            minChars: {{ $minChars }},

            init() {
                // Fix weird issue when navigating back
                document.addEventListener('livewire:navigating', () => {
                    let elements = document.querySelectorAll('.mary-choices-element');
                    elements.forEach(el =>  el.remove());
                });
            },
            get selectedOptions() {
                return this.isSingle
                    ? this.options.filter(i => i.{{ $optionValue }} == this.selection)
                    : this.selection.map(i => this.options.filter(o => o.{{ $optionValue }} == i)[0])
            },
            get noResults() {
                if (!this.isSearchable || this.$refs.searchInput.value == '') {
                    return false
                }

                return this.isSingle
                        ? (this.selection && this.options.length  == 1) || (!this.selection && this.options.length == 0)
                        : this.options.length <= this.selection.length
            },
            get isAllSelected() {
                return this.options.length == this.selection.length
            },
            get isSelectionEmpty() {
                return this.isSingle
                    ? this.selection == null || this.selection == ''
                    : this.selection.length == 0
            },
            selectAll() {
                this.selection = this.options.map(i => i.{{ $optionValue }})
                this.dispatchChangeEvent({ value: this.selection })
            },
            clear() {
                this.focused = false;
                this.$refs.searchInput.value = ''
            },
            reset() {
                this.clear();
                this.isSingle
                    ? this.selection = null
                    : this.selection = []

                this.dispatchChangeEvent({ value: this.selection})
            },
            focus() {
                if (this.isReadonly || this.isDisabled) {
                    return
                }

                this.focused = true
                this.$refs.searchInput.focus()
            },
            resize() {
                $refs.searchInput.style.width = ($refs.searchInput.value.length + 1) * 0.55 + 'rem'
            },
            isActive(id) {
                return this.isSingle
                    ? this.selection == id
                    : this.selection.includes(id)
            },
            toggle(id, keepOpen = false) {
                if (this.isReadonly || this.isDisabled) {
                    return
                }

                if (this.isSingle) {
                    this.selection = id
                    this.focused = false
                } else {
                    this.selection.includes(id)
                        ? this.selection = this.selection.filter(i => i != id)
                        : this.selection.push(id)
                }

                this.dispatchChangeEvent({ value: this.selection })

                this.$refs.searchInput.value = ''

                if (!keepOpen) {
                    this.$refs.searchInput.focus()
                }

            },
            search(value, event) {
                if (value.length < this.minChars) {
                    return
                }

                // Prevent search for this keys
                if (event && ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'Shift', 'CapsLock', 'Tab',
                              'Control', 'Alt', 'Home', 'End', 'PageUp', 'PageDown'].includes(event.key)) {
                    return;
                }

                // Call search function from parent component
                // `search(value)` or `search(value, extra1, extra2 ...)`
                @this.{{ str_contains($searchFunction, '(')
                          ? preg_replace('/\((.*?)\)/', '(value, $1)', $searchFunction)
                          : $searchFunction . '(value)'
                        }}.then(()=> this.resize())
            },
            dispatchChangeEvent(detail) {
                this.$refs.searchInput.dispatchEvent(new CustomEvent('change-selection', { bubbles: true, detail }))
            }
        }"

        @keydown.up="$focus.previous()"
        @keydown.down="$focus.next()"
    >
        <fieldset class="fieldset py-0">
            {{-- STANDARD LABEL --}}
            @if($label && !$inline)
                <legend class="fieldset-legend mb-0.5">
                    {{ $label }}

                    @if($attributes->get('required'))
                        <span class="text-error">*</span>
                    @endif
                </legend>
            @endif

            <label @class(["floating-label" => $label && $inline])>
                {{-- FLOATING LABEL--}}
                @if ($label && $inline)
                    <span class="font-semibold">{{ $label }}</span>
                @endif

                <div @class(["w-full", "join" => $prepend || $append])>
                    {{-- PREPEND --}}
                    @if($prepend)
                        {{ $prepend }}
                    @endif

                    {{-- THE LABEL THAT HOLDS THE INPUT --}}
                    <label
                        x-ref="container"

                        @if($isDisabled())
                            disabled
                        @endif

                        @if(!$isDisabled() && !$isReadonly())
                            @click="focus()"
                        @endif

                        {{
                            $attributes->whereStartsWith('class')->class([
                                "select w-full min-h-fit pl-2.5",
                                "join-item" => $prepend || $append,
                                "border-dashed" => $attributes->has("readonly") && $attributes->get("readonly") == true,
                            ])
                        }}
                    >
                        <div class="flex items-center gap-2 py-2">
                            {{-- ICON --}}
                            @if($icon)
                                <x-artisanpack-icon :name="$icon" class="pointer-events-none w-4 h-4 opacity-40" />
                            @endif

                            {{-- PREFIX --}}
                            @if($prefix)
                                <span class="label">{{ $prefix }}</span>
                            @endif

                            {{-- SELECTED ITEMS --}}
                            <div class="flex flex-wrap gap-1 items-center">
                                <template x-for="(option, index) in selectedOptions" :key="index">
                                    <div
                                        class="mary-choices-element badge badge-neutral gap-1 cursor-pointer"
                                        @click="toggle(option.{{ $optionValue }})"
                                    >
                                        {{-- SELECTION SLOT --}}
                                        @if($selection)
                                            <span x-html="document.getElementById('selection-{{ $uuid }}-' + option.{{ $optionValue }})?.innerHTML"></span>
                                        @else
                                            <span x-text="option.{{ $optionLabel }}"></span>
                                        @endif

                                        {{-- REMOVE ICON --}}
                                        <x-artisanpack-icon
                                            x-show="!isReadonly && !isDisabled"
                                            name="o-x-mark"
                                            class="w-3 h-3"
                                        />
                                    </div>
                                </template>

                                {{-- COMPACT BADGE --}}
                                <div
                                    x-show="{{ $compact ? 'true' : 'false' }} && !isSelectionEmpty && selection.length > 1"
                                    class="badge badge-neutral gap-1 cursor-pointer"
                                    @click="focus()"
                                >
                                    <span x-text="selection.length + ' {{ $compactText }}'"></span>
                                </div>

                                {{-- SEARCH INPUT --}}
                                <div class="flex-1">
                                    <input
                                        x-ref="searchInput"
                                        type="text"
                                        class="bg-transparent border-0 outline-none p-0 m-0 min-w-[4rem] w-auto"
                                        {{ $attributes->whereStartsWith('@') }}

                                        @if($isReadonly() || $isDisabled() || ! $searchable)
                                            readonly
                                        @else
                                            @focus="focus()"
                                        @endif

                                        @if($isDisabled())
                                            disabled
                                         @endif

                                        @if($searchable)
                                            @keydown.debounce.{{ $debounce }}="search($el.value, $event)"
                                        @endif
                                     />
                                </div>
                            </div>

                            {{-- CLEAR ICON  --}}
                            @if($clearable && !$isReadonly() && !$isDisabled())
                                <x-artisanpack-icon @click="reset()" x-show="!isSelectionEmpty" name="o-x-mark" class="cursor-pointer w-4 h-4 opacity-40"/>
                            @endif

                            {{-- ICON RIGHT --}}
                            @if($iconRight)
                                <x-artisanpack-icon :name="$iconRight" class="pointer-events-none w-4 h-4 opacity-40" />
                            @endif

                            {{-- SUFFIX --}}
                            @if($suffix)
                                <span class="label">{{ $suffix }}</span>
                            @endif
                        </label>

                        {{-- APPEND --}}
                        @if($append)
                            {{ $append }}
                        @endif
                    </div>
                </label>

                {{-- ERROR --}}
                @if(!$omitError && $errors->has($errorFieldName()))
                    @foreach($errors->get($errorFieldName()) as $message)
                        @foreach(Arr::wrap($message) as $line)
                            <div class="{{ $errorClass }}" x-class="text-error">{{ $line }}</div>
                            @break($firstErrorOnly)
                        @endforeach
                        @break($firstErrorOnly)
                    @endforeach
                @endif

                {{-- HINT --}}
                @if($hint)
                    <div class="{{ $hintClass }}" x-classes="fieldset-label">{{ $hint }}</div>
                @endif
            </fieldset>

            {{-- OPTIONS LIST --}}
            <div x-cloak x-show="focused" class="relative" wire:key="options-list-main-{{ $uuid }}">
                <div
                    wire:key="options-list-{{ $uuid }}"
                    class="{{ $height }} w-full absolute z-10 shadow-xl bg-base-100 border border-base-content/10 rounded-lg cursor-pointer overflow-y-auto"
                    x-anchor.bottom-start="$refs.container"
                >

                    {{-- PROGRESS --}}
                    <progress class="progress absolute top-0 h-0.5 hidden data-loading:block" wire:loading.class.remove="hidden" wire:target="{{ preg_replace('/\((.*?)\)/', '', $searchFunction) }}"></progress>

                   {{-- SELECT ALL --}}
                   @if($allowAll)
                       <div
                            wire:key="allow-all-{{ rand() }}"
                            class="font-bold   border border-s-4 border-s-base-content/10 border-base-200 hover:bg-base-200"
                       >
                            <div x-show="!isAllSelected" @click="selectAll()" class="p-3 underline decoration-wavy decoration-info">{{ $allowAllText }}</div>
                            <div x-show="isAllSelected" @click="reset()" class="p-3 underline decoration-wavy decoration-error">{{ $removeAllText }}</div>
                       </div>
                   @endif

                    {{-- NO RESULTS --}}
                    <div
                        x-show="noResults"
                        wire:key="no-results-{{ rand() }}"
                        class="p-3 decoration-wavy decoration-warning underline font-bold border border-s-4 border-s-warning border-b-base-200"
                    >
                        {{ $noResultText }}
                    </div>

                    @foreach($options as $option)
                        <div
                            wire:key="option-{{ data_get($option, $optionValue) }}"
                            @click="toggle({{ $getOptionValue($option) }}, true)"
                            @keydown.enter="toggle({{ $getOptionValue($option) }}, true)"
                            :class="isActive({{ $getOptionValue($option) }}) && 'border-s-4 border-s-base-content'"
                            class="border-s-4 border-base-content/10 focus:bg-base-200 focus:outline-none"
                            tabindex="0"
                        >
                            {{-- ITEM SLOT --}}
                            @if($item)
                                {{ $item($option) }}
                            @else
                                <x-artisanpack-list-item :item="$option" :value="$optionLabel" :sub-value="$optionSubLabel" :avatar="$optionAvatar" />
                            @endif

                            {{-- SELECTION SLOT --}}
                            @if($selection)
                                <span id="selection-{{ $uuid }}-{{ data_get($option, $optionValue) }}" class="hidden">
                                    {{ $selection($option) }}
                                </span>
                            @endif
                        </div>
                    @endforeach

                    {{-- LAZY LOAD MORE OPTIONS (Livewire 4+) --}}
                    @if($isLazyLoadEnabled() && $hasMoreOptions)
                        <div
                            wire:key="lazy-load-{{ $uuid }}"
                            {{ $getLazyLoadDirective() }}="{{ $lazyLoadMethod }}"
                            class="p-3 text-center border-s-4 border-base-content/10"
                        >
                            <x-artisanpack-loading class="loading-sm" />
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
