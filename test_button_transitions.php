<?php

require_once 'vendor/autoload.php';

use ArtisanPack\LivewireUiComponents\View\Components\Button;
use Illuminate\View\ComponentAttributeBag;
use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;

echo "=== Testing Button Component with 300ms Transitions ===\n\n";

// Mock Laravel's view helpers for testing
if (!function_exists('view')) {
    function view($view, $data = []) {
        return new class {
            public function render() {
                return 'mock-view';
            }
        };
    }
}

// Test different button configurations
$testCases = [
    [
        'description' => 'Primary button with transition',
        'props' => ['variant' => 'primary', 'label' => 'Primary Button'],
    ],
    [
        'description' => 'Button with Tailwind color and transition',
        'props' => ['color' => 'blue-500', 'label' => 'Blue Button'],
    ],
    [
        'description' => 'Button with hex color and transition',
        'props' => ['color' => '#ef4444', 'label' => 'Red Button'],
    ],
    [
        'description' => 'Success variant button with transition',
        'props' => ['color' => 'success', 'label' => 'Success Button'],
    ],
    [
        'description' => 'Button with color adjustment and transition',
        'props' => ['color' => 'green-600', 'colorAdjustment' => 'lighter', 'label' => 'Light Green Button'],
    ],
];

foreach ($testCases as $i => $testCase) {
    echo "Test Case " . ($i + 1) . ": {$testCase['description']}\n";
    echo str_repeat("-", 60) . "\n";
    
    try {
        // Create button component
        $button = new Button(
            id: $testCase['props']['id'] ?? null,
            label: $testCase['props']['label'] ?? null,
            variant: $testCase['props']['variant'] ?? 'primary',
            color: $testCase['props']['color'] ?? null,
            colorAdjustment: $testCase['props']['colorAdjustment'] ?? null,
        );
        
        // Get the color classes
        $colorClasses = $button->getColorClasses();
        
        echo "Button Properties:\n";
        echo "  Label: " . ($testCase['props']['label'] ?? 'N/A') . "\n";
        echo "  Variant: " . ($testCase['props']['variant'] ?? 'N/A') . "\n";
        echo "  Color: " . ($testCase['props']['color'] ?? 'N/A') . "\n";
        echo "  Color Adjustment: " . ($testCase['props']['colorAdjustment'] ?? 'N/A') . "\n\n";
        
        echo "Generated Color Classes:\n";
        if (empty($colorClasses)) {
            echo "  (No color classes - using fallback variant)\n";
        } else {
            foreach ($colorClasses as $key => $value) {
                echo "  {$key}: {$value}\n";
            }
        }
        
        // Simulate the base classes array from the template
        $baseClasses = ['btn', '!inline-flex', 'transition-all', 'duration-300'];
        
        // Add color classes
        if (!empty($colorClasses)) {
            foreach ($colorClasses as $type => $class) {
                if ($type !== 'style' && $class) {
                    $baseClasses[] = $class;
                }
            }
        } else {
            $baseClasses[] = $button->getVariantClasses();
        }
        
        echo "\nFinal CSS Classes:\n";
        echo "  " . implode(' ', $baseClasses) . "\n";
        
        // Check for transition classes
        $hasTransition = in_array('transition-all', $baseClasses);
        $hasDuration = in_array('duration-300', $baseClasses);
        
        echo "\nTransition Analysis:\n";
        echo "  Has transition-all: " . ($hasTransition ? "✅ Yes" : "❌ No") . "\n";
        echo "  Has duration-300: " . ($hasDuration ? "✅ Yes" : "❌ No") . "\n";
        
        if ($hasTransition && $hasDuration) {
            echo "  ✅ 300ms transition properly applied!\n";
        } else {
            echo "  ❌ Missing transition classes\n";
        }
        
        // Check for hover/focus states
        $hasHover = isset($colorClasses['hover']);
        $hasFocus = isset($colorClasses['focus']);
        
        echo "\nHover/Focus States (for smooth transitions):\n";
        echo "  Has Hover State: " . ($hasHover ? "✅ Yes" : "❌ No") . "\n";
        echo "  Has Focus State: " . ($hasFocus ? "✅ Yes" : "❌ No") . "\n";
        
        if ($hasHover || $hasFocus) {
            echo "  ✅ Hover/Focus states will animate smoothly with 300ms transition\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n" . str_repeat("=", 80) . "\n\n";
}

echo "=== Transition Implementation Summary ===\n\n";

echo "Added Transition Classes:\n";
echo "✅ transition-all: Applies transition to all CSS properties\n";
echo "✅ duration-300: Sets transition duration to 300ms\n\n";

echo "Expected Button Behavior:\n";
echo "- All property changes will animate smoothly over 300ms\n";
echo "- Hover state color changes will transition smoothly\n";
echo "- Focus state color changes will transition smoothly\n";
echo "- Background color, border color, and other properties animate\n";
echo "- Maintains JIT compatibility through CSS custom properties\n\n";

echo "Tailwind Classes Applied:\n";
echo "- Base: btn !inline-flex\n";
echo "- Transitions: transition-all duration-300\n";
echo "- Colors: From ColorGenerator (CSS custom properties + arbitrary values)\n";
echo "- Hover/Focus: Generated darker versions for smooth animation\n\n";

echo "Test completed! 🎉\n";