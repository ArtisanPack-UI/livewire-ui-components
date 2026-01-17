<div x-data="{
        tags: @entangle($attributes->wire('model')),
        tag: null,
        focused: false,
        isReadonly: {{ json_encode($isReadonly()) }},
        isRequired: {{ json_encode($isRequired()) }},
        isDisabled: {{ json_encode($isDisabled()) }},

        // Search functionality properties
        options: {{ json_encode($options) }},
        isSearchable: {{ json_encode($searchable) }},
        minChars: {{ $minChars }},
        showDropdown: false,
        searchValue: '',
        filteredOptions: [],

        init() {
            if (this.tags == null || !Array.isArray(this.tags)) {
                this.tags = [];
            }

            // Initialize filtered options
            if (this.isSearchable) {
                this.filteredOptions = this.options;
            }

            // Fix weird issue when navigating back
            document.addEventListener('livewire:navigating', () => {
                let elements = document.querySelectorAll('.artisanpack-tags-element');
                elements.forEach(el =>  el.remove());
            });
        },

        get noResults() {
            if (!this.isSearchable || this.searchValue === '') {
                return false;
            }
            return this.filteredOptions.length === 0;
        },
        push() {
            if (this.tag != '' && this.tag != null && this.tag != undefined) {
                let tag = this.tag.toString().replace(/,/g, '').trim()

                if (tag != '' && !this.hasTag(tag)) {
                    this.tags.push(tag)
                }
            }

            this.clear()
        },

        hasTag(tag) {
            var tag = this.tags.find(e => {
                e = e.toString();
                return e.toLowerCase() === tag.toLowerCase()
            })
            return tag != undefined
        },

        remove(index) {
            this.tags.splice(index, 1)
        },

        clear() {
            this.tag = null;
            this.focused = false;
            this.showDropdown = false;
            this.searchValue = '';
            if (this.isSearchable) {
                this.filteredOptions = this.options;
            }
        },

        clearAll() {
            this.tags = [];
        },

        focus() {
            if (this.isReadonly || this.isDisabled) {
                return
            }

            this.focused = true
            if (this.isSearchable) {
                this.showDropdown = true;
            }
            $refs.searchInput.focus()
        },

        resize() {
            $refs.searchInput.style.width = ($refs.searchInput.value.length + 1) * 0.55 + 'rem'
        },

        search(value, event) {
            this.searchValue = value;
            this.tag = value; // Keep tag and searchValue synchronized

            if (value.length < this.minChars) {
                this.filteredOptions = [];
                this.showDropdown = false;
                return;
            }

            // Prevent search for navigation keys
            if (event && ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'Shift', 'CapsLock', 'Tab',
                          'Control', 'Alt', 'Home', 'End', 'PageUp', 'PageDown'].includes(event.key)) {
                return;
            }

            this.showDropdown = true;

            // Call server-side search function if configured, otherwise filter locally
            if (this.isSearchable && '{{ $searchFunction }}' !== 'search') {
                @this.{{ str_contains($searchFunction, '(')
                      ? preg_replace('/\((.*?)\)/', '(value, $1)', $searchFunction)
                      : $searchFunction . '(value)'
                    }}.then(() => this.resize());
            } else {
                this.filterLocalOptions(value);
            }
        },

        filterLocalOptions(value) {
            if (!value) {
                this.filteredOptions = this.options;
                return;
            }

            const searchTerm = value.toLowerCase();
            this.filteredOptions = this.options.filter(option => {
                const label = option.{{ $optionLabel }} || option.name || option.title || '';
                return label.toLowerCase().includes(searchTerm);
            });
        },

        selectOption(option) {
            if (this.isReadonly || this.isDisabled) {
                return;
            }

            const optionLabel = option.{{ $optionLabel }} || option.name || option.title || '';
            
            if (optionLabel && !this.hasTag(optionLabel)) {
                this.tags.push(optionLabel);
            }

            this.tag = null;
            this.searchValue = '';
            this.showDropdown = false;
            this.filteredOptions = this.options;
            this.$refs.searchInput.value = '';
            this.resize();
        },

        toggleDropdown() {
            if (this.isReadonly || this.isDisabled || !this.isSearchable) {
                return;
            }
            
            this.showDropdown = !this.showDropdown;
            if (this.showDropdown) {
                this.$refs.searchInput.focus();
            }
        }
    }"
    @keydown.escape="clear()"
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
                    @click="focus()"

                    @if($isDisabled())
                        disabled
                    @endif

                    {{
                        $attributes->whereStartsWith('class')->class([
                            "input w-full h-fit pl-2.5",
                            "join-item" => $prepend || $append,
                            "border-dashed" => $attributes->has("readonly") && $attributes->get("readonly") == true,
                            "!input-error" => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError
                        ])
                    }}
                 >
                    {{-- PREFIX --}}
                    @if($prefix)
                        <span class="label">{{ $prefix }}</span>
                    @endif

                    {{-- ICON LEFT --}}
                    @if($icon)
                        <x-artisanpack-icon :name="$icon" class="pointer-events-none w-4 h-4 opacity-40" />
                    @endif

                    <div class="w-full py-1 min-h-9.5 content-center text-wrap flex items-center gap-1">
                        {{-- TAGS --}}
                        <span wire:key="tags-{{ $uuid }}">
                            <template :key="index" x-for="(tag, index) in tags">
                                <span class="artisanpack-tags-element cursor-pointer badge badge-soft m-0.5 !inline-block">
                                    <span x-text="tag"></span>
                                    <x-artisanpack-icon @click="remove(index)" x-show="!isReadonly && !isDisabled" name="o-x-mark" class="w-4 h-4 mb-0.5 hover:text-error" />
                                </span>
                            </template>
                        </span>

                        {{-- PLACEHOLDER --}}
                        <span :class="(focused || tags.length || tag) ? 'hidden' : 'text-base-content/40'">
                            {{ $attributes->get('placeholder') }}
                        </span>

                        {{-- INPUT --}}
                        <input
                            id="{{ $uuid }}"
                            type="text"
                            enterkeyhint="done"
                            class="flex-1 !inline-block"
                            x-ref="searchInput"
                            :required="isRequired"
                            :readonly="isReadonly"
                            :disabled="isDisabled"
                            x-model="tag"
                            @input="
                                focus(); 
                                resize();
                                searchValue = $el.value; // Always sync searchValue with input
                                if (isSearchable) {
                                    search($el.value, $event);
                                }
                            "
                            @focus="focus()"
                            @click.outside="clear()"
                            @keydown.enter.prevent="
                                if (isSearchable && showDropdown && filteredOptions.length > 0) {
                                    selectOption(filteredOptions[0]);
                                } else if ({{ json_encode($allowCustomTags) }} || !isSearchable) {
                                    push();
                                }
                            "
                            @keyup.prevent="if (event.key === ',') { push() }"
                            @if($searchable)
                                @keydown.debounce.{{ $debounce }}="search($el.value, $event)"
                            @endif
                        />
                    </div>

                    {{-- CLEAR ICON  --}}
                    @if($clearable && !$isReadonly() && !$isDisabled())
                        <x-artisanpack-icon @click="clearAll()" x-show="tags.length" name="o-x-mark" class="cursor-pointer w-4 h-4 opacity-40"/>
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
                    <div class="{{ $errorClass }}" :class="'text-error'">{{ $line }}</div>
                    @break($firstErrorOnly)
                @endforeach
                @break($firstErrorOnly)
            @endforeach
        @endif

        {{-- HINT --}}
        @if($hint)
            <div class="{{ $hintClass }}" :class="'fieldset-label'">{{ $hint }}</div>
        @endif
    </fieldset>

    {{-- SEARCH DROPDOWN --}}
    @if($searchable)
        <div x-cloak x-show="showDropdown && isSearchable" class="relative" wire:key="tags-dropdown-{{ $uuid }}">
            <div
                wire:key="tags-options-list-{{ $uuid }}"
                class="{{ $height }} w-full absolute z-10 shadow-xl bg-base-100 border border-base-content/10 rounded-lg cursor-pointer overflow-y-auto"
                x-anchor.bottom-start="$refs.container ?? $el.previousElementSibling"
                @click.outside="clear()"
            >
                {{-- PROGRESS --}}
                <progress class="progress absolute top-0 h-0.5 hidden data-loading:block" wire:loading.class.remove="hidden" wire:target="{{ preg_replace('/\((.*?)\)/', '', $searchFunction) }}"></progress>

                {{-- NO RESULTS --}}
                <div
                    x-show="noResults"
                    wire:key="no-results-{{ rand() }}"
                    class="p-3 decoration-wavy decoration-warning underline font-bold border border-s-4 border-s-warning border-b-base-200"
                >
                    {{ $noResultText }}
                </div>

                {{-- CUSTOM TAG CREATION HINT --}}
                @if($allowCustomTags)
                    <div
                        x-show="searchValue !== '' && noResults"
                        wire:key="custom-tag-hint-{{ rand() }}"
                        class="p-3 text-sm text-base-content/60 border-b border-base-200 bg-base-50"
                    >
                        {{ $customTagsText }}: "<span x-text="searchValue"></span>"
                    </div>
                @endif

                {{-- OPTIONS LIST --}}
                <template x-for="(option, index) in filteredOptions" :key="option.{{ $optionValue }} || option.id || index">
                    <div
                        @click="selectOption(option)"
                        @keydown.enter="selectOption(option)"
                        class="border-s-4 border-base-content/10 focus:bg-base-200 focus:outline-none hover:bg-base-200 cursor-pointer"
                        tabindex="0"
                        :class="index === 0 && 'border-s-base-content'"
                    >
                        {{-- OPTION ITEM --}}
                        <div class="flex justify-start items-center gap-4 px-3">
                            <!-- AVATAR -->
                            <template x-if="option.{{ $optionAvatar }}">
                                <div class="py-3">
                                    <div class="avatar">
                                        <div class="w-11 rounded-full">
                                            <img :src="option.{{ $optionAvatar }}" />
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- CONTENT -->
                            <div class="flex-1 overflow-hidden whitespace-nowrap text-ellipsis truncate w-0">
                                <div class="py-3">
                                    <div class="font-semibold truncate" x-text="option.{{ $optionLabel }} || option.name || option.title || ''"></div>
                                    <template x-if="option.{{ $optionSubLabel }} || '{{ $optionSubLabel }}'">
                                        <div class="text-base-content/50 text-sm truncate" x-text="option.{{ $optionSubLabel }} || ''"></div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    @endif
</div>
