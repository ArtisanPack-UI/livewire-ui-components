@php
    // Wee need this extra step to support models arrays. Ex: wire:model="emails.0"  , wire:model="emails.1"
    $uuid = $uuid . $modelName()
@endphp

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

        {{--  EDITOR  --}}
        <div
            x-data="
                {
                    value: @entangle($attributes->wire('model')),
                    uploadUrl: '{{ $uploadUrl }}?disk={{ $disk }}&folder={{ $folder }}&_token={{ csrf_token() }}'
                }"
            x-init="
                tinymce.init({
                    {{ $setup() }},

                    @if($gplLicense)
                        license_key: 'gpl',
                    @endif

                    target: $refs.tinymce,
                    images_upload_url: uploadUrl,
                    readonly: {{ json_encode($attributes->get('readonly') || $attributes->get('disabled')) }},
                    skin: document.documentElement.getAttribute('class') == 'dark' ? 'oxide-dark' : 'oxide',
                    content_css: document.documentElement.getAttribute('class') == 'dark' ? 'dark' : 'default',

                    @if($attributes->get('disabled'))
                        content_style: 'body { opacity: 50% }',
                    @else
                        content_style: 'img { max-width: 100%; height: auto; }',
                    @endif

                    setup: function(editor) {
                        editor.on('keyup', (e) => value = editor.getContent())
                        editor.on('change', (e) => value = editor.getContent())
                        editor.on('undo', (e) => value = editor.getContent())
                        editor.on('redo', (e) => value = editor.getContent())
                        editor.on('init', () =>  editor.setContent(value ?? ''))
                        editor.on('OpenWindow', (e) => tinymce.activeEditor.topLevelWindow = e.dialog)

                        // Handles a case where people try to change contents on the fly from Livewire methods
                        $watch('value', function (newValue) {
                            if (newValue !== editor.getContent()) {
                                editor.resetContent(newValue || '');
                            }
                        })
                    },
                    file_picker_callback: function(cb, value, meta) {
                        const formData = new FormData()
                        const input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.click();

                        tinymce.activeEditor.topLevelWindow.block('');

                        input.addEventListener('change', (e) => {
                            formData.append('file', e.target.files[0])
                            formData.append('_token', '{{ csrf_token() }}')

                            fetch(uploadUrl, { method: 'POST', body: formData })
                               .then(response => response.json())
                               .then(data => cb(data.location))
                               .catch((err) => console.error(err))
                               .finally(() => tinymce.activeEditor.topLevelWindow.unblock());
                        });
                    }
                })
            "
            x-on:livewire:navigating.window="tinymce.activeEditor.destroy();"
            wire:ignore
        >
            <input id="{{ $id ?? $uuid }}" x-ref="tinymce" type="textarea" {{ $attributes->whereDoesntStartWith('wire:model') }} />
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

<script src="{{ asset('vendor/artisanpack-ui/js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
