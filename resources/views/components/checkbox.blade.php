<div>
    <fieldset class="fieldset">
        <div class="w-full">
            @if($card)
                <label class="relative block">
                    <input
                        id="{{ $uuid }}"
                        type="checkbox"
                        class="sr-only peer"
                        @checked($value == true || $value === 1 || $value === '1') {{-- [CHANGED] Added @checked --}}
                        {{
                            $attributes->whereDoesntStartWith(["id", "checked"])
                         }}
                    />
                    <div class="{{ $cardClass }} peer-checked:border-primary peer-checked:bg-primary/10 peer-disabled:opacity-50 peer-disabled:cursor-not-allowed">
                        <div class="card-body">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    {{-- LABEL --}}
                                    <h3 class="text-sm font-medium text-base-content">
                                        {{ $label }}

                                        @if($attributes->get('required'))
                                            <span class="text-error">*</span>
                                        @endif
                                    </h3>

                                    {{-- HINT --}}
                                    @if($hint)
                                        <p class="{{ $hintClass }} mt-1 text-secondary" x-classes="fieldset-label">{{ $hint }}</p>
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
                <label @class(["flex gap-3 items-center cursor-pointer", "justify-between" => $right, "!items-start" => $hint])>

                    {{-- CHECKBOX --}}
                    <input
                        id="{{ $uuid }}"
                        type="checkbox"
                        @checked($value == true || $value === 1 || $value === '1') {{-- [CHANGED] Added @checked --}}
                        {{
                            $attributes->whereDoesntStartWith(["id", "checked"])
                                ->class(["order-2" => $right])
                                ->merge(["class" => "checkbox"])
                         }}
                    />

                    {{-- LABEL --}}
                    <div @class(["order-1" => $right])>
                        <div class="text-sm font-medium">
                            {{ $label }}

                            @if($attributes->get('required'))
                                <span class="text-error">*</span>
                            @endif
                        </div>

                        {{-- HINT --}}
                        @if($hint)
                            <div class="{{ $hintClass }}" x-classes="fieldset-label">{{ $hint }}</div>
                        @endif
                    </div>
                </label>
            @endif
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
    </fieldset>
</div>