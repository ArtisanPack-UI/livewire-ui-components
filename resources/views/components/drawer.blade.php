@php
    $uuid = 'drawer-' . $id();
@endphp

<div
    x-data="{
        open:
            @if($modelName()->value)
                @entangle($modelName())
            @else
                false
            @endif
        ,
        lastFocusedElement: null,
        close() {
            this.open = false
            $refs.checkbox.checked = false
            // Return focus to trigger element
            if (this.lastFocusedElement) {
                this.lastFocusedElement.focus();
            }
        }
    }"

    x-init="
        $watch('open', value => {
            if (!value) {
                $dispatch('close');
                // Return focus handled in close()
            } else {
                // Store last focused element
                lastFocusedElement = document.activeElement;
                $dispatch('open');
                // Focus first focusable element
                $nextTick(() => {
                    const drawer = $refs.drawerContent;
                    if (drawer) {
                        const firstFocusable = drawer.querySelector('button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex=\'-1\'])');
                        if (firstFocusable) {
                            firstFocusable.focus();
                        }
                    }
                });
            }
        })
    "

    @if($closeOnEscape)
        @keydown.window.escape="close()"
    @endif

    @if(!$withoutTrapFocus)
        x-trap="open" x-bind:inert="!open"
    @endif

    {{-- ADD: Dynamic event listeners based on props --}}
    @if($openOn)
        x-on:{{ $openOn }}.window="open = true"
    @endif

    @if($closeOn)
        x-on:{{ $closeOn }}.window="close()"
    @endif

    @class(["drawer absolute z-50", "drawer-end" => $right])

    {{ $attributes->whereStartsWith('@') }}
>
    <input
        id="{{ $id() }}"
        x-model="open"
        x-ref="checkbox"
        type="checkbox"
        class="drawer-toggle"/>

    <div
        class="drawer-side"
        role="dialog"
        aria-modal="true"
        @if($title)
            aria-labelledby="{{ $uuid }}-title"
        @endif
        aria-describedby="{{ $uuid }}-content"
    >
        <label for="{{ $id() }}" class="drawer-overlay" aria-hidden="true"></label>

        <div x-ref="drawerContent">
            <x-artisanpack-card
                :title="$title"
                :subtitle="$subtitle"
                :separator="$separator"
                wire:key="drawer-card"
                {{ $attributes->except('wire:model')->class(['min-h-screen rounded-none px-8']) }}
                :id="$title ? $uuid . '-title' : null"
            >
                @if($withCloseButton)
                    <x-slot:menu>
                        <x-artisanpack-button
                            icon="o-x-mark"
                            class="btn-ghost btn-sm btn-circle"
                            @click="close()"
                            aria-label="Close drawer"
                            type="button"
                        />
                    </x-slot:menu>
                @endif

                <div id="{{ $uuid }}-content">
                    {{ $slot }}
                </div>

                @if($actions)
                    <x-slot:actions>
                        {{ $actions }}
                    </x-slot:actions>
                @endif
            </x-artisanpack-card>
        </div>
    </div>
</div>
