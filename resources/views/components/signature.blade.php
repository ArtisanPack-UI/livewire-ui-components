<div>
    <div
        x-data="{
            value: $wire.entangle('{{ $attributes->wire('model')->value() }}'),
            signature: null,
            init() {
                let canvas = document.getElementById('{{ $uuid }}signature')
                this.signature = new SignaturePad(canvas, {{ $setup() }});

                // Resize
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                this.signature.fromData(this.signature.toData());

                // Event
                this.signature.addEventListener('endStroke', () =>  this.extract() );
            },
            extract() {
                this.value = this.signature.toDataURL();
            },
            clear() {
                this.signature.clear();
                this.value = null;
            }
         }"

         wire:ignore
         class="select-none touch-none block"
    >
        <div
            {{
                $attributes
                    ->except("wire:model")
                    ->class([
                        "border-[length:var(--border)] border-base-300 rounded-lg relative bg-white select-none touch-none block",
                        "!border-error" => $errors->has($modelName())
                    ])
            }}
        }>
            <canvas id="{{ $uuid }}signature" height="{{ $height }}" class="rounded-lg block w-full select-none touch-none"></canvas>

            <!-- CLEAR BUTTON -->
            <div class="absolute end-2 top-1/2 -translate-y-1/2 ">
                <x-artisanpack-button icon="o-backspace" :label="$clearText" @click="clear" class="{{$clearBtnStyle ?? 'btn-sm btn-ghost'}}" />
            </div>
        </div>
    </div>

    <!-- ERROR -->
    @if(!$omitError && $errors->has($errorFieldName()))
        @foreach($errors->get($errorFieldName()) as $message)
            @foreach(Arr::wrap($message) as $line)
                <div class="{{ $errorClass }}" x-classes="text-error text-xs pt-1">{{ $line }}</div>
                @break($firstErrorOnly)
            @endforeach
            @break($firstErrorOnly)
        @endforeach
    @endif

    <!-- HINT -->
    @if($hint)
        <div class="{{ $hintClass }}" x-classes="fieldset-label text-xs pt-1">{{ $hint }}</div>
    @endif
</div>
