{{--
    ColorPicker (#53). Markup contract: design-system ColorPicker, on the behavior
    engine's color-picker machine (apuiColorPicker). The field's parts are
    server-rendered with stable ids (D-015); the popover's gradients and thumbs are
    the machine's, inside the wire:ignore positioner. `class` lands on the root,
    listeners (x-on:*, wire:*) on the root where the widget dispatches, every other
    attribute on the text input. wire:model binds the text value through entangle.
--}}
@php
    $controlId   = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $state       = $open ? 'open' : 'closed';
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $format      = $resolvedFormat();
    $labels      = $labels();
    $presets     = $presetSwatches();
    $fill        = $fillColor();
    $view        = $machineView();
    $current     = $currentValue();
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $controlId . '-hint' : null,
        null !== $message ? $controlId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-color-picker' ) ) }}
    {{ $listenerAttributes() }}
    data-scope="color-picker"
    data-part="root"
    id="{{ $partId( 'root' ) }}"
    data-size="{{ $size }}"
    data-format="{{ $format }}"
    data-state="{{ $state }}"
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    x-data="{{ $alpineData( $isInvalid ) }}"
    x-apui:root
>
    @if ( $hasLabel )
        <label data-scope="color-picker" data-part="label" id="{{ $partId( 'label' ) }}" for="{{ $controlId }}"{{ $partClass( 'label' ) }} x-apui:label>{{ $content( $label ) }}@if ( $required )<span data-scope="color-picker" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</label>
    @endif
    <div data-scope="color-picker" data-part="box" id="{{ $partId( 'box' ) }}"{{ $partClass( 'box' ) }} x-apui:box>
        <button
            type="button"
            data-scope="color-picker"
            data-part="trigger"
            id="{{ $partId( 'trigger' ) }}"
            aria-label="{{ $labels['trigger'] }}"
            aria-haspopup="dialog"
            aria-expanded="{{ $open ? 'true' : 'false' }}"
            aria-controls="{{ $partId( 'content' ) }}"
            @if ( $disabled ) disabled @endif
            {{ $partClass( 'trigger' ) }}
            x-apui:trigger
        ><span data-scope="color-picker" data-part="swatch"{{ $partClass( 'swatch' ) }} style="background: {{ $view['css'] }}" x-apui:swatch="{ current: true }"></span></button>
        <input {{ $controlAttributes() }}
            type="text"
            data-scope="color-picker"
            data-part="control"
            id="{{ $controlId }}"
            value="{{ $current }}"
            autocomplete="off"
            spellcheck="false"
            dir="ltr"
            data-format="{{ $format }}"{{ $partClass( 'control' ) }}
            @if ( $disabled ) disabled @endif
            @if ( $required ) required @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
            x-apui:control
        />
    </div>
    @if ( $hasHint )
        <p data-scope="color-picker" data-part="hint" id="{{ $controlId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="color-picker" data-part="error" id="{{ $controlId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
    @if ( $portal )<template x-teleport="body">@endif
    <div data-scope="color-picker" data-part="positioner" id="{{ $partId( 'positioner' ) }}"{{ $partClass( 'positioner' ) }} wire:ignore x-apui:positioner>
        <div
            data-scope="color-picker"
            data-part="content"
            id="{{ $partId( 'content' ) }}"
            role="dialog"
            tabindex="-1"
            aria-label="{{ $labels['trigger'] }}"
            data-state="{{ $state }}"
            @if ( ! $open ) hidden @endif
            {{ $partClass( 'content' ) }}
            x-apui:content
        >
            <div data-scope="color-picker" data-part="area" id="{{ $partId( 'area' ) }}"{{ $partClass( 'area' ) }} x-apui:area>
                <div data-scope="color-picker" data-part="area-background" style="--apui-_hue-color: {{ $view['pure'] }}"{{ $partClass( 'areaBackground' ) }} x-apui:area-background></div>
                <div data-scope="color-picker" data-part="area-thumb" id="{{ $partId( 'area-thumb' ) }}" role="slider" tabindex="0" aria-label="{{ $labels['area'] }}" aria-roledescription="2d slider" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $view['saturation'] }}" aria-valuetext="{{ round( $view['saturation'] ) }}%, {{ round( $view['brightness'] ) }}%" style="--apui-_x: {{ $view['saturation'] }}%; --apui-_y: {{ 100 - $view['brightness'] }}%; background: {{ $view['opaque'] }}"{{ $partClass( 'areaThumb' ) }} x-apui:area-thumb></div>
            </div>
            <div data-scope="color-picker" data-part="channel-slider" data-channel="hue"{{ $partClass( 'channelSlider' ) }} x-apui:channel-slider="{ channel: 'hue' }">
                <div data-scope="color-picker" data-part="channel-slider-track" id="{{ $partId( 'hue-track' ) }}" data-channel="hue"{{ $partClass( 'channelSliderTrack' ) }} x-apui:channel-slider-track="{ channel: 'hue' }">
                    <div data-scope="color-picker" data-part="channel-slider-thumb" id="{{ $partId( 'hue-thumb' ) }}" role="slider" tabindex="0" aria-label="{{ $labels['hue'] }}" aria-orientation="horizontal" aria-valuemin="0" aria-valuemax="360" aria-valuenow="{{ $view['hue'] }}" aria-valuetext="{{ round( $view['hue'] ) }}°" data-channel="hue" style="--apui-_x: {{ round( $view['hue'] / 360 * 100, 4 ) }}%; background: {{ $view['pure'] }}"{{ $partClass( 'channelSliderThumb' ) }} x-apui:channel-slider-thumb="{ channel: 'hue' }"></div>
                </div>
            </div>
            @if ( $alpha )
                <div data-scope="color-picker" data-part="channel-slider" data-channel="alpha"{{ $partClass( 'channelSlider' ) }} x-apui:channel-slider="{ channel: 'alpha' }">
                    <div data-scope="color-picker" data-part="transparency-grid"{{ $partClass( 'transparencyGrid' ) }} x-apui:transparency-grid></div>
                    <div data-scope="color-picker" data-part="channel-slider-track" id="{{ $partId( 'alpha-track' ) }}" data-channel="alpha" style="--apui-_from: {{ $view['transparent'] }}; --apui-_to: {{ $view['opaque'] }}"{{ $partClass( 'channelSliderTrack' ) }} x-apui:channel-slider-track="{ channel: 'alpha' }">
                        <div data-scope="color-picker" data-part="channel-slider-thumb" id="{{ $partId( 'alpha-thumb' ) }}" role="slider" tabindex="0" aria-label="{{ $labels['alpha'] }}" aria-orientation="horizontal" aria-valuemin="0" aria-valuemax="1" aria-valuenow="{{ $view['alpha'] }}" aria-valuetext="{{ round( $view['alpha'] * 100 ) }}%" data-channel="alpha" style="--apui-_x: {{ $view['alpha'] * 100 }}%; background: {{ $view['css'] }}"{{ $partClass( 'channelSliderThumb' ) }} x-apui:channel-slider-thumb="{ channel: 'alpha' }"></div>
                    </div>
                </div>
            @endif
            <div data-scope="color-picker" data-part="row"{{ $partClass( 'row' ) }} x-apui:row>
                <select data-scope="color-picker" data-part="format-select" id="{{ $partId( 'format' ) }}" aria-label="{{ $labels['format'] }}"{{ $partClass( 'formatSelect' ) }} @if ( $disabled ) disabled @endif x-apui:format-select>
                    @foreach ( \ArtisanPack\LivewireUiComponents\View\Components\ColorPicker::FORMATS as $formatValue => $formatName )
                        <option value="{{ $formatValue }}"@if ( $formatValue === $format ) selected @endif>{{ $formatName }}</option>
                    @endforeach
                </select>
                <button type="button" data-scope="color-picker" data-part="eye-dropper-trigger" id="{{ $partId( 'eye-dropper' ) }}" aria-label="{{ $labels['eyeDropper'] }}" hidden{{ $partClass( 'eyeDropperTrigger' ) }} @if ( $disabled ) disabled @endif x-apui:eye-dropper-trigger>{{ $iconSvg( 'pipette' ) }}</button>
            </div>
            @if ( [] !== $presets )
                <div data-scope="color-picker" data-part="swatch-group" role="group" aria-label="{{ $labels['swatches'] }}"{{ $partClass( 'swatchGroup' ) }} x-apui:swatch-group>
                    @foreach ( $presets as $preset )
                        <button type="button" data-scope="color-picker" data-part="swatch-trigger" id="{{ $partId( 'swatch-' . $loop->index ) }}" aria-label="{{ $preset }}" aria-pressed="{{ strtolower( $preset ) === strtolower( (string) $current ) ? 'true' : 'false' }}"{{ $partClass( 'swatchTrigger' ) }} @if ( $disabled ) disabled @endif x-apui:swatch-trigger="{ value: @js( $preset ) }"><span data-scope="color-picker" data-part="swatch" style="background: {{ $preset }}"{{ $partClass( 'swatch' ) }} x-apui:swatch="{ value: @js( $preset ) }"></span></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @if ( $portal )</template>@endif
</div>
