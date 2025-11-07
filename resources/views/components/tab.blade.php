<a
        class="hidden tab"
        :class="{ 'tab-active': selected === '{{ $name }}' }"
        data-name="{{ $name }}"
        x-init="
            const newItem = { name: '{{ $name }}', label: {{ json_encode($tabLabel($label)) }}, disabled: {{ $disabled ? 'true' : 'false' }}, hidden: {{ $hidden ? 'true' : 'false' }} };
            const index = tabs.findIndex(item => item.name === '{{ $name }}');
            index !== -1 ? tabs[index] = newItem : tabs.push(newItem);

            Livewire.hook('morph.removed', ({el}) => {
                if (el.getAttribute('data-name') == '{{ $name }}'){
                    tabs = tabs.filter(i => i.name !== '{{ $name }}')
                }
            })
        "
></a>

<div
    x-show="selected === '{{ $name }}'"
    role="tabpanel"
    id="tabpanel-{{ $name }}"
    aria-labelledby="tab-{{ $name }}"
    :tabindex="selected === '{{ $name }}' ? '0' : '-1'"
    {{ $attributes->class(['tab-content', $contentClasses ?? 'py-5 px-1']) }}
>
    {{ $slot }}
</div>
