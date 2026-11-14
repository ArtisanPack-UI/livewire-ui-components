{{--
    File (#53). Markup contract: design-system File.
    `class` lands on the root; every other attribute (wire:model, name) lands on the native
    input, which is transparent over the drop zone: a click browses and a drop chooses with
    no script, and wire:model uploads natively. Alpine sets data-state="drag" while files
    are over the zone and shows Livewire's upload progress in a pending row.
--}}
@php
    $controlId   = $controlId();
    $message     = $errorMessage( $errors ?? null );
    $isInvalid   = $invalid || null !== $message;
    $hasLabel    = $filled( $label );
    $hasHint     = $filled( $hint );
    $entries     = $entries();
    $model       = $modelName() ? $attributes->wire( 'model' )->value() : null;
    $describedBy = $describedBy( array_values( array_filter( [
        $hasHint ? $controlId . '-hint' : null,
        null !== $message ? $controlId . '-error' : null,
    ] ) ) );
@endphp
<div
    {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-file' ) ) }}
    data-variant="{{ $variant }}"
    @if ( null !== $maxSize ) data-max-size="{{ $maxSize }}" @endif
    @if ( $isInvalid ) data-invalid @endif
    @if ( $disabled ) data-disabled @endif
    x-data="{ depth: 0, uploading: false, progress: 0 }"
    x-on:livewire-upload-start="uploading = true; progress = 0"
    x-on:livewire-upload-progress="progress = $event.detail.progress"
    x-on:livewire-upload-finish="uploading = false"
    x-on:livewire-upload-error="uploading = false"
    x-on:livewire-upload-cancel="uploading = false"
>
    @if ( $hasLabel )
        <label data-scope="file" data-part="label" for="{{ $controlId }}"{{ $partClass( 'label' ) }}>{{ $content( $label ) }}@if ( $required )<span data-scope="file" data-part="required" aria-hidden="true"{{ $partClass( 'required' ) }}>*</span>@endif</label>
    @endif
    <div
        data-scope="file"
        data-part="dropzone"{{ $partClass( 'dropzone' ) }}
        x-on:dragenter="depth++; $el.dataset.state = 'drag'"
        x-on:dragleave="depth = Math.max(0, depth - 1); if (0 === depth) delete $el.dataset.state"
        x-on:drop="depth = 0; delete $el.dataset.state"
    >
        <input {{ $attributes->except( [ 'class', 'id', 'aria-describedby' ] ) }}
            type="file"
            data-scope="file"
            data-part="control"
            id="{{ $controlId }}"{{ $partClass( 'control' ) }}
            @if ( null !== $accept ) accept="{{ $accept }}" @endif
            @if ( $multiple ) multiple @endif
            @if ( $disabled ) disabled @endif
            @if ( $required ) required @endif
            @if ( $isInvalid ) aria-invalid="true" @endif
            @if ( ! $hasLabel ) aria-labelledby="{{ $controlId }}-title" @endif
            @if ( null !== $describedBy ) aria-describedby="{{ $describedBy }}" @endif
        />
        <span data-scope="file" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}>{{ $iconSvg( 'compact' === $variant ? 'file-text' : 'cloud-upload' ) }}</span>
        <p data-scope="file" data-part="title" id="{{ $controlId }}-title"{{ $partClass( 'title' ) }}>{{ $titleText() }}</p>
        @if ( $hasHint )
            <p data-scope="file" data-part="hint" id="{{ $controlId }}-hint"{{ $partClass( 'hint' ) }}>{{ $content( $hint ) }}</p>
        @endif
    </div>
    <ul data-scope="file" data-part="list"{{ $partClass( 'list' ) }} @if ( [] === $entries ) x-bind:hidden="! uploading" hidden @endif>
        @foreach ( $entries as $index => $entry )
            <li data-scope="file" data-part="item" wire:key="{{ $controlId }}-file-{{ $index }}"{{ $partClass( 'item' ) }} @if ( null !== $entry['error'] ) data-state="error" @endif>
                <span data-scope="file" data-part="thumb" aria-hidden="true"{{ $partClass( 'thumb' ) }}>{{ $iconSvg( null !== $entry['error'] ? 'circle-alert' : 'file-text' ) }}</span>
                <span data-scope="file" data-part="name"{{ $partClass( 'name' ) }}>{{ $entry['name'] }}</span>
                <span data-scope="file" data-part="actions"{{ $partClass( 'actions' ) }}>
                    @if ( null !== $entry['upload'] && null !== $model )
                        <x-artisanpack-button variant="ghost" color="neutral" size="sm" icon-only leading-icon="x" :aria-label="$removeText( $entry['name'] )" :disabled="$disabled" x-on:click="$wire.$removeUpload({{ \Illuminate\Support\Js::from( $model ) }}, {{ \Illuminate\Support\Js::from( $entry['upload'] ) }})" />
                    @else
                        <x-artisanpack-button variant="ghost" color="neutral" size="sm" icon-only leading-icon="x" :aria-label="$removeText( $entry['name'] )" :disabled="$disabled" x-on:click="$dispatch('remove', { index: {{ $index }}, name: {{ \Illuminate\Support\Js::from( $entry['name'] ) }} })" />
                    @endif
                </span>
                @if ( null !== $entry['progress'] && null === $entry['error'] )
                    <progress data-scope="file" data-part="progress" max="100" value="{{ $entry['progress'] }}" aria-label="{{ $entry['name'] }}"{{ $partClass( 'progress' ) }}>{{ $entry['progress'] }}%</progress>
                @else
                    <span data-scope="file" data-part="meta"{{ $partClass( 'meta' ) }}>@if ( null !== $entry['error'] ){{ $iconSvg( 'circle-alert' ) }}{{ $entry['error'] }}@elseif ( null !== $entry['size'] ){{ $formatSize( $entry['size'] ) }}@endif</span>
                @endif
            </li>
        @endforeach
        <li data-scope="file" data-part="item" wire:key="{{ $controlId }}-pending" hidden x-bind:hidden="! uploading" aria-live="polite"{{ $partClass( 'item' ) }}>
            <span data-scope="file" data-part="thumb" aria-hidden="true"{{ $partClass( 'thumb' ) }}>{{ $iconSvg( 'cloud-upload' ) }}</span>
            <span data-scope="file" data-part="name"{{ $partClass( 'name' ) }}>{{ __( 'Uploading…' ) }}</span>
            <progress data-scope="file" data-part="progress" max="100" value="0" x-bind:value="progress" aria-label="{{ __( 'Uploading…' ) }}"{{ $partClass( 'progress' ) }}></progress>
        </li>
    </ul>
    @if ( null !== $message )
        <p data-scope="file" data-part="error" id="{{ $controlId }}-error"{{ $partClass( 'error' ) }}>{{ $iconSvg( 'circle-alert' ) }}{{ $message }}</p>
    @endif
</div>
