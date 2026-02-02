<dialog
    {{ $attributes->except('wire:model')->class(["modal"]) }}

    @if($id)
        id="{{ $id }}"
    @else
        x-data="{open: $wire.entangle('{{ $attributes->wire('model')->value() }}').live }"
        x-init="$watch('open', value => { if (!value){ $dispatch('close') }else{ $dispatch('open') } })"
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
    <div
        @class([
            'modal-box',
            $boxClass,
            $glassClasses() => $glass,
        ])

        @if($glassStyle())
            style="{{ $glassStyle() }}"
        @endif
    >
        @if(!$persistent)
            @if ($id)
                <x-artisanpack-button class="btn-circle btn-sm btn-ghost absolute end-2 top-2 z-[999]" icon="o-x-mark" type="button" onclick="document.getElementById('{{ $id }}').close()" tabindex="-1" />
            @else
                <x-artisanpack-button class="btn-circle btn-sm btn-ghost absolute end-2 top-2 z-[999]" icon="o-x-mark" type="button" @click="$wire.{{ $attributes->wire('model')->value() }} = false" tabindex="-1" />
            @endif
        @endif

        @if($title)
            <x-artisanpack-header :title="$title" :subtitle="$subtitle" size="text-xl" :separator="$separator" class="!mb-5" />
        @endif

        <div>
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
        <div class="modal-backdrop">
            @if ($id)
                <button type="button" onclick="document.getElementById('{{ $id }}').close()">close</button>
            @else
                <button @click="$wire.{{ $attributes->wire('model')->value() }} = false" type="button">close</button>
            @endif
        </div>
    @endif
</dialog>
