<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-artisanpack-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-artisanpack-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-artisanpack-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-artisanpack-nav>

    {{-- MAIN --}}
    <x-artisanpack-main>
        {{-- SIDEBAR --}}
        <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <x-artisanpack-app-brand class="px-5 pt-4" />

            {{-- MENU --}}
            <x-artisanpack-menu activate-by-route>

                {{-- User --}}
                @if($user = auth()->user())
                    <x-artisanpack-menu-separator />

                    <x-artisanpack-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="-mx-2 !-my-2 rounded">
                        <x-slot:actions>
                            <x-artisanpack-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate link="/logout" />
                        </x-slot:actions>
                    </x-artisanpack-list-item>

                    <x-artisanpack-menu-separator />
                @endif

                <x-artisanpack-menu-item title="Hello" icon="o-sparkles" link="/" />

                <x-artisanpack-menu-sub title="Settings" icon="o-cog-6-tooth">
                    <x-artisanpack-menu-item title="Wifi" icon="o-wifi" link="####" />
                    <x-artisanpack-menu-item title="Archives" icon="o-archive-box" link="####" />
                </x-artisanpack-menu-sub>
            </x-artisanpack-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-artisanpack-main>

    {{--  TOAST area --}}
    <x-artisanpack-toast />
</body>
</html>
