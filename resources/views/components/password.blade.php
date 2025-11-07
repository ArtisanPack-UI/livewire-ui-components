<div>
    @php
        // We need this extra step to support models arrays. Ex: wire:model="emails.0"  , wire:model="emails.1"
        $uuid = $uuid . $modelName()
    @endphp

    <fieldset class="fieldset py-0">
        {{-- STANDARD LABEL --}}
        @if($label && !$inline)
            <legend class="fieldset-legend mb-0.5">
                {{ $label }}

                @if($attributes->get('required'))
                    <span class="text-error">*</span>
                @endif
            </legend>
        @endif

        <div @class(["floating-label" => $label && $inline])>
            {{-- FLOATING LABEL--}}
            @if ($label && $inline)
                <span class="font-semibold">{{ $label }}</span>
            @endif

            <div @class(["w-full", "join" => $prepend || $append])>
                {{-- PREPEND --}}
                @if($prepend)
                    {{ $prepend }}
                @endif

                {{-- THE LABEL THAT HOLDS THE INPUT --}}
                <div
                    x-data="{ hidden: true }"

                    {{
                        $attributes->whereStartsWith('class')->class([
                            "input w-full",
                            "join-item" => $prepend || $append,
                            "border-dashed" => $attributes->has("readonly") && $attributes->get("readonly") == true,
                            "!input-error" => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError
                        ])
                    }}
                 >
                    {{-- PREFIX --}}
                    @if($prefix)
                        <span class="label">{{ $prefix }}</span>
                    @endif

                    {{-- ICON LEFT / TOGGLE INPUT TYPE --}}
                    @if($icon)
                        <x-artisanpack-icon :name="$icon" class="pointer-events-none w-4 h-4 opacity-40" />
                    @elseif($placeToggleLeft())
                        <x-artisanpack-button
                            x-on:click="hidden = !hidden"
                            class="btn-ghost btn-xs btn-circle -m-1"
                            x-bind:aria-label="hidden ? 'Show password' : 'Hide password'"
                            type="button"
                        >
                            <x-artisanpack-icon name="{{ $passwordIcon }}" x-show="hidden" class="w-4 h-4 opacity-40" aria-hidden="true" />
                            <x-artisanpack-icon name="{{ $passwordVisibleIcon }}" x-show="!hidden" x-cloak class="w-4 h-4 opacity-40" aria-hidden="true" />
                        </x-artisanpack-button>
                    @endif

                    {{-- INPUT --}}
                    <input
                        id="{{ $uuid }}"
                        placeholder="{{ $attributes->get('placeholder') }} "
                        @if ($onlyPassword) type="password" @else x-bind:type="hidden ? 'password' : 'text'" @endif

                        @if($attributes->has('autofocus') && $attributes->get('autofocus') == true)
                            autofocus
                        @endif

                        @if($attributes->get('required'))
                            aria-required="true"
                        @endif
                        @if($errorFieldName() && $errors->has($errorFieldName()) && !$omitError)
                            aria-invalid="true"
                            aria-describedby="{{ $uuid }}-error @if($hint) {{ $uuid }}-hint @endif"
                        @elseif($hint)
                            aria-describedby="{{ $uuid }}-hint"
                        @endif

                        {{ $attributes->except('type')->merge() }}
                    />

                    {{-- CLEAR ICON  --}}
                    @if($clearable)
                        <x-artisanpack-icon x-on:click="$wire.set('{{ $modelName() }}', '', {{ json_encode($attributes->wire('model')->hasModifier('live')) }})"  name="o-x-mark" class="cursor-pointer w-4 h-4 opacity-40"/>
                    @endif

                    {{-- ICON RIGHT / TOGGLE INPUT TYPE --}}
                    @if($iconRight)
                        <x-artisanpack-icon :name="$iconRight" @class(["pointer-events-none w-4 h-4 opacity-40", "!end-10" => $clearable]) />
                    @elseif($placeToggleRight())
                        <x-artisanpack-button
                            x-on:click="hidden = !hidden"
                            @class(["btn-ghost btn-xs btn-circle -m-1", "!end-9" => $clearable])
                            x-bind:aria-label="hidden ? 'Show password' : 'Hide password'"
                            type="button"
                        >
                            <x-artisanpack-icon name="{{ $passwordIcon }}" x-show="hidden" class="w-4 h-4 opacity-40" aria-hidden="true" />
                            <x-artisanpack-icon name="{{ $passwordVisibleIcon }}" x-show="!hidden" x-cloak class="w-4 h-4 opacity-40" aria-hidden="true" />
                        </x-artisanpack-button>
                    @endif

                    {{-- SUFFIX --}}
                    @if($suffix)
                        <span class="label">{{ $suffix }}</span>
                    @endif
                </div>

                {{-- APPEND --}}
                @if($append)
                    {{ $append }}
                @endif
            </div>
        </div>

        {{-- HINT --}}
        @if($hint)
            <div id="{{ $uuid }}-hint" class="{{ $hintClass }}" x-classes="fieldset-label">{{ $hint }}</div>
        @endif

        {{-- ERROR --}}
        @if(!$omitError && $errors->has($errorFieldName()))
            <div id="{{ $uuid }}-error" role="alert" aria-live="polite">
                @foreach($errors->get($errorFieldName()) as $message)
                    @foreach(Arr::wrap($message) as $line)
                        <div class="{{ $errorClass }}" x-class="text-error">{{ $line }}</div>
                        @break($firstErrorOnly)
                    @endforeach
                    @break($firstErrorOnly)
                @endforeach
            </div>
        @endif
    </fieldset>
</div>
