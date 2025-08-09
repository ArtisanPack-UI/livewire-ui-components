<!DOCTYPE html>
<html>
<head>
    <title>Test Custom Icons for Accordion and Collapse</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
</head>
<body class="p-8">
    <h1 class="text-2xl font-bold mb-4">Testing Custom Icons for Accordion and Collapse Components</h1>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Test 1: Accordion with custom icons (propagated to children):</h2>
        <x-accordion wire:model="group1" custom-open-icon="minus" custom-closed-icon="plus">
            <x-collapse name="group1a">
                <x-slot:heading>Group 1A (inherited icons)</x-slot:heading>
                <x-slot:content>Content 1A - should show plus/minus icons from accordion</x-slot:content>
            </x-collapse>
            <x-collapse name="group1b">
                <x-slot:heading>Group 1B (inherited icons)</x-slot:heading>
                <x-slot:content>Content 1B - should show plus/minus icons from accordion</x-slot:content>
            </x-collapse>
        </x-accordion>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Test 2: Accordion with custom icons, child overrides:</h2>
        <x-accordion wire:model="group2" custom-open-icon="minus" custom-closed-icon="plus">
            <x-collapse name="group2a">
                <x-slot:heading>Group 2A (inherited icons)</x-slot:heading>
                <x-slot:content>Content 2A - should show plus/minus icons from accordion</x-slot:content>
            </x-collapse>
            <x-collapse name="group2b" custom-open-icon="arrow-down" custom-closed-icon="arrow-right">
                <x-slot:heading>Group 2B (local custom icons)</x-slot:heading>
                <x-slot:content>Content 2B - should show arrow icons (local override)</x-slot:content>
            </x-collapse>
        </x-accordion>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Test 3: Standalone collapse with custom icons:</h2>
        <x-collapse wire:model="standalone1" custom-open-icon="chevron-up" custom-closed-icon="chevron-down">
            <x-slot:heading>Standalone Collapse with Custom Icons</x-slot:heading>
            <x-slot:content>This standalone collapse should show custom chevron icons</x-slot:content>
        </x-collapse>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Test 4: Accordion without custom icons (default behavior):</h2>
        <x-accordion wire:model="group3">
            <x-collapse name="group3a">
                <x-slot:heading>Group 3A (default icons)</x-slot:heading>
                <x-slot:content>Content 3A - should show default CSS-based icons</x-slot:content>
            </x-collapse>
            <x-collapse name="group3b">
                <x-slot:heading>Group 3B (default icons)</x-slot:heading>
                <x-slot:content>Content 3B - should show default CSS-based icons</x-slot:content>
            </x-collapse>
        </x-accordion>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Test 5: Accordion with collapse-plus-minus and custom icons:</h2>
        <x-accordion wire:model="group4" collapse-plus-minus custom-open-icon="x-mark" custom-closed-icon="bars-3">
            <x-collapse name="group4a">
                <x-slot:heading>Group 4A (custom icons override plus-minus)</x-slot:heading>
                <x-slot:content>Content 4A - should show X and bars icons, not plus/minus</x-slot:content>
            </x-collapse>
        </x-accordion>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Test 6: Accordion with no-icon disabled:</h2>
        <x-accordion wire:model="group5" custom-open-icon="heart" custom-closed-icon="star">
            <x-collapse name="group5a" no-icon>
                <x-slot:heading>Group 5A (icons disabled)</x-slot:heading>
                <x-slot:content>Content 5A - should show no icons despite custom icons being set</x-slot:content>
            </x-collapse>
        </x-accordion>
    </div>

    @livewireScripts
    @livewire('livewire-ui-components')

    <script>
        // Initialize some test data
        window.addEventListener('DOMContentLoaded', function() {
            console.log('Custom icon tests loaded');
        });
    </script>
</body>
</html>
