{{--
    Tabs (#56). Markup contract: design-system Tabs. The .apui-tabs root is the
    machine's root part; the tablist renders a trigger per registered tab, then
    the panels follow. Children register their triggers while Blade renders the
    slot, before this view; `tabs` renders the same children from data. Every
    part has a stable, server-rendered id, per value for triggers and panels
    (D-015). Attributes land on the root, where value-change is dispatched.
--}}
@php
    $baseId = $baseId();
    $renderingData( true );
    ob_start();
@endphp
@foreach ( $entries() as $entry )
    <x-artisanpack-tab
        :value="$entry['value']"
        :label="$entry['label']"
        :icon="$entry['icon']"
        :count="$entry['count']"
        :disabled="$entry['disabled']"
    >{{ $entry['content'] }}</x-artisanpack-tab>
@endforeach
@php
    $dataPanels = new \Illuminate\Support\HtmlString( (string) ob_get_clean() );
    $renderingData( false );
    $selected   = $selectedValue();
@endphp
<div
    {{ $rootAttributes()->class( $rootClasses( 'apui-tabs' ) ) }}
    id="{{ $baseId }}"
    data-scope="tabs"
    data-part="root"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    data-orientation="{{ $orientation }}"
    data-activation-mode="{{ $activationMode }}"
    x-data="{{ $alpineData() }}"
    x-apui:root
>
    <div
        data-scope="tabs"
        data-part="list"
        id="{{ $partId( 'list' ) }}"
        role="tablist"
        aria-orientation="{{ $orientation }}"
        data-orientation="{{ $orientation }}"
        @if ( $filled( $label ) ) aria-label="{{ $label }}" @endif
        {{ $partClass( 'list' ) }}
        x-apui:list
    >
        @foreach ( $triggers() as $trigger )
            @php $isSelected = $selected === $trigger['value']; @endphp
            <button
                type="button"
                data-scope="tabs"
                data-part="trigger"
                id="{{ $valuePartId( 'trigger', $trigger['value'] ) }}"
                role="tab"
                data-value="{{ $trigger['value'] }}"
                data-orientation="{{ $orientation }}"
                aria-selected="{{ $isSelected ? 'true' : 'false' }}"
                @if ( $isSelected ) data-selected aria-controls="{{ $valuePartId( 'content', $trigger['value'] ) }}" @endif
                tabindex="{{ $isSelected ? '0' : '-1' }}"
                @if ( $trigger['disabled'] ) disabled aria-disabled="true" data-disabled @endif
                {{ $partClass( 'trigger' ) }}
                x-apui:trigger="{{ $partArgs( [ 'value' => $trigger['value'], 'disabled' => $trigger['disabled'] ] ) }}"
            >
                @if ( null !== $trigger['icon'] )
                    <span data-scope="tabs" data-part="icon" aria-hidden="true"{{ $partClass( 'icon' ) }}@if ( $mirrors( $trigger['icon'] ) ) data-mirror @endif>{{ $iconSvg( $trigger['icon'] ) }}</span>
                @endif
                {{ $trigger['label'] }}
                @if ( null !== $trigger['count'] )
                    <span data-scope="tabs" data-part="count"{{ $partClass( 'count' ) }}>{{ $trigger['count'] }}</span>
                @endif
            </button>
        @endforeach
    </div>
    {{ $dataPanels }}
    {{ $slot ?? '' }}
</div>
@php $finishRendering(); @endphp
