{{--
    ThemeToggle (#65). Markup contract: design-system ThemeToggle. The
    .apui-theme-toggle root holds apuiThemeToggle. Segmented: the root is also
    .apui-tabs and its list is a radiogroup of role=radio triggers. Icon: a ghost
    icon-only Button. Menu: an outline Button trigger and a DropdownMenu of
    radio items on apuiDropdownMenu, scoped dropdown-menu (D-019). Every part
    has a stable, server-rendered id (D-015). Attributes land on the root, where
    value-change is dispatched.
--}}
@php
    $baseId  = $baseId();
    $current = $currentValue();
    $labels  = $labels();
    $choices = $offeredChoices();
    $tabStop = $tabStop();
    $names   = [ 'light' => $labels['light'], 'dark' => $labels['dark'], 'high-contrast' => $labels['high-contrast'], 'system' => $labels['system'] ];
    $icons   = \ArtisanPack\LivewireUiComponents\View\Components\ThemeToggle::ICONS;
    $menuId  = $partId( 'menu' );
@endphp
<div
    {{ $rootAttributes()->class( $rootClasses( 'apui-theme-toggle' ) )->class( [ 'apui-tabs' => 'segmented' === $variant ] ) }}
    id="{{ $baseId }}"
    data-scope="theme-toggle"
    data-part="root"
    data-variant="{{ $variant }}"
    data-size="{{ $size }}"
    @if ( 'segmented' === $variant && $iconOnly ) data-icon-only @endif
    data-value="{{ $current }}"
    x-bind:data-value="current()"
    x-data="{{ $alpineData() }}"
>
    @if ( 'segmented' === $variant )
        <div
            data-scope="tabs"
            data-part="list"
            id="{{ $partId( 'list' ) }}"
            role="radiogroup"
            aria-label="{{ $labels['label'] }}"{{ $partClass( 'list' ) }}
            x-on:keydown="keydown( $event )"
        >
            @foreach ( $choices as $choice )
                <button
                    type="button"
                    data-scope="tabs"
                    data-part="trigger"
                    id="{{ $partId( 'option-' . $choice ) }}"
                    role="radio"
                    data-value="{{ $choice }}"
                    aria-checked="{{ $current === $choice ? 'true' : 'false' }}"
                    tabindex="{{ $tabStop === $choice ? '0' : '-1' }}"{{ $partClass( 'option' ) }}
                    x-bind:aria-checked="checked( '{{ $choice }}' )"
                    x-bind:tabindex="tabStop( '{{ $choice }}' )"
                    x-on:click="choose( '{{ $choice }}' )"
                ><span data-scope="tabs" data-part="icon" aria-hidden="true">{{ $iconSvg( $icons[ $choice ] ) }}</span>@if ( $iconOnly )<span class="apui-sr-only">{{ $names[ $choice ] }}</span>@else{{ $names[ $choice ] }}@endif</button>
            @endforeach
        </div>
    @elseif ( 'icon' === $variant )
        @php $next = $nextTheme(); @endphp
        <x-artisanpack-button
            variant="ghost"
            color="neutral"
            :size="$size"
            icon-only
            :id="$partId( 'button' )"
            :class="$classes['button'] ?? null"
            :aria-label="'light' === $next ? $labels['switchToLight'] : $labels['switchToDark']"
            x-bind:aria-label="'light' === nextTheme() ? {{ \Illuminate\Support\Js::from( $labels['switchToLight'] ) }} : {{ \Illuminate\Support\Js::from( $labels['switchToDark'] ) }}"
            x-on:click="toggle()"
        ><x-slot:icon>{{ $shownIcon( 'moon', "'dark' === nextTheme()", 'dark' === $next ) }}{{ $shownIcon( 'sun', "'light' === nextTheme()", 'light' === $next ) }}</x-slot:icon></x-artisanpack-button>
    @else
        <div id="{{ $menuId }}" x-data="{{ $menuAlpineData() }}" style="display: contents">
            <x-artisanpack-button
                variant="outline"
                color="neutral"
                :size="$size"
                trailing-icon="chevron-down"
                :id="$menuId . '-trigger'"
                :class="$classes['trigger'] ?? null"
                data-scope="dropdown-menu"
                data-part="trigger"
                aria-haspopup="menu"
                aria-expanded="false"
                :aria-controls="$menuId . '-content'"
                data-state="closed"
                x-apui:trigger=""
            ><x-slot:icon>@foreach ( $choices as $choice ){{ $shownIcon( $icons[ $choice ], "'" . $choice . "' === current()", $current === $choice ) }}@endforeach</x-slot:icon>{{ $labels['label'] }}</x-artisanpack-button>
            <template x-teleport="body">
            <div data-scope="dropdown-menu" data-part="positioner" id="{{ $menuId }}-positioner" x-apui:positioner>
                <div
                    @class( [ 'apui-dropdown-menu', $classes['menu'] ?? null ] )
                    data-scope="dropdown-menu"
                    data-part="content"
                    id="{{ $menuId }}-content"
                    role="menu"
                    tabindex="0"
                    aria-labelledby="{{ $menuId }}-trigger"
                    data-state="closed"
                    data-align="start"
                    data-surface="overlay"
                    hidden
                    x-apui:content
                >
                    @foreach ( $choices as $choice )
                        @if ( 'system' === $choice && $loop->index > 0 )
                            <hr id="{{ $menuId }}-separator-1" data-scope="dropdown-menu" data-part="separator" role="separator" aria-orientation="horizontal" x-apui:separator />
                        @endif
                        @php
                            $isChecked = $current === $choice;
                            $args      = "{ type: 'radio', value: '" . $choice . "', checked: '" . $choice . "' === current() }";
                        @endphp
                        <div
                            wire:key="{{ $menuId }}-item-{{ $choice }}"
                            id="{{ $menuId }}/{{ $choice }}"
                            data-scope="dropdown-menu"
                            data-part="item"
                            data-value="{{ $choice }}"
                            role="menuitemradio"
                            data-type="radio"
                            aria-checked="{{ $isChecked ? 'true' : 'false' }}"
                            data-state="{{ $isChecked ? 'checked' : 'unchecked' }}"{{ $partClass( 'item' ) }}
                            x-apui:option-item="{{ $args }}"
                            x-on:checked-change="choose( '{{ $choice }}' )"
                        >
                            <span data-scope="dropdown-menu" data-part="item-indicator" aria-hidden="true" data-state="{{ $isChecked ? 'checked' : 'unchecked' }}" @if ( ! $isChecked ) hidden @endif x-apui:item-indicator="{{ $args }}">{{ $iconSvg( 'check' ) }}</span>
                            <span data-scope="dropdown-menu" data-part="icon" aria-hidden="true">{{ $iconSvg( $icons[ $choice ] ) }}</span>
                            <span data-scope="dropdown-menu" data-part="item-text" x-apui:item-text="{{ $args }}">{{ 'system' === $choice ? $labels['matchSystem'] : $names[ $choice ] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            </template>
        </div>
    @endif
</div>
