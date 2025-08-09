<!DOCTYPE html>
<html>
<head>
    <title>Test Accordion Prop Propagation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
</head>
<body class="p-8">
    <h1 class="text-2xl font-bold mb-4">Testing Accordion collapse-plus-minus Prop</h1>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">With collapse-plus-minus attribute:</h2>
        <x-accordion wire:model="group" collapse-plus-minus>
            <x-collapse name="group1">
                <x-slot:heading>Group 1</x-slot:heading>
                <x-slot:content>Hello 1</x-slot:content>
            </x-collapse>
            <x-collapse name="group2">
                <x-slot:heading>Group 2</x-slot:heading>
                <x-slot:content>Hello 2</x-slot:content>
            </x-collapse>
            <x-collapse name="group3">
                <x-slot:heading>Group 3</x-slot:heading>
                <x-slot:content>Hello 3</x-slot:content>
            </x-collapse>
        </x-accordion>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Without collapse-plus-minus attribute (default):</h2>
        <x-accordion wire:model="group2">
            <x-collapse name="group4">
                <x-slot:heading>Group 4</x-slot:heading>
                <x-slot:content>Hello 4</x-slot:content>
            </x-collapse>
            <x-collapse name="group5">
                <x-slot:heading>Group 5</x-slot:heading>
                <x-slot:content>Hello 5</x-slot:content>
            </x-collapse>
        </x-accordion>
    </div>

    @livewireScripts
    @livewire('livewire-ui-components')
</body>
</html>
