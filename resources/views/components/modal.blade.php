@php
    $uuid = $id ?: 'modal-' . md5(uniqid());
@endphp

<dialog
    {{ $attributes->except('wire:model')->class(["modal"]) }}
    role="dialog"
    aria-modal="true"
    @if($title)
        aria-labelledby="{{ $uuid }}-title"
    @endif
    aria-describedby="{{ $uuid }}-content"

    @if($id)
        id="{{ $id }}"
    @else
        x-data="{
            open: @entangle($attributes->wire('model')).live,
            lastFocusedElement: null
        }"
        x-init="
            $watch('open', value => {
                if (!value) {
                    $dispatch('close');
                    // Return focus to trigger element
                    if (lastFocusedElement) {
                        lastFocusedElement.focus();
                    }
                } else {
                    // Store last focused element
                    lastFocusedElement = document.activeElement;
                    $dispatch('open');
                    // Focus first focusable element
                    $nextTick(() => {
                        const firstFocusable = $el.querySelector('button:not([tabindex=\"-1\"]), [href], input, select, textarea, [tabindex]:not([tabindex=\"-1\"])');
                        if (firstFocusable) {
                            firstFocusable.focus();
                        }
                    });
                }
            })
        "
        :class="{'modal-open !animate-none': open}"
        :open="open"
        @if(!$persistent)
            @keydown.escape.window = "$wire.{{ $attributes->wire('model')->value() }} = false"
        @endif
    @endif

    @if(!$withoutTrapFocus)
        x-trap="open" x-bind:inert="!open"
    @endif
>
    <div class="modal-box {{ $boxClass }}" role="document">
        @if(!$persistent)
            @if ($id)
                <x-artisanpack-button
                    class="btn-circle btn-sm btn-ghost absolute end-2 top-2 z-[999]"
                    icon="o-x-mark"
                    type="button"
                    onclick="document.getElementById('{{ $id }}').close()"
                    aria-label="Close modal"
                />
            @else
                <x-artisanpack-button
                    class="btn-circle btn-sm btn-ghost absolute end-2 top-2 z-[999]"
                    icon="o-x-mark"
                    type="button"
                    @click="$wire.{{ $attributes->wire('model')->value() }} = false"
                    aria-label="Close modal"
                />
            @endif
        @endif

        @if($title)
            <x-artisanpack-header
                :title="$title"
                :subtitle="$subtitle"
                size="text-xl"
                :separator="$separator"
                class="!mb-5"
                :id="$uuid . '-title'"
            />
        @endif

        <div id="{{ $uuid }}-content">
            {{ $slot }}
        </div>

        @if($separator && $actions)
            <hr class="border-t-[length:var(--border)] border-base-content/10 mt-5" />
        @endif

        @if($actions)
            <div class="modal-action">
                {{ $actions }}
            </div>
        @endif
    </div>

    @if(!$persistent)
        <div class="modal-backdrop" aria-hidden="true">
            @if ($id)
                <button type="button" onclick="document.getElementById('{{ $id }}').close()" aria-label="Close modal">close</button>
            @else
                <button @click="$wire.{{ $attributes->wire('model')->value() }} = false" type="button" aria-label="Close modal">close</button>
            @endif
        </div>
    @endif
</dialog>
