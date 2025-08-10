@aware(['noJoin' => null, 'usePlusMinus' => false])

<div
    {{
        $attributes->class([
            'collapse border-[length:var(--border)] border-base-content/10',
            'join-item' => !$noJoin,
            'collapse-arrow' => (!$collapsePlusMinus && !$usePlusMinus) && !$noIcon,
            'collapse-plus' => ($collapsePlusMinus || $usePlusMinus) && !$noIcon
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
        {{ $heading }}
    </div>
    <div {{ $content->attributes->merge(["class" => "collapse-content text-sm"]) }} wire:key="content-{{ $uuid }}">
        @if($separator)
            <hr class="mb-3 border-t-[length:var(--border)] border-base-content/10" />
        @endif

        {{ $content }}
    </div>
</div>
