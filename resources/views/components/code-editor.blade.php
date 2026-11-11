{{--
    CodeEditor (#53). Markup contract: design-system CodeEditor.
    The textarea is the native control, so wire:model binds it without Alpine
    (D-018). apuiCodeEditor indents with Tab, keeps the gutter and the caret
    position in step and runs the file tabs. The body stays left to right in
    RTL (D-004). `class` and x-on listeners land on the root, where tab-change
    and language-change are dispatched; every other attribute lands on the
    textarea.
--}}
@php
    $baseId      = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $strings     = $strings();
    $tabList     = $tabList();
    $languages   = $languageList();
    $selected    = $selectedTab();
    $diagnostics = $diagnosticList();
    $severities  = $severityByLine();
    $problems    = $problemsLabel();
    $languageText = $languageName();
    $selectedIndex = array_search( $selected, array_column( $tabList, 'value' ), true );
    $hasHeader   = [] !== $tabList || [] !== $languages;
    $lineTotal   = $lineTotal();
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $baseId . '-hint' : null,
        null !== $message ? $baseId . '-error' : null,
        $baseId . '-instructions',
    ] ) ) );
    $severityName = [ 'error' => $strings['severityError'], 'warning' => $strings['severityWarning'], 'info' => $strings['severityInfo'] ];
    $severityIcon = [ 'error' => 'circle-alert', 'warning' => 'triangle-alert', 'info' => 'info' ];
@endphp
<div
    {{ $attributes->only( 'class' )->merge( $attributes->whereStartsWith( [ 'x-on:', '@' ] )->getAttributes() )->class( $rootClasses( 'apui-code-editor' ) ) }}
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    @if ( $readonly ) data-readonly @endif
    x-data="{{ $alpineData() }}"
    x-init="$watch('tab', (value) => $dispatch('tab-change', { value }))"
>
    @if ( $hasLabel )
        <label data-scope="code-editor" data-part="label" for="{{ $baseId }}"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}@if ( $required )<span data-scope="code-editor" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</label>
    @endif
    <div data-scope="code-editor" data-part="box"{{ $partClass( 'box' ) }}>
        @if ( $hasHeader )
            <div data-scope="code-editor" data-part="header"{{ $partClass( 'header' ) }}>
                @if ( [] !== $tabList )
                    <div data-scope="code-editor" data-part="tabs" id="{{ $baseId }}-tabs" role="tablist" aria-label="{{ $strings['tabs'] }}"{{ $partClass( 'tabs' ) }} x-ref="tabs" x-on:keydown="tabsKeydown($event)">
                        @foreach ( $tabList as $index => $item )
                            @php $isSelected = $item['value'] === $selected; @endphp
                            <button
                                type="button"
                                data-scope="code-editor"
                                data-part="tab"
                                data-value="{{ $item['value'] }}"
                                id="{{ $baseId }}-tab-{{ $index }}"{{ $partClass( 'tab' ) }}
                                role="tab"
                                aria-controls="{{ $baseId }}-body"
                                aria-selected="{{ $isSelected ? 'true' : 'false' }}"
                                tabindex="{{ $isSelected ? '0' : '-1' }}"
                                x-bind:aria-selected="String(tab === {{ \Illuminate\Support\Js::from( $item['value'] ) }})"
                                x-on:click="selectTab({{ \Illuminate\Support\Js::from( $item['value'] ) }})"
                            >{{ $item['label'] }}</button>
                        @endforeach
                    </div>
                @endif
                @if ( [] !== $languages )
                    <select
                        data-scope="code-editor"
                        data-part="languageSelect"
                        id="{{ $baseId }}-language"{{ $partClass( 'languageSelect' ) }}
                        aria-label="{{ $strings['language'] }}"
                        @if ( $disabled ) disabled @endif
                        x-on:change="$dispatch('language-change', { value: $event.target.value })"
                    >
                        @foreach ( $languages as $option )
                            <option value="{{ $option['value'] }}" @selected( $option['value'] === $language )>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        @endif
        <div
            data-scope="code-editor"
            data-part="body"
            id="{{ $baseId }}-body"{{ $partClass( 'body' ) }}
            dir="ltr"
            @if ( [] !== $tabList )
                role="tabpanel"
                @if ( false !== $selectedIndex ) aria-labelledby="{{ $baseId }}-tab-{{ $selectedIndex }}" @endif
            @endif
        >
            <div data-scope="code-editor" data-part="gutter" aria-hidden="true"{{ $partClass( 'gutter' ) }} x-ref="gutter">@for ( $line = 1; $line <= $lineTotal; $line++ )<span data-scope="code-editor" data-part="line"{{ $partClass( 'line' ) }}@if ( isset( $severities[ $line ] ) ) data-severity="{{ $severities[ $line ] }}"@endif>{{ $line }}</span>@endfor</div>
            <textarea {{ $attributes->whereDoesntStartWith( [ 'x-on:', '@' ] )->except( [ 'class', 'id', 'aria-describedby' ] ) }}
                data-scope="code-editor"
                data-part="control"
                id="{{ $baseId }}"{{ $partClass( 'control' ) }}
                rows="{{ $lineTotal }}"
                wrap="off"
                spellcheck="false"
                autocapitalize="off"
                autocomplete="off"
                @if ( null !== $placeholder ) placeholder="{{ $placeholder }}" @endif
                @if ( $disabled ) disabled @endif
                @if ( $readonly ) readonly @endif
                @if ( $required ) required @endif
                @if ( $isInvalid ) aria-invalid="true" @endif
                aria-describedby="{{ $describedBy }}"
                x-ref="control"
                x-on:keydown="keydown($event)"
                x-on:input="sync()"
                x-on:keyup="sync()"
                x-on:click="sync()"
                x-on:focus="sync()"
            >{{ $value }}</textarea>
        </div>
        @if ( [] !== $diagnostics )
            <ul data-scope="code-editor" data-part="diagnostics" aria-label="{{ $strings['problems'] }}"{{ $partClass( 'diagnostics' ) }}>
                @foreach ( $diagnostics as $diagnostic )
                    <li data-scope="code-editor" data-part="diagnostic" data-severity="{{ $diagnostic['severity'] }}"{{ $partClass( 'diagnostic' ) }}>{{ $iconSvg( $severityIcon[ $diagnostic['severity'] ] ) }}<span class="apui-sr-only">{{ $severityName[ $diagnostic['severity'] ] }}:</span><span>{{ str_replace( ':line', (string) $diagnostic['line'], $strings['line'] ) }}</span><span>{{ $diagnostic['message'] }}</span></li>
                @endforeach
            </ul>
        @endif
        <div data-scope="code-editor" data-part="status"{{ $partClass( 'status' ) }}>
            @if ( '' !== $problems )<span>{{ $problems }}</span>@endif
            <span x-text="position">{{ str_replace( [ ':line', ':column' ], [ '1', '1' ], $strings['position'] ) }}</span>
            <span>{{ str_replace( ':count', (string) max( 1, $tabSize ), $strings['spaces'] ) }}</span>
            @if ( null !== $languageText )<span>{{ $languageText }}</span>@endif
        </div>
    </div>
    <span data-scope="code-editor" data-part="instructions" id="{{ $baseId }}-instructions" class="apui-sr-only">{{ $strings['instructions'] }}</span>
    @if ( $hasHint )
        <p data-scope="code-editor" data-part="hint" id="{{ $baseId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
    @endif
    @if ( null !== $message )
        <p data-scope="code-editor" data-part="error" id="{{ $baseId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</div>
