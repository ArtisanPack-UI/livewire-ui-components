<?php
/**
 * Pin
 *
 * This file contains the Pin class for the ArtisanPack UI Livewire UI Components package.
 *
 * @package    ArtisanPack\LivewireUiComponents\View
 * @subpackage Components
 * @author     Jacob Martella
 * @copyright  2023 Jacob Martella
 * @license    MIT
 * @link       https://github.com/robsontenorio/mary Original MaryUI Repository
 * @link       https://gitlab.com/jacob-martella-web-design/artisanpack-ui/livewire-ui-components
 * @since      1.0.0
 */


namespace ArtisanPack\LivewireUiComponents\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
/**
 * Pin Class
 *
 * Provides functionality for the Pin component.
 *
 * @since 1.0.0
 */

class Pin extends Component
{
    public string $uuid;

    public function __construct(
        public int $size,
        public ?string $id = null,
        public ?bool $numeric = false,
        public ?bool $hide = false,
        public ?string $hideType = "disc",

        // Validations
        public ?string $errorField = null,
        public ?string $errorClass = 'text-error text-xs pt-2',
        public ?bool $omitError = false,
        public ?bool $firstErrorOnly = false,

    ) {
        $this->uuid = "artisanpack" . md5(serialize($this)) . $id;
    }

    public function modelName(): ?string
    {
        return $this->attributes->whereStartsWith('wire:model')->first();
    }

    public function errorFieldName(): ?string
    {
        return $this->errorField ?? $this->modelName();
    }

    public function render(): View|Closure|string
    {
        return <<<'HTML'
                <div>
                    <div
                        x-data="{
                                value: @entangle($attributes->wire('model')),
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
            HTML;
    }
}
