<div wire:key="datepicker-{{ rand() }}">
    @php
        // We need this extra step to support models arrays. Ex: wire:model="emails.0"  , wire:model="emails.1"
        $uuid = $uuid . ($modelName() ?? '');
        
        // Get theming data
        $customCSSVariables = $getCustomCSSVariables();
        $themeAttributes = $getThemeAttributes();
    @endphp

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

            <div class="w-full">
                {{-- PREPEND --}}
                @if($prepend)
                    {{ $prepend }}
                @endif

                {{-- THE LABEL THAT HOLDS THE INPUT --}}
                <label
                    @if($isDisabled())
                        disabled
                    @endif

                    {{
                        $attributes->whereStartsWith('class')->class([
                            "input w-full",
                            "join-item" => $prepend || $append,
                            "border-dashed" => $isReadonly(),
                            "!input-error" => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError
                        ])
                    }}
                 >

                    {{-- ICON LEFT --}}
                    @if($icon)
                        <x-artisanpack-icon :name="$icon" class="pointer-events-none w-4 h-4 -ml-1 opacity-40" />
                    @endif

                    {{-- INPUT --}}
                    <div
                        x-data="{instance: undefined}"
                        x-init="
                            const config = {{ $setup() }};
                            
                            // Apply theme configuration
                            config.onReady = function(selectedDates, dateStr, instance) {
                                if (instance.calendarContainer) {
                                    @foreach($themeAttributes as $attr => $value)
                                        instance.calendarContainer.setAttribute('{{ $attr }}', '{{ $value }}');
                                    @endforeach
                                    
                                    @if($color)
                                        instance.calendarContainer.setAttribute('data-color', '{{ $color }}');
                                    @endif
                                    
                                    @if($colorAdjustment)
                                        instance.calendarContainer.setAttribute('data-color-adjustment', '{{ $colorAdjustment }}');
                                    @endif
                                    
                                    @if($customCSSVariables)
                                        instance.calendarContainer.style.cssText = '{{ $customCSSVariables }}' + (instance.calendarContainer.style.cssText || '');
                                    @endif
                                }
                            };
                            
                            instance = flatpickr($refs.input, config);
                        "
                        @if(isset($config["mode"]) && $config["mode"] == "range" && $attributes->get('live') && $modelName())
                            @change="const value = $event.target.value; if(value.split(instance.l10n.rangeSeparator).length == 2 && typeof $wire !== 'undefined') { $wire.set('{{ $modelName() }}', value) };"
                        @endif
                        x-on:livewire:navigating.window="instance.destroy();"
                        class="w-full"
                        @if($customCSSVariables)
                            style="{{ $customCSSVariables }}"
                        @endif
                    >
                        <input x-ref="input" {{ $attributes->merge(['type' => 'date']) }} />
                    </div>


                    {{-- ICON RIGHT --}}
                    @if($iconRight)
                        <x-artisanpack-icon :name="$iconRight" class="pointer-events-none w-4 h-4 opacity-40" />
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
</div>
