{{--
    ThemePreview (#65, Livewire only, D-003). Markup contract: design-system
    ThemePreview. A role=radiogroup of .apui-theme-card labels: the frame with a
    decorative miniature that carries data-theme and data-preset, so the real
    tokens draw it, then the native radio and the option's name. `class`,
    aria-label and aria-labelledby land on the root; every other attribute
    (wire:model) lands on every radio, so it binds natively and the platform
    supplies roving focus and arrow keys.
--}}
@php
    $baseId   = $baseId();
    $selected = $selectedValue();
    $name     = $groupName();
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-theme-preview' ) ) }}
    id="{{ $baseId }}"
    data-scope="theme-preview"
    data-part="root"
    role="radiogroup"
    {{ $groupLabelAttributes() }}
>
    @foreach ( $cards() as $index => $card )
        @php
            $isChecked = null !== $selected && $selected === $card['value'];
            $halves    = 'system' === $card['theme'] ? [ 'start' => 'light', 'end' => 'dark' ] : [ null => $card['theme'] ];
        @endphp
        <label
            @class( [ 'apui-theme-card', $classes['card'] ?? null ] )
            data-scope="theme-preview"
            data-part="card"
            data-value="{{ $card['value'] }}"
            data-state="{{ $isChecked ? 'checked' : 'unchecked' }}"
            wire:key="{{ $baseId }}-card-{{ $index }}"
            @if ( $card['disabled'] ) data-disabled @endif
        >
            <span data-scope="theme-preview" data-part="frame"{{ $partClass( 'frame' ) }}>
                @foreach ( $halves as $half => $theme )
                    <span data-scope="theme-preview" data-part="miniature" aria-hidden="true" data-theme="{{ $theme }}"@if ( null !== $card['preset'] ) data-preset="{{ $card['preset'] }}"@endif @if ( '' !== (string) $half ) data-half="{{ $half }}"@endif{{ $partClass( 'miniature' ) }}>
                        <span data-scope="theme-preview" data-part="sidebar"><span data-scope="theme-preview" data-part="line" data-accent></span><span data-scope="theme-preview" data-part="line"></span><span data-scope="theme-preview" data-part="line"></span></span>
                        <span data-scope="theme-preview" data-part="main">
                            <span data-scope="theme-preview" data-part="tile" data-header><span data-scope="theme-preview" data-part="line"></span><span data-scope="theme-preview" data-part="pill"></span></span>
                            <span data-scope="theme-preview" data-part="tiles"><span data-scope="theme-preview" data-part="tile"><span data-scope="theme-preview" data-part="line"></span><span data-scope="theme-preview" data-part="line" data-strong></span></span><span data-scope="theme-preview" data-part="tile"><span data-scope="theme-preview" data-part="line"></span><span data-scope="theme-preview" data-part="line" data-strong></span></span></span>
                        </span>
                    </span>
                @endforeach
            </span>
            <span data-scope="theme-preview" data-part="label"{{ $partClass( 'label' ) }}>
                <span data-scope="theme-preview" data-part="box"{{ $partClass( 'box' ) }}>
                    <input {{ $attributes->except( [ 'class', 'id', 'name', 'value', 'aria-label', 'aria-labelledby' ] ) }}
                        type="radio"
                        data-scope="theme-preview"
                        data-part="control"
                        id="{{ $baseId }}-{{ $index }}"
                        name="{{ $name }}"
                        value="{{ $card['value'] }}"{{ $partClass( 'control' ) }}
                        @if ( $isChecked ) checked @endif
                        @if ( $card['disabled'] ) disabled @endif
                    />
                    <span data-scope="theme-preview" data-part="indicator" aria-hidden="true"{{ $partClass( 'indicator' ) }}></span>
                </span>
                <span data-scope="theme-preview" data-part="text"{{ $partClass( 'text' ) }}>{{ $card['label'] }}</span>
            </span>
        </label>
    @endforeach
</div>
