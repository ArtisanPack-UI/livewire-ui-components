{{--
    Swap (#65). Markup contract: design-system Swap, a token-driven toggle
    with no spec. label.apui-swap holds the native checkbox (control) over the
    on and off contents; its CSS swaps them on the native :checked, so
    wire:model binds and swaps with no script. `class` lands on the root;
    every other attribute lands on the checkbox. On change, Alpine writes
    data-state back from the input. The 2.x true and false slots still work.
--}}
@php
    $onSlot  = $on ?? $true ?? null;
    $offSlot = $off ?? $false ?? null;
@endphp
<label
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-swap' ) ) }}
    data-scope="swap"
    data-part="root"
    data-size="{{ $size }}"
    data-transition="{{ $transition }}"
    data-state="{{ $checked ? 'checked' : 'unchecked' }}"
    @if ( $disabled ) data-disabled @endif
>
    <input {{ $attributes->except( [ 'class', 'icon-size', 'iconSize' ] ) }}
        type="checkbox"
        data-scope="swap"
        data-part="control"{{ $partClass( 'control' ) }}
        @if ( $filled( $label ) ) aria-label="{{ $label }}" @endif
        x-data
        x-on:change="$el.closest('.apui-swap').dataset.state = $el.checked ? 'checked' : 'unchecked'"
        @if ( $checked ) checked @endif
        @if ( $disabled ) disabled @endif
    />
    <span data-scope="swap" data-part="on" aria-hidden="true"{{ $partClass( 'on' ) }}>@if ( $filled( $onSlot ) ){{ $content( $onSlot ) }}@else{{ $iconSvg( $onIcon ) }}@endif</span>
    <span data-scope="swap" data-part="off" aria-hidden="true"{{ $partClass( 'off' ) }}>@if ( $filled( $offSlot ) ){{ $content( $offSlot ) }}@else{{ $iconSvg( $offIcon ) }}@endif</span>
</label>
