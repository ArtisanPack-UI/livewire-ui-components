<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __( 'Theme Preview Tool' ) }} - ArtisanPack UI</title>
    {{-- Pinned CDN versions for standalone preview tool usage --}}
    <script src="https://cdn.tailwindcss.com?v=3.4.17"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.48/dist/full.min.css" rel="stylesheet" type="text/css" />
    @livewireStyles
</head>
<body>
    <livewire:theme-preview />

    @livewireScripts
</body>
</html>
