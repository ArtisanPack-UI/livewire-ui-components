<div
    {{
        $attributes
            ->class("radial-progress")
            ->style("--value: $value")
    }}

    role="progressbar"
    aria-valuemin="0"
    aria-valuemax="100"
    aria-valuenow="{{ $value }}"
    aria-label="{{ $attributes->get('aria-label') ?? 'Progress' }}"
>
    {{ $value }}{{ $unit }}
</div>
