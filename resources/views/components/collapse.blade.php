@aware(['noJoin' => null, 'usePlusMinus' => false, 'customOpenIcon' => null, 'customClosedIcon' => null])

@php
    // Resolve custom icons early so we can use them in CSS class logic
    // The @aware variables come from the accordion parent component
    $inheritedOpenIcon = $customOpenIcon ?? null;
    $inheritedClosedIcon = $customClosedIcon ?? null;
    
    // Get the local component properties directly from attributes or use null
    // Since attributes are passed as kebab-case, we need to check for both formats
    $localOpenIcon = $attributes->get('custom-open-icon') ?? $attributes->get('customOpenIcon') ?? null;
    $localClosedIcon = $attributes->get('custom-closed-icon') ?? $attributes->get('customClosedIcon') ?? null;
    
    // Local component properties take precedence over inherited ones from accordion
    $finalOpenIcon = $localOpenIcon ?? $inheritedOpenIcon;
    $finalClosedIcon = $localClosedIcon ?? $inheritedClosedIcon;
    $hasCustomIcons = $finalOpenIcon || $finalClosedIcon;
@endphp

<div
    {{
        $attributes->class([
            'collapse border-[length:var(--border)] border-base-content/10',
            'join-item' => !$noJoin,
            'collapse-arrow' => (!$collapsePlusMinus && !$usePlusMinus) && !$noIcon && !$hasCustomIcons,
            'collapse-plus' => ($collapsePlusMinus || $usePlusMinus) && !$noIcon && !$hasCustomIcons
        ])
    }}

    wire:key="collapse-{{ $uuid }}"
>
    <!-- Detects if it is inside an accordion.  -->
    @if(isset($noJoin))
        <input id="radio-{{ $uuid }}" type="radio" value="{{ $name }}" x-model="model" />
    @else
        <input id="checkbox-{{ $uuid }}" {{ $attributes->wire('model') }} type="checkbox" x-ref="checkbox" />
    @endif

    <div
        {{ $heading->attributes->merge(["class" => "collapse-title font-semibold"]) }}

        @if(isset($noJoin))
            :class="model == '{{ $name }}' && 'z-10'"
        @click="if (model == '{{ $name }}') model = null"
        @endif
    >
        @if($hasCustomIcons && !$noIcon)
            <div class="flex items-center justify-between w-full">
                <span>{{ $heading }}</span>
                @if(isset($noJoin))
                    <x-artisanpack-icon
                        :name="$finalOpenIcon ?? 'chevron-down'"
                        class="w-4 h-4 transition-transform duration-200"
                        x-show="model == '{{ $name }}'"
                    />
                    <x-artisanpack-icon
                        :name="$finalClosedIcon ?? 'chevron-right'"
                        class="w-4 h-4 transition-transform duration-200"
                        x-show="model != '{{ $name }}'"
                    />
                @else
                    <x-artisanpack-icon
                        :name="$finalOpenIcon ?? 'chevron-down'"
                        class="w-4 h-4 transition-transform duration-200"
                        x-show="$refs.checkbox.checked"
                    />
                    <x-artisanpack-icon
                        :name="$finalClosedIcon ?? 'chevron-right'"
                        class="w-4 h-4 transition-transform duration-200"
                        x-show="!$refs.checkbox.checked"
                    />
                @endif
            </div>
        @else
            {{ $heading }}
        @endif
    </div>
    <div {{ $content->attributes->merge(["class" => "collapse-content text-sm"]) }} wire:key="content-{{ $uuid }}">
        @if($separator)
            <hr class="mb-3 border-t-[length:var(--border)] border-base-content/10" />
        @endif

        {{ $content }}
    </div>
</div>
