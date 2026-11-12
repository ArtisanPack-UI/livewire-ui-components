{{--
    Kbd (#65). Markup contract: design-system Kbd. With keys, an outer kbd
    (.apui-kbd-combo) around one .apui-kbd per key, spelled for the platform:
    the server renders the other platforms' keys, and with platform auto the
    apuiKbd Alpine component swaps in Apple's (⌘, ⌥, ⇧) after load. A symbol
    shows hidden from assistive technology, followed by its name as hidden
    text. Without keys, the slot is one key.
--}}
@php
    $size  = $sizeValue();
    $keys  = $keyList( 'mac' === $platform );
    $bound = $dependsOnPlatform();
    $extra = static fn ( string $root, string $part ): array => array_values( array_filter( [ ...$rootClasses( $root ), $classes[ $part ] ?? null ], static fn ( mixed $class ): bool => is_string( $class ) && '' !== trim( $class ) ) );
@endphp
@if ( [] === $keys )
<kbd {{ $attributes->class( $extra( 'apui-kbd', 'key' ) ) }} data-scope="kbd" data-part="key" data-size="{{ $size }}">{{ $slot }}</kbd>
@else
<kbd
    {{ $attributes->class( $extra( 'apui-kbd-combo', 'combo' ) ) }}
    data-scope="kbd"
    data-part="combo"
    @if ( $bound ) x-data="{{ $alpineData() }}" @endif
>@foreach ( $keys as $index => $key )<kbd class="{{ trim( 'apui-kbd ' . ( $classes['key'] ?? '' ) ) }}" data-scope="kbd" data-part="key" data-size="{{ $size }}">@if ( $bound )<span @if ( null !== $key['label'] ) aria-hidden="true" @endif x-bind:aria-hidden="keys[{{ $index }}].label ? 'true' : null" x-text="keys[{{ $index }}].text">{{ $key['text'] }}</span><span class="apui-sr-only" x-text="keys[{{ $index }}].label ?? ''">{{ $key['label'] }}</span>@elseif ( null !== $key['label'] )<span aria-hidden="true">{{ $key['text'] }}</span><span class="apui-sr-only">{{ $key['label'] }}</span>@else{{ $key['text'] }}@endif</kbd>@endforeach</kbd>
@endif
