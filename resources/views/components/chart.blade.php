<div
    wire:key="{{ $uuid }}-{{ rand() }}"
    x-data="{
        settings: @entangle($attributes->wire('model')),
        init(){
            new Chart($refs.chart, this.settings);
        }
    }"

    {{ $attributes->class(["relative"]) }}
>
    <canvas x-ref="chart"></canvas>
</div>