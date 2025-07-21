<progress
    {{ $attributes->class("progress") }}

    @if(!$indeterminate)
        value="{{ $value }}"
        max="{{ $max }}"
    @endif
></progress>
