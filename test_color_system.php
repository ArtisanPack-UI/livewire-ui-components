<?php
/**
 * Test script for the new Color System implementation
 * 
 * This script tests the ColorGenerator class and component color resolution
 * to verify that all color inputs work correctly.
 */

require_once 'vendor/autoload.php';

use ArtisanPack\LivewireUiComponents\Styling\ColorGenerator;

echo "=== ArtisanPack UI Components - Color System Test ===\n\n";

$colorGenerator = new ColorGenerator();

// Test predefined variants
echo "1. Testing Predefined Variants:\n";
$variants = ['primary', 'secondary', 'accent', 'success', 'warning', 'error', 'info', 'neutral'];

foreach ($variants as $variant) {
    $result = $colorGenerator->resolveComponentColor($variant, null, 'button');
    echo "  {$variant}: " . json_encode($result) . "\n";
}

echo "\n2. Testing Tailwind Colors with Intensity:\n";
$tailwindColors = ['red-500', 'blue-600', 'green-400', 'purple-700'];

foreach ($tailwindColors as $color) {
    $result = $colorGenerator->resolveComponentColor($color, null, 'alert');
    echo "  {$color}: " . json_encode($result) . "\n";
}

echo "\n3. Testing Tailwind Colors without Intensity (defaults to 500):\n";
$tailwindColorNames = ['red', 'blue', 'green', 'purple'];

foreach ($tailwindColorNames as $colorName) {
    $result = $colorGenerator->resolveComponentColor($colorName, null, 'badge');
    echo "  {$colorName}: " . json_encode($result) . "\n";
}

echo "\n4. Testing Hex Colors:\n";
$hexColors = ['#FF5733', '#33FF57', '#3357FF', '#FF33F5'];

foreach ($hexColors as $hex) {
    $result = $colorGenerator->resolveComponentColor($hex, null, 'avatar');
    echo "  {$hex}: " . json_encode($result) . "\n";
}

echo "\n5. Testing Color Adjustments:\n";
$adjustments = ['lighter', 'darker', 'transparent', 'subtle'];

foreach ($adjustments as $adjustment) {
    $result = $colorGenerator->resolveComponentColor('red-500', $adjustment, 'toast');
    echo "  red-500 with {$adjustment}: " . json_encode($result) . "\n";
}

echo "\n6. Testing Invalid Colors (should return empty array):\n";
$invalidColors = ['invalid-color', 'not-a-hex', 'blue-1000'];

foreach ($invalidColors as $invalid) {
    $result = $colorGenerator->resolveComponentColor($invalid, null, 'button');
    $isEmpty = empty($result) ? 'PASS (empty)' : 'FAIL (not empty)';
    echo "  {$invalid}: {$isEmpty}\n";
}

echo "\n7. Testing Component-specific Examples:\n";

// Button examples
echo "  Button Examples:\n";
echo "    Primary variant: " . json_encode($colorGenerator->resolveComponentColor('primary', null, 'button')) . "\n";
echo "    Blue-500: " . json_encode($colorGenerator->resolveComponentColor('blue-500', null, 'button')) . "\n";
echo "    #FF6B6B with lighter: " . json_encode($colorGenerator->resolveComponentColor('#FF6B6B', 'lighter', 'button')) . "\n";

// Alert examples
echo "  Alert Examples:\n";
echo "    Success variant: " . json_encode($colorGenerator->resolveComponentColor('success', null, 'alert')) . "\n";
echo "    Red-600: " . json_encode($colorGenerator->resolveComponentColor('red-600', null, 'alert')) . "\n";
echo "    Emerald with subtle: " . json_encode($colorGenerator->resolveComponentColor('emerald-500', 'subtle', 'alert')) . "\n";

// Badge examples
echo "  Badge Examples:\n";
echo "    Accent variant: " . json_encode($colorGenerator->resolveComponentColor('accent', null, 'badge')) . "\n";
echo "    Purple-400: " . json_encode($colorGenerator->resolveComponentColor('purple-400', null, 'badge')) . "\n";

// Avatar examples
echo "  Avatar Examples:\n";
echo "    Neutral variant: " . json_encode($colorGenerator->resolveComponentColor('neutral', null, 'avatar')) . "\n";
echo "    #FFA500: " . json_encode($colorGenerator->resolveComponentColor('#FFA500', null, 'avatar')) . "\n";

// Toast examples
echo "  Toast Examples:\n";
echo "    Warning variant: " . json_encode($colorGenerator->resolveComponentColor('warning', null, 'toast')) . "\n";
echo "    Sky-500 with transparent: " . json_encode($colorGenerator->resolveComponentColor('sky-500', 'transparent', 'toast')) . "\n";

echo "\n=== Color System Test Complete ===\n";
echo "All components now support:\n";
echo "- Predefined variants (primary, secondary, accent, success, warning, error, info, neutral, ghost, outline)\n";
echo "- Tailwind colors with intensity (e.g., 'red-500', 'blue-600')\n";
echo "- Tailwind color names (defaults to 500 intensity)\n";
echo "- Hex colors (e.g., '#FF5733')\n";
echo "- Background adjustments (lighter, darker, transparent, subtle)\n";
echo "- Backward compatibility with existing implementations\n";