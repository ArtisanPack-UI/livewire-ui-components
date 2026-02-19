<div
    x-data="{
        open:
            @if($modelName()->value)
                $wire.entangle('{{ $modelName()->value() }}')
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
            :subtitle="$subtitle"
            :separator="$separator"
            :glass="$glass"
            :glass-tint="$glassTint"
            :glass-tint-opacity="$glassTintOpacity"
            wire:key="drawer-card"
            {{ $attributes->except('wire:model')->class(['min-h-screen rounded-none px-8']) }}
        >
            @if($withCloseButton)
                <x-slot:menu>
                    <x-artisanpack-button icon="o-x-mark" class="btn-ghost btn-sm btn-circle" @click="close()"/>
                </x-slot:menu>
            @endif

            {{ $slot }}

            @if($actions)
                <x-slot:actions>
                    {{ $actions }}
                </x-slot:actions>
            @endif
        </x-artisanpack-card>
    </div>
</div>
