{{--
    DropdownMenuGroup (#50). Markup contract: design-system pilot, DropdownMenu.
    An item-group labelled by its item-group-label; both keep their ids (D-015).
--}}
@php
    $groupId  = $groupId();
    $hasLabel = $filled( $label );
    $groupKey = (string) json_encode( [ 'id' => $groupId ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
    $labelKey = (string) json_encode( [ 'htmlFor' => $groupId ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
@endphp
<div
    {{ $attributes->except( 'id' )->when( $filled( $classes['itemGroup'] ?? null ), fn ( $bag ) => $bag->class( $classes['itemGroup'] ) ) }}
    id="{{ $groupId }}"
    data-scope="dropdown-menu"
    data-part="item-group"
    role="group"
    @if ( $hasLabel ) aria-labelledby="{{ $groupId }}-label" @endif
    x-apui:item-group="{{ $groupKey }}"
>
    @if ( $hasLabel )
        <div data-scope="dropdown-menu" data-part="item-group-label" id="{{ $groupId }}-label"{{ $partClass( 'itemGroupLabel' ) }} x-apui:item-group-label="{{ $labelKey }}">{{ $content( $label ) }}</div>
    @endif
    {{ $slot }}
</div>
