@if(strlen($label ?? '') > 0)
    <div class="inline-flex items-center gap-1">
@endif
    <x-svg
        :name="$icon()"
        {{ $attributes->class(['inline', 'w-5 h-5' => !Str::contains($attributes->get('class') ?? '', ['w-', 'h-']) ]) }}
        @if(strlen($label ?? '') > 0)
            aria-hidden="true"
        @elseif(!$attributes->has('aria-label') && !$attributes->has('aria-hidden'))
            aria-hidden="true"
        @endif
        @if(!strlen($label ?? '') > 0 && $attributes->has('aria-label'))
            role="img"
        @endif
    />

@if(strlen($label ?? '') > 0)
        <div class="{{ $labelClasses() }}" {{ $attributes->whereStartsWith('@') }}>
            {{ $label }}
        </div>
    </div>
@endif
