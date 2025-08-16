<?php
/**
 * Test script to verify the DatePicker array to string conversion fix
 */

require_once __DIR__ . '/vendor/autoload.php';

use ArtisanPack\LivewireUiComponents\View\Components\DatePicker;
use Illuminate\View\ComponentAttributeBag;

// Mock the necessary classes for testing
class MockAttributes extends ComponentAttributeBag
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    
    public function wire($name)
    {
        return new class {
            public function hasModifier($modifier) { return false; }
            public function first() { return 'test_model'; }
        };
    }
    
    public function whereStartsWith($prefix)
    {
        return new class {
            public function first() { return null; }
        };
    }
    
    public function has($key) { return false; }
    public function get($key, $default = null) { return $default; }
}

// Test cases
$testCases = [
    'Basic font config (strings only)' => [
        'fontConfig' => [
            'font-family' => 'Arial, sans-serif',
            'font-size' => '1rem',
            'font-weight' => '500'
        ],
        'expectError' => false
    ],
    
    'Mixed config with array values' => [
        'fontConfig' => [
            'font-family' => 'Inter, sans-serif',
            'font-size' => '0.875rem',
            'custom' => ['property' => 'value'], // This array should be skipped
            'font-weight' => '400'
        ],
        'expectError' => false
    ],
    
    'Config with nested arrays' => [
        'fontConfig' => [
            'fonts' => ['primary' => 'Arial', 'secondary' => 'Helvetica'], // Array - should be skipped
            'sizes' => ['small' => '0.8rem', 'normal' => '1rem'], // Array - should be skipped
            'font-family' => 'Georgia, serif',
            'font-weight' => '600'
        ],
        'expectError' => false
    ]
];

echo "Testing DatePicker array to string conversion fix...\n\n";

foreach ($testCases as $testName => $testCase) {
    echo "Test: {$testName}\n";
    echo "Font config: " . json_encode($testCase['fontConfig']) . "\n";
    
    try {
        // Create DatePicker instance with test font config
        $datePicker = new DatePicker(
            fontConfig: $testCase['fontConfig'],
            color: 'primary'
        );
        
        // Set mock attributes
        $datePicker->setAttributes(new MockAttributes(['wire:model' => 'test_model']));
        
        // Test the method that was causing the error
        $cssVariables = $datePicker->getCustomCSSVariables();
        
        echo "✓ SUCCESS: CSS variables generated: " . substr($cssVariables, 0, 100) . (strlen($cssVariables) > 100 ? '...' : '') . "\n";
        
        if ($testCase['expectError']) {
            echo "⚠ UNEXPECTED: Expected error but none occurred\n";
        }
        
    } catch (Throwable $e) {
        if ($testCase['expectError']) {
            echo "✓ EXPECTED ERROR: " . $e->getMessage() . "\n";
        } else {
            echo "✗ ERROR: " . $e->getMessage() . "\n";
            echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        }
    }
    
    echo "\n";
}

echo "Test completed!\n";