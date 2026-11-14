{{--
    UserMenu (#58). Markup contract: design-system wave, UserMenu.
    Avatar + DropdownMenu (D-019): the trigger is the menu machine's trigger,
    scoped user-menu, and the panel is .apui-dropdown-menu.apui-user-menu. The
    header, workspace group, theme control, items, composed children and Log out
    render in that order. Workspace and theme options are radio items whose
    values carry a prefix; the inner element turns their checked-change into
    workspace-change and theme-change, and the outer one, which takes the
    attributes, hears those and select.
--}}
@php
    $baseId     = $baseId();
    $isOpen     = $isOpen();
    $state      = $isOpen ? 'open' : 'closed';
    $align      = in_array( $align, [ 'start', 'center', 'end' ], true ) ? $align : 'start';
    $name       = $userField( 'name' );
    $email      = $userField( 'email' );
    $role       = $userField( 'role' );
    $avatar     = $userField( 'avatar' );
    $status     = $userField( 'status' );
    $initials   = \ArtisanPack\LivewireUiComponents\View\Components\Avatar::initialsFor( $name );
    $tone       = \ArtisanPack\LivewireUiComponents\View\Components\Avatar::colorFor( $name );
    $workspaces = $workspaceOptions();
    $theme      = $themeValue();
    $triggerSlot = isset( $trigger ) && $filled( $trigger ) ? $trigger : null;
@endphp
<div {{ $hostAttributes() }} style="display: contents">
<div
    id="{{ $baseId }}"
    x-data="{{ $alpineData() }}"
    style="display: contents"
    x-on:checked-change.stop="const value = String( $event.detail?.value ?? '' ); if ( value.startsWith( 'workspace:' ) ) { $dispatch( 'workspace-change', { value: value.slice( 10 ) } ) } else if ( value.startsWith( 'theme:' ) ) { $dispatch( 'theme-change', { value: value.slice( 6 ) } ) }"
    x-on:select="if ( $event.detail?.relayed ) { return } $event.stopPropagation(); const value = String( $event.detail?.value ?? '' ); if ( value.startsWith( 'workspace:' ) || value.startsWith( 'theme:' ) ) { return } if ( 'logout' === value && $refs.logout ) { $refs.logout.submit() } $dispatch( 'select', { value, relayed: true } )"
>
    <button
        type="button"
        id="{{ $partId( 'trigger' ) }}"
        data-scope="user-menu"
        data-part="trigger"{{ $partClass( 'trigger' ) }}
        aria-haspopup="menu"
        aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
        aria-controls="{{ $partId( 'content' ) }}"
        data-state="{{ $state }}"
        @if ( $compact ) data-avatar-only aria-label="{{ $name }}" @endif
        x-apui:trigger
    >@if ( null !== $triggerSlot ){{ $content( $triggerSlot ) }}@else<x-artisanpack-avatar data-scope="user-menu" data-part="avatar" :class="$classes['avatar'] ?? null" :initials="$initials" :color="$tone" :src="'' !== $avatar ? $avatar : null" :status="'' !== $status ? $status : null" size="sm" />@unless ( $compact )<span data-scope="user-menu" data-part="identity"{{ $partClass( 'identity' ) }}><span data-scope="user-menu" data-part="name"{{ $partClass( 'name' ) }}>{{ $name }}</span>@if ( '' !== $role )<span data-scope="user-menu" data-part="role"{{ $partClass( 'role' ) }}>{{ $role }}</span>@endif</span><span data-scope="user-menu" data-part="indicator" aria-hidden="true"{{ $partClass( 'indicator' ) }}>{{ $iconSvg( 'chevrons-up-down' ) }}</span>@endunless @endif</button>
    @if ( null !== $logoutAction && '' !== $logoutAction )
        <form x-ref="logout" method="POST" action="{{ $logoutAction }}" hidden>@csrf</form>
    @endif
    @if ( $portal )<template x-teleport="body">@endif
    <div data-scope="dropdown-menu" data-part="positioner" id="{{ $partId( 'positioner' ) }}"{{ $partClass( 'positioner' ) }} x-apui:positioner>
        <div
            {{ $attributes->only( 'class' )->class( $rootClasses( 'apui-dropdown-menu' ) )->class( [ 'apui-user-menu' ] ) }}
            data-scope="dropdown-menu"
            data-part="content"
            id="{{ $partId( 'content' ) }}"
            role="menu"
            tabindex="0"
            aria-labelledby="{{ $partId( 'trigger' ) }}"
            data-state="{{ $state }}"
            data-align="{{ $align }}"
            data-surface="{{ $surface }}"
            @if ( ! $isOpen ) hidden @endif
            x-apui:content
        >
            <div data-scope="user-menu" data-part="header"{{ $partClass( 'header' ) }}><x-artisanpack-avatar data-scope="user-menu" data-part="avatar" :class="$classes['avatar'] ?? null" :initials="$initials" :color="$tone" :src="'' !== $avatar ? $avatar : null" :status="'' !== $status ? $status : null" /><span data-scope="user-menu" data-part="identity"{{ $partClass( 'identity' ) }}><span data-scope="user-menu" data-part="name"{{ $partClass( 'name' ) }}>{{ $name }}</span>@if ( '' !== $email )<span data-scope="user-menu" data-part="email"{{ $partClass( 'email' ) }}>{{ $email }}</span>@endif</span></div>
            <x-artisanpack-dropdown-menu-separator />
            @if ( count( $workspaces ) > 1 )
                <x-artisanpack-dropdown-menu-group :label="$workspaceLabel">
                    @foreach ( $workspaces as $option )
                        <x-artisanpack-dropdown-menu-item type="radio" :value="'workspace:' . $option['id']" :label="$option['name']" :checked="$option['id'] === $workspace" />
                    @endforeach
                </x-artisanpack-dropdown-menu-group>
                <x-artisanpack-dropdown-menu-separator />
            @endif
            @include( 'livewire-ui-components::components.partials.dropdown-menu-entries', [ 'entries' => $entries() ] )
            {{ $slot ?? '' }}
            @unless ( $hideTheme )
                <div data-scope="user-menu" data-part="theme"{{ $partClass( 'theme' ) }}>
                    <x-artisanpack-dropdown-menu-group :label="$themeLabel">
                        <div data-scope="user-menu" data-part="theme-options"{{ $partClass( 'themeOptions' ) }}>
                            @foreach ( \ArtisanPack\LivewireUiComponents\View\Components\UserMenu::THEMES as $option => $icon )
                                <x-artisanpack-dropdown-menu-item type="radio" :value="'theme:' . $option" :label="$themeLabels[ $option ]" :icon="$icon" :checked="$option === $theme" />
                            @endforeach
                        </div>
                    </x-artisanpack-dropdown-menu-group>
                </div>
            @endunless
            <x-artisanpack-dropdown-menu-separator />
            <x-artisanpack-dropdown-menu-item value="logout" icon="log-out" :label="$logoutLabel" />
        </div>
    </div>
    @if ( $portal )</template>@endif
</div>
</div>
@php $finishRendering(); @endphp
