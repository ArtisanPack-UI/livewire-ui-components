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

        <label @class(["floating-label" => $label && $inline])>
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
                <label
                    {{
                        $attributes->whereStartsWith('class')->class([
                            "select w-full",
                            "join-item" => $prepend || $append,
                            "border-dashed" => $attributes->has("readonly") && $attributes->get("readonly") == true,
                            "!select-error" => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError
                        ])
                    }}
                 >

                    {{-- ICON LEFT --}}
                    @if($icon)
                        <x-artisanpack-icon :name="$icon" class="pointer-events-none w-4 h-4 -ml-1 opacity-40" />
                    @endif

                    {{-- SELECT --}}
                    <select id="{{ $uuid }}" {{ $attributes->whereDoesntStartWith('class') }}>
                        @if($placeholder)
                            <option value="{{ $placeholderValue }}">{{ $placeholder }}</option>
                        @endif

                        @foreach (array_keys($options) as $option)
                          <optgroup label="{{ $option }}">
                            @foreach($options[$option] as $opts)
                              <option value="{{ $opts[$optionValue] }}">{{ $opts[$optionLabel] }}</option>
                            @endforeach
                        @endforeach
                    </select>

                    {{-- ICON RIGHT --}}
                    @if($iconRight)
                        <x-artisanpack-icon :name="$iconRight" class="pointer-events-none w-4 h-4 opacity-40" />
                    @endif
                </label>

                {{-- APPEND --}}
                @if($append)
                    {{ $append }}
                @endif
            </div>
        </label>

        {{-- HINT --}}
        @if($hint)
            <div class="{{ $hintClass }}" x-classes="fieldset-label">{{ $hint }}</div>
        @endif

        {{-- ERROR --}}
        @if(!$omitError && $errors->has($errorFieldName()))
            @foreach($errors->get($errorFieldName()) as $message)
                @foreach(Arr::wrap($message) as $line)
                    <div class="{{ $errorClass }}" x-class="text-error">{{ $line }}</div>
                    @break($firstErrorOnly)
                @endforeach
                @break($firstErrorOnly)
            @endforeach
        @endif
    </fieldset>
</div>
