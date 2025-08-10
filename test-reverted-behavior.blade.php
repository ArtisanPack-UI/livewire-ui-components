<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Reverted Simple Icon Behavior</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="p-8" x-data="testData()">
    <h1 class="text-2xl font-bold mb-6">Reverted Simple Icon Behavior Test</h1>
    
    <!-- Test 1: Accordion with plus/minus icons -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Accordion with Plus/Minus Icons</h2>
        <x-artisanpack-accordion 
            wire:model="plusMinusModel"
            collapse-plus-minus
        >
            <x-artisanpack-collapse name="plus1">
                <x-slot:heading>Plus/Minus Item 1</x-slot:heading>
                <x-slot:content>This accordion should use plus/minus icons (DaisyUI collapse-plus class)</x-slot:content>
            </x-artisanpack-collapse>
            
            <x-artisanpack-collapse name="plus2">
                <x-slot:heading>Plus/Minus Item 2</x-slot:heading>
                <x-slot:content>This should also use plus/minus icons inherited from accordion</x-slot:content>
            </x-artisanpack-collapse>
        </x-artisanpack-accordion>
    </div>

    <!-- Test 2: Accordion with arrows (default) -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Accordion with Arrow Icons (Default)</h2>
        <x-artisanpack-accordion wire:model="arrowModel">
            <x-artisanpack-collapse name="arrow1">
                <x-slot:heading>Arrow Item 1</x-slot:heading>
                <x-slot:content>This accordion should use arrow icons (DaisyUI collapse-arrow class)</x-slot:content>
            </x-artisanpack-collapse>
            
            <x-artisanpack-collapse name="arrow2">
                <x-slot:heading>Arrow Item 2</x-slot:heading>
                <x-slot:content>This should also use arrow icons (default behavior)</x-slot:content>
            </x-artisanpack-collapse>
        </x-artisanpack-accordion>
    </div>

    <!-- Test 3: Individual collapse with plus/minus -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Individual Collapse with Plus/Minus</h2>
        <x-artisanpack-collapse 
            wire:model="individualPlusModel"
            collapse-plus-minus
        >
            <x-slot:heading>Individual Plus/Minus Collapse</x-slot:heading>
            <x-slot:content>This individual collapse should use plus/minus icons</x-slot:content>
        </x-artisanpack-collapse>
    </div>

    <!-- Test 4: Individual collapse with arrows (default) -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Individual Collapse with Arrows</h2>
        <x-artisanpack-collapse wire:model="individualArrowModel">
            <x-slot:heading>Individual Arrow Collapse</x-slot:heading>
            <x-slot:content>This individual collapse should use arrow icons (default)</x-slot:content>
        </x-artisanpack-collapse>
    </div>

    <!-- Test 5: Accordion with no icons -->
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Accordion with No Icons</h2>
        <x-artisanpack-accordion wire:model="noIconModel">
            <x-artisanpack-collapse name="noicon1" no-icon>
                <x-slot:heading>No Icon Item 1</x-slot:heading>
                <x-slot:content>This collapse should have no icons at all</x-slot:content>
            </x-artisanpack-collapse>
            
            <x-artisanpack-collapse name="noicon2" no-icon>
                <x-slot:heading>No Icon Item 2</x-slot:heading>
                <x-slot:content>This should also have no icons</x-slot:content>
            </x-artisanpack-collapse>
        </x-artisanpack-accordion>
    </div>

    <div class="mt-8 p-4 bg-base-200 rounded-lg">
        <h3 class="font-semibold mb-2">Expected Behavior:</h3>
        <ul class="list-disc list-inside text-sm space-y-1">
            <li><strong>Plus/Minus:</strong> Should show + when collapsed, - when expanded</li>
            <li><strong>Arrows:</strong> Should show > when collapsed, ∨ when expanded</li>
            <li><strong>No Icons:</strong> Should show no icons at all</li>
            <li>Icons are controlled by DaisyUI's collapse-plus and collapse-arrow classes</li>
            <li>No custom icon logic or Alpine.js conditionals</li>
        </ul>
    </div>

    <script>
        function testData() {
            return {
                plusMinusModel: null,
                arrowModel: null,
                individualPlusModel: false,
                individualArrowModel: false,
                noIconModel: null
            }
        }
    </script>
</body>
</html>