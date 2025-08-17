@php
    $componentId = $id ?? $uuid;
    $inputName = $name ?? $modelName() ?? $componentId;
    
    // Size classes - use the size() method or default to md
    $sizeClass = $size() ?? match($size) {
        'sm' => 'rating-sm',
        'md' => 'rating-md', 
        'lg' => 'rating-lg',
        'xl' => 'rating-xl',
        default => 'rating-md'
    };
    
    // Color resolution for background color on mask
    $resolvedColor = $color ?? 'warning';
    
    // JIT-safe color resolution - explicitly define common colors to ensure they're included in CSS
    $colorClass = match($resolvedColor) {
        // DaisyUI semantic colors
        'primary' => 'bg-primary',
        'secondary' => 'bg-secondary',
        'accent' => 'bg-accent',
        'warning' => 'bg-warning',
        'error' => 'bg-error',
        'success' => 'bg-success',
        'info' => 'bg-info',
        'neutral' => 'bg-neutral',
        
        // Common Tailwind colors - Red
        'red' => 'bg-red-500', 'red-50' => 'bg-red-50', 'red-100' => 'bg-red-100', 'red-200' => 'bg-red-200',
        'red-300' => 'bg-red-300', 'red-400' => 'bg-red-400', 'red-500' => 'bg-red-500', 'red-600' => 'bg-red-600',
        'red-700' => 'bg-red-700', 'red-800' => 'bg-red-800', 'red-900' => 'bg-red-900', 'red-950' => 'bg-red-950',
        
        // Orange
        'orange' => 'bg-orange-500', 'orange-50' => 'bg-orange-50', 'orange-100' => 'bg-orange-100', 'orange-200' => 'bg-orange-200',
        'orange-300' => 'bg-orange-300', 'orange-400' => 'bg-orange-400', 'orange-500' => 'bg-orange-500', 'orange-600' => 'bg-orange-600',
        'orange-700' => 'bg-orange-700', 'orange-800' => 'bg-orange-800', 'orange-900' => 'bg-orange-900', 'orange-950' => 'bg-orange-950',
        
        // Yellow
        'yellow' => 'bg-yellow-500', 'yellow-50' => 'bg-yellow-50', 'yellow-100' => 'bg-yellow-100', 'yellow-200' => 'bg-yellow-200',
        'yellow-300' => 'bg-yellow-300', 'yellow-400' => 'bg-yellow-400', 'yellow-500' => 'bg-yellow-500', 'yellow-600' => 'bg-yellow-600',
        'yellow-700' => 'bg-yellow-700', 'yellow-800' => 'bg-yellow-800', 'yellow-900' => 'bg-yellow-900', 'yellow-950' => 'bg-yellow-950',
        
        // Green
        'green' => 'bg-green-500', 'green-50' => 'bg-green-50', 'green-100' => 'bg-green-100', 'green-200' => 'bg-green-200',
        'green-300' => 'bg-green-300', 'green-400' => 'bg-green-400', 'green-500' => 'bg-green-500', 'green-600' => 'bg-green-600',
        'green-700' => 'bg-green-700', 'green-800' => 'bg-green-800', 'green-900' => 'bg-green-900', 'green-950' => 'bg-green-950',
        
        // Blue
        'blue' => 'bg-blue-500', 'blue-50' => 'bg-blue-50', 'blue-100' => 'bg-blue-100', 'blue-200' => 'bg-blue-200',
        'blue-300' => 'bg-blue-300', 'blue-400' => 'bg-blue-400', 'blue-500' => 'bg-blue-500', 'blue-600' => 'bg-blue-600',
        'blue-700' => 'bg-blue-700', 'blue-800' => 'bg-blue-800', 'blue-900' => 'bg-blue-900', 'blue-950' => 'bg-blue-950',
        
        // Purple
        'purple' => 'bg-purple-500', 'purple-50' => 'bg-purple-50', 'purple-100' => 'bg-purple-100', 'purple-200' => 'bg-purple-200',
        'purple-300' => 'bg-purple-300', 'purple-400' => 'bg-purple-400', 'purple-500' => 'bg-purple-500', 'purple-600' => 'bg-purple-600',
        'purple-700' => 'bg-purple-700', 'purple-800' => 'bg-purple-800', 'purple-900' => 'bg-purple-900', 'purple-950' => 'bg-purple-950',
        
        // Pink
        'pink' => 'bg-pink-500', 'pink-50' => 'bg-pink-50', 'pink-100' => 'bg-pink-100', 'pink-200' => 'bg-pink-200',
        'pink-300' => 'bg-pink-300', 'pink-400' => 'bg-pink-400', 'pink-500' => 'bg-pink-500', 'pink-600' => 'bg-pink-600',
        'pink-700' => 'bg-pink-700', 'pink-800' => 'bg-pink-800', 'pink-900' => 'bg-pink-900', 'pink-950' => 'bg-pink-950',
        
        // Grayscale
        'gray' => 'bg-gray-500', 'gray-50' => 'bg-gray-50', 'gray-100' => 'bg-gray-100', 'gray-200' => 'bg-gray-200',
        'gray-300' => 'bg-gray-300', 'gray-400' => 'bg-gray-400', 'gray-500' => 'bg-gray-500', 'gray-600' => 'bg-gray-600',
        'gray-700' => 'bg-gray-700', 'gray-800' => 'bg-gray-800', 'gray-900' => 'bg-gray-900', 'gray-950' => 'bg-gray-950',
        'slate' => 'bg-slate-500', 'slate-50' => 'bg-slate-50', 'slate-100' => 'bg-slate-100', 'slate-200' => 'bg-slate-200',
        'slate-300' => 'bg-slate-300', 'slate-400' => 'bg-slate-400', 'slate-500' => 'bg-slate-500', 'slate-600' => 'bg-slate-600',
        'slate-700' => 'bg-slate-700', 'slate-800' => 'bg-slate-800', 'slate-900' => 'bg-slate-900', 'slate-950' => 'bg-slate-950',
        
        // Handle hex colors and unknowns
        default => str_starts_with($resolvedColor, '#') ? '' : 'bg-warning' // Fallback to bg-warning instead of dynamic class
    };
    
    // Handle custom hex colors with inline styles
    $colorStyle = str_starts_with($resolvedColor, '#') ? "background-color: {$resolvedColor};" : '';
    
    // Icon type - determine mask type based on icon prop
    $maskType = match($icon) {
        's-heart', 'o-heart' => 'mask-heart',
        's-triangle', 'o-triangle' => 'mask-triangle',
        's-square', 'o-square' => 'mask-squircle',
        default => 'mask-star-2'
    };
@endphp

<div class="form-control {{ $inlineLabel ? 'flex-row items-center gap-4' : '' }}">
    @if($label)
        <label for="{{ $componentId }}" class="{{ $inlineLabel ? 'label-text' : 'label' }}">
            <span class="label-text">
                {{ $label }}
                @if($required) <span class="text-error">*</span> @endif
            </span>
        </label>
    @endif

    <div class="flex items-center gap-2">
        <div id="{{ $componentId }}" class="rating gap-1 {{ $sizeClass }}">
            
            <!-- NO RATING -->
            <input type="radio"
                   name="{{ $inputName }}"
                   value="0"
                   class="rating-hidden hidden"
                   {{ $attributes->whereStartsWith('wire:model') }}
                   {{ $disabled ? 'disabled' : '' }}
                   {{ $readonly ? 'readonly' : '' }} />

            <!-- Rating stars -->
            @for ($i = 1; $i <= $total; $i++)
                <input type="radio"
                       name="{{ $inputName }}"
                       value="{{ $i }}"
                       {{ $attributes->whereStartsWith('wire:model') }}
                       {{ $attributes->class(["mask {$maskType} {$colorClass}"]) }}
                       @if($colorStyle) style="{{ $colorStyle }}" @endif
                       {{ $disabled ? 'disabled' : '' }}
                       {{ $readonly ? 'readonly' : '' }}
                       @if($value == $i) checked @endif />
            @endfor
        </div>

        @if($showValue && $value > 0)
            <span class="text-sm text-gray-600 ml-2">{{ $value }}/{{ $total }}</span>
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
