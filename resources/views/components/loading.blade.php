@php
use Illuminate\Support\Str;
@endphp

<div role="status" aria-live="polite" aria-label="{{ $attributes->get('aria-label') ?? 'Loading' }}">
    @if($shouldUseSvg())
        @if($customSvg)
            {!! $customSvg !!}
        @elseif($getLoadingIcon())
            <x-svg
                :name="$getLoadingIcon()"
                {{ $attributes->except('aria-label')->class(['inline', 'w-5 h-5' => !Str::contains($attributes->get('class') ?? '', ['w-', 'h-'])]) }}
                @if($animated) style="animation: spin 1s linear infinite;" @endif
                aria-hidden="true"
            />
        @else
            <span {{ $attributes->except('aria-label')->class("loading loading-spinner") }} aria-hidden="true"></span>
        @endif
    @else
        <span {{ $attributes->except('aria-label')->class("loading") }} aria-hidden="true"></span>
    @endif
    <span class="sr-only">{{ $attributes->get('aria-label') ?? 'Loading' }}</span>
</div>
