<progress
    {{ $attributes->class("progress") }}
    role="progressbar"
    aria-label="{{ $attributes->get('aria-label') ?? 'Progress' }}"
    @if(!$indeterminate)
        value="{{ $value }}"
        max="{{ $max }}"
        aria-valuemin="0"
        aria-valuemax="{{ $max }}"
        aria-valuenow="{{ $value }}"
    @else
        aria-valuemin="0"
        aria-valuemax="100"
    @endif
></progress>
