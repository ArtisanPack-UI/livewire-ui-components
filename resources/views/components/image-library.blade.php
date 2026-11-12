{{--
    ImageLibrary (#61), Livewire only (D-003). Markup contract: design-system
    ImageLibrary. The grid is a listbox of server-rendered options carrying
    their data in data-*, so a morph updates them in place; apuiImageLibrary
    keeps the search, selection, active asset and view on the client, and
    the details panel reads the active option. With wire:model (uploads) and
    wire:library (the collection), it drives the WithMediaSync trait as 2.x
    did: upload, replace, crop (with Cropper.js loaded), remove and reorder
    (with SortableJS loaded). Every part has a stable id. `class` lands on
    the root with the other attributes.
--}}
@php
    $baseId   = $baseId();
    $entries  = $entries();
    $count    = count( $entries );
    $model    = $modelName();
    $library  = $libraryName();
    $editable = $editable();
    $multiple = 'multiple' === $selectable;
    $messages = [];
    if ( ! $hideErrors && isset( $errors ) ) {
        foreach ( array_filter( [ $library, $model ] ) as $field ) {
            foreach ( $errors->get( $field ) as $message ) {
                $messages[] = $message;
            }
        }
        if ( null !== $model ) {
            foreach ( $errors->get( $model . '.*' ) as $list ) {
                foreach ( (array) $list as $message ) {
                    $messages[] = $validationMessage( $message );
                }
            }
        }
    }
@endphp
<div
    {{ $rootAttributes()->class( $rootClasses( 'apui-image-library' ) ) }}
    id="{{ $baseId }}"
    data-scope="image-library"
    data-part="root"
    data-view="{{ $viewMode() }}"
    x-data="{{ $alpineData() }}"
    x-bind:data-view="view"
    x-bind:data-dragging="dragging ? '' : null"
    @if ( $editable )
        x-on:livewire-upload-progress="uploading( $event.detail.progress )"
        x-on:livewire-upload-finish="uploaded()"
        x-on:livewire-upload-error="uploading( 0 )"
    @endif
    @if ( $editable && $droppable )
        x-on:dragenter="dragEnter( $event )"
        x-on:dragover.prevent=""
        x-on:dragleave="dragLeave( $event )"
        x-on:drop="drop( $event )"
    @endif
>
    <div data-scope="image-library" data-part="main" id="{{ $partId( 'main' ) }}"{{ $partClass( 'main' ) }}>
        <div data-scope="image-library" data-part="toolbar" id="{{ $partId( 'toolbar' ) }}"{{ $partClass( 'toolbar' ) }}>
            <div data-scope="image-library" data-part="search" id="{{ $partId( 'search' ) }}"{{ $partClass( 'search' ) }}>
                <x-artisanpack-input
                    size="sm"
                    type="search"
                    leading-icon="search"
                    :id="$partId( 'search-control' )"
                    :aria-label="$text( 'search' )"
                    :placeholder="$text( 'search' )"
                    :aria-controls="$partId( 'grid' )"
                    x-model="query"
                />
            </div>
            <span data-scope="image-library" data-part="spacer" id="{{ $partId( 'spacer' ) }}" aria-hidden="true"{{ $partClass( 'spacer' ) }}></span>
            <div data-scope="image-library" data-part="view" id="{{ $partId( 'view' ) }}" role="group" aria-label="{{ $text( 'view' ) }}"{{ $partClass( 'view' ) }}>
                @foreach ( [ 'grid' => 'layout-grid', 'list' => 'list' ] as $mode => $icon )
                    <x-artisanpack-button
                        variant="ghost"
                        color="neutral"
                        size="sm"
                        icon-only
                        :leading-icon="$icon"
                        :id="$partId( 'view-' . $mode )"
                        :aria-label="$text( $mode )"
                        :aria-pressed="$viewMode() === $mode ? 'true' : 'false'"
                        x-bind:aria-pressed="view === '{{ $mode }}' ? 'true' : 'false'"
                        x-on:click="view = '{{ $mode }}'"
                    />
                @endforeach
            </div>
            @if ( $editable )
                <x-artisanpack-button
                    size="sm"
                    leading-icon="upload"
                    :id="$partId( 'upload' )"
                    x-on:click="$refs.files.click()"
                    x-bind:disabled="busy"
                >{{ $text( 'upload' ) }}</x-artisanpack-button>
            @endif
        </div>
        <div data-scope="image-library" data-part="bulk" id="{{ $partId( 'bulk' ) }}"{{ $partClass( 'bulk' ) }} hidden x-bind:hidden="! selected.length">
            @if ( $multiple )
                <x-artisanpack-checkbox
                    :id="$partId( 'select-all' )"
                    :label="$text( 'select-all' )"
                    x-bind:checked="allSelected"
                    x-effect="const state = allSelected ? 'checked' : ( someSelected ? 'indeterminate' : 'unchecked' ); $el.indeterminate = 'indeterminate' === state; $el.closest('.apui-checkbox').dataset.state = state"
                    x-on:change="toggleAll()"
                />
            @endif
            <span data-scope="image-library" data-part="count" id="{{ $partId( 'count' ) }}"{{ $partClass( 'count' ) }} x-text="countText"></span>
            <x-artisanpack-button variant="ghost" color="neutral" size="xs" :id="$partId( 'bulk-insert' )" x-on:click="insert()">{{ $text( 'insert' ) }}</x-artisanpack-button>
            @if ( $editable )
                <x-artisanpack-button variant="ghost" color="error" size="xs" leading-icon="trash" :id="$partId( 'bulk-remove' )" x-on:click="remove()">{{ $text( 'remove' ) }}</x-artisanpack-button>
            @endif
        </div>
        <span class="apui-sr-only" id="{{ $partId( 'status' ) }}" role="status" x-text="selected.length ? countText : ''"></span>
        @if ( ! $hideProgress )
            <progress data-scope="image-library" data-part="progress" id="{{ $partId( 'progress' ) }}" max="100" value="0" aria-label="{{ $text( 'upload' ) }}"{{ $partClass( 'progress' ) }} hidden x-bind:hidden="! progress" x-bind:value="progress"></progress>
        @endif
        <div
            data-scope="image-library"
            data-part="grid"
            id="{{ $partId( 'grid' ) }}"
            role="listbox"
            aria-label="{{ $text( 'label' ) }}"
            aria-multiselectable="{{ $multiple ? 'true' : 'false' }}"
            {{ $partClass( 'grid' ) }}
            x-ref="grid"
            x-on:keydown="keydown( $event )"
            @if ( 0 === $count ) hidden @endif
        >
            @foreach ( $entries as $i => $entry )
                @php $assetId = $partId( 'asset-' . preg_replace( '/[^A-Za-z0-9_-]+/', '-', $entry['id'] ) ); @endphp
                <div
                    data-scope="image-library"
                    data-part="asset"
                    id="{{ $assetId }}"
                    role="option"
                    aria-selected="false"
                    tabindex="{{ 0 === $i ? '0' : '-1' }}"
                    wire:key="{{ $assetId }}"
                    data-id="{{ $entry['id'] }}"
                    data-key="{{ $entry['key'] }}"
                    data-src="{{ $entry['src'] }}"
                    data-name="{{ $entry['name'] }}"
                    data-alt="{{ $entry['alt'] }}"
                    data-size="{{ $entry['size'] }}"
                    data-dimensions="{{ $entry['dimensions'] }}"
                    data-type="{{ $entry['type'] }}"
                    data-image="{{ $entry['image'] ? 'true' : 'false' }}"
                    {{ $partClass( 'asset' ) }}
                    x-bind:aria-selected="isSelected( {{ \Illuminate\Support\Js::from( $entry['id'] ) }} ) ? 'true' : 'false'"
                    x-bind:tabindex="isTabStop( {{ \Illuminate\Support\Js::from( $entry['id'] ) }} ) ? 0 : -1"
                    x-bind:hidden="! matches( {{ \Illuminate\Support\Js::from( $entry['name'] ) }} )"
                    x-on:click="toggle( {{ \Illuminate\Support\Js::from( $entry['id'] ) }} )"
                    x-on:focus="focused = {{ \Illuminate\Support\Js::from( $entry['id'] ) }}"
                >
                    <div data-scope="image-library" data-part="thumb"{{ $partClass( 'thumb' ) }}>
                        @if ( $entry['image'] )
                            <img src="{{ $entry['src'] }}" alt="" loading="lazy" decoding="async">
                        @else
                            {{ $iconSvg( 'file-text' ) }}
                            <span data-scope="image-library" data-part="type"{{ $partClass( 'type' ) }}>{{ $entry['type'] }}</span>
                        @endif
                        <span data-scope="image-library" data-part="check" aria-hidden="true"{{ $partClass( 'check' ) }}>{{ $iconSvg( 'check' ) }}</span>
                    </div>
                    <span data-scope="image-library" data-part="name"{{ $partClass( 'name' ) }}>{{ $entry['name'] }}</span>
                    <span data-scope="image-library" data-part="meta"{{ $partClass( 'meta' ) }}>{{ $entry['meta'] }}</span>
                </div>
            @endforeach
        </div>
        <div data-scope="image-library" data-part="empty" id="{{ $partId( 'empty' ) }}"{{ $partClass( 'empty' ) }} @if ( $count > 0 ) hidden @endif x-bind:hidden="shown > 0">
            <span x-text="{{ \Illuminate\Support\Js::from( $count > 0 ? $text( 'no-match' ) : $text( 'empty' ) ) }}">{{ $count > 0 ? $text( 'no-match' ) : $text( 'empty' ) }}</span>
        </div>
        @if ( $filled( $hint ) )
            <p data-scope="image-library" data-part="hint" id="{{ $partId( 'hint' ) }}"{{ $partClass( 'hint' ) }}>{{ $hint }}</p>
        @endif
        <p data-scope="image-library" data-part="error" id="{{ $partId( 'error' ) }}" role="alert"{{ $partClass( 'error' ) }} @if ( [] === $messages ) hidden @endif>{{ implode( ' ', array_unique( $messages ) ) }}</p>
    </div>
    <aside data-scope="image-library" data-part="details" id="{{ $partId( 'details' ) }}" aria-label="{{ $text( 'details' ) }}"{{ $partClass( 'details' ) }} hidden x-bind:hidden="! active">
        <div data-scope="image-library" data-part="preview" id="{{ $partId( 'preview' ) }}"{{ $partClass( 'preview' ) }}>
            <img alt="" x-show="field( 'image' ) === 'true'" x-bind:src="field( 'image' ) === 'true' ? field( 'src' ) : null" x-bind:alt="field( 'alt' )">
            <span x-show="field( 'image' ) !== 'true'" aria-hidden="true">{{ $iconSvg( 'file-text' ) }}</span>
        </div>
        <h3 data-scope="image-library" data-part="title" id="{{ $partId( 'title' ) }}"{{ $partClass( 'title' ) }} x-text="field( 'name' )"></h3>
        <div x-show="field( 'image' ) === 'true'">
            <x-artisanpack-input
                size="sm"
                :id="$partId( 'alt' )"
                :label="$text( 'alt' )"
                :hint="$text( 'alt-hint' )"
                :readonly="! $editable"
                x-bind:value="field( 'alt' )"
                x-on:change="saveAlt( $event.target.value )"
            />
        </div>
        <dl data-scope="image-library" data-part="metadata" id="{{ $partId( 'metadata' ) }}"{{ $partClass( 'metadata' ) }}>
            @foreach ( [ 'dimensions', 'size', 'type' ] as $field )
                <dt x-show="field( '{{ $field }}' )">{{ $text( $field ) }}</dt>
                <dd x-show="field( '{{ $field }}' )" x-text="field( '{{ $field }}' )"></dd>
            @endforeach
        </dl>
        <div data-scope="image-library" data-part="actions" id="{{ $partId( 'actions' ) }}"{{ $partClass( 'actions' ) }}>
            <x-artisanpack-button size="sm" :id="$partId( 'insert' )" x-on:click="insert()">{{ $text( 'insert' ) }}</x-artisanpack-button>
            @if ( $editable )
                <x-artisanpack-button variant="outline" color="neutral" size="sm" :id="$partId( 'replace' )" x-on:click="replace()">{{ $text( 'replace' ) }}</x-artisanpack-button>
                <x-artisanpack-button variant="outline" color="neutral" size="sm" leading-icon="crop" :id="$partId( 'crop' )" x-show="canCrop" x-on:click="crop()">{{ $text( 'crop' ) }}</x-artisanpack-button>
                <x-artisanpack-button variant="ghost" color="error" size="sm" leading-icon="trash" :id="$partId( 'remove' )" x-on:click="remove()">{{ $text( 'remove' ) }}</x-artisanpack-button>
            @endif
        </div>
    </aside>
    @if ( $droppable )
        <div data-scope="image-library" data-part="dropzone" id="{{ $partId( 'dropzone' ) }}" aria-hidden="true"{{ $partClass( 'dropzone' ) }} hidden x-bind:hidden="! dragging">{{ $text( 'drop' ) }}</div>
    @endif
    @if ( $editable )
        <input type="file" id="{{ $partId( 'files' ) }}" x-ref="files" hidden multiple accept="{{ $accept }}" wire:model="{{ $model }}.*" tabindex="-1" aria-hidden="true">
        @foreach ( $entries as $entry )
            <input type="file" id="{{ $baseId }}-file-{{ $entry['key'] }}" hidden accept="{{ $accept }}" wire:model="{{ $model }}.{{ $entry['key'] }}" wire:key="{{ $baseId }}-file-{{ $entry['key'] }}" tabindex="-1" aria-hidden="true">
        @endforeach
        <div wire:ignore>
            <x-artisanpack-dialog
                :id="$partId( 'crop-dialog' )"
                :title="$text( 'crop-title' )"
                size="lg"
                x-effect="open = cropping !== null"
                x-on:open-change="if ( ! $event.detail.open ) cropCancel()"
            >
                <img id="{{ $baseId }}-crop-image" src="" alt="" crossorigin="anonymous" style="display: block; max-inline-size: 100%">
                <x-slot:footer>
                    <x-artisanpack-button variant="outline" color="neutral" :id="$partId( 'crop-cancel' )" x-on:click="cropCancel()">{{ $text( 'crop-cancel' ) }}</x-artisanpack-button>
                    <x-artisanpack-button :id="$partId( 'crop-save' )" x-on:click="cropSave()">{{ $text( 'crop-save' ) }}</x-artisanpack-button>
                </x-slot:footer>
            </x-artisanpack-dialog>
        </div>
    @endif
</div>
