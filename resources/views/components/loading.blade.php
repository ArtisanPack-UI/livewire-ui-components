@php
use Illuminate\Support\Str;
@endphp

@if($shouldUseSvg())
    @if($customSvg)
        {!! $customSvg !!}
    @elseif($getLoadingIcon())
        <x-svg 
            :name="$getLoadingIcon()" 
            {{ $attributes->class(['inline', 'w-5 h-5' => !Str::contains($attributes->get('class') ?? '', ['w-', 'h-'])]) }}
            @if($animated) style="animation: spin 1s linear infinite;" @endif
        />
    @else
        <span {{ $attributes->class("loading loading-spinner") }}></span>
    @endif
@else
    <span {{ $attributes->class("loading") }}></span>
@endif
