{{--
    Streamable Content Component

    A container for streaming content with wire:stream support (Livewire 4+).
    In Livewire 3, the content is displayed statically without streaming.

    @since 2.0.0
--}}

@php
    $classes = $attributes->get('class', '');
    if ($prose) {
        $classes .= ' prose dark:prose-invert max-w-none';
    }
    if ($showCursor) {
        $classes .= ' streaming-cursor';
    }
@endphp

<{{ $tag }}
    id="{{ $id ?? $uuid }}"
    @if($supportsStreaming())
        wire:stream="{{ $target }}"
    @endif
    {{ $attributes->except(['class'])->merge(['class' => trim($classes)]) }}
    data-streamable-content
    @if($placeholder && !$slot->isNotEmpty())
        data-placeholder="{{ $placeholder }}"
    @endif
>
    @if($slot->isNotEmpty())
        {{ $slot }}
    @elseif($placeholder)
        <span class="text-base-content/50 italic">{{ $placeholder }}</span>
    @endif
</{{ $tag }}>

@if($showCursor)
    @once
        <style>
            .streaming-cursor::after {
                content: '▋';
                animation: blink 1s step-end infinite;
                margin-left: 2px;
                color: currentColor;
            }

            .streaming-cursor[data-streaming-complete]::after {
                display: none;
            }

            @keyframes blink {
                0%, 100% { opacity: 1; }
                50% { opacity: 0; }
            }
        </style>
    @endonce
@endif
