{{--
    Markdown (#65). Markup contract: design-system Utility wave, Markdown.
    Preview mode: the .apui-markdown root carries .apui-prose and holds the
    HTML from MarkdownRenderer (league/commonmark), the HTML React and Vue
    write. Write and split modes: the design-system Tabs (Write, Preview,
    Split) over the Textarea. The panels render on the server, so the Write
    and Split panels each hold a Textarea bound to the same wire:model, or,
    without one, to the root's Alpine `source` (x-model), so an edit in one
    panel shows in the other; the hidden one is disabled, so a form posts
    the source once. Typing dispatches source-change.
--}}
@php
    $html    = $html();
    $mode    = $modeValue();
    $baseId  = $baseId();
    $text    = $sourceText();
    $control = $controlAttributes();
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
                                :rows="12"
                                :value="$text"
                                x-bind:disabled="view !== '{{ $tab }}'"
                                :x-model="$bindsLocally() ? 'source' : null"
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
                        >{{ $html }}</div>
                        @if ( 'split' === $tab )</div>@endif
                    @endif
                </x-artisanpack-tab>
            @endforeach
        </x-artisanpack-tabs>
    </div>
@endif
