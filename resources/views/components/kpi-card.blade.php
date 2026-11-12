{{--
    KpiCard (#61). Markup contract: design-system wave 5, KpiCard.
    A Card built from Stat: the top row (label, period, icon tile), the row
    (value and foot beside a Sparkline) and the target (a Progress and its
    text). The label, value and delta parts share Stat's styles. The period
    slot holds a control, such as a Select.
--}}
@php
    $metric     = $metric();
    $points     = $sparklineData();
    $target     = $targetData();
    $attributes = $attributes->except( \ArtisanPack\LivewireUiComponents\View\Components\KpiCard::DEPRECATED_ATTRIBUTES );
    $hasPeriod  = $filled( $period );
@endphp
<div
    {{ $attributes->class( $rootClasses( 'apui-card apui-kpi-card' ) ) }}
    data-color="{{ $metric['color'] }}"
>
    @if ( null !== $metric['label'] || $hasPeriod || null !== $metric['icon'] )
        <div data-scope="kpi-card" data-part="top"{{ $partClass( 'top' ) }}>@if ( null !== $metric['label'] )<span data-scope="kpi-card" data-part="label"{{ $partClass( 'label' ) }}>{{ $metric['label'] }}</span>@endif @if ( $hasPeriod )<div data-scope="kpi-card" data-part="period"{{ $partClass( 'period' ) }}>{{ $content( $period ) }}</div>@endif @if ( null !== $metric['icon'] )<span data-scope="kpi-card" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>{{ $iconSvg( $metric['icon'] ) }}</span>@endif</div>
    @endif
    <div data-scope="kpi-card" data-part="row"{{ $partClass( 'row' ) }}>
        <div data-scope="kpi-card" data-part="summary"{{ $partClass( 'summary' ) }}>
            <span data-scope="kpi-card" data-part="value"{{ $partClass( 'value' ) }}>{{ $metric['value'] }}@if ( null !== $metric['unit'] )<span data-scope="kpi-card" data-part="unit"{{ $partClass( 'unit' ) }}>{{ $metric['unit'] }}</span>@endif</span>
            @if ( null !== $metric['delta'] || null !== $metric['description'] )
                <span data-scope="kpi-card" data-part="foot"{{ $partClass( 'foot' ) }}>@if ( null !== $metric['delta'] )<span data-scope="kpi-card" data-part="delta"{{ $partClass( 'delta' ) }}@if ( null !== $metric['trend'] ) data-trend="{{ $metric['trend'] }}"@endif>@if ( null !== $metric['trendLabel'] )<span class="apui-sr-only">{{ $metric['trendLabel'] }} </span>@endif{{-- --}}@if ( null !== $metric['trendIcon'] ){{ $iconSvg( $metric['trendIcon'] ) }}@endif{{ $metric['delta'] }}</span>@endif @if ( null !== $metric['description'] )<span data-scope="kpi-card" data-part="description"{{ $partClass( 'description' ) }}>{{ $metric['description'] }}</span>@endif</span>
            @endif
        </div>
        @if ( [] !== \ArtisanPack\LivewireUiComponents\Support\SparklineGeometry::values( $points ) )
            <x-artisanpack-sparkline :data="$points" variant="area" :width="96" :height="36" :label="$sparklineLabel" data-scope="kpi-card" data-part="sparkline" :class="$classes['sparkline'] ?? null" />
        @endif
    </div>
    @if ( null !== $target )
        <div data-scope="kpi-card" data-part="target"{{ $partClass( 'target' ) }}>
            <x-artisanpack-progress :value="$target['value']" :max="$target['max']" size="sm" :color="'neutral' === $metric['color'] ? null : $metric['color']" :aria-label="$target['label'] ?? $target['text']" />
            <div data-scope="kpi-card" data-part="targetText"{{ $partClass( 'targetText' ) }}>@if ( null !== $target['label'] )<span>{{ $target['label'] }}</span>@endif<span>{{ $target['text'] }}</span></div>
        </div>
    @endif
</div>
