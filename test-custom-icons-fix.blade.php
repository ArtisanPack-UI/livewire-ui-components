<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Icons Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="p-8 bg-gray-100">
    <h1 class="text-2xl font-bold mb-6">Custom Icons Test</h1>
    
    <div class="space-y-8">
        <!-- Test 1: Accordion with custom icons -->
        <div>
            <h2 class="text-lg font-semibold mb-4">Test 1: Accordion with custom icons</h2>
            <x-artisanpack-accordion 
                wire:model="accordion1" 
                custom-open-icon="minus" 
                custom-closed-icon="plus"
            >
                <x-artisanpack-collapse name="item1">
                    <x-slot name="heading">Item 1 with accordion custom icons</x-slot>
                    <x-slot name="content">Content for item 1</x-slot>
                </x-artisanpack-collapse>
                
                <x-artisanpack-collapse name="item2">
                    <x-slot name="heading">Item 2 with accordion custom icons</x-slot>
                    <x-slot name="content">Content for item 2</x-slot>
                </x-artisanpack-collapse>
            </x-artisanpack-accordion>
        </div>

        <!-- Test 2: Standalone collapse with custom icons -->
        <div>
            <h2 class="text-lg font-semibold mb-4">Test 2: Standalone collapse with custom icons</h2>
            <x-artisanpack-collapse 
                custom-open-icon="chevron-up" 
                custom-closed-icon="chevron-down"
            >
                <x-slot name="heading">Standalone collapse with custom icons</x-slot>
                <x-slot name="content">This is standalone collapse content</x-slot>
            </x-artisanpack-collapse>
        </div>

        <!-- Test 3: Accordion with custom icons, but collapse overrides them -->
        <div>
            <h2 class="text-lg font-semibold mb-4">Test 3: Accordion with custom icons, collapse overrides</h2>
            <x-artisanpack-accordion 
                wire:model="accordion3" 
                custom-open-icon="minus" 
                custom-closed-icon="plus"
            >
                <x-artisanpack-collapse 
                    name="item1"
                    custom-open-icon="star" 
                    custom-closed-icon="heart"
                >
                    <x-slot name="heading">Item 1 - collapse overrides accordion icons</x-slot>
                    <x-slot name="content">This should show star/heart icons (collapse override)</x-slot>
                </x-artisanpack-collapse>
                
                <x-artisanpack-collapse name="item2">
                    <x-slot name="heading">Item 2 - uses accordion icons</x-slot>
                    <x-slot name="content">This should show minus/plus icons (from accordion)</x-slot>
                </x-artisanpack-collapse>
            </x-artisanpack-accordion>
        </div>

        <!-- Test 4: Default behavior (no custom icons) -->
        <div>
            <h2 class="text-lg font-semibold mb-4">Test 4: Default behavior (no custom icons)</h2>
            <x-artisanpack-accordion wire:model="accordion4">
                <x-artisanpack-collapse name="item1">
                    <x-slot name="heading">Item 1 - default icons</x-slot>
                    <x-slot name="content">Should use default plus/minus or arrow behavior</x-slot>
                </x-artisanpack-collapse>
            </x-artisanpack-accordion>
        </div>
    </div>

    <script>
        // Mock Livewire for testing purposes
        window.Livewire = {
            find: () => ({
                entangle: (key) => ({
                    get: () => null,
                    set: () => {},
                    on: () => {}
                })
            })
        };
    </script>
</body>
</html>