<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Current Custom Icon Behavior</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="p-8">
    <h1 class="text-2xl font-bold mb-6">Current Custom Icon Behavior Test</h1>
    
    <!-- Test 1: Accordion with custom icons -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Accordion with Custom Icons</h2>
        <x-artisanpack-accordion 
            wire:model="accordionModel"
            custom-open-icon="minus"
            custom-closed-icon="plus"
        >
            <x-artisanpack-collapse name="item1">
                <x-slot:heading>Custom Icon Item 1</x-slot:heading>
                <x-slot:content>This accordion should use custom minus/plus icons</x-slot:content>
            </x-artisanpack-collapse>
            
            <x-artisanpack-collapse name="item2">
                <x-slot:heading>Custom Icon Item 2</x-slot:heading>
                <x-slot:content>This should also inherit the custom icons</x-slot:content>
            </x-artisanpack-collapse>
        </x-artisanpack-accordion>
    </div>

    <!-- Test 2: Individual collapse with custom icons -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Individual Collapse with Custom Icons</h2>
        <x-artisanpack-collapse 
            wire:model="collapseModel"
            custom-open-icon="chevron-up"
            custom-closed-icon="chevron-down"
        >
            <x-slot:heading>Individual Custom Icon Collapse</x-slot:heading>
            <x-slot:content>This collapse should use custom chevron icons</x-slot:content>
        </x-artisanpack-collapse>
    </div>

    <!-- Test 3: Regular accordion with plus/minus (no custom icons) -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Regular Accordion with Plus/Minus</h2>
        <x-artisanpack-accordion 
            wire:model="regularModel"
            collapse-plus-minus
        >
            <x-artisanpack-collapse name="regular1">
                <x-slot:heading>Regular Plus/Minus Item 1</x-slot:heading>
                <x-slot:content>This should use default plus/minus icons</x-slot:content>
            </x-artisanpack-collapse>
            
            <x-artisanpack-collapse name="regular2">
                <x-slot:heading>Regular Plus/Minus Item 2</x-slot:heading>
                <x-slot:content>This should also use default plus/minus icons</x-slot:content>
            </x-artisanpack-collapse>
        </x-artisanpack-accordion>
    </div>

    <!-- Test 4: Regular accordion with arrows (default) -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Regular Accordion with Arrows</h2>
        <x-artisanpack-accordion wire:model="arrowModel">
            <x-artisanpack-collapse name="arrow1">
                <x-slot:heading>Regular Arrow Item 1</x-slot:heading>
                <x-slot:content>This should use default arrow icons</x-slot:content>
            </x-artisanpack-collapse>
            
            <x-artisanpack-collapse name="arrow2">
                <x-slot:heading>Regular Arrow Item 2</x-slot:heading>
                <x-slot:content>This should also use default arrow icons</x-slot:content>
            </x-artisanpack-collapse>
        </x-artisanpack-accordion>
    </div>

    <script>
        // Simple Alpine.js data for testing
        document.addEventListener('alpine:init', () => {
            Alpine.data('testData', () => ({
                accordionModel: null,
                collapseModel: false,
                regularModel: null,
                arrowModel: null
            }));
        });
    </script>
</body>
</html>