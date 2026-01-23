<a
        class="hidden tab"
        :class="{ 'tab-active': selected === '{{ $name }}' }"
        data-name="{{ $name }}"
        data-tab-component="true"
        x-init="
            const newItem = { name: '{{ $name }}', label: {{ json_encode($tabLabel($label)) }}, disabled: {{ $disabled ? 'true' : 'false' }}, hidden: {{ $hidden ? 'true' : 'false' }} };
            const index = tabs.findIndex(item => item.name === '{{ $name }}');
            index !== -1 ? tabs[index] = newItem : tabs.push(newItem);
        "
></a>

<div x-show="selected === '{{ $name }}'" role="tabpanel" {{ $attributes->class(['tab-content', $contentClasses ?? 'py-5 px-1']) }}>
    {{ $slot }}
</div>
