<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/vendor/orchestra/testbench-core/laravel/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Register the service provider
$app->register('ArtisanPack\LivewireUiComponents\LivewireUiComponentsServiceProvider');

use ArtisanPack\LivewireUiComponents\View\Components\Button;
use Illuminate\View\ComponentAttributeBag;

// Create a button component
$button = new Button(
    color: 'primary',
    label: 'Test Button',
    size: 'md'
);

// Set up attributes
$button->withAttributes(new ComponentAttributeBag([]));

// Get the view
$view = $button->render();

// Render it
try {
    $html = $view->render();
    echo "=== RENDERED HTML ===\n";
    echo $html;
    echo "\n\n";

    // Extract just the button tag
    preg_match('/<button[^>]*>/', $html, $matches);
    if (isset($matches[0])) {
        echo "=== BUTTON TAG ===\n";
        echo $matches[0];
        echo "\n\n";

        // Extract classes
        preg_match('/class="([^"]*)"/', $matches[0], $classMatches);
        if (isset($classMatches[1])) {
            echo "=== CLASSES ===\n";
            $classes = explode(' ', $classMatches[1]);
            foreach ($classes as $class) {
                echo "  - $class\n";
            }

            // Check for hover/focus classes
            echo "\n=== HOVER/FOCUS CHECK ===\n";
            $hasHover = false;
            $hasFocus = false;
            foreach ($classes as $class) {
                if (strpos($class, 'hover:') === 0) {
                    echo "  ✓ Found hover class: $class\n";
                    $hasHover = true;
                }
                if (strpos($class, 'focus:') === 0) {
                    echo "  ✓ Found focus class: $class\n";
                    $hasFocus = true;
                }
            }

            if (!$hasHover) {
                echo "  ✗ NO HOVER CLASSES FOUND!\n";
            }
            if (!$hasFocus) {
                echo "  ✗ NO FOCUS CLASSES FOUND!\n";
            }
        }

        // Extract style attribute
        preg_match('/style="([^"]*)"/', $matches[0], $styleMatches);
        if (isset($styleMatches[1])) {
            echo "\n=== STYLE ATTRIBUTE ===\n";
            echo $styleMatches[1];
            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
