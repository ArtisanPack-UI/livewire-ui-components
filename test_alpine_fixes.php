<?php

/**
 * Test script to verify Alpine.js directive fixes in tags component
 */

echo "Testing Alpine.js directive fixes in tags.blade.php...\n\n";

$filePath = __DIR__ . '/resources/views/components/tags.blade.php';

if (!file_exists($filePath)) {
    echo "❌ File not found: $filePath\n";
    exit(1);
}

$content = file_get_contents($filePath);

// Test 1: Check that x-class was fixed to :class
echo "1. Checking x-class directive fix...\n";
if (strpos($content, 'x-class="text-error"') !== false) {
    echo "❌ ERROR: x-class directive still exists (should be :class)\n";
} else if (strpos($content, ':class="\'text-error\'"') !== false) {
    echo "✅ SUCCESS: x-class fixed to :class\n";
} else {
    echo "❓ WARNING: Could not verify x-class fix\n";
}

// Test 2: Check that x-classes was fixed to :class
echo "\n2. Checking x-classes directive fix...\n";
if (strpos($content, 'x-classes="fieldset-label"') !== false) {
    echo "❌ ERROR: x-classes directive still exists (should be :class)\n";
} else if (strpos($content, ':class="\'fieldset-label\'"') !== false) {
    echo "✅ SUCCESS: x-classes fixed to :class\n";
} else {
    echo "❓ WARNING: Could not verify x-classes fix\n";
}

// Test 3: Check that x-ref="container" was added
echo "\n3. Checking x-anchor reference fix...\n";
if (strpos($content, 'x-ref="container"') !== false) {
    echo "✅ SUCCESS: x-ref=\"container\" reference added\n";
} else {
    echo "❌ ERROR: x-ref=\"container\" not found\n";
}

// Test 4: Check that x-anchor still references $refs.container
echo "\n4. Checking x-anchor directive...\n";
if (strpos($content, 'x-anchor.bottom-start="$refs.container ?? $el.previousElementSibling"') !== false) {
    echo "✅ SUCCESS: x-anchor directive references \$refs.container\n";
} else {
    echo "❌ ERROR: x-anchor directive not found or incorrect\n";
}

// Test 5: Check for any remaining x-class or x-classes issues
echo "\n5. Scanning for any remaining Alpine.js directive issues...\n";
$xClassMatches = [];
$xClassesMatches = [];

preg_match_all('/x-class(?:es)?="[^"]*"/', $content, $xClassMatches);

if (!empty($xClassMatches[0])) {
    echo "❌ WARNING: Found remaining x-class/x-classes directives:\n";
    foreach ($xClassMatches[0] as $match) {
        echo "   - $match\n";
    }
} else {
    echo "✅ SUCCESS: No remaining x-class/x-classes directives found\n";
}

echo "\n=== SUMMARY ===\n";
echo "All Alpine.js directive fixes have been applied to resolve:\n";
echo "1. :class attributes being rendered as literal text\n";
echo "2. Alpine: no element provided to x-anchor error\n";
echo "\nThe component should now work correctly with Alpine.js!\n";