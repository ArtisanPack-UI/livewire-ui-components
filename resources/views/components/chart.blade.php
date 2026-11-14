{{--
    Chart (#63). Markup contract: design-system Chart.
    A Card holding the header, the plot and the tooltip, and the data table
    when tabular is set. The SVG comes from ChartGeometry, the geometry React
    and Vue draw; apuiChart opens the tooltip on hover and keyboard focus of
    each mark. The plot stays LTR (D-004); the header and legend mirror.
--}}
@php
    $type       = $typeValue();
    $geometry   = $geometry();
    $title      = $titleText();
    $donut      = 'donut' === $type;
    $attributes = $attributes->except( \ArtisanPack\LivewireUiComponents\View\Components\Chart::DEPRECATED_ATTRIBUTES );
@endphp
<div {{ $attributes->class( $rootClasses( 'apui-card apui-chart' ) ) }} data-type="{{ $type }}" x-data="apuiChart">
    <div data-scope="chart" data-part="header"{{ $partClass( 'header' ) }}>
        <div>
            @if ( '' !== $title )<h3 data-scope="chart" data-part="title"{{ $partClass( 'title' ) }}>{{ $title }}</h3>@endif
            @if ( $filled( $description ) )<p data-scope="chart" data-part="description"{{ $partClass( 'description' ) }}>{{ $description }}</p>@endif
        </div>
        @if ( ! $donut && [] !== $geometry['legend'] )
            <ul data-scope="chart" data-part="legend"{{ $partClass( 'legend' ) }}>@foreach ( $geometry['legend'] as $item )<li><span data-scope="chart" data-part="swatch" data-series="{{ $item['series'] }}" aria-hidden="true"{{ $partClass( 'swatch' ) }}></span>{{ $item['name'] }}</li>@endforeach</ul>
        @endif
        @if ( $filled( $actions ) )
            <div data-scope="chart" data-part="actions"{{ $partClass( 'actions' ) }}>{{ $content( $actions ) }}</div>
        @endif
    </div>
    <div data-scope="chart" data-part="body"{{ $partClass( 'body' ) }}>
        <svg data-scope="chart" data-part="plot"{{ $partClass( 'plot' ) }} viewBox="0 0 {{ $geometry['width'] }} {{ $geometry['height'] }}" width="{{ $geometry['width'] }}" height="{{ $geometry['height'] }}" role="group" @if ( '' !== $title ) aria-label="{{ $title }}" @endif>
            @if ( ! $donut )
                <g aria-hidden="true">@foreach ( $geometry['ticks'] as $tick )<line data-scope="chart" data-part="grid"{{ $partClass( 'grid' ) }} x1="{{ $geometry['left'] }}" x2="{{ $geometry['right'] }}" y1="{{ $tick['y'] }}" y2="{{ $tick['y'] }}" /><text data-scope="chart" data-part="axis"{{ $partClass( 'axis' ) }} x="{{ $geometry['tickX'] }}" y="{{ $tick['textY'] }}" text-anchor="end">{{ $tick['label'] }}</text>@endforeach
                @foreach ( $geometry['categories'] as $category )<text data-scope="chart" data-part="axis"{{ $partClass( 'axis' ) }} x="{{ $category['x'] }}" y="{{ $geometry['labelY'] }}" text-anchor="middle">{{ $category['label'] }}</text>@endforeach</g>
                @if ( [] !== $geometry['lines'] )
                    <g aria-hidden="true">@foreach ( $geometry['areas'] as $area )<path data-scope="chart" data-part="area" data-series="{{ $area['series'] }}"{{ $partClass( 'area' ) }} d="{{ $area['d'] }}" />@endforeach
                    @foreach ( $geometry['lines'] as $line )<path data-scope="chart" data-part="line" data-series="{{ $line['series'] }}"{{ $partClass( 'line' ) }} d="{{ $line['d'] }}" />@endforeach</g>
                    <g aria-hidden="true">@foreach ( $geometry['points'] as $point )<g data-scope="chart" data-part="point" data-index="{{ $point['index'] }}" visibility="hidden"{{ $partClass( 'point' ) }}><line data-scope="chart" data-part="crosshair"{{ $partClass( 'crosshair' ) }} x1="{{ $point['x'] }}" x2="{{ $point['x'] }}" y1="{{ $point['top'] }}" y2="{{ $point['bottom'] }}" />@foreach ( $point['dots'] as $dot )<circle data-scope="chart" data-part="dot" data-series="{{ $dot['series'] }}"{{ $partClass( 'dot' ) }} cx="{{ $dot['cx'] }}" cy="{{ $dot['cy'] }}" r="5" />@endforeach</g>@endforeach</g>
                @endif
                @if ( null !== $geometry['value'] )<text data-scope="chart" data-part="value"{{ $partClass( 'value' ) }} x="{{ $geometry['value']['x'] }}" y="{{ $geometry['value']['y'] }}" text-anchor="middle" aria-hidden="true">{{ $geometry['value']['text'] }}</text>@endif
            @endif
            @foreach ( $geometry['marks'] as $mark )
                @if ( 'rect' === $mark['shape'] )
                    <rect data-scope="chart" data-part="mark" data-series="{{ $mark['series'] }}" data-index="{{ $mark['index'] }}"{{ $partClass( 'mark' ) }} x="{{ $mark['x'] }}" y="{{ $mark['y'] }}" width="{{ $mark['width'] }}" height="{{ $mark['height'] }}" tabindex="0" role="img" aria-label="{{ $mark['label'] }}" data-tooltip-title="{{ $mark['title'] }}" data-tooltip-body="{{ $mark['body'] }}" data-anchor-x="{{ $mark['anchorX'] }}" data-anchor-y="{{ $mark['anchorY'] }}" />
                @else
                    <path data-scope="chart" data-part="mark" data-series="{{ $mark['series'] }}" data-index="{{ $mark['index'] }}"{{ $partClass( 'mark' ) }} d="{{ $mark['d'] }}" tabindex="0" role="img" aria-label="{{ $mark['label'] }}" data-tooltip-title="{{ $mark['title'] }}" data-tooltip-body="{{ $mark['body'] }}" data-anchor-x="{{ $mark['anchorX'] }}" data-anchor-y="{{ $mark['anchorY'] }}" />
                @endif
            @endforeach
        </svg>
        @if ( $donut )
            <ul data-scope="chart" data-part="legend"{{ $partClass( 'legend' ) }}>@foreach ( $geometry['legend'] as $item )<li><span data-scope="chart" data-part="swatch" data-series="{{ $item['series'] }}" aria-hidden="true"{{ $partClass( 'swatch' ) }}></span>{{ $item['name'] }}<span data-scope="chart" data-part="value"{{ $partClass( 'value' ) }}>{{ $item['value'] }}</span></li>@endforeach</ul>
        @endif
        <div data-scope="chart" data-part="tooltip"{{ $partClass( 'tooltip' ) }} aria-hidden="true" hidden></div>
    </div>
    @if ( $showsTable() )
        <table data-scope="chart" data-part="table"{{ $partClass( 'table' ) }} @if ( '' !== $title ) aria-label="{{ $title }}" @endif>
            <thead><tr>@foreach ( $geometry['table']['columns'] as $index => $column )@if ( 0 === $index )<td></td>@else<th scope="col">{{ $column }}</th>@endif @endforeach</tr></thead>
            <tbody>@foreach ( $geometry['table']['rows'] as $row )<tr><th scope="row">{{ $row['label'] }}</th>@foreach ( $row['cells'] as $cell )<td>{{ $cell }}</td>@endforeach</tr>@endforeach</tbody>
        </table>
    @endif
</div>
