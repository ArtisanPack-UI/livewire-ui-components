<?php

require_once __DIR__ . '/vendor/autoload.php';

$componentDirectory = __DIR__ . '/src/View/Components';

// Get all PHP files recursively
$files = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($componentDirectory, RecursiveDirectoryIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $files[] = $file->getPathname();
    }
}

echo "Found " . count($files) . " PHP files\n\n";

// Test extraction on first file
if (!empty($files)) {
    $testFile = $files[0];
    echo "Testing on: " . basename($testFile) . "\n";

    $content = file_get_contents($testFile);

    // Extract namespace
    if (preg_match('/namespace\s+([^;]+);/', $content, $namespaceMatches)) {
        echo "Namespace: " . $namespaceMatches[1] . "\n";
    }

    // Extract class name
    if (preg_match('/^\s*class\s+(\w+)/m', $content, $classMatches)) {
        echo "Class: " . $classMatches[1] . "\n";
    }

    if (isset($namespaceMatches[1]) && isset($classMatches[1])) {
        $fullClass = $namespaceMatches[1] . '\\' . $classMatches[1];
        echo "Full class name: $fullClass\n";

        // Check if class exists and is a component
        if (class_exists($fullClass)) {
            echo "Class exists: YES\n";
            $reflection = new ReflectionClass($fullClass);
            echo "Is subclass of Component: " . ($reflection->isSubclassOf('Illuminate\View\Component') ? 'YES' : 'NO') . "\n";
            echo "Is abstract: " . ($reflection->isAbstract() ? 'YES' : 'NO') . "\n";
        } else {
            echo "Class exists: NO\n";
        }
    }
}
