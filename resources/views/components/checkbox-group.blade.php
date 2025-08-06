<div>
    <fieldset class="fieldset py-0">
        {{-- GROUP LABEL --}}
        @if($label)
            <legend class="fieldset-legend mb-2">
                {{ $label }}

                @if($attributes->get('required'))
                    <span class="text-error">*</span>
                @endif
            </legend>
        @endif

        <div @class(["gap-4 grid", "sm:flex sm:gap-6" => $horizontal])>
            @foreach ($options as $option)
                @if($card)
                    <label class="relative">
                        <input
                            type="checkbox"
                            name="{{ $modelName() }}[]"
                            value="{{ data_get($option, $optionValue) }}"
                            @if(data_get($option, 'disabled')) disabled @endif
                            class="sr-only peer"
                            {{ $attributes->whereStartsWith('wire:model') }}
                        />
                        <div class="{{ $cardClass }} peer-checked:border-primary peer-checked:bg-primary/10 peer-disabled:opacity-50 peer-disabled:cursor-not-allowed">
                            <div class="card-body">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        {{-- NAME --}}
                                        <h3 class="text-sm font-medium text-base-content">
                                            {{ data_get($option, $optionLabel) }}
                                        </h3>

                                        {{-- HINT --}}
                                        @if(data_get($option, $optionHint))
                                            <p class="{{ $hintClass }} mt-1 text-secondary" x-classes="fieldset-label">
                                                {{ data_get($option, $optionHint) }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="ml-3 flex h-5 items-center">
                                        {{-- Placeholder for visual alignment --}}
                                        <div class="h-4 w-4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Checkbox indicator positioned as peer sibling --}}
                        <div class="absolute top-4 right-4 h-4 w-4 rounded border-2 border-base-300 bg-base-100 peer-checked:border-primary peer-checked:bg-primary transition-colors flex items-center justify-center pointer-events-none">
                            <svg class="w-3 h-3 text-primary-content opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </label>
                @else
                    <label>
                        <div @class(["flex items-center gap-3 cursor-pointer", "!items-start" => data_get($option, $optionHint)])>
                            <input
                                type="checkbox"
                                name="{{ $modelName() }}[]"
                                value="{{ data_get($option, $optionValue) }}"
                                @if(data_get($option, 'disabled')) disabled @endif
                                {{ $attributes->whereStartsWith('wire:model') }}
                                {{ $attributes->class(["checkbox"]) }}
                            />

                            <div>
                                {{-- NAME --}}
                                <div class="text-sm font-medium">
                                    {{ data_get($option, $optionLabel) }}
                                </div>

                                {{-- HINT --}}
                                @if(data_get($option, $optionHint))
                                    <div class="{{ $hintClass }} mt-1" x-classes="fieldset-label">
                                        {{ data_get($option, $optionHint) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </label>
                @endif
            @endforeach
        </div>

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
