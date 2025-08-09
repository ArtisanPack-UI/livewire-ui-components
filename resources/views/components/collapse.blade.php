@aware(['noJoin' => null, 'usePlusMinus' => false, 'customOpenIcon' => null, 'customClosedIcon' => null])
<div
    {{
        $attributes->class([
            'collapse border-[length:var(--border)] border-base-content/10',
            'join-item' => !$noJoin,
            'collapse-arrow' => (!$collapsePlusMinus && !$usePlusMinus) && !$noIcon && !($customOpenIcon || $customClosedIcon),
            'collapse-plus' => ($collapsePlusMinus || $usePlusMinus) && !$noIcon && !($customOpenIcon || $customClosedIcon)
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
        @php
            // Determine which icons to use - local component props take precedence over inherited accordion props
            $finalOpenIcon = $customOpenIcon;
            $finalClosedIcon = $customClosedIcon;
            $hasCustomIcons = $finalOpenIcon || $finalClosedIcon;
        @endphp

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
