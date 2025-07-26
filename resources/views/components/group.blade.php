<div>
    <fieldset class="fieldset py-0">
        {{-- STANDARD LABEL --}}
        @if($label)
            <legend class="fieldset-legend mb-0.5">
                {{ $label }}

                @if($attributes->get('required'))
                    <span class="text-error">*</span>
                @endif
            </legend>
        @endif

        <div class="join">
            @foreach ($options as $option)
                <input
                    type="radio"
                    name="{{ $modelName() }}"
                    value="{{ data_get($option, $optionValue) }}"
                    aria-label="{{ data_get($option, $optionLabel) }}"
                    @if(data_get($option, 'disabled')) disabled @endif

                    {{ $attributes->whereStartsWith('wire:model') }}
                    {{
                        $attributes->class([
                            "join-item btn [&:checked]:btn-neutral",
                            "!border-l-base-100" => data_get($option, 'disabled')
                        ])
                    }}
                />
            @endforeach
        </div>

        {{-- ERROR --}}
        @if(!$omitError && $errors->has($errorFieldName()))
            @foreach($errors->get($errorFieldName()) as $message)
                @foreach(\Illuminate\Support\Arr::wrap($message) as $line)
                    <div class="{{ $errorClass }}" x-class="text-error">{{ $line }}</div>
                    @break($firstErrorOnly)
                @endforeach
                @break($firstErrorOnly)
            @endforeach
        @endif

        {{-- HINT --}}
        @if($hint)
            <div class="{{ $hintClass }}" x-classes="fieldset-label">{{ $hint }}</div>
        @endif
    </fieldset>
</div>
