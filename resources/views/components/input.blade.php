<div @click.stop class="w-full">
    @php
        // We need this extra step to support models arrays. Ex: wire:model="emails.0"  , wire:model="emails.1"
        $uuid = $uuid . $modelName()
    @endphp

    <fieldset class="fieldset py-0 gap-0">
        {{--
          ***** SEMANTIC FIX *****
          Changed from <legend> to <label> as requested.
          This is now the one true label for the input.
        --}}
        @if($label && !$inline)
            <label for="{{ $uuid }}" class="fieldset-legend mb-0.5">
                {{ $label }}

                @if($attributes->get('required'))
                    <span class="text-error">*</span>
                @endif
            </label>
        @endif

        {{--
          ***** PREVIOUS FIX *****
          This wrapper was changed from <label> to <div>.
          This stops the click event from bubbling up and blurring the input.
        --}}
        <div @class(["floating-label" => $label && $inline])>
            {{-- FLOATING LABEL (for inline variant) --}}
            @if ($label && $inline)
                {{--
                  This also needs to be a <label for> to be accessible.
                --}}
                <label for="{{ $uuid }}" class="font-semibold">{{ $label }}</label>
            @endif

            <div @class(["w-full", "join" => $prepend || $append])>
                {{-- PREPEND --}}
                @if($prepend)
                    {{ $prepend }}
                @endif

                {{--
                  ***** THE FINAL FIX IS HERE *****
                  @mousedown.stop prevents the click event from bubbling up
                  to the drag-and-drop library, stopping the focus-steal.
                --}}
                <div
                    @click.outside="$event.stopPropagation()"
                @if($isDisabled())
                    disabled
                    @endif

                    {{
						$attributes->whereStartsWith('class')->class([
							"input w-full",
							"join-item" => $prepend || $append,
							"border-dashed" => $isReadonly(),
							"!input-error" => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError
						])
					}}
                >
                    {{-- PREFIX --}}
                    @if($prefix)
                        <span class="label">{{ $prefix }}</span>
                    @endif

                    {{-- ICON LEFT --}}
                    @if($icon)
                        <x-artisanpack-icon :name="$icon" class="pointer-events-none w-4 h-4 opacity-40" />
                    @endif

                    {{--
                      Restructured @if($money) block (from previous fix)
                    --}}
                    @if($money)
                        <div
                            class="w-full"
                            x-data="{ amount: $wire.get('{{ $modelName() }}') }" x-init="$nextTick(() => new Currency($refs->myInput, {{ $moneySettings() }}))"
                        >
                            {{-- INPUT (Money Version) --}}
                            <input
                                id="{{ $uuid }}"
                                placeholder="{{ $attributes->get('placeholder') }} "
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
                                x-ref="myInput"
                                :value="amount"
                                x-on:input="$nextTick(() => $wire.set('{{ $modelName() }}', Currency.getUnmasked(), {{ json_encode($attributes->wire('model')->hasModifier('live')) }}))"
                                x-on:blur="$nextTick(() => $wire.set('{{ $modelName() }}', Currency.getUnmasked(), {{ json_encode($attributes->wire('model')->hasModifier('blur')) }}))"
                                inputmode="numeric"
                                @mousedown="$nextTick(() => $el.focus())"
                                {{
									$attributes
										->merge(['type' => 'text'])
										->except(['wire:model', 'wire:model.live', 'wire:model.blur'])
										->except('class')
										->class('grow')
								}}
                            />
                            {{-- HIDDEN MONEY INPUT --}}
                            <input type="hidden" {{ $attributes->wire('model') }} />
                        </div>
                    @else
                        {{-- INPUT (Normal Version) --}}
                        <input
                            id="{{ $uuid }}"
                            placeholder="{{ $attributes->get('placeholder') }} "
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
                            @mousedown="$nextTick(() => $el.focus())"
                            {{
								$attributes
									->merge(['type' => 'text'])
									->except('class')
									->class('grow')
							}}
                        />
                    @endif
                    {{-- ***** END OF @if($money) BLOCK ***** --}}


                    {{-- CLEAR ICON  --}}
                    @if($clearable)
                        <x-artisanpack-icon
                            x-on:click="$wire.set('{{ $modelName() }}', '', {{ json_encode($attributes->wire('model')->hasModifier('live')) }})"
                            name="o-x-mark"
                            class="cursor-pointer w-4 h-4 opacity-40"
                            role="button"
                            tabindex="0"
                            aria-label="Clear input"
                        />
                    @endif

                    {{-- ICON RIGHT --}}
                    @if($iconRight)
                        <x-artisanpack-icon :name="$iconRight" class="pointer-events-none w-4 h-4 opacity-40" />
                    @endif

                    {{-- SUFFIX --}}
                    @if($suffix)
                        <span class="label">{{ $suffix }}</span>
                    @endif
                </div> {{-- /div.input --}}

                {{-- APPEND --}}
                @if($append)
                    {{ $append }}
                @endif
            </div>
        </div> {{-- /div wrapper --}}

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