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
    class="{{ $getTabsContainerClass() }}"
    @if($isVertical())
        aria-orientation="vertical"
    @endif
    x-class="font-semibold pb-1 border-b-[length:var(--border)] border-b-base-content/50 border-b-base-content/10 flex overflow-x-auto scrollbar-hide relative w-full"
>
    @if($isVerticalRight())
        <!-- CONTENT FIRST for right-side tabs -->
        <div role="tablist" {{ $attributes->except(['wire:model', 'wire:model.live'])->class([$verticalContentClass]) }}>
            {{ $slot }}
        </div>
    @endif

    <!-- TAB LABELS -->
    <div class="{{ $getLabelDivClass() }}" role="tablist">
        <template x-for="tab in tabs">
            <a
                role="tab"
                x-html="tab.label"
                 @click="tab.disabled ? null: selected = tab.name"
                :class="{ '{{ $getActiveClass() }} tab-active': selected === tab.name, 'hidden': tab.hidden }"
                class="tab {{ $getLabelClass() }}"></a>
        </template>
    </div>

    @if(!$isVerticalRight())
        <!-- CONTENT AFTER for left-side tabs or horizontal tabs -->
        <div role="tablist" {{ $attributes->except(['wire:model', 'wire:model.live'])->class($isVertical() ? [$verticalContentClass] : ["block"]) }}>
            {{ $slot }}
        </div>
    @endif
</div>
