<div @click.stop>
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

            <div class="w-full">
                {{-- TEXTAREA --}}
                <textarea
                    placeholder="{{ $attributes->get('placeholder') }} "
                    @if($attributes->get('required'))
                        aria-required="true"
                    @endif
                    @if($errorFieldName() && $errors->has($errorFieldName()) && !$omitError)
                        aria-invalid="true"
                        aria-describedby="{{ $uuid }}-error @if($hint) {{ $uuid }}-hint @endif"
                    @elseif($hint)
                        aria-describedby="{{ $uuid }}-hint"
                    @endif
                   {{
                        $attributes->merge(['id' => $uuid])
                        ->class([
                            "textarea w-full",
                            "border-dashed" => $attributes->has("readonly") && $attributes->get("readonly") == true,
                            "!textarea-error" => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError
                        ])
                   }}
                >{{ $slot }}</textarea>
            </div>
        </label>

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

        {{-- HINT --}}
        @if($hint)
            <div id="{{ $uuid }}-hint" class="{{ $hintClass }}" x-classes="fieldset-label">{{ $hint }}</div>
        @endif
    </fieldset>
</div>
