<div
    {{
        $attributes
            ->class("radial-progress")
            ->style("--value: $value")
    }}

    role="progressbar"
>
    {{ $value }}{{ $unit }}
</div>
