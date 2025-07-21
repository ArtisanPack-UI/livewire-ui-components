<form
    {{ $attributes->whereDoesntStartWith('class') }}
    {{ $attributes->class(['grid grid-flow-row auto-rows-min gap-3']) }}
>

    {{ $slot }}

    @if ($actions)
        @if(!$noSeparator)
            <hr class="border-t-[length:var(--border)] border-base-content/10 my-3" />
        @else
            <div></div>
        @endif

        <div {{ $actions->attributes->class(["flex justify-end gap-3"]) }}>
            {{ $actions}}
        </div>
    @endif
</form>
