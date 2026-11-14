<!DOCTYPE html>
<html lang="{{ str_replace( '_', '-', app()->getLocale() ) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset( $title ) ? $title . ' - ' . config( 'app.name' ) : config( 'app.name' ) }}</title>

    {{-- Applies the stored light, dark or system theme before the first paint. --}}
    @apuiThemeScript
    @vite( [ 'resources/css/app.css', 'resources/js/app.js' ] )
</head>
<body>
    <x-artisanpack-app-shell :label="__( 'Main navigation' )">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>

        <x-slot:sidebar>
            <x-artisanpack-menu :label="__( 'Main navigation' )">
                <x-artisanpack-menu-item :label="__( 'Home' )" icon="house" href="/" />

                <x-artisanpack-menu-sub :label="__( 'Settings' )" icon="settings">
                    <x-artisanpack-menu-item :label="__( 'Profile' )" icon="user" href="#" />
                    <x-artisanpack-menu-item :label="__( 'Notifications' )" icon="inbox" href="#" />
                </x-artisanpack-menu-sub>
            </x-artisanpack-menu>
        </x-slot:sidebar>

        <x-slot:footer>
            {{-- Applies and stores the light, dark or system choice. --}}
            <x-artisanpack-theme-toggle variant="icon" />

            @if ( $user = auth()->user() )
                {{-- The theme control lives in the toggle above, so the menu hides its own. --}}
                <x-artisanpack-user-menu
                    :user="[ 'name' => $user->name, 'email' => $user->email ]"
                    logout-action="/logout"
                    hide-theme
                />
            @endif
        </x-slot:footer>

        {{ $slot }}
    </x-artisanpack-app-shell>

    {{-- Toasts: $this->toast(), $this->dispatch( 'toast', … ) and flashed toasts show here. --}}
    <x-artisanpack-toaster />
</body>
</html>
