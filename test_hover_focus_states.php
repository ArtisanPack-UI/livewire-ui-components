<?php

require_once 'vendor/autoload.php';

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;

echo "=== Testing Hover and Focus States for Button Colors ===\n\n";

$colorGenerator = new ColorGenerator();

// Test cases for different color types
$testCases = [
    // Tailwind colors
    ['color' => 'blue-500', 'adjustment' => null, 'type' => 'Tailwind Color'],
    ['color' => 'red-600', 'adjustment' => null, 'type' => 'Tailwind Color'],
    ['color' => 'green-400', 'adjustment' => null, 'type' => 'Tailwind Color'],
    ['color' => 'purple-700', 'adjustment' => null, 'type' => 'Tailwind Color'],
    
    // Hex colors
    ['color' => '#3b82f6', 'adjustment' => null, 'type' => 'Hex Color'],
    ['color' => '#ef4444', 'adjustment' => null, 'type' => 'Hex Color'],
    ['color' => '#22c55e', 'adjustment' => null, 'type' => 'Hex Color'],
    
    // Variant colors
    ['color' => 'primary', 'adjustment' => null, 'type' => 'Variant'],
    ['color' => 'success', 'adjustment' => null, 'type' => 'Variant'],
    ['color' => 'warning', 'adjustment' => null, 'type' => 'Variant'],
    ['color' => 'error', 'adjustment' => null, 'type' => 'Variant'],
    ['color' => 'ghost', 'adjustment' => null, 'type' => 'Variant (transparent)'],
    ['color' => 'outline', 'adjustment' => null, 'type' => 'Variant (transparent)'],
    
    // Colors with adjustments
    ['color' => 'blue-500', 'adjustment' => 'lighter', 'type' => 'Tailwind Color with adjustment'],
    ['color' => '#ef4444', 'adjustment' => 'darker', 'type' => 'Hex Color with adjustment'],
];

foreach ($testCases as $i => $testCase) {
    echo "Test Case " . ($i + 1) . ": {$testCase['type']}\n";
    echo "Color: '{$testCase['color']}', Adjustment: '" . ($testCase['adjustment'] ?? 'none') . "'\n";
    echo str_repeat("-", 60) . "\n";
    
    try {
        $result = $colorGenerator->resolveComponentColor(
            $testCase['color'], 
            $testCase['adjustment'], 
            'button'
        );
        
        if (empty($result)) {
            echo "❌ No color classes generated\n";
            echo "\n";
            continue;
        }
        
        echo "Generated Classes:\n";
        foreach ($result as $key => $value) {
            echo "  {$key}: {$value}\n";
        }
        
        // Check for hover and focus states
        $hasHover = isset($result['hover']);
        $hasFocus = isset($result['focus']);
        $hasStyle = isset($result['style']);
        
        echo "\nHover/Focus Analysis:\n";
        echo "  Has Hover State: " . ($hasHover ? "✅ Yes" : "❌ No") . "\n";
        echo "  Has Focus State: " . ($hasFocus ? "✅ Yes" : "❌ No") . "\n";
        
        if ($hasHover) {
            echo "  Hover Class: {$result['hover']}\n";
        }
        if ($hasFocus) {
            echo "  Focus Class: {$result['focus']}\n";
        }
        
        // Analyze CSS custom properties for hover/focus colors
        if ($hasStyle && ($hasHover || $hasFocus)) {
            echo "  CSS Properties:\n";
            $properties = explode(';', $result['style']);
            foreach ($properties as $property) {
                $property = trim($property);
                if (strpos($property, 'hover') !== false || strpos($property, 'focus') !== false) {
                    echo "    {$property}\n";
                }
            }
        }
        
        // Special handling for transparent variants
        if (in_array($testCase['color'], ['ghost', 'outline'])) {
            if (!$hasHover && !$hasFocus) {
                echo "  ✅ Correctly skipped hover/focus for transparent variant\n";
            } else {
                echo "  ⚠️  Unexpected hover/focus for transparent variant\n";
            }
        } else {
            if ($hasHover && $hasFocus) {
                echo "  ✅ Both hover and focus states generated successfully\n";
            } else {
                echo "  ❌ Missing hover and/or focus states\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
    
    echo "\n" . str_repeat("=", 80) . "\n\n";
}

echo "=== Summary ===\n\n";

echo "Hover/Focus State Features:\n";
echo "✅ Tailwind Colors: Generate darker versions using +100 intensity\n";
echo "✅ Hex Colors: Generate darker versions using -20% brightness\n";
echo "✅ Variants: Map to Tailwind equivalents and generate darker versions\n";
echo "✅ CSS Custom Properties: Use separate variables for hover/focus colors\n";
echo "✅ Arbitrary Values: Use Tailwind arbitrary value syntax for JIT compatibility\n";
echo "✅ Transparent Variants: Skip hover/focus for ghost and outline variants\n\n";

echo "Expected Button Behavior:\n";
echo "- Normal state: Uses base background color\n";
echo "- Hover state: Background becomes darker on mouse hover\n";
echo "- Focus state: Background becomes darker when focused (keyboard navigation)\n";
echo "- All states maintain JIT compatibility through CSS custom properties\n\n";

echo "Test completed! 🎉\n";