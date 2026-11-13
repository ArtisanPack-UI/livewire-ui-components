{{--
    Markdown (#65). Markup contract: design-system Utility wave, Markdown.
    Preview mode: the .apui-markdown root carries .apui-prose and holds the
    HTML from MarkdownRenderer (league/commonmark), the HTML React and Vue
    write. Write and split modes: the design-system Tabs (Write, Preview,
    Split) over the Textarea. The panels render on the server, so the Write
    and Split panels each hold a Textarea bound to the same wire:model, or,
    without one, to apuiMarkdown's `source` (x-model), so an edit in one
    panel shows in the other. One Textarea is enabled at a time (Write's on
    Write and Preview, Split's on Split), so a form posts the source once
    whichever tab is selected; an invalid one opens Write. Without
    wire:model the preview panels show apuiMarkdown's `html`, the draft
    rendered in the browser. Native control attributes (name, required,
    disabled, placeholder, maxlength, rows…) go to the Textareas. Typing
    dispatches source-change.
--}}
@php
    $html    = $html();
    $mode    = $modeValue();
    $baseId  = $baseId();
    $text    = $sourceText();
    $control = $controlAttributes();
    $props   = $controlProps();
    $local   = $bindsLocally();
    $locks   = [ 'write' => $disabledBinding( 'write' ), 'split' => $disabledBinding( 'split' ) ];
@endphp
@if ( ! $editing() )
    <div {{ $rootAttributes()->class( $rootClasses( 'apui-markdown apui-prose' ) ) }} data-mode="preview">{{ $html }}</div>
@else
    <div
        {{ $rootAttributes()->except( [ 'id' ] )->class( $rootClasses( 'apui-markdown' ) ) }}
        id="{{ $baseId }}"
        data-mode="{{ $mode }}"
        x-data="{{ $alpineData() }}"
        x-on:value-change="{{ $tabChangeHandler() }}"
    >
        <x-artisanpack-tabs size="sm" :label="$tabsLabel" :value="$mode" :id="$baseId . '-tabs'">
            @foreach ( [ 'write' => $writeLabel, 'preview' => $previewLabel, 'split' => $splitLabel ] as $tab => $tabLabel )
                <x-artisanpack-tab :value="$tab" :label="$tabLabel">
                    @if ( 'preview' !== $tab )
                        @php $inSplit = 'split' === $tab; @endphp
                        @if ( $inSplit )<div data-scope="markdown" data-part="split"{{ $partClass( 'split' ) }}>@endif
                        <div data-scope="markdown" data-part="editor"{{ $partClass( 'editor' ) }}>
                            <x-artisanpack-textarea
                                :attributes="$control"
                                :id="$baseId . '-' . $tab . '-source'"
                                :aria-label="$editorLabel"
                                dir="auto"
                                :rows="$props['rows']"
                                :disabled="$props['disabled']"
                                :readonly="$props['readonly']"
                                :required="$props['required']"
                                :placeholder="$props['placeholder']"
                                :value="$text"
                                x-bind:disabled="{{ $locks[ $tab ] }}"
                                :x-model="$local ? 'source' : null"
                                :x-on:invalid="'write' === $tab ? 'select(\'write\')' : null"
                                x-on:input="$dispatch('source-change', { value: $event.target.value })"
                            />
                        </div>
                    @endif
                    @if ( 'write' !== $tab )
                        <div
                            data-scope="markdown"
                            data-part="preview"
                            class="apui-prose{{ $filled( $classes['preview'] ?? null ) ? ' ' . $classes['preview'] : '' }}"
                            role="region"
                            aria-label="{{ $outputLabel }}"
                            @if ( $local ) x-html="html" @endif
                        >{{ $html }}</div>
                        @if ( 'split' === $tab )</div>@endif
                    @endif
                </x-artisanpack-tab>
            @endforeach
        </x-artisanpack-tabs>
    </div>
@endif
