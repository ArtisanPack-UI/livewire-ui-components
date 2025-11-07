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
             focusedIndex: 0,
             init() {
                 // Fix weird issue when navigating back
                 document.addEventListener('livewire:navigating', () => {
                     document.querySelectorAll('.tab').forEach(el =>  el.remove());
                 });
             },
             navigateTabs(direction) {
                 const tabElements = this.$el.querySelectorAll('[role=\"tab\"]:not(.hidden)');
                 if (tabElements.length === 0) return;

                 if (direction === 'next') {
                     this.focusedIndex = (this.focusedIndex + 1) % tabElements.length;
                 } else if (direction === 'prev') {
                     this.focusedIndex = this.focusedIndex <= 0 ? tabElements.length - 1 : this.focusedIndex - 1;
                 } else if (direction === 'first') {
                     this.focusedIndex = 0;
                 } else if (direction === 'last') {
                     this.focusedIndex = tabElements.length - 1;
                 }

                 const targetTab = tabElements[this.focusedIndex];
                 if (targetTab) {
                     targetTab.focus();
                     // Activate on focus (automatic activation)
                     const tabName = this.tabs[this.focusedIndex]?.name;
                     if (tabName && !this.tabs[this.focusedIndex]?.disabled) {
                         this.selected = tabName;
                     }
                 }
             }
    }"
        class="{{ $getTabsContainerClass() }}"
        @if($isVertical())
            aria-orientation="vertical"
        @endif
        x-class="font-semibold pb-1 border-b-[length:var(--border)] border-b-base-content/50 border-b-base-content/10 flex overflow-x-auto scrollbar-hide relative w-full"
>
    @if($isVerticalRight())
        <div role="tablist" {{ $attributes->except(['wire:model', 'wire:model.live'])->class([$verticalContentClass]) }}>
            {{ $slot }}
        </div>
    @endif

    <div
        class="{{ $getLabelDivClass() }}"
        role="tablist"
        @keydown.arrow-right.prevent="navigateTabs('next')"
        @keydown.arrow-left.prevent="navigateTabs('prev')"
        @keydown.home.prevent="navigateTabs('first')"
        @keydown.end.prevent="navigateTabs('last')"
    >
        <template x-for="(tab, index) in tabs" :key="tab.name">
            <a
                role="tab"
                x-html="tab.label"
                @click="tab.disabled ? null: selected = tab.name"
                :class="{ '{{ $getActiveClass() }} tab-active': selected === tab.name, 'hidden': tab.hidden }"
                :aria-selected="selected === tab.name ? 'true' : 'false'"
                :aria-controls="'tabpanel-' + tab.name"
                :tabindex="selected === tab.name ? '0' : '-1'"
                :aria-disabled="tab.disabled ? 'true' : 'false'"
                class="tab {{ $getFinalLabelClass() }}"
            ></a>
        </template>
    </div>

    @if(!$isVerticalRight())
        <div role="tablist" {{ $attributes->except(['wire:model', 'wire:model.live'])->class($isVertical() ? [$verticalContentClass] : ["block"]) }}>
            {{ $slot }}
        </div>
    @endif
</div>
