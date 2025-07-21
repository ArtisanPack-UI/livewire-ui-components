<div wire:key="calendar-{{ rand() }}">
    <div x-data x-init="const calendar = new VanillaCalendar($el, {{ $setup() }}); calendar.init();" class="w-fit"></div>
</div>