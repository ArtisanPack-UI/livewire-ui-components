@php
    $componentId = $id ?? $uuid;
    $inputName = $name ?? $modelName() ?? $componentId;
@endphp

<div class="form-control {{ $inlineLabel ? 'flex-row items-center gap-4' : '' }}" x-cloak>
    @if($label)
        <label for="{{ $componentId }}" class="{{ $inlineLabel ? 'label-text' : 'label' }}">
            <span class="label-text">
                {{ $label }}
                @if($required) <span class="text-error">*</span> @endif
            </span>
        </label>
    @endif

    <div class="flex items-center gap-2">
        <div id="{{ $componentId }}" class="rating gap-1 {{ $this->getSizeClasses() }}"
             @if($hoverEffect) x-data="{ hovering: 0 }" @endif>

            <!-- Hidden input for no rating -->
            <input type="radio"
                   name="{{ $inputName }}"
                   value="0"
                   class="rating-hidden hidden"
                   {{ $attributes->whereStartsWith('wire:model') }}
                   {{ $disabled ? 'disabled' : '' }}
                   {{ $readonly ? 'readonly' : '' }} />

            <!-- Rating inputs -->
            @for ($i = 1; $i <= $total; $i++)
                <label class="cursor-pointer {{ $disabled ? 'cursor-not-allowed opacity-50' : '' }}"
                       @if($hoverEffect)
                       @mouseenter="hovering = {{ $i }}"
                       @mouseleave="hovering = 0"
                       @endif>
                    <input type="radio"
                           name="{{ $inputName }}"
                           value="{{ $i }}"
                           class="sr-only"
                           {{ $attributes->whereStartsWith('wire:model') }}
                           {{ $disabled ? 'disabled' : '' }}
                           {{ $readonly ? 'readonly' : '' }}
                           @if($value == $i) checked @endif />

                    <!-- Rating icon -->
                    <span class="inline-block transition-colors duration-150
                                {{ $this->getStarColorClass($i) }}"
                          style="{{ $this->getStarColorStyle($i) }}"
                          @if($hoverEffect)
                          :class="{ '{{ $this->getColorClass($this->resolveFilledColor()) }}': hovering >= {{ $i }},
                                    '{{ $this->getColorClass($this->resolveEmptyColor()) }}': hovering > 0 && hovering < {{ $i }} }"
                          @endif>

                        @if($halfStars && $this->getStarState($i) === 'half')
                            <!-- Half star using CSS clip-path or overlay -->
                            <span class="relative">
                                <x-artisanpack-icon :name="$this->resolveEmptyIcon()" class="w-6 h-6 {{ $this->getColorClass($this->resolveEmptyColor()) }}" />
                                <span class="absolute inset-0 overflow-hidden" style="clip-path: inset(0 50% 0 0);">
                                    <x-artisanpack-icon :name="$this->resolveFilledIcon()" class="w-6 h-6 {{ $this->getColorClass($this->resolveFilledColor()) }}" />
                                </span>
                            </span>
                        @else
                            <x-artisanpack-icon :name="$this->getStarIcon($i)" class="w-6 h-6" />
                        @endif
                    </span>
                </label>
            @endfor

            @if($clearable && $value > 0)
                <button type="button"
                        class="ml-2 text-gray-400 hover:text-gray-600 transition-colors"
                        {{ $attributes->whereStartsWith('wire:click') }}
                        onclick="document.querySelector('input[name=&quot;{{ $inputName }}&quot;][value=&quot;0&quot;]').checked = true;
                                 document.querySelector('input[name=&quot;{{ $inputName }}&quot;][value=&quot;0&quot;]').dispatchEvent(new Event('change'));">
                    <x-artisanpack-icon :name="$clearIcon" class="w-4 h-4" />
                </button>
            @endif
        </div>

        @if($showValue && $value > 0)
            <span class="text-sm text-gray-600 ml-2">{{ $this->getFormattedValue() }}</span>
        @endif
    </div>

    @if($helper && !$error)
        <div class="label">
            <span class="label-text-alt text-gray-500">{{ $helper }}</span>
        </div>
    @endif

    @if($error)
        <div class="label">
            <span class="label-text-alt text-error">{{ $error }}</span>
        </div>
    @endif
</div>
