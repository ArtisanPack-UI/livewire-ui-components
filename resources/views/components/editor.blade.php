@php
    $id = $id ?? 'editor-' . uniqid();
    $wireModel = $attributes->whereStartsWith('wire:model')->first();
    $required = $required ?? false;
@endphp

<div class="w-full"
     x-data="tinymceEditor('{{ $id }}', '{{ $wireModel }}')"
     x-init="initEditor()"
     wire:ignore>

    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea
            id="{{ $id }}"
            {{ $attributes->whereDoesntStartWith('wire:model') }}
            class="w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
            @if($wireModel) wire:model="{{ $wireModel }}" @endif
            style="visibility: hidden;"
    >{{ $slot }}</textarea>

    @error($wireModel)
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
