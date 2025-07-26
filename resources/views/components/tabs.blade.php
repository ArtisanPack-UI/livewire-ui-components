<div
    x-data="{
            tabs: [],
            selected:
                @if($selected)
                    '{{ $selected }}'
                @else
                    @entangle($attributes->wire('model'))
                @endif
             ,
             init() {
                 // Fix weird issue when navigating back
                 document.addEventListener('livewire:navigating', () => {
                     document.querySelectorAll('.tab').forEach(el =>  el.remove());
                 });
             }
    }"
    class="{{ $tabsClass }}"
    x-class="font-semibold pb-1 border-b-[length:var(--border)] border-b-base-content/50 border-b-base-content/10 flex overflow-x-auto scrollbar-hide relative w-full"
>
    <!-- TAB LABELS -->
    <div class="{{ $labelDivClass }}">
        <template x-for="tab in tabs">
            <a
                role="tab"
                x-html="tab.label"
                 @click="tab.disabled ? null: selected = tab.name"
                :class="{ '{{ $activeClass }} tab-active': selected === tab.name, 'hidden': tab.hidden }"
                class="tab {{ $labelClass }}"></a>
        </template>
    </div>

    <!-- TAB CONTENT -->
    <div role="tablist" {{ $attributes->except(['wire:model', 'wire:model.live'])->class(["block"]) }}>
        {{ $slot }}
    </div>
</div>
