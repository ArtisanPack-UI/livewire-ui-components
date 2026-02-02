<div
        x-data="{
            tabs: [],
            selected:
                @if($selected)
                    '{{ $selected }}'
                @else
                    $wire.entangle('{{ $attributes->wire('model')->value() }}')
                @endif
             ,
             morphRemovedCleanup: null,
             _onNavigating: null,
             init() {
                 // Fix weird issue when navigating back
                 // Use a named handler scoped to this component's tabs only
                 const self = this;
                 this._onNavigating = () => {
                     self.$el.querySelectorAll('.tab').forEach(el => el.remove());
                 };
                 document.addEventListener('livewire:navigating', this._onNavigating);

                 // Register a single morph.removing hook to handle dynamic tab removal
                 // Use 'morph.removing' (not 'morph.removed') so contains() check works before detachment
                 // This works with both Livewire 3 and Livewire 4
                 this.morphRemovedCleanup = Livewire.hook('morph.removing', ({ el, component }) => {
                     // Check if the element being removed is a tab component within this instance
                     if (el && el.hasAttribute && el.hasAttribute('data-tab-component')) {
                         // Verify the element belongs to this component (must check before detachment)
                         if (!self.$el.contains(el)) {
                             return;
                         }
                         const tabName = el.getAttribute('data-name');
                         if (tabName) {
                             self.tabs = self.tabs.filter(tab => tab.name !== tabName);
                             // If the removed tab was selected, select the first available tab
                             if (self.selected === tabName && self.tabs.length > 0) {
                                 const firstVisibleTab = self.tabs.find(tab => !tab.hidden && !tab.disabled);
                                 if (firstVisibleTab) {
                                     self.selected = firstVisibleTab.name;
                                 }
                             }
                         }
                     }
                 });
             },
             destroy() {
                 // Clean up the navigating event listener
                 if (this._onNavigating) {
                     document.removeEventListener('livewire:navigating', this._onNavigating);
                     this._onNavigating = null;
                 }
                 // Clean up the hook when the component is destroyed
                 if (this.morphRemovedCleanup) {
                     this.morphRemovedCleanup();
                     this.morphRemovedCleanup = null;
                 }
             }
    }"
        class="{{ $getTabsContainerClass() }}"
        @if($isVertical())
            aria-orientation="vertical"
        @endif
        x-on:livewire:navigating.window="destroy()"
>
    @if($isVerticalRight())
        <div role="tablist" {{ $attributes->except(['wire:model', 'wire:model.live'])->class([$verticalContentClass]) }}>
            {{ $slot }}
        </div>
    @endif

    <div
        @class([
            $getLabelDivClass(),
            $glassClasses() => $glass,
        ])
        @if($glassStyle())
            style="{{ $glassStyle() }}"
        @endif
        role="tablist"
    >
        <template x-for="tab in tabs">
            <a
                    role="tab"
                    x-html="tab.label"
                    @click="tab.disabled ? null: selected = tab.name"
                    :class="{ '{{ $getActiveClass() }} tab-active': selected === tab.name, 'hidden': tab.hidden }"
                    class="tab {{ $getFinalLabelClass() }}"></a>
        </template>
    </div>

    @if(!$isVerticalRight())
        <div role="tablist" {{ $attributes->except(['wire:model', 'wire:model.live'])->class($isVertical() ? [$verticalContentClass] : ["block"]) }}>
            {{ $slot }}
        </div>
    @endif
</div>
