<div
    x-data="{
        open:
            @if($modelName()->value)
                @entangle($modelName())
            @else
                false
            @endif
        ,
        close() {
            this.open = false
            $refs.checkbox.checked = false
        }
    }"

    x-init="$watch('open', value => { if (!value){ $dispatch('close') }else{ $dispatch('open') } })"

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

    <div class="drawer-side">
        <label for="{{ $id() }}" class="drawer-overlay"></label>

        <x-artisanpack-card
            :title="$title"
            :description="$subtitle"
            :surface="$glass ? 'glass' : 'default'"
            wire:key="drawer-card"
            {{ $attributes->except('wire:model')->class(['min-h-screen rounded-none']) }}
        >
            @if($withCloseButton)
                <x-slot:actions>
                    <x-artisanpack-button variant="ghost" color="neutral" size="sm" icon-only leading-icon="o-x-mark" :aria-label="__( 'Close' )" @click="close()"/>
                </x-slot:actions>
            @endif

            {{ $slot }}

            @if($actions)
                <x-slot:footer>
                    {{ $actions }}
                </x-slot:footer>
            @endif
        </x-artisanpack-card>
    </div>
</div>
