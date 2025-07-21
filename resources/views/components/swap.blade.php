<label
    for="{{ $uuid }}"
    {{ $attributes->whereDoesntStartWith('wire:model') }}>

    {{-- Before --}}
    @isset ($before)
        <div {{ $before->attributes }}>
            {{ $before }}
        </div>
    @endif

    <div class="swap">

        {{-- Hidden checkbox for state --}}
        <input id="{{ $uuid }}" type="checkbox" {{ $attributes->wire('model') }} />

        {{-- True Element --}}
        @isset ($true)
            <div {{ is_string($true) ? new Illuminate\View\ComponentAttributeBag(['class' => 'swap-on']) : $true->attributes->merge(['class' => 'swap-on']) }}>
                {{ $true ?? '' }}
            </div>
        @else
            <x-artisanpack-icon :name="$trueIcon" class="swap-on {{ $iconSize }}" />
        @endif

        {{-- False Element --}}
        @isset ($false)
        <div {{ is_string($false) ? new Illuminate\View\ComponentAttributeBag(['class' => 'swap-off']) : $false->attributes->merge(['class' => 'swap-off']) }}>
                {{ $false ?? '' }}
            </div>
        @else
            <x-artisanpack-icon :name="$falseIcon" class="swap-off {{ $iconSize }}" />
        @endif

    </div>

    {{-- After --}}
    @isset ($after)
        <div {{ $after->attributes }}>
            {{ $after }}
        </div>
    @endif
</label>
