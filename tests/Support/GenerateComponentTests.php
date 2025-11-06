<?php

namespace ArtisanPack\LivewireUiComponents\Tests\Support;

/**
 * Script to generate comprehensive unit tests for all UI components.
 * 
 * This script uses the ComponentTestGenerator to automatically create
 * tests for all components in the package.
 */
class GenerateComponentTests
{
    public static function run(): array
    {
        $componentDirectory = __DIR__ . '/../../src/View/Components';
        $testDirectory = __DIR__ . '/../Unit/Components';
        
        $generator = new ComponentTestGenerator($testDirectory);
        
        // Scan for all component classes
        echo "Scanning for components in: {$componentDirectory}\n";
        $components = $generator->scanComponents($componentDirectory);
        
        echo "Found " . count($components) . " components:\n";
        foreach ($components as $component) {
            echo "  - " . class_basename($component) . "\n";
        }
        
        // Generate tests for all components
        echo "\nGenerating tests...\n";
        $generated = $generator->generateAllTests();
        
        echo "Generated " . count($generated) . " test files:\n";
        foreach ($generated as $testFile) {
            echo "  - " . basename($testFile) . "\n";
        }
        
        return [
            'components_found' => count($components),
            'tests_generated' => count($generated),
            'generated_files' => $generated
        ];
    }
    
    public static function generateSpecificComponent(string $componentName): ?string
    {
        $componentClass = "ArtisanPack\\LivewireUiComponents\\View\\Components\\{$componentName}";
        $testDirectory = __DIR__ . '/../Unit/Components';
        
        $generator = new ComponentTestGenerator($testDirectory);
        
        if (!class_exists($componentClass)) {
            echo "Component class {$componentClass} not found.\n";
            return null;
        }
        
        $testFile = $generator->generateTestForComponent($componentClass);
        
        if ($testFile) {
            echo "Generated test file: {$testFile}\n";
        } else {
            echo "Test file already exists or generation failed.\n";
        }
        
        return $testFile;
    }
}

// If run directly from command line
if (isset($argv) && basename($argv[0]) === 'GenerateComponentTests.php') {
    if (isset($argv[1]) && $argv[1] === 'specific' && isset($argv[2])) {
        GenerateComponentTests::generateSpecificComponent($argv[2]);
    } else {
        GenerateComponentTests::run();
    }
}