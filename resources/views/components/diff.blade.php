{{--
    Diff (#61). Markup contract: design-system wave 5, Diff.
    The header (icon, file, counts with hidden words, actions) and the table
    of hunks and lines from DiffBuilder, the rows React and Vue render. Every
    cell carries its side's data-type; changed words are ins and del. The
    header mirrors in RTL; the table stays LTR (D-004). 2.x old and new still
    work, as oldContent and newContent.
--}}
@php
    $result     = $result();
    $view       = $viewValue();
    $columns    = 'split' === $view ? 6 : 4;
    $attributes = $attributes->except( \ArtisanPack\LivewireUiComponents\View\Components\Diff::DEPRECATED_ATTRIBUTES );
@endphp
<div
    {{ $attributes->class( $rootClasses( 'apui-diff' ) ) }}
    data-view="{{ $view }}"
>
    <div data-scope="diff" data-part="header"{{ $partClass( 'header' ) }}>
        <span data-scope="diff" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>{{ $iconSvg( 'git-compare' ) }}</span>
        @if ( $filled( $fileName ) )
            <span data-scope="diff" data-part="file"{{ $partClass( 'file' ) }}>{{ $fileName }}</span>
        @endif
        <span data-scope="diff" data-part="counts"{{ $partClass( 'counts' ) }}><span class="apui-sr-only">{{ $countsText( $result['additions'], $result['deletions'] ) }}</span><span data-type="add" aria-hidden="true">+{{ $result['additions'] }}</span><span data-type="del" aria-hidden="true">&minus;{{ $result['deletions'] }}</span></span>
        @if ( $filled( $actions ) )
            <div data-scope="diff" data-part="actions"{{ $partClass( 'actions' ) }}>{{ $content( $actions ) }}</div>
        @endif
    </div>
    <table data-scope="diff" data-part="table"{{ $partClass( 'table' ) }}>
        <tbody>
            @if ( [] === $result['hunks'] )
                <tr data-scope="diff" data-part="hunk"{{ $partClass( 'hunk' ) }}><td colspan="{{ $columns }}">{{ $emptyLabel }}</td></tr>
            @endif
            @foreach ( $result['hunks'] as $hunk )
                <tr data-scope="diff" data-part="hunk"{{ $partClass( 'hunk' ) }}><td colspan="{{ $columns }}">{{ $hunk['header'] }}</td></tr>
                @if ( 'split' === $view )
                    @foreach ( $splitRows( $hunk['lines'] ) as $row )
                        <tr data-scope="diff" data-part="line"{{ $partClass( 'line' ) }}>
                            @foreach ( [ $row['old'], $row['new'] ] as $side )
                                <td data-scope="diff" data-part="lineNumber" data-type="{{ $side['type'] }}"{{ $partClass( 'lineNumber' ) }}>{{ $side['number'] }}</td><td data-scope="diff" data-part="sign" data-type="{{ $side['type'] }}"{{ $partClass( 'sign' ) }}>{{ $sign( $side['type'] ) }}</td><td data-scope="diff" data-part="code" data-type="{{ $side['type'] }}"{{ $partClass( 'code' ) }}>{{ $codeHtml( $side['segments'], $side['type'] ) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    @foreach ( $hunk['lines'] as $line )
                        <tr data-scope="diff" data-part="line" data-type="{{ $line['type'] }}"{{ $partClass( 'line' ) }}><td data-scope="diff" data-part="lineNumber" data-type="{{ $line['type'] }}"{{ $partClass( 'lineNumber' ) }}>{{ $line['oldNumber'] }}</td><td data-scope="diff" data-part="lineNumber" data-type="{{ $line['type'] }}"{{ $partClass( 'lineNumber' ) }}>{{ $line['newNumber'] }}</td><td data-scope="diff" data-part="sign" data-type="{{ $line['type'] }}"{{ $partClass( 'sign' ) }}>{{ $sign( $line['type'] ) }}</td><td data-scope="diff" data-part="code" data-type="{{ $line['type'] }}"{{ $partClass( 'code' ) }}>{{ $codeHtml( $line['segments'], $line['type'] ) }}</td></tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
</div>
