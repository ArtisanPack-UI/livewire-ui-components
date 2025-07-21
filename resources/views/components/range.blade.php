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

        {{-- RANGE --}}
        <input
            type="range"
            min="{{ $min }}"
            max="{{ $max }}"
            {{ $attributes->merge(["class" => "range w-full", "id" => $uuid])->except('label', 'hint', 'min', 'max') }}
        />

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

        {{-- HINT --}}
        @if($hint)
            <div class="{{ $hintClass }}" x-classes="fieldset-label">{{ $hint }}</div>
        @endif
    </fieldset>
</div>
