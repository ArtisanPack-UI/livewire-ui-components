<div>
    <div
        x-data="{
                value: $wire.entangle($attributes->wire('model')),
                inputs: [],
                init() {
                    // Copy & Paste
                    document.getElementById('pin{{ $uuid }}').addEventListener('paste', (e) => {
                        const paste = (e.clipboardData || window.clipboardData).getData('text');

                         for (var i = 0; i < {{ $size }}; i++) {
                            this.inputs[i] = paste[i];
                        }

                        e.preventDefault()
                        this.handlePin()
                    })
                },
                next(el) {
                    this.handlePin()

                    if (el.value.length == 0) {
                        return
                    }

                    if (el.nextElementSibling) {
                        el.nextElementSibling.focus()
                        el.nextElementSibling.select()
                    }
                },
                remove(el, i) {
                    this.inputs[i] = ''
                    this.handlePin()

                    if (el.previousElementSibling) {
                        el.previousElementSibling.focus()
                        el.previousElementSibling.select()
                    }
                },
                handlePin() {
                    this.value = this.inputs.join('')

                    this.value.length === {{ $size }}
                        ? this.$dispatch('completed', this.value)
                        : this.$dispatch('incomplete', this.value)
                }
        }"
    >
        <div class="flex gap-3" id="pin{{ $uuid }}">
            @foreach(range(0, $size - 1) as $i)
                <input
                    @style([
                        $hide ? "text-security: $hideType;
                                -webkit-text-security: $hideType;
                                -moz-text-security $hideType;
                                " : "",
                    ])
                    id="{{ $uuid }}-pin-{{ $i }}"
                    type="text"
                    maxlength="1"
                    x-model="inputs[{{ $i }}]"
                    @keydown.space.prevent
                    @keydown.backspace.prevent="remove($event.target, {{ $i }})"
                    @input="next($event.target)"
                    @if($numeric)
                        inputmode="numeric"
                        x-mask="9"
                    @endif
                    {{
                        $attributes->whereDoesntStartWith('wire')->class([
                            "input input-border !w-12 font-black text-xl text-center",
                            "!input-error" => $errorFieldName() && $errors->has($errorFieldName()) && !$omitError
                        ])
                    }}
                />
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
    </div>
</div>
